<?php

/**
 * Core_Request_ModRewrite
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_Request_ModRewrite
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Request_ModRewrite extends Core_Request
{
	protected $routes;

	protected $currentRoute;

	/**
	 * Constructor
	 */
	protected function __construct(){
		$this->setLocale();
		$this->setView();
	}

	public function getUrl()
	{
		return 'http://' . $this->getServer('SERVER_NAME') . $this->getServer('REQUEST_URI', TRUE);
	}

	public function forward($params, $event = FALSE){
		return $this->genUrl(FALSE, $event, FALSE, $params, TRUE);
	}

	public function redirect($module, $event, $route = FALSE, $params = FALSE, $inherit_params = FALSE, $moved = FALSE){
		if($moved) header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$this->genUrl($module, $event, $route, $params, $inherit_params, TRUE));
		header("Connection: close");
		exit(0);
	}

	public function isAjax()
	{
		return ($_SERVER['REQUEST_METHOD'] == 'POST' &&  //have to be a POST request
		           ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				     $_SERVER['HTTP_X_REQUESTED_WITH']
					 ) == 'XMLHttpRequest'  || //text data sent by ajax
			           (isset($_GET['command']) && isset($_FILES)) //or files sent by hidden textarea
				    )
			    );
	}

	public function genURL($module = FALSE,
	                       $event = FALSE,
	                       $routeName = FALSE,
	                       $other_args = FALSE,
	                       $inherit_params = FALSE,
	                       $in_header = FALSE)
	{
		if (is_array($routeName)) throw new Exception('Wrong usage');

		//init
		$routeName = $routeName ? $routeName : $this->currentRoute;
		$delimiter = $in_header ? '&' : '&amp;';
		$_GET = is_array($_GET) ? $_GET : array();
		$other_args = is_array($other_args) ? $other_args : array();
		$module = $module ? $module : $_GET['module'];
		$event  = $event  ? $event  : $_GET['event'];

		//prepare args
		$args = array_merge( $other_args, array('module' => $module,
		                                        'event'  => $event) );
		if ($inherit_params) $args = array_merge($_GET,$args);

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
			unset($args[$part]);
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

	public function decodeUrl(){
		//prepare routes
		$config = Core_Config::singleton();
		foreach($config->routes as $name=>$route){
			$this->routes[$name] = array('url'=>$route->format,'defaults'=>$route->defaults);
		}

		//decode URL
		$directory = eregi_replace('http://([^/]+)','',APP_URL); //directory where is app placed
		$url = $this->getServer('REQUEST_URI');

		if (strlen($directory) > 1) { //app in subdir
			$url = str_replace($directory.'/','',$url);
		} else { //in root
			$url = substr($url,1);
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
		$url = 'http://' . $this->getServer('SERVER_NAME') . $this->getServer('REQUEST_URI');
		//$url = str_replace('&','&amp;',$url);
		if ($this->genUrl($_GET['module'], $_GET['event'], FALSE, $_GET) != $url){
			//echo $this->genUrl($_GET['module'], $_GET['event'], FALSE, $_GET).' - '.$url;
			$this->redirect($_GET['module'], $_GET['event'], FALSE,$_GET,FALSE,TRUE);
		}
	}

	protected function setLocale(){
		if ($this->getGet('lang')){ //GET - nejvyssi priorita
			$lang = $this->getGet('lang');
			$this->setCookie('lang',$lang);
			unset($_GET['lang']);
		} elseif ($this->getCookie('lang')) {
			$lang = $this->getCookie('lang'); //COOKIE - stredni priorita
		} elseif ($this->getServer('HTTP_ACCEPT_LANGUAGE')) { //HTTP - nejnizsi priorita
			$arr = explode(';',$this->getServer('HTTP_ACCEPT_LANGUAGE'));
			$languages = $arr[0];
			$arr = explode(',',$languages);
			foreach($arr as $item){
				$lang = $item;
				if (file_exists(APP_PATH.'/locales/'.ucfirst($lang).'.php')) break;
			}
		} else {
			$config = Core_Config::singleton();
			$lang = $config->locale;
		}
		$this->locale = ucfirst($lang);
		Core_Locale::factory($this->locale); //creates static instance
	}

	protected function setView(){
		if ($this->getGet('ajax') !== FALSE) $this->view = 'Ajax';
		elseif ($this->getGet('rss') !== FALSE) $this->view = 'Rss';
		elseif ($this->getGet('csv') !== FALSE) $this->view = 'Csv';

		$agent = $this->getServer('HTTP_USER_AGENT');
		if (strpos($agent, 'Opera') !== FALSE) $this->setVar('browser', 'Opera');
		if (strpos($agent, 'Gecko') !== FALSE) $this->setVar('browser', 'Firefox');
		if (strpos($agent, 'MSIE')  !== FALSE) $this->setVar('browser', 'IE');
	}
}
