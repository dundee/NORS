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
	* $module
	*
	* @var Core_Module $module
	*/
	protected $module;

	/**
	* $modelEvent
	*
	* @var string $modelEvent
	*/
	protected $modelEvent;

	/**
	* Constructor
	* @access public
	*/
	public function __construct(Core_Module $module, $event){
        $this->module = $module;
        $this->moduleEvent = $event;
    }

	/**
    * factory
    *
    * @access public
    * @param string $type Language
    * @return mixed Exception on erroe or a valid language instance
    * @static
    */
    static public function factory($type, Core_Module $module, $event){
        $file = APP_PATH.'/Core/View/'.$type.'.php';
        if (loadFile($file)) {
            $class = 'Core_View_'.$type;
            if (class_exists($class)) {
                $view = new $class($module,$event);
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
