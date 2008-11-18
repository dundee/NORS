<?php

/**
* Page
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Page
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Page extends Core_Module
{
	public $css = array(
		'normal' => array('layout.css'),
		'ie6'    => array('ie6.css'),
		'ie7'    => array('ie7.css'),
		'print'  => array('print.css'),
	);
	public $helpers = array('Menu');

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
		$table = new Table_Page();
		$page = $table->findById(intval($this->request->getGet('page')));

		if ($page) $this->setData('text', $page->text, TRUE);
		else $this->setData('text', '', TRUE);
	}
}
