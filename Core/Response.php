<?php

/**
 * Response wrapper (HTTP geaders, GET, POST, Session, Cookie, Server)
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Response
{
	/**
	 * $instance
	 *
	 * @var Core_Request $instance
	 */
	static private $instance = NULL;

	/**
	 * $session
	 *
	 * @var Core_Session $session
	 */
	private $session;

	/**
	 * factory
	 *
	 * @return Core_Response
	 */
	static public function factory($class = FALSE)
	{
		if (self::$instance == NULL) {
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
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

	/**
	 * setTo
	 *
	 * @param string $key Wrapper for writing $_...[$key]
	 * @param mixed $value
	 * @param mixed $destination GET | POST | COOKIE | SESSION
	 * @return void
	 */
	protected function setTo($key, $value, &$destination)
	{
		if (!$value) unset($destination[$key]);
		else $destination[$key] = $value;
	}
}
