<?php

/**
 * Core_Request
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_Request
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Request
{
	/**
	 * $instance
	 * 
	 * @var Core_Request $instance
	 */
	static private $instance;

	/**
	 * $session
	 * 
	 * @var Core_Session $session
	 */
	private $session;

	/**
	 * $locale
	 * 
	 * @var Core_Locale $locale
	 */
	public $locale;

	/**
	 * $view
	 * 
	 * @var Core_View $view
	 */
	public $view;

	/**
	 * $vars
	 * 
	 * @var string[] $var
	 */
	protected $vars;

	/**
	 * factory
	 *
	 * @return Core_Request
	 */
	static public function factory($class = FALSE)
	{
		if (isset(self::$instance)) {
			return self::$instance;
		}
		
		$config = Core_Config::singleton();
		if (!$class) {
			if (!$config->request_class) {
				if (isset($_SERVER['HTTP_HOST'])) {
					$class = 'ModRewrite';	
				} elseif(isset($_SERVER['argc'])) {
					$class = 'Cli';
				} else {
					throw new RuntimeException("Not a valid Request");
				}
			} else {
				$class = $config->request_class; 
			}
		}
		
		$class = 'Core_Request_' . $class;
		self::$instance = new $class;
		return self::$instance;
	}

	/**
	 * __toString
	 * 
	 * @return string serialized object which is unique identification of request
	 */
	public function __toString()
	{
		$arr = array_merge( $this->get(), array($this->locale, $this->getVar('browser')) );
		return implode('-',$arr);
	}

	/**
	 * get
	 * 
	 * Returns $_GET array
	 * 
	 * @return mixed $_GET
	 */
	public function get()
	{
		return $_GET;
	}
	
	/**
	 * Returns $_POST array
	 * 
	 * @return mixed $_POST
	 */
	public function post()
	{
		return $_POST;
	}
	
	/**
	 * Returns $_POST array
	 * 
	 * @return mixed $_POST
	 */
	public function files()
	{
		return $_FILES;
	}

	/**
	 * getFrom
	 * 
	 * @param string $key Wrapper for reading $_...[$key]
	 * @param mixed $source GET | POST | COOKIE | SESSION
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 * @return mixed
	 */
	public function getFrom($key, &$source, $acceptHTML = FALSE)
	{
		if(!isset($source[$key])) return FALSE;
		
		//remove slashes
		if (get_magic_quotes_gpc()) {
			$output = apply($source[$key], 'stripslashes');
		} else $output = $source[$key];
		
		//clear HTML
		if ($acceptHTML == FALSE) {
			$output = apply($output, 'htmlspecialchars');
		}
			
		return apply($output, 'trim');		
	}
	
	/**
	 * setTo
	 * 
	 * @param string $key Wrapper for writing $_...[$key]
	 * @param mixed $value
	 * @param mixed $destination GET | POST | COOKIE | SESSION
	 */
	public function setTo($key, $value, &$destination)
	{
		$destination[$key] = $value;
	}
	
	/**
	 * getGet 
	 * 
	 * @param string $key Wrapper for $_GET[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getGet($key,$acceptHTML = FALSE)
	{
		return $this->getFrom($key, $_GET, $acceptHTML);
	}

	/**
	 * getPost
	 * 
	 * @param string $key Wrapper for $_POST[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getPost($key, $acceptHTML = FALSE)
	{
		return $this->getFrom($key, $_POST, $acceptHTML);
	}

	/**
	 * getSession 
	 * 
	 * @param string $key Wrapper for $_SESSION[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getSession($key, $acceptHTML = FALSE)
	{
		if (!$this->session) $this->session = Core_Session::singleton();
		return $this->getFrom($key, $_SESSION, $acceptHTML);
	}

	/**
	 * getServer 
	 * 
	 * @param string $key Wrapper for $_SERVER[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getServer($key, $acceptHTML = FALSE)
	{
		return $this->getFrom($key, $_SERVER, $acceptHTML);
	}

	/**
	 * getCookie
	 * 
	 * @param string $key Wrapper for $_COOKIE[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getCookie($key, $acceptHTML = FALSE)
	{
		return $this->getFrom($key, $_COOKIE, $acceptHTML);
	}

	/**
	 * getVar
	 * 
	 * Returns variable stored in request object
	 * @param string $key
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 * @return mixed
	 */
	public function getVar($key, $acceptHTML = FALSE)
	{
		return $this->getFrom($key, $this->vars, $acceptHTML);
	}
	
	/**
	 * setGet
	 * 
	 * @param string $key Wrapper for writing $_...[$key]
	 * @param mixed $value
	 * @return void
	 */
	public function setGet($key, $value)
	{
		$this->setTo($key, $value, $_GET);
	}

	/**
	 * setPost
	 *  
	 * @param string $key Wrapper for writing $_...[$key]
	 * @param mixed $value
	 * @return void
	 */
	public function setPost($key, $value)
	{
		$this->setTo($key, $value, $_POST);
	}

	/**
	 * setSession
	 * 
	 * @param string $key Wrapper for writing $_...[$key]
	 * @param mixed $value
	 * @return void
	 */
	public function setSession($key, $value)
	{
		if (!$this->session) $this->session = Core_Session::singleton();
		$this->setTo($key, $value, $_SESSION);
	}

	/**
	 * setCookie
	 * 
	 * @param string $key Wrapper for writing $_...[$key]
	 * @param mixed $value
	 * @param int $time
	 * @param string $directory
	 * @return void
	 */
	public function setCookie($key, $value, $time = FALSE, $directory = '/')
	{
		if ($time === FALSE) $time = time() + Core_Config::singleton()->cookie->expiration;
		setcookie($key,$value,$time,$directory);
	}

	/**
	 * setVar
	 * 
	 * @param string $key Wrapper for writing $_...[$key]
	 * @param mixed $value
	 * @return void
	 */
	public function setVar($key, $value){
		$this->setTo($key, $value, $this->vars);
	}

	/**
	 * setGetFromArray
	 * 
	 * Loads $_GET from array.
	 * 
	 * @param mixed[] $array
	 */
	public function setGetFromArray($array){
		$_GET = $array;
	}
	
	/**
	 * sendHeaders
	 * 
	 * Sends HTTP headers
	 *
	 * @param string $content_type
	 * @param int $expires_in Number of seconds page should be cached
	 * @return void
	 */
	public function sendHeaders($content_type = 'text/html', $expires_in = 0)
	{
		if (headers_sent()) return;
		
		if ($expires_in == 0) {
			$expires = 0;
			$modified = time();
		}
		else {
			$expires = time() + $expires_in;
			$modified = time() - $expires_in;
		}
		
		header('Expires: ' . date("r", $expires));
		header("Cache-Control: no-cache, must-revalidate, max-age=" . $expires_in);
		header ("Last-Modified: " . date("r", $modified));
		if ($expires_in == 0) header ("Pragma: no-cache");
		header("Content-type: " . $content_type . "; charset=" . Core_Config::singleton()->encoding);
		header("X-Powered-By: Core Framework");
	}

	/*----------------- ABSTRACT ------------------*/
	
	/**
	 * getUrl
	 * 
	 * @return string actual URL
	 */ 
	abstract public function getUrl();

	/**
	 * forward
	 * 
	 * Creates URL from new GET request extended from actual request. Previous GET params will be used.
	 * 
	 * @param string[] $params GET parameters
	 * @param string $event Controller event
	 * @return string URL
	 */
	abstract public function forward($params, $event = FALSE);

	/**
	 * redirect
	 * 
	 * Creates URL from new GET request. Previous GET params won't be used.
	 * 
	 * @param string $module Name of controller class
	 * @param string $event Name of controller event
	 * @param string $route Name of route which should be used
	 * @param string[] $params Other GET params
	 * @param boolean $inherit_params Should we used previous GET params?
	 * @param boolean $moved Should we redirect via 301 HTTP header?
	 * @return string
	 */
	abstract public function redirect($module, $event, $route = FALSE, $params = FALSE, $inherit_params = FALSE, $moved = FALSE);
	
	/**
	 * isAjax
	 * 
	 * Is this AJAX request? 
	 * 
	 * @return boolean
	 */
	abstract public function isAjax();
	
	/**
	 * genUrl
	 * 
	 * Creates URL from new GET request. 
	 * 
	 * @param string $module Name of controller class
	 * @param string $event Name of controller event
	 * @param string $routeName Name of route which should be used
	 * @param string[] $params Other GET params
	 * @param boolean $inherit_params Should we used previous GET params?
	 * @return string
	 */
	abstract public function genURL($module = FALSE, $event = FALSE, $routeName = FALSE, $other_args = FALSE, $inherit_params = FALSE, $in_header = FALSE);
	
	/**
	 * decodeUrl
	 * 
	 * Converts URL to inner request (module+event+params)
	 * @return void 
	 */
	abstract public function decodeUrl();
	
	/**
	 * setLocale
	 * 
	 * Detects which localization should be used in this session.
	 * 
	 * @return void
	 */
	abstract protected function setLocale();
	
	/**
	 * setView
	 * 
	 * Detects which view wants user to be displayed
	 * @return void
	 */
	abstract protected function setView();
}