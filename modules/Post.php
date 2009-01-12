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
		'normal' => array('layout.css',
	                      'forms.css',
	                      'thickbox.css',
		                  ),
		'ie6'    => array('ie6.css'),
		'ie7'    => array('ie7.css'),
		'print'  => array('print.css'),
	);
	public $helpers = array('Menu');

	public $js = array('jquery.js',
	                   'jquery.thickbox.js',
	                   'comment.js',
	                   'paging.js');

	public $cache = 0;

	public $views = array('Default', 'Rss');

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
		if (intval($this->request->getGet('post')) > 0) $this->view_post();
		else $this->list_items();
	}

	public function list_items()
	{
		$this->tplFile = 'posts.tpl.php';

		$max = $this->config->front_end->posts_per_page;
		$limit = ($this->request->getPost('page') * $max) . ',' . $max;

		$table = new Table_Post();
		$posts = $table->getPosts('date', 'desc', $limit);
		$count = $table->getCount();

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

		$helper = new Core_Helper_AjaxPaging('post');
		$paging = $helper->paging($count, $max, TRUE);
		$this->setData('paging', $paging, TRUE);
	}

	/**
	*
	* @return void
	*/
	public function view_post()
	{
		$id_post = intval($this->request->getGet('post'));

		if ($this->request->getPost('send')) {
			if ($this->request->getPost('check') != 3) $this->router->redirect('http://www.prdel.cz');

			$comment = new ActiveRecord_Comment();
			$comment->user  = $this->request->getPost('user');
			$comment->www   = $this->request->getPost('www');
			$comment->email = $this->request->getPost('email');
			$comment->text  = $this->request->getPost('text');
			$comment->ip    = $this->request->getServer('REMOTE_ADDR');
			$comment->id_post  = $id_post;
			$comment->date  = date('Y-m-d H:i:s');
			$comment->save();

			//disable F5 to cause resending
			$this->router->redirect('post', '__default', FALSE, FALSE, TRUE);
		}

		$table = new Table_Post();
		list($post) = $table->findById($id_post);

		if ($post) $this->setData('post', $post, TRUE);
		else $this->setData('post', '', TRUE);

		$files = $post->getFiles();
		$this->setData('photos', $files);

		$table = new Table_Comment();
		$comments = $table->findById_Post($post->getID());
		$text = new Core_Text();

		$coms = array();
		if (iterable($comments)) {
			$i = 0;
			foreach ($comments as $comment) {
				++$i;
				$coms[$i]['href'] = $comment->www ? ltrim(strtolower($comment->www), 'javascript:')
				                                  : ($comment->email ? $text->hideMail('mailto:' . $comment->email) : '');
				$coms[$i]['user'] = htmlspecialchars($comment->user);
				$coms[$i]['text'] = $text->format_comment($comment->text);
				$coms[$i]['id']   = $comment->getID();
				$coms[$i]['date'] = $comment->date;

				while(eregi("\[([[:digit:]]+)\]",$coms[$i]['text'])){
					$j = eregi_replace(".*\[([[:digit:]]+)\].*","\\1",$coms[$i]['text']);
					if ($i > $j) {
						$reaction[$i][]    = $j;
						$inspiration[$j][] = $i;
					}
					$coms[$i]['text'] = eregi_replace("\[($j)\]","#$j#",$coms[$i]['text']);
				}
			}

			//bind reaction and inspiration
			foreach ($coms as $i => $com) {
				if (isset($reaction[$i]) && iterable($reaction[$i])) {
					foreach($reaction[$i] as $r){
						$span = '<span class="reaction"><a href="#post' . $coms[$r]['id'] . '">#' . $r . ' ' . $coms[$r]['user'] . '</a>:</span> ';
						$coms[$i]['text'] = str_replace("#$r#", $span, $coms[$i]['text']);
					}
				}
				if (isset($inspiration[$i]) && iterable($inspiration[$i])) {
					foreach($inspiration[$i] as $insp){
						$coms[$i]['text'] .= '<div class="inspiration">' . __('replied_by') . ' <a href="#post'.$coms[$insp]['id'].'">[' . $insp . '] '.$coms[$insp]['user'].'</a></div>';
					}
				}
			}
		}
		$this->setData('comments', $coms, TRUE);

		$form = new Core_Helper_Form();
		$form->form(NULL, '#', __('add') . ' ' . __('comment'), __('ok'));
		$form->input(NULL, 'user', __('username'))->setValidation();
		$form->input(NULL, 'www', __('www'));
		$form->input(NULL, 'email', __('email'));
		$form->input(NULL, 'check', '1 + 2?');
		$form->textarea(NULL, 'text', 'text');
		$this->setData('comment_form', $form->render(1, TRUE), TRUE);
	}
}
