<?php

/**
* Page
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
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

		$category = new Table_Category();
		$categories = $category->getAll('name', 'asc');
		$categories = $menu_helper->prepare($categories, 'category');
		$this->setData('categories', $menu_helper->render($categories, 4), TRUE);

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
		$text_obj = new Core_Text();
		$table = new Table_Page();
		$pages = $table->findById(intval($this->request->getGet('page')));
		if (!iterable($pages)) throw new Exception('Page not found', 404);
		$page = $pages[0];

		//is URL canonical?
		$url = $this->request->getUrl();
		$url = str_replace('&','&amp;',$url);
		$url_name = $text_obj->urlEncode($page->name);
		$can_url = $this->router->genUrl('page', FALSE, 'page', array('page' => $page->id_page . '-' . $url_name));
		if ($can_url != $url) {
			$this->router->redirect($can_url, FALSE, FALSE, FALSE, FALSE, TRUE);
		}

		$this->setData('title', $page->name);

		if ($page) $this->setData('text', $page->text, TRUE);
		else $this->setData('text', '', TRUE);
	}
}
