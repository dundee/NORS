<?php

/**
* User
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* User
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class User extends Core_Controller
{
	public $headerTplFile = 'header_admin.tpl.php';
	public $footerTplFile = 'footer_admin.tpl.php';

	public $css = array(
		'normal' => array('admin.css', 'forms.css'),
		'ie6'    => array(),
		'ie7'    => array(),
		'print'  => array(),
	);
	public $helpers = array('Form', 'Menu');

	public $cache = 0;

	public function beforeAction()
	{
		$this->setData('user', '');

		/*$table = new Table_Page();
		$pages = $table->getAll('position', 'asc');

		$menu_helper = new Core_Helper_Menu();
		$menu = $menu_helper->prepare($pages);
		$this->setData('menu_items', $menu);*/

		//$this->setData('images_dir', APP_URL.'/styles/'.$this->style.'/images');
	}

	/**
	* __default
	*
	* @return void
	*/
	public function __default(){
		$this->response->setPost('send', '');
		new Component_Login($this, 'login');
	}

	public function login(){
		$this->__default();
	}
}
