<?php

/**
* Category
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Category extends Core_Controller
{
	public $css = array(
		'normal' => array('layout.css'),
		'ie6'    => array('ie6.css'),
		'ie7'    => array('ie7.css'),
		'print'  => array('print.css'),
	);

	public $js = array('jquery.js',
	//                   'paging.js'
	);

	public $helpers = array('Menu');

	public $cache = 0;

	public $views = array('Default', 'Rss');

	public function beforeFooter()
	{
		$menu_helper = new Core_Helper_Menu();

		$category = new Table_Category();
		$categories = $category->getAll('name', 'asc');
		$categories = $menu_helper->prepare($categories, 'category');
		$this->setData('categories', $menu_helper->render($categories, 4), TRUE);

		$table = new Table_Page();
		$pages = $table->getActive('position', 'asc');
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
		$text_obj = new Core_Text();
		$this->tplFile = 'posts.tpl.php';
		$id_category = intval($this->request->getGet('category'));


		$category = new ActiveRecord_Category($id_category);
		if (!$category->id_category) throw new Exception('Cathegory not found', 404);

		$page = $this->request->getPost('p');
		if ($page === FALSE) $page = $this->request->getGet('p');

		//is URL canonical?
		$url = $this->request->getUrl();
		$url = str_replace('&','&amp;',$url);
		$url_name = $text_obj->urlEncode($category->name);
		$can_url = $this->router->genUrl('category', FALSE, 'category', array('category' => $category->id_category . '-' . $url_name, 'p'=>$page));
		if ($this->request->view != 'Default') {
			$can_url .= '?' . strtolower($this->request->view);
		}
		if ($can_url != $url) {
			$this->router->redirect($can_url, FALSE, FALSE, FALSE, FALSE, TRUE);
		}

		$this->setData('title', $category->name);

		if ($page == '') {
			$page = $this->request->getCookie('page');
			$this->response->setPost('p', $page);
		}

		$max = $this->config->front_end->posts_per_page;
		$limit = ($page * $max) . ',' . $max;

		$table = new Table_Post();
		$posts = $table->getByCategory($id_category, 'date', 'desc', $limit);
		$count = $table->getCountByCategory($id_category);

		$text_obj = new Core_Text();

		if (iterable($posts)) {
			foreach ($posts as &$post) {
				$url  = $text_obj->urlEncode($post->name);
				$curl = $text_obj->urlEncode($post->category_name);
				$text = $text_obj->getPerex(Core_Config::singleton()->front_end->perex_length, $post->text);
				$text = strip_tags($text);
				$text = $text_obj->clearAmpersand($text);

				$post->name = clearOutput($post->name);
				$post->url  = $this->router->genUrl('post', FALSE, 'post', array('post' => $post->id_post . '-' . $url));
				$post->text = $text;
				$post->category_url = $this->router->genUrl('category', FALSE, 'category', array('category' => $post->id_category . '-' . $curl));
				$post->date         = Core_Locale::factory()->decodeDatetime($post->date);
			}
		}

		$this->setData('items', $posts, TRUE);

		$helper = new Core_Helper_AjaxPaging('category');
		$paging = $helper->paging($count, $max, TRUE);
		$this->setData('paging', $paging, TRUE);
	}
}
