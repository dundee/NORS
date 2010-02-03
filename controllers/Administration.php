<?php

/**
* Administration
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Administration extends Core_Controller_Auth
{

	public $css = array('normal' => array('admin.css',
	                                      'markitup.css',
	                                      'jquery.lightbox-0.5.css',
	                                      'jquery-ui-1.7.2.custom.css'),
	                   );
	public $js = array('jquery.js',
	                   'dump_filter.js',
	                   'jquery.markitup.pack.js',
	                   'jquery-ui-1.7.2.custom.min.js',
	                   'set.js',
	                   'jquery.lightbox-0.5.min.js',
	                   'jquery.blockUI.js',
	                   'jquery.datetimepicker.js',
	                   'admin_form.js',
	                   'clicker.js',
	                  );
	public $headerTplFile = 'header_admin.tpl.php';
	public $footerTplFile = 'footer_admin.tpl.php';
	public $helpers = array('Administration');

	public $cache = 0;

	public function __construct(){
		parent::__construct();
	}

	public function beforeAction()
	{
		$r = $this->router;
		$menu = array('news'         => $r->genUrl('administration',
		                                           'news',
		                                            FALSE),
		              'content'      => $r->genUrl('administration',
		                                           'content',
		                                            FALSE,
		                                            array('event' => $this->config->administration->content->default_event)),
		              'users'        => $r->genUrl('administration',
		                                           'users',
		                                            FALSE,
		                                            array('event' => $this->config->administration->users->default_event)),
		              'settings'     => $r->genUrl('administration',
		                                           'settings',
		                                            FALSE,
		                                            array('event' => $this->config->administration->settings->default_event)),
		              );
		$selected = $this->request->getGet('action');
		if ($selected == '__default') $selected = 'content';

		$this->setData('selected', $selected);
		$this->setData('menu', $menu);
		$this->setData('user', $this->user->userName);
		$this->setData('logout_url', $r->genUrl('administration','logout','default', FALSE, FALSE, FALSE, TRUE));
		$this->setData('title', 'Administration');
	}

	/**
	* __default
	*
	* @return void
	*/
	public function __default()
	{
		$this->content();
	}

	public function news()
	{
		$this->tplFile = 'admin_news.tpl.php';
		$this->setData('src', 'http://norsphp.com/news.htm');
	}

	public function content()
	{
		$r = $this->router;
		$submenu = array(
			'category'    => array('label' => 'categories',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('event' => 'category'))),
			'post'    => array('label' => 'posts',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('event' => 'post'))),
			'page'    => array('label' => 'pages',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('event' => 'page'))),
			'comment' => array('label' => 'comments',
			                   'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('event' => 'comment'))),
		);
		$this->guidepost('content', $submenu);
	}

	public function event_category()
	{
		$r = $this->router;
		$actions = array('add'  => $r->forward(array('command'=>'add', 'id'=>0)),
		                 'tree' => $r->forward(array('command'=>'tree')),
		                 );
		$this->basic_page('category', $actions);
	}

	public function event_post()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('command'=>'add', 'id'=>0)));
		$this->basic_page('post', $actions);
	}

	public function event_page()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('command'=>'add', 'id'=>0)));
		$this->basic_page('page', $actions);
	}

	public function event_comment()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('command'=>'add', 'id'=>0)));
		$this->basic_page('comment', $actions);
	}

	public function users()
	{
		$r = $this->router;

		$submenu = array(
			'user'   => array('label' => 'users',
			                   'link'  => $r->genUrl('administration',
			                                         'users',
			                                         FALSE,
			                                         array('event' => 'user'))),
			'group'  => array('label' => 'groups',
			                   'link'  => $r->genUrl('administration',
			                                         'users',
			                                         FALSE,
			                                         array('event' => 'group'))),
		);

		$this->setData('submenu', $submenu);
		$this->guidepost('users', $submenu);
	}

	public function event_user()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('command'=>'add')));
		$this->basic_page('user', $actions);
	}

	public function event_group()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('command'=>'add')));
		$this->basic_page('group', $actions);
	}

	public function settings()
	{
		$r = $this->router;
		$submenu = array(
			'basic'    => array('label' => 'basic',
			                    'link'  => $r->genUrl('administration',
			                                          'settings',
			                                          FALSE,
			                                          array('event' => 'basic'))),
			'advanced' => array('label' => 'advanced',
			                    'link'  => $r->genUrl('administration',
			                                          'settings',
			                                          FALSE,
			                                          array('event' => 'advanced'))),
		);

		$this->guidepost('settings', $submenu);
	}

	public function event_basic()
	{
		$r = $this->request;
		$c = $this->config;

		$this->tplFile = 'admin_basic.tpl.php';

		if ($r->getPost('send')) {
			$this->checkCSRF();

			include(APP_PATH . '/cache/' . $c->host . '.yml.php.cache.php');
			$config = $data;

			$config['name']        = $r->getPost('name');
			$config['description'] = $r->getPost('description');
			$config['keywords']    = $r->getPost('keywords');

			Core_Parser_YML::write($config, APP_PATH . '/config/' . $c->host . '.yml.php');
			$c->read(APP_PATH . '/config/' . $c->host . '.yml.php', TRUE);
		}

		$form = new Core_Helper_Form();
		$form->form(NULL, '', __('basic_settings'), __('save'));
		$form->input(NULL, 'name', __('name_of_web'))        ->setParam('value', $c->name);
		$form->input(NULL, 'description', __('description')) ->setParam('value', $c->description);
		$form->input(NULL, 'keywords', __('keywords'))       ->setParam('value', $c->keywords);

		//CSRF protection
		if ($r->getServer('REMOTE_ADDR') == 'unit') $key = 1; //unit tests
		else $key = rand(0, 100);
		$hash = md5($r->getSession('password') . $key . $r->sessionID());
		$form->input(FALSE, 'token', FALSE, 'hidden', $hash . $key);

		$this->setData('form', $form);



	}

	public function event_advanced()
	{
		$this->tplFile = 'admin_advanced_settings.tpl.php';
		$c = $this->config;

		include(APP_PATH . '/cache/' . $c->host . '.yml.php.cache.php');

		if ($this->request->getPost('send')) {
			$config = convertArrayToObject($data);
			$post = $this->request->getPost();
			unset($post['send']);

			foreach ($post as $name => $value) {
				$p = $config;
				$arr = explode('__', $name);
				for ($i=0; $i < count($arr) - 1; $i++) {
					$next = $arr[$i];
					$p = $p->{$next};
				}
				$p->$arr[count($arr) - 1] = $value;
			}

			$config = convertObjectToArray($config);

			Core_Parser_YML::write($config, APP_PATH . '/config/' . $c->host . '.yml.php');

			$this->router->redirect('administration', FALSE, FALSE, FALSE, TRUE);
		}

		$this->setData('settings', $data);
		$this->setData('clicker', new Core_Helper_Clicker());
	}

	public function logout()
	{
		$this->request->checkCSRF();
		$this->user->logout();
		$this->router->redirect('administration', '__default');
	}



	/**************************************************************************/


	protected function guidepost($name, $submenu = FALSE)
	{
		if ($this->request->getPost('table')) $this->save();

		if (!($subselected = $this->request->getGet('event')))
			$subselected = $this->config->administration->$name->default_event;

		$this->setData('subselected', $subselected);
		$this->setData('submenu', $submenu);

		if (!method_exists($this, 'event_' . $subselected)) throw new UnexpectedValueException('Could not find event ' . $subselected . ', url: ' . $this->request->getUrl(), 404);
		$this->{'event_' . $subselected}();
	}

	protected function basic_page($event, $actions = FALSE)
	{
		$this->tplFile = 'admin_list.tpl.php';

		$this->response->setGet('event', $event);

		$this->setData('actions', $actions, TRUE);

		if (!($command = $this->request->getGet('command'))) {
			$command = 'dump';
		}

		if (!method_exists($this, $command)) throw new UnexpectedValueException('Could not find command ' . $command . ', url: ' . $this->request->getUrl(), 404);
		$this->{$command}($event);
	}

	protected function dump($table)
	{
		//load saved settings
		$resp = $this->response;
		$req  = $this->request;
		if ($req->getCookie('table') == $table) {
			$resp->setPost('name', $req->getCookie('name'));
			$resp->setPost('p', $req->getCookie('page'));
			$resp->setPost('order', $req->getCookie('order'));
			$resp->setPost('a', $req->getCookie('a'));
		}

		new Component_DumpFilter($this, 'dump_filter', array('table' => $table));
		new Component_Dump($this, 'dump', array('table' => $table));
	}

	protected function add($table)
	{
		$this->tplFile = 'admin_form.tpl.php';
		new Component_AdminForm(
			$this,
			'form',
			array('table'  => $table,
			      'action' => $this->router->genURL('administration',
			                                        $this->request->getGet('action'),
			                                        FALSE,
			                                        array('event'=> $this->request->getGet('event'))
			      )
			)
		);
	}

	protected function edit($table)
	{
		$this->tplFile = 'admin_form.tpl.php';
		new Component_AdminForm(
			$this,
			'form',
			array('table' => $table,
			      'id'     => $this->request->getGet('id'),
			      'action' => $this->router->genURL('administration',
			                                        $this->request->getGet('action'),
			                                        FALSE,
			                                        array('event'=> $this->request->getGet('event'))
			                  )
			)
		);
	}

	protected function save()
	{
		$this->checkCSRF();

		$class = 'ActiveRecord_' . ucfirst($this->request->getPost('table'));
		$id = $this->request->getPost('id');
		$model = new $class($id);
		$post = $this->request->getPost();
		$html = FALSE;
		foreach ($model->fields as $key=>$val) {
			if ($val['type'] == 'bool') $model->{$key} = isset($post[$key]) ? $post[$key] : FALSE;
			elseif (isset($post[$key])) $model->{$key} = $post[$key];
		}

		try{
			$id = $model->save($id);
			if (count($_FILES) > 0) $model->saveFiles();
		} catch (Exception $ex) {
			echo $ex->getMessage();
			$this->setData('errors', $ex->getMessage());
		}

		$this->response->setPost('name', '');

		if ($this->request->getPost('send_continue')) {
			$this->router->redirect('administration',
			                         $this->request->getGet('action'),
			                         FALSE,
			                         array('command' => 'edit',
			                               'id' => $id),
			                         TRUE);
		}

		//disable F5 to cause resending
		$this->router->redirect('administration',
		                         $this->request->getGet('action'),
		                         FALSE,
		                         FALSE,
		                         TRUE //inherit
		                         );
	}

	protected function tree($table)
	{
		$this->tplFile = 'admin_tree.tpl.php';

		switch ($table) {
			default:
				$parent = $table;
		}

		$this->setData('table', $table);
		$this->setData('parent', $parent);
	}

	protected function activate($table)
	{
		$this->request->checkCSRF();

		$class = 'ActiveRecord_' . ucfirst($table);
		$model = new $class($this->request->getGet('id'));
		$model->activate();

		$this->router->redirect('administration',
		                        $this->request->getGet('action'),
		                        FALSE,
		                        array('event' => $this->request->getGet('event'))
		                        );
	}

	protected function del($table)
	{
		$this->request->checkCSRF();

		$class = 'ActiveRecord_' . ucfirst($table);
		$model = new $class($this->request->getGet('id'));
		$model->delete();

		//$this->dump($table);
		$this->router->redirect('administration',
		                        $this->request->getGet('action'),
		                        FALSE,
		                        array('event' => $this->request->getGet('event'))
		                        );
	}

	protected function checkCSRF()
	{
		$token = $this->request->getPost('token');
		$hash_part = substr($token, 0, 32);
		$key       = substr($token, 32);
		$hash = md5($this->request->getSession('password') . $key . $this->request->sessionID());
		if ($hash !== $hash_part)
			throw new Exception('Cross site request forgery attact from IP: ' . $this->request->getServer('REMOTE_ADDR'), 401);
	}
}
