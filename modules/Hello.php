<?php

/**
* Hello
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Hello
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Hello extends Core_Module
{
	public $css = array('src' => 'default.css');
	public $helpers = array();
	
	public $cache = 0;
	
	/**
	* __default
	*
	* @return void
	*/
	public function __default(){
		$this->setData('msg', 'hello');
	}
}
