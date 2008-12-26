<?php

/**
* Administration
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Nors4
*/

/**
* Administration
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors4
*/
class Administration extends Core_Module_Auth
{

	public $css = array('normal' => array('admin.css',
	                                      'markitup.css',
	                                      'thickbox.css'),
	                   );
	public $js = array('jquery-1.2.2.min.js',
	                   'jquery.dump_filter.js',
	                   'jquery.markitup.js',
	                   'set.js',
	                   'jquery.thickbox.js',
	                   'jquery.blockUI.js',
	                   'ui.datetimepicker.js',
	                   'jquery.admin_form.js',
	                   'jquery.clicker.js',
	                  );
	public $headerTplFile = 'header_admin.tpl.php';
	public $footerTplFile = 'footer_admin.tpl.php';
	public $helpers = array('Administration');

	public $cache = 0;

	public function __construct(){
		parent::__construct();
	}

	public function beforeEvent()
	{
		$r = $this->router;
		$menu = array('content'      => $r->genUrl('administration',
		                                           'content',
		                                            FALSE,
		                                            array('subevent' => $this->config->administration->content->default_subevent)),
		              'users'        => $r->genUrl('administration',
		                                           'users',
		                                            FALSE,
		                                            array('subevent' => $this->config->administration->users->default_subevent)),
		              'settings'     => $r->genUrl('administration',
		                                           'settings',
		                                            FALSE,
		                                            array('subevent' => $this->config->administration->settings->default_subevent)),
		              );

		$this->setData('selected', $this->request->getGet('event'));
		$this->setData('menu', $menu);
		$this->setData('user', $this->user->userName);
		$this->setData('logout_url', $r->genUrl('administration','logout','default'));
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

	public function content()
	{
		$r = $this->router;
		$submenu = array(
			'cathegory'    => array('label' => 'cathegories',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' => 'cathegory'))),
			'post'    => array('label' => 'posts',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' => 'post'))),
			'gallery'    => array('label' => 'galleries',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' => 'gallery'))),
			'page'    => array('label' => 'pages',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' => 'page'))),
			'anquette'    => array('label' => 'anquettes',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' => 'anquette'))),
			'citate'    => array('label' => 'citates',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' => 'citate'))),
		);
		$this->guidepost('content', $submenu);
	}

	public function cathegory()
	{
		$r = $this->router;
		$actions = array('add'  => $r->forward(array('action'=>'add')),
		                 'tree' => $r->forward(array('action'=>'tree')),
		                 );
		$this->basic_page('cathegory', $actions);
	}

	public function post()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('action'=>'add')));
		$this->basic_page('post', $actions);
	}

	public function gallery()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('action'=>'add')));
		$this->basic_page('gallery', $actions);
	}

	public function page()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('action'=>'add')));
		$this->basic_page('page', $actions);
	}

	public function anquette()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('action'=>'add')));
		$this->basic_page('anquette', $actions);
	}

	public function citate()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('action'=>'add')));
		$this->basic_page('citate', $actions);
	}

	public function users()
	{
		$r = $this->router;

		$submenu = array(
			'user'   => array('label' => 'users',
			                   'link'  => $r->genUrl('administration',
			                                         'users',
			                                         FALSE,
			                                         array('subevent' => 'user'))),
			'group'  => array('label' => 'groups',
			                   'link'  => $r->genUrl('administration',
			                                         'users',
			                                         FALSE,
			                                         array('subevent' => 'group'))),
		);

		$this->setData('submenu', $submenu);
		$this->guidepost('users', $submenu);
	}

	public function user()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('action'=>'add')));
		$this->basic_page('user', $actions);
	}

	public function group()
	{
		$r = $this->router;
		$actions = array('add' => $r->forward(array('action'=>'add')));
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
			                                          array('subevent' => 'basic'))),
			'advanced' => array('label' => 'advanced',
			                    'link'  => $r->genUrl('administration',
			                                          'settings',
			                                          FALSE,
			                                          array('subevent' => 'advanced'))),
		);
		if (!($subselected = $this->request->getGet('subevent')))
			$subselected = $this->config->administration->settings->default_subevent;

		$this->setData('subselected', $subselected);
		$this->setData('submenu', $submenu);

		$this->{$subselected}();
	}

	public function basic()
	{
		$r = $this->request;
		$c = $this->config;
		$host = $c->host;

		$this->tplFile = 'admin_basic.tpl.php';

		if ($r->getPost('send')) {
			$this->checkCSRF();

			include(APP_PATH . '/cache/config.yml.php.cache.php');
			$config = $data;

			$config[$host]['name']        = $r->getPost('name');
			$config[$host]['description'] = $r->getPost('description');
			$config[$host]['keywords']    = $r->getPost('keywords');

			Core_Parser_YML::write($config, APP_PATH . '/config/config.yml.php');
			$c->read(APP_PATH . '/config/config.yml.php', TRUE);
		}

		$form = new Core_Helper_Form();
		$form->form(NULL, '', __('basic_settings'), __('save'));
		$form->input(NULL, 'name', __('name_of_web'))        ->setParam('value', $c->name);
		$form->input(NULL, 'description', __('description')) ->setParam('value', $c->description);
		$form->input(NULL, 'keywords', __('keywords'))       ->setParam('value', $c->keywords);

		//XSRF protection
		if ($r->getServer('REMOTE_ADDR') == 'unit') $key = 1; //unit tests
		else $key = rand(0, 100);
		$hash = md5($r->getSession('password') . $key);
		$form->input(FALSE, 'random_key', FALSE, 'hidden', $key);
		$form->input(FALSE, 'hashed_key', FALSE, 'hidden', $hash);

		$this->setData('form', $form);



	}

	public function advanced()
	{
		$this->tplFile = 'admin_advanced_settings.tpl.php';

		include(APP_PATH . '/cache/config.yml.php.cache.php');

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

			Core_Parser_YML::write($config, APP_PATH . '/config/config.yml.php');
		}

		$this->setData('settings', $data);
		$this->setData('clicker', new Core_Helper_Clicker());
	}

	public function logout()
	{
		$this->user->logout();
		$this->router->redirect('administration', '__default');
	}



	/**************************************************************************/


	protected function guidepost($name, $submenu = FALSE)
	{
		if ($this->request->getPost('table')) $this->save();

		if (!($subselected = $this->request->getGet('subevent')))
			$subselected = $this->config->administration->$name->default_subevent;

		$this->setData('subselected', $subselected);
		$this->setData('submenu', $submenu);

		$this->{$subselected}();
	}

	protected function basic_page($subevent, $actions = FALSE)
	{
		$this->tplFile = 'admin_list.tpl.php';

		$this->response->setGet('subevent', $subevent);

		$this->setData('actions', $actions, TRUE);

		if (!($action = $this->request->getGet('action'))) $action = 'dump';
		$this->{$action}($subevent);
	}

	protected function dump($table)
	{
		//load saved settings
		$resp = $this->response;
		$req  = $this->request;
		if ($req->getCookie('table') == $table) {
			$resp->setPost('name', $req->getCookie('name'));
			$resp->setPost('page', $req->getCookie('page'));
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
			                                        $this->request->getGet('event'),
			                                        FALSE,
			                                        array('subevent'=> $this->request->getGet('subevent'))
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
			                                        $this->request->getGet('event'),
			                                        FALSE,
			                                        array('subevent'=> $this->request->getGet('subevent'))
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
			$model->save($id);
			if (count($_FILES) > 0) $model->saveFiles();

		} catch (Exception $ex) {
			echo $ex->getMessage();
			$this->setData('errors', $ex->getMessage());
		}

		$this->response->setPost('name', '');

		$this->router->redirect('administration',
		                         $this->request->getGet('event'),
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

		$this->request->setGet('action', FALSE);
		$this->request->setGet('id', FALSE);
		$this->request->redirect('administration',
		                         'content',
		                         FALSE,
		                         FALSE,
		                         TRUE);
		die();
	}

	protected function del($table)
	{
		$this->request->checkCSRF();

		$class = 'ActiveRecord_' . ucfirst($table);
		$model = new $class($this->request->getGet('id'));
		$model->delete();

		$this->dump($table);
	}

	protected function checkCSRF()
	{
		$key = $this->request->getPost('random_key');
		$hash = md5($this->request->getSession('password') . $key);
		if ($hash != $this->request->getPost('hashed_key'))
			throw new Exception('Cross site request forgery attact from IP: ' . $this->request->getServer('REMOTE_ADDR'), 401);
	}
}
