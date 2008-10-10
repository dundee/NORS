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
	 * $request
	 *
	 * @var Core_Request $request
	 */
	protected $request;

	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{

		Core_Debug::start();

		$this->request = Core_Request::factory();
		$this->request->decodeUrl();

		if ( !Core_Config::singleton()->enabled ) {
			throw new RuntimeException('Out of order', 503);
		}

		if ( $this->request->isAjax() ) {
			$this->dispatchAjaxRequest();
		} else {
			$this->dispatchGetRequest();
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
				throw new UnexpectedValueException('Could not find module '
				                                   . $moduleFile
				                                   . ', url: '
				                                   . $this->request->getUrl(),
				                                   404);
			} else {
				loadFile($moduleFile);
				if (!class_exists($module)) {
					throw new RuntimeException('Module '
					                           . $module
					                           . ' includes no module class',
					                           500);
				} else {
					$instance = new $module();
					if (!Core_Module::isValid($instance)) {
						throw new RuntimeException('Module '
						                           . $module
						                           . ' is not a valid module',
						                           500);
					}
					if (!$instance->authenticate()) {
						$this->request->setSession('request',
						                           $this->request->getUrl());
						$module = 'login';
						$event  = '__default';
						$this->request->redirect($module,
						                         $event,
						                         FALSE,
						                         'default');
					} else {
						$instance->checkRights();
						$view = Core_View::factory($this->request->view,
						                           $instance,
						                           $event);
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
		$instance->loadHelpers();
		if (method_exists($instance, 'beforeEvent')) $instance->beforeEvent();
		$instance->$event();
		$data = $instance->getData();

		if ($instance->responseType == 'json') {
			$this->request->sendHeaders('text/x-json');
			echo json_encode($data);
		} elseif ($instance->responseType == 'html') {
			$this->request->sendHeaders();
			echo $data['html'];
		}
	}
}
