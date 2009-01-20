<?php

/**
 * Core_Router_ModRewrite
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_Router_ModRewrite
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Router_ModRewrite extends Core_Router
{
	protected $routes;

	protected $currentRoute;

	/**
	 * Constructor
	 */
	protected function __construct(){
	}

	public function forward($params, $event = FALSE, $csrf = FALSE){
		return $this->genUrl(FALSE, $event, FALSE, $params, TRUE, FALSE, $csrf);
	}

	public function redirect($module, $event = FALSE, $route = FALSE, $params = FALSE, $inherit_params = FALSE, $moved = FALSE, $csrf = FALSE){
		if ($moved) header("HTTP/1.1 301 Moved Permanently");

		if (strpos($module, 'http') === 0) $l = "Location: " . $module;
		else $l = "Location: ".$this->genUrl($module, $event, $route, $params, $inherit_params, TRUE, $csrf);

		header($l);
		header("Connection: close");
		exit(0);
	}

	public function genURL($module = FALSE,
	                       $event = FALSE,
	                       $routeName = FALSE,
	                       $other_args = FALSE,
	                       $inherit_params = FALSE,
	                       $in_header = FALSE,
	                       $csrf = FALSE)
	{
		if (is_array($routeName)) throw new Exception('Wrong usage');
		if (!count($this->routes)) throw new Exception('Router not ready, run decodeUrl first');

		//init
		$routeName = $routeName ? $routeName : $this->currentRoute;
		$delimiter = $in_header ? '&' : '&amp;';
		$_GET = is_array($_GET) ? $_GET : array();
		$other_args = is_array($other_args) ? $other_args : array();
		$module = $module ? $module : $_GET['module'];
		$event  = $event  ? $event  : $_GET['event'];

		//csrf
		if ($csrf) {
			$request = Core_Request::factory();
			if ($request->getServer('REMOTE_ADDR') == 'unit') $key = 1; //unit tests
			else $key = rand(0, 100);
			$other_args['random_key'] = $key;
			$other_args['hashed_key'] = md5($request->getSession('password') . $key . $request->sessionID());
		}

		//prepare args
		$args = array_merge( $other_args, array('module' => $module,
		                                        'event'  => $event) );
		if ($inherit_params) $args = array_merge($_GET,$args);

		if (!isset($this->routes[$routeName])) throw new Exception('Route "' .$routeName . '" is not defined.');

		$route = $this->routes[$routeName];
		$urlForm = $route['url']; //URL form of route...e.g.: ':module/:event'

		//create cool URL according to URL form
		$url_parts = explode('/', $urlForm);
		$url = APP_URL;
		$url .= substr(APP_URL, strlen(APP_URL)-1, 1) == '/' ? '' : '/'; //slash on end

		foreach ($url_parts as $part) {
			if (substr($part, 0, 1) == ':') { //variable part
				$part = substr($part, 1); //remove colon
				$part_value = $args[$part];
				unset($args[$part]);
			} else { //static part
				$part_value = $part;
			}

			if ($part_value == '__default') continue;
			if (!$part_value && //no value for this part
			    isset($route['defaults']) &&
			    isset($route['defaults']->{$part})
			    ) $part_value = $route['defaults']->{$part}; //default values

			if ($part_value) $url .= $part_value . '/';
			//echo $part . ' - ' . $args[$part] .' - '. $url.'<br />';
		}

		//after ?
		$url2 = '';
		foreach($args as $key=>$value){
			if (
			$key == 'browser' ||
			$key == 'module' ||
			$value == '__default'
			) continue;
			$url2 .= ($url2 ? $delimiter : '')   . $key;
			$url2 .= ($value==='' ? '' : '=') . stripslashes($value);
		}
		$url .= $url2 ? '?'.$url2 : '';
		return $url;
	}

	public function decodeUrl(Core_Request $request, $redirect = TRUE){
		//prepare routes
		$config = Core_Config::singleton();
		foreach($config->routes as $name=>$route){
			$this->routes[$name] = array('url'=>$route->format,'defaults'=>$route->defaults);
		}

		//decode URL
		$directory = eregi_replace('http://([^/]+)', '', APP_URL); //directory where is app placed
		$url = $request->getServer('REQUEST_URI');
		$url = preg_replace('%^http://' . $request->getServer('HTTP_HOST') . '%', '', $url);

		if (strlen($directory) > 1) { //app in subdir
			$url = preg_replace('%^' . $directory.'/%', '', $url);
		} else { //in root
			$url = substr($url, 1);
		}

		$arr = explode('?',$url);
		$event = $arr[0];
		$params = explode('/',$event);

		if (!iterable($this->routes)) throw new UnderflowException("No routes defined");

		//find matching route
		foreach($this->routes as $name=>$route){
			$urlForm = $route['url'];
			$urlForm = eregi_replace(':([^/]*)','([^/]*)',$urlForm);
			if ( eregi('^'.$urlForm.'/?$', $event) ) { //route matches
				$this->currentRoute = $name;
				break;
			}
		}

		if (!$this->currentRoute) $this->currentRoute = 'default';
		$route = $this->routes[$this->currentRoute];
		if (!iterable($route)) throw new UnderflowException("Route ".$this->currentRoute." empty");

		$parts = explode('/',$route['url']);
		$i = 0;
		foreach ($parts as $key) {
			if (substr($key,0,1) != ':') {
				$i++;
				continue; //static part of URL
			}
			$key = substr($key,1);
			$_GET[$key] = isset($params[$i]) ? $params[$i] : FALSE;
			$i++;
		}

		foreach ($route['defaults'] as $k => $v) {
			if (isset($_GET[$k]) && $_GET[$k]) continue;
			$_GET[$k] = $v;
		}

		$_GET['event'] = isset($_GET['event']) ? $_GET['event'] : '__default';

		//canonical URL? Redirect if not
		$url = $request->getUrl();
		$url = str_replace('&','&amp;',$url);
		if ($this->genUrl($_GET['module'], $_GET['event'], FALSE, $_GET) != $url && $redirect){
			//echor($this->genUrl($_GET['module'], $_GET['event'], FALSE, $_GET).' - '.$url);
			$this->redirect($_GET['module'], $_GET['event'], FALSE,$_GET,FALSE,TRUE);
		}
	}
}
