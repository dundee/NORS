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
	public $css = array(
		'normal' => array('default.css', 'forms.css'),
		'ie6'    => array(),
		'ie7'    => array(),
		'print'  => array(),
	);
	public $helpers = array('Form', 'Menu');

	public $cache = 0;

	public function beforeEvent()
	{
		$table = new Table_Page();
		$pages = $table->getAll('position', 'asc');

		$menu_helper = new Core_Helper_Menu();
		$menu = $menu_helper->prepare($pages);
		$this->setData('menu_items', $menu);

		$this->setData('images_dir', APP_URL.'/styles/'.$this->style.'/images');
	}

	/**
	* __default
	*
	* @return void
	*/
	public function __default(){
		new Component_Login($this, 'login');
	}
}
