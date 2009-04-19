<?php

/**
* Core_View
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_View
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
abstract class Core_View
{
	/**
	* $controller
	*
	* @var Core_Controller $controller
	*/
	protected $controller;

	/**
	* $action
	*
	* @var string $action
	*/
	protected $action;

	/**
	* Constructor
	* @access public
	*/
	public function __construct(Core_Controller $controller, $action){
        $this->controller = $controller;
        $this->action     = $action;
    }

	/**
    * factory
    *
    * @access public
    * @param string $type Language
    * @return mixed Exception on erroe or a valid language instance
    * @static
    */
    static public function factory($type, Core_Controller $controller, $action){
        $file = APP_PATH.'/Core/View/'.$type.'.php';
        if (loadFile($file)) {
            $class = 'Core_View_' . $type;
            if (class_exists($class)) {
                $view = new $class($controller,$action);
                if ($view instanceof Core_View) {
                    return $view;
                }
				throw new RuntimeException('Invalid view class: '.$type);
            }
            throw new RuntimeException('View class not found: '.$type);
        }
		throw new UnexpectedValueException('View file not found: '.$type);
    }

    abstract public function display();
}
?>
