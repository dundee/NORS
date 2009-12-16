<?php

/**
 * Front Controller class. All requests goes trought this.
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Application
{
	/**
	 * @var Core_Request $request
	 */
	protected $request;

	/**
	 * @var Core_Response $response
	 */
	protected $response;

	/**
	 * @var Core_Router $router
	 */
	protected $router;

	/**
	 * @var Core_Config $config
	 */
	protected $config;

	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{

		Core_Debug::start();

		$this->request  = Core_Request ::factory();
		$this->response = Core_Response::factory();
		$this->router   = Core_Router  ::factory();
		$this->config   = Core_Config  ::singleton();

		//some settings
		date_default_timezone_set($this->config->timezone);
		mb_internal_encoding($this->config->encoding);

		//parse URL
		$this->router->decodeUrl($this->request);

		//site turned off
		if ( !$this->config->enabled ) {
			throw new RuntimeException('Out of order', 503);
		}

		//redirect to installation
		if ( !$this->config->db->user && $this->request->controller != 'installation') {
			$this->router->redirect('installation', '__default', 'default');
		}

		//redirect to DB upgrade
		if ( (!isset($this->config->db->version) || $this->config->db->version != norsVersion())
		     && $this->request->controller != 'upgrade'
		     && $this->request->controller != 'installation') {
			$this->router->redirect('upgrade', '__default', 'default');
		}

		if ( $this->request->isAjax() ) {
			$this->dispatchAjaxRequest(); //ajax request
		} else {
			$this->dispatchGetRequest(); //ordinary GET request
		}
	}

	/**
	 * dispatchGetRequest
	 *
	 * Dispatches normal HTTP GET request.
	 *
	 * @return void
	 */
	protected function dispatchGetRequest()
	{
		$controller = $this->request->getGet('controller');
		$action     = $this->request->getGet('action');

		do {
			$controllerFile = APP_PATH . '/controllers/' . ucfirst($controller) . '.php';
			if (!file_exists($controllerFile)) {
				$this->tryRedirect($controller, $action);
				throw new UnexpectedValueException('Could not find controller ' . $controllerFile . ', url: ' . $this->request->getUrl(), 404);
			} else {
				loadFile($controllerFile);
				if (!class_exists($controller)) {
					throw new RuntimeException('Controller ' . $controller . ' includes no controller class', 500);
				} else {
					$instance = new $controller();
					if (!Core_Controller::isValid($instance)) {
						throw new RuntimeException('Controller ' . $controller . ' is not a valid controller', 500);
					}
					if (!$instance->authenticate()) {
						if ($controller != 'user') $this->response->setSession('request', $this->request->getUrl());
						$this->router->redirect('user', 'login', 'default');
					} else {
						$instance->authorize();
						$view = Core_View::factory($this->request->view, $instance, $action);
						$view->display();
					} //end if authenticated
				} //end if class exists
			} //end if file exists
		} while (FALSE);
	}

	/**
	 * dispatchAjaxRequest
	 *
	 * Dispatches asynchronous HTTP request (usually AJAX).
	 *
	 * @return void
	 */
	protected function dispatchAjaxRequest()
	{
		$command = $this->request->getGet('command');
		list($component,$action) = explode('-', $command);
		$class = 'Component_' . ucwords($component);
		$instance = new $class();

		//auth
		if (!$instance->auth()) {
			$data = array('errors' => 'Not signed in or not enough rights.');
		} else {
			$instance->loadHelpers();
			if (method_exists($instance, 'beforeAction')) $instance->beforeAction();
			$instance->$action();
			$data = $instance->getData();
		}

		if ($instance->responseType == 'json') {
			$this->response->sendHeaders('text/x-json');
			echo json_encode($data);
		} elseif ($instance->responseType == 'html') {
			$this->response->sendHeaders();
			if (isset($data['html'])) echo $data['html'];
		}
	}

	/**
	 * Tries redirect to new URL. (for old NORS 3 urls)
	 * @param string $controller
	 * @param string $action
	 * @return void
	 */
	public function tryRedirect($controller, $action)
	{
		$text = new Core_Text();

		$action = strtolower($action);
		$action = $text->urlEncode($action);

		$table = new Table_Post();
		$posts = $table->getActive();

		if (iterable($posts)) {
			foreach ($posts as $post) {
				if ($action == $text->urlEncode($post->name)) {
					$this->router->redirect('post',
					                        '__default',
					                        'post',
					                        array('post' => $post->id_post . '-' . $text->urlEncode($post->name)),
					                        FALSE,
					                        TRUE);
				}
			}
		}
	}
}
