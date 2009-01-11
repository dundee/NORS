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
	                   'cat-paging.js');

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

	public function __default()
	{
		$this->tplFile = 'posts.tpl.php';

		$max = $this->config->front_end->posts_per_page;
		$offset = $this->request->getPost('page') * $max;

		$table = new Table_Post();
		$posts = $table->getByCathegory(intval($this->request->getGet('cathegory')));
		$count = count($posts);
		if (iterable($posts)) $posts = array_slice($posts , $offset, $max);

		$text = new Core_Text();

		if (iterable($posts)) {
			foreach ($posts as $i=>$post) {
				$url = $text->urlEncode($post->name);
				$curl = $text->urlEncode($post->cathegory_name);
				$posts[$i]->url  = $this->router->genUrl('post', FALSE, 'post', array('post' => $post->id_post . '-' . $url));
				$posts[$i]->text = $text->getWords(Core_Config::singleton()->front_end->perex_length, $post->text);
				$posts[$i]->cathegory_url = $this->router->genUrl('cathegory', FALSE, 'cathegory', array('cathegory' => $post->cathegory . '-' . $curl));
			}
		}

		$this->setData('posts', $posts, TRUE);

		$helper = new Core_Helper_AjaxPaging();
		$paging = $helper->paging($count, $max, TRUE);
		$this->setData('paging', $paging, TRUE);
	}
}
