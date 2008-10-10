<?php

/**
* Core_Session
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Session
*
* Singleton
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Session
{
    /**
     * $instance
     *
     * @var Core_Session $instance
     */
    private static $instance;

    /**
     * $sessionID
     *
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
     * singleton
     *
     * Singleton pattern
     *
     * @return Core_Session
     */
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }

    /**
     * destroy
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
     * __get($var)
     *
     * Returns the requested session variable.
     *
     * @return mixed Returns the value of $_SESSION[$var]
     */
    public function __get($key)
    {
        if (!isset($_SESSION[$key])) return FALSE;
        return $_SESSION[$key];
    }

    /**
     * __set
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
     * __destruct()
     *
     * @return void
     */
    public function __destruct()
    {
        session_write_close();
    }
}
