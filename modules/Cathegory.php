<?php

/**
* Cathegory
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Nors
*/

/**
* Cathegory
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Cathegory extends Core_Module
{
	public $css = array(
		'normal' => array('layout.css'),
		'ie6'    => array('ie6.css'),
		'ie7'    => array('ie7.css'),
		'print'  => array('print.css'),
	);

	public $js = array('jquery.js',
	                   'paging.js');

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
		$pages = $table->getPages('position', 'asc');
		$pages = $menu_helper->prepare($pages, 'page');
		$this->setData('pages', $menu_helper->render($pages, 4), TRUE);

		$this->setData('administration', $this->router->genUrl('administration', FALSE, 'default'));

		$this->setData('name',        $this->config->name);
		$this->setData('description', $this->config->description);
	}

	public function __default()
	{
		$this->list_items();
	}

	public function list_items()
	{
		$this->tplFile = 'posts.tpl.php';
		$id_cathegory = intval($this->request->getGet('cathegory'));

		$max = $this->config->front_end->posts_per_page;
		$limit = ($this->request->getPost('page') * $max) . ',' . $max;

		$table = new Table_Post();
		$posts = $table->getByCathegory($id_cathegory, 'date', 'desc', $limit);
		$count = $table->getCountByCathegory($id_cathegory);

		$text_obj = new Core_Text();

		if (iterable($posts)) {
			foreach ($posts as $i=>$post) {
				$url = $text_obj->urlEncode($post->name);
				$curl = $text_obj->urlEncode($post->cathegory_name);
				$text = $text_obj->getWords(Core_Config::singleton()->front_end->perex_length, $post->text);
				$text = strip_tags($text);
				$text = $text_obj->clearAmpersand($text);

				$posts[$i]->url  = $this->router->genUrl('post', FALSE, 'post', array('post' => $post->id_post . '-' . $url));
				$posts[$i]->text = $text;
				$posts[$i]->cathegory_url = $this->router->genUrl('cathegory', FALSE, 'cathegory', array('cathegory' => $post->cathegory . '-' . $curl));
			}
		}

		$this->setData('posts', $posts, TRUE);

		$helper = new Core_Helper_AjaxPaging('cathegory');
		$paging = $helper->paging($count, $max, TRUE);
		$this->setData('paging', $paging, TRUE);
	}
}
