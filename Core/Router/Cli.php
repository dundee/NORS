<?php

/**
 * Core_Request_Cli
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_Request_Cli
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Request_Cli extends Core_Request
{

	/**
	 * Constructor
	 */
	protected function __construct()
	{
		$this->setLocale();
		$this->setView();
	}

	public function getUrl()
	{
		return 'http://localhost/';
	}

	public function forward($params, $action = FALSE)
	{
		return $this->genUrl(FALSE, $action, FALSE, $params, TRUE);
	}

	public function redirect($controller, $action, $route = FALSE, $params = FALSE, $inherit_params = FALSE, $moved = FALSE)
	{
		if($moved) header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$this->genUrl($controller, $action, $route, $params, $inherit_params, TRUE));
		header("Connection: close");
		exit(0);
	}

	public function isAjax()
	{
		if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) return FALSE;
		return $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' &&
		$_SERVER['REQUEST_METHOD']   == 'POST';
	}

	public function genURL($controller = FALSE,
	                       $action = FALSE,
	                       $route = FALSE,
	                       $args = FALSE,
	                       $inherit_params = FALSE,
	                       $in_header = FALSE)
	{
		if (is_array($route)) throw new Exception('Wrong usage');

		$delimiter = '&amp;';
		$_GET = is_array($_GET) ? $_GET : array();
		$args = is_array($args) ? $args : array();
		$controller = $controller ? $controller : $_GET['controller'];
		$action  = $action ? $action : $_GET['action'];

		$args = array_merge( $args, array('controller'=>$controller,'action'=>$action) );
		if ($inherit_params) $args = array_merge($_GET,$args);

		$url = APP_URL;
		$url .= substr(APP_URL, strlen(APP_URL)-1, 1)=='/' ? '' : '/'; //slash on end

		$url2 = ''; //part after ?
		foreach($args as $key=>$value){
			if (
			$key == 'browser' ||
			$value == '__default'
			) continue;
			$url2 .= ($url2 ? $delimiter : '')   . $key;
			$url2 .= ($value==='' ? '' : '=') . stripslashes($value);
		}
		$url .= $url2 ? '?'.$url2 : '';
		return $url;
	}

	public function decodeUrl()
	{
		$config = Core_Config::singleton();
		if ($_SERVER['argc'] < 2) {
			$_GET['controller'] = $config->routes->default->defaults->controller;
		} else {
			$_GET['controller'] = $_SERVER['argv'][1];
			$_GET['action']  = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : '__default';
		}
	}

	protected function setLocale()
	{
		$config = Core_Config::singleton();
		$lang = $config->locale;
		$this->locale = ucfirst($lang);
		Core_Locale::factory($this->locale); //creates static instance
	}

	protected function setView()
	{
		$this->view = 'Default';
	}
}
