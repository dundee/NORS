<?php

/**
 * Core_Application
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_Application
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
		if ( !$this->config->db->user && $this->request->module != 'installation') {
			$this->router->redirect('installation', '__default', 'default');
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
		$module = $this->request->getGet('module');
		$event  = $this->request->getGet('event');

		do {
			$moduleFile = APP_PATH . '/modules/' . ucfirst($module) . '.php';
			if (!file_exists($moduleFile)) {
				$this->tryRedirect($module, $event);
				throw new UnexpectedValueException('Could not find module ' . $moduleFile . ', url: ' . $this->request->getUrl(), 404);
			} else {
				loadFile($moduleFile);
				if (!class_exists($module)) {
					throw new RuntimeException('Module ' . $module . ' includes no module class', 500);
				} else {
					$instance = new $module();
					if (!Core_Module::isValid($instance)) {
						throw new RuntimeException('Module ' . $module . ' is not a valid module', 500);
					}
					if (!$instance->authenticate()) {
						if ($module != 'login') $this->response->setSession('request', $this->request->getUrl());
						$this->router->redirect('login', '__default', FALSE, 'default');
					} else {
						$instance->authorize();
						$view = Core_View::factory($this->request->view, $instance, $event);
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
		list($component,$event) = explode('-', $command);
		$class = 'Component_' . ucwords($component);
		$instance = new $class();

		//auth
		if (!$instance->auth()) {
			$data = array('errors' => 'Not signed in or not enough rights.');
		} else {
			$instance->loadHelpers();
			if (method_exists($instance, 'beforeEvent')) $instance->beforeEvent();
			$instance->$event();
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
	 * @param string $module
	 * @param string $event
	 * @return void
	 */
	public function tryRedirect($module, $event)
	{
		$text = new Core_Text();

		$event = strtolower($event);
		$event = $text->urlEncode($event);

		$table = new Table_Post();
		$posts = $table->getPosts();

		foreach ($posts as $post) {
			if ($event == $text->urlEncode($post->name)) {
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
