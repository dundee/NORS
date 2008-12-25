<?php

/**
 * Core_Router
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_Router
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Router
{
	/**
	 * $instance
	 *
	 * @var Core_Request $instance
	 */
	static private $instance;

	/**
	 * factory
	 *
	 * @return Core_Router
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

		$class = 'Core_Router_' . $class;
		self::$instance = new $class;
		return self::$instance;
	}

	/**
	 * Creates URL from new GET request extended from actual request. Previous GET params will be used.
	 *
	 * @param string[] $params GET parameters
	 * @param string $event Controller event
	 * @param boolean $csrf Adds CSRF protection
	 * @return string URL
	 */
	abstract public function forward($params, $event = FALSE, $csrf = FALSE);

	/**
	 * Creates URL from new GET request. Previous GET params won't be used.
	 *
	 * @param string $module Name of controller class
	 * @param string $event Name of controller event
	 * @param string $route Name of route which should be used
	 * @param string[] $params Other GET params
	 * @param boolean $inherit_params Should we used previous GET params?
	 * @param boolean $moved Should we redirect via 301 HTTP header?
	 * @param boolean $csrf Adds CSRF protection
	 * @return string
	 */
	abstract public function redirect($module, $event, $route = FALSE, $params = FALSE, $inherit_params = FALSE, $moved = FALSE, $csrf = FALSE);

	/**
	 * Creates URL from new GET request.
	 *
	 * @param string $module Name of controller class
	 * @param string $event Name of controller event
	 * @param string $routeName Name of route which should be used
	 * @param string[] $params Other GET params
	 * @param boolean $inherit_params Should we used previous GET params?
	 * @param boolean $csrf Adds CSRF protection
	 * @return string
	 */
	abstract public function genURL($module = FALSE, $event = FALSE, $routeName = FALSE, $other_args = FALSE, $inherit_params = FALSE, $in_header = FALSE, $csrf = FALSE);

	/**
	 * Converts URL to inner request (module+event+params)
	 * @param Core_Request $request
	 * @return void
	 */
	abstract public function decodeUrl(Core_Request $request);
}
