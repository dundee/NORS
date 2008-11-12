<?php

/**
* Home
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Home
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Home extends Core_Module
{
	public $css = array('default.css');
	public $helpers = array('Menu');
	
	public $cache = 0;
	
	public function beforeEvent()
	{
		$table = new Table_Page();
		$pages = $table->getAll('position', 'asc');
		
		$menu_helper = new Core_Helper_Menu();
		$menu = $menu_helper->prepare($pages);
		$this->setData('menu_items', $menu);
	}
	
	/**
	* __default
	*
	* @return void
	*/
	public function __default(){
	}
}
