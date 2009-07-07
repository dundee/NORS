<?php

/**
 * Converts HTTP request to inner request (class and method name, params) and back
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
	static private $instance = NULL;

	/**
	 * factory
	 *
	 * @return Core_Router
	 */
	static public function factory($class = FALSE)
	{
		if (self::$instance == NULL) {
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
		}
		return self::$instance;
	}

	/**
	 * Creates URL from new GET request extended from actual request. Previous GET params will be used.
	 *
	 * @param string[] $params GET parameters
	 * @param string $action Controller action
	 * @param boolean $csrf Adds CSRF protection
	 * @return string URL
	 */
	abstract public function forward($params, $action = FALSE, $csrf = FALSE);

	/**
	 * Creates URL from new GET request. Previous GET params won't be used.
	 *
	 * @param string $controller Name of controller class or some outside URL
	 * @param string $action Name of controller action
	 * @param string $route Name of route which should be used
	 * @param string[] $params Other GET params
	 * @param boolean $inherit_params Should we used previous GET params?
	 * @param boolean $moved Should we redirect via 301 HTTP header?
	 * @param boolean $csrf Adds CSRF protection
	 * @return string
	 */
	abstract public function redirect($controller, $action = FALSE, $route = FALSE, $params = FALSE, $inherit_params = FALSE, $moved = FALSE, $csrf = FALSE);

	/**
	 * Creates URL from new GET request.
	 *
	 * @param string $controller Name of controller class
	 * @param string $action Name of controller action
	 * @param string $routeName Name of route which should be used
	 * @param string[] $params Other GET params
	 * @param boolean $inherit_params Should we used previous GET params?
	 * @param boolean $csrf Adds CSRF protection
	 * @return string
	 */
	abstract public function genURL($controller = FALSE, $action = FALSE, $routeName = FALSE, $other_args = FALSE, $inherit_params = FALSE, $in_header = FALSE, $csrf = FALSE);

	/**
	 * Converts URL to inner request (controller+action+params)
	 * @param Core_Request $request
	 * @return void
	 */
	abstract public function decodeUrl(Core_Request $request);
}
