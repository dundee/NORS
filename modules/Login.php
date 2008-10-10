<?php

/**
* Login
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Login
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Login extends Core_Module
{
	public $css = array('src' => 'default.css');
	public $helpers = array('Form');
	
	public $cache = 0;
	
	/**
	* __default
	*
	* @return void
	*/
	public function __default(){	
		new Component_Login($this, 'login');
	}
}