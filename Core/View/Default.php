<?php

/**
 * Default View. Provides caching.
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_View_Default extends Core_View
{

	public function __construct(Core_Controller $controller,$action){
		parent::__construct($controller,$action);
	}

	/**
	 * display
	 *
	 */
	public function display(){

		$request  = $this->controller->request;
		$response = $this->controller->response;

		$request->setVar('view', 'default');

		$createCache = FALSE;  //should we create cache file?
		$cacheLifeTime = $this->controller->cache;

		if ($cacheLifeTime > 0){ //caching allowed
			$cacheFileName = $request . ".cache.php";
			$cacheFilePath = APP_PATH.'/tpl/cache/'.$cacheFileName;
			if (file_exists($cacheFilePath)){
				$time = filemtime($cacheFilePath); //unix timestamp
				$request->setVar('cacheTime', $time);
				$age = time() - $time;
				if($age < $cacheLifeTime){ //cache not expired
					$this->setDoctype($data,$request, $response);
					include($cacheFilePath); //display cache
					return TRUE;
				} else $createCache = TRUE; //cache expired
			} else $createCache = TRUE; //cache not exists
		}

		$request->setVar('caching', $createCache);
		if ($createCache) ob_start(); //start output buffer

		//load helpers
		if (iterable($this->controller->helpers)) {
			foreach ($this->controller->helpers as $helper) {
				$class = 'Core_Helper_' . ucfirst($helper);
				$this->controller->setData(strtolower($helper), new $class);
			}
		}

		//execute action
		if (method_exists($this->controller,'beforeAction')) $this->controller->beforeAction();
		$action = $this->action;
		if (!method_exists($this->controller, $action)) throw new UnexpectedValueException('Could not find action ' . $action . ', url: ' . $this->request->getUrl(), 404);
		
		try {
			$this->controller->$action();
		} catch (Exception $ex) {
			define('KILLED', 1);
			throw $ex;
		}
		
		if (method_exists($this->controller,'afterAction')) $this->controller->afterAction();

		$data = $this->controller->getData();

		$this->setDoctype($data,$request, $response);

		foreach($data as $k=>$v){
			${$k} = $v;
		}
		unset($data);

		if ($this->controller->headerTplFile){
			include(APP_PATH.'/tpl/layout/'.$this->controller->headerTplFile);
		}
		include(APP_PATH.'/tpl/'.$this->controller->tplFile);

		//write cache
		if ($createCache) {  //create cache file
			$buffer = ob_get_contents(); //write buffer to String
			ob_end_clean(); //clear buffer
			file_put_contents($cacheFilePath,$buffer);
			include($cacheFilePath);
			return TRUE;
		}
	}

	protected function setDoctype(&$data, $request, $response){
		if(!headers_sent()){
			$config = Core_Config::singleton();
			if($request->getVar('browser')=='Firefox') {
				$data['doctype'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . ENDL;
				$response->sendHeaders();
			} else {
				$data['doctype'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . ENDL;
				$response->sendHeaders();
			}
		}
	}

	public function __destruct()
	{
		if (defined('KILLED')) return; //Exception thrown

		$this->controller->delData();
		try {
			if (method_exists($this->controller,'beforeFooter')) $this->controller->beforeFooter();
		} catch (Exception $ex) {
			die('Exception:' . $ex->getMessage()); //cut second Exception from propagation
		}

		$data = $this->controller->getData();
		foreach($data as $k=>$v){
			${$k} = $v;
		}
		$this->controller->delData();
		unset($data);

		if($this->controller->footerTplFile) require_once(APP_PATH . '/tpl/layout/' . $this->controller->footerTplFile);
	}
}
