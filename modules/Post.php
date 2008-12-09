<?php

/**
* Post
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Post
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Post extends Core_Module
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

	public function __default()
	{
		if (intval($this->request->getGet('post')) > 0) $this->view_post();
		else $this->list_posts();
	}

	public function list_posts()
	{
		$this->tplFile = 'posts.tpl.php';

		$table = new Table_Post();
		$posts = $table->getAll('date', 'desc');

		$text = new Core_Text();

		foreach ($posts as $i=>$post) {
			$url = $text->urlEncode($post->name);
			$posts[$i]->url  = $this->router->forward(array('post' => $post->id_post . '-' . $url));
			$posts[$i]->text = $text->getWords(Core_Config::singleton()->front_end->perex_length, $post->text);
		}

		$this->setData('posts', $posts);
	}

	/**
	* __default
	*
	* @return void
	*/
	public function view_post()
	{
		$table = new Table_Page();
		$page = $table->findById(intval($this->request->getGet('page')));

		if ($page) $this->setData('text', $page->text, TRUE);
		else $this->setData('text', '', TRUE);
	}
}
