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
class Page extends Core_Controller
{
	public $css = array(
		'normal' => array('layout.css'),
		'ie6'    => array('ie6.css'),
		'ie7'    => array('ie7.css'),
		'print'  => array('print.css'),
	);
	public $helpers = array('Menu');

	public $cache = 0;

	public function beforeFooter()
	{
		$menu_helper = new Core_Helper_Menu();

		$cathegory = new Table_Cathegory();
		$cathegories = $cathegory->getAll('name', 'asc');
		$cathegories = $menu_helper->prepare($cathegories, 'cathegory');
		$this->setData('cathegories', $menu_helper->render($cathegories, 4), TRUE);

		$table = new Table_Page();
		$pages = $table->getAll('position', 'asc');
		$pages = $menu_helper->prepare($pages, 'page');
		$this->setData('pages', $menu_helper->render($pages, 4), TRUE);

		$this->setData('administration', $this->router->genUrl('administration', FALSE, 'default'));

		$this->setData('name',        $this->config->name);
		$this->setData('description', $this->config->description);
	}

	/**
	* __default
	*
	* @return void
	*/
	public function __default()
	{
		$table = new Table_Page();
		list($page) = $table->findById(intval($this->request->getGet('page')));

		$this->setData('title', $page->name);

		if ($page) $this->setData('text', $page->text, TRUE);
		else $this->setData('text', '', TRUE);
	}
}
