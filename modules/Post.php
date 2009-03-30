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
	                      'markitup-comment.css',
		                  ),
		'ie6'    => array('ie6.css'),
		'ie7'    => array('ie7.css'),
		'print'  => array('print.css'),
	);
	public $helpers = array('Menu');

	public $js = array('jquery.js',
	                   'jquery.thickbox.js',
	                   'paging.js',
	                   'jquery.markitup.js',
	                   'set-comment.js',
	                   'post.js');

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
		$pages = $table->getPages('position', 'asc');
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

		$page = $this->request->getPost('page');
		if ($page == '') {
			$page = $this->request->getCookie('page');
			$this->response->setPost('page', $page);
		}

		$max = $this->config->front_end->posts_per_page;
		$limit = ($page * $max) . ',' . $max;

		//setcookie
		$this->response->setCookie('page', $page);

		if ($this->request->getVar('view') == 'rss') {
			$limit = 20;
		}

		$table = new Table_Post();
		$posts = $table->getPosts('date', 'desc', $limit);
		$count = $table->getCount();

		$text_obj = new Core_Text();

		if (iterable($posts)) {
			foreach ($posts as $i=>$post) {
				$url = $text_obj->urlEncode($post->name);
				$curl = $text_obj->urlEncode($post->cathegory_name);
				$text = $text_obj->getPerex(Core_Config::singleton()->front_end->perex_length, $post->text);
				$text = strip_tags($text);
				$text = $text_obj->clearAmpersand($text);

				$posts[$i]->url            = $this->router->genUrl('post', FALSE, 'post', array('post' => $post->id_post . '-' . $url));
				$posts[$i]->text           = $text;
				$posts[$i]->cathegory_url  = $this->router->genUrl('cathegory', FALSE, 'cathegory', array('cathegory' => $post->id_cathegory . '-' . $curl));
				$posts[$i]->name           = clearOutput($post->name);
				$posts[$i]->cathegory_name = clearOutput($post->cathegory_name);
				$posts[$i]->date           = Core_Locale::factory()->decodeDatetime($post->date);
			}
		}

		$this->setData('items', $posts, TRUE);

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
		$this->response->setGet('rss', FALSE);
		$id_post = intval($this->request->getGet('post'));
		$text_obj = new Core_Text();

		//save comment
		if ($this->request->getPost('send')) {
			$text = $this->request->getPost('text');

			do {
				if ($this->request->getPost('check') != 3) break;
				if ($this->request->getPost('subject') != '') break;
				if (strpos($text, 'href') !== FALSE) break;
				if (strpos($this->request->getServer('HTTP_REFERER'), APP_URL) !== 0) break;

				$comment = new ActiveRecord_Comment();
				$comment->user  = $this->request->getPost('user');
				$comment->www   = $this->request->getPost('www');
				$comment->email = $this->request->getPost('email');
				$comment->text  = $this->request->getPost('text');
				$comment->ip    = $this->request->getServer('REMOTE_ADDR');
				$comment->id_post  = $id_post;
				$comment->date  = date('Y-m-d H:i:s');
				$comment->save();

				//send cookies
				$this->response->setCookie('user', $comment->user);
				$this->response->setCookie('email', $comment->email);
				$this->response->setCookie('www', $comment->www);
			} while (FALSE);

			//disable F5 to cause resending
			$this->router->redirect('post', '__default', FALSE, FALSE, TRUE);
		}

		$table = new Table_Post();

		//incement seen
		list($post) = $table->findById($id_post);
		$post->seen += 1;
		$post->save();

		list($post) = $table->findById($id_post);

		$this->setData('title', $post->name);

		$post->text = $text_obj->format_html($post->text);
		$post->name = clearOutput($post->name);

		if ($post) $this->setData('post', $post, TRUE);
		else $this->setData('post', '', TRUE);

		$files = $post->getFiles();
		$this->setData('photos', $files);

		//karma
		$eval = '';
		if ($this->request->getCookie('eval')) $eval = 'Karma: ' . round($post->karma, 2);
		else for ($i = 1; $i <= 10; $i++) $eval .= '<a href="#" title="' . $i . '">' . $i . '</a>';
		$this->setData('eval', $eval, TRUE);

		$table = new Table_Comment();
		$comments = $table->findById_Post($post->getID());
		$text = new Core_Text();

		$coms = array();
		if (iterable($comments)) {
			$i = 0;
			foreach ($comments as $comment) {
				++$i;

				$coms[$i] = new StdClass();

				if ($comment->www) {
					$comment->www = strtolower($comment->www);
					$coms[$i]->href = (strpos($comment->www, 'http') === 0) ? $comment->www : 'http://' . $comment->www;
				} elseif ($comment->email) {
					$coms[$i]->href = $text->hideMail('mailto:' . $comment->email);
				} else {
					$coms[$i]->href = '';
				}

				$coms[$i]->user = strip_tags($comment->user);
				$coms[$i]->text = $text->format_comment($comment->text);
				$coms[$i]->id   = $comment->getID();
				$coms[$i]->date = $comment->date;
				$coms[$i]->name = $coms[$i]->user;
				$coms[$i]->url  =$this->router->genUrl('post', '__default', FALSE, FALSE, TRUE) . '#post' . $coms[$i]->id;

				while(eregi("\[([[:digit:]]+)\]",$coms[$i]->text)){
					$j = eregi_replace(".*\[([[:digit:]]+)\].*","\\1",$coms[$i]->text);
					if ($i > $j) {
						$reaction[$i][]    = $j;
						$inspiration[$j][] = $i;
					}
					$coms[$i]->text = eregi_replace("\[($j)\]","#$j#",$coms[$i]->text);
				}
			}

			//bind reaction and inspiration
			foreach ($coms as $i => $com) {
				if (isset($reaction[$i]) && iterable($reaction[$i])) {
					foreach($reaction[$i] as $r){
						if (!isset($coms[$r])) continue;
						$span = '<span class="reaction"><a href="#post' . $coms[$r]->id . '">#' . $r . ' ' . $coms[$r]->user . '</a>:</span> ';
						$coms[$i]->text = str_replace("#$r#", $span, $coms[$i]->text);
					}
				}
				if (isset($inspiration[$i]) && iterable($inspiration[$i])) {
					foreach($inspiration[$i] as $insp){
						$coms[$i]->text .= '<div class="inspiration">' . __('replied_by') . ' <a href="#post' . $coms[$insp]->id . '">[' . $insp . '] ' . $coms[$insp]->user . '</a></div>';
					}
				}
			}
		}
		$this->setData('items', $coms, TRUE);

		$r = $this->request;

		$form = new Core_Helper_Form();
		$form->form(NULL, '#', __('add') . ' ' . __('comment'), __('ok'));
		$form->input(NULL, 'user', __('username'))->setValidation()->setParam('value', $r->getCookie('user'));
		$form->input(NULL, 'www', __('www'))->setParam('value', $r->getCookie('www'));
		$form->input(NULL, 'email', __('email'))->setParam('value', $r->getCookie('email'));
		$form->input(NULL, 'subject', __('subject'));
		$form->input(NULL, 'check', '1 + 2?');
		$form->textarea(NULL, 'text', 'text');
		$this->setData('comment_form', $form->render(1, TRUE), TRUE);
	}
}
