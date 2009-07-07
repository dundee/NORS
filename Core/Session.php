<?php

/**
 * Seesion wrapper
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Session
{
	/**
	 * @var Core_Session $instance
	 */
	private static $instance = NULL;

	/**
	 * @var string $sessionID
	 */
	public $sessionID = '';

	private function __construct()
	{
		if (ini_get('session.auto_start') == 0) {
			session_start();
		}
		$this->sessionID = session_id();
	}

	/**
	 * Singleton pattern
	 * @return Core_Session
	 */
	public static function singleton()
	{
		if (self::$instance == NULL) {
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}

	/**
	 * destroy session
	 *
	 * @return void
	 */
	public function destroy()
	{
		foreach ($_SESSION as $var => $val) {
			session_unregister($var);
		}

		session_destroy();
	}

	/**
	 * Returns the requested session variable.
	 * @return mixed Returns the value of $_SESSION[$var]
	 */
	public function __get($key)
	{
		if (!isset($_SESSION[$key])) return FALSE;
		return $_SESSION[$key];
	}

	/**
	 * Sets session variable
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return boolean
	 */
	public function __set($key,$value)
	{
		return ($_SESSION[$key] = $value);
	}

	/**
	 * Protection against session fixation
	 */
	public function renewId()
	{
		session_regenerate_id();
		$this->sessionID = session_id();
	}

	/**
	 * __destruct()
	 *
	 * @return void
	 */
	public function __destruct()
	{
		session_write_close();
	}
}
