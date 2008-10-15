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

	public $css = array('admin.css',
	                    'markitup.css',
	                    'thickbox.css'
	                   );
	public $js = array('jquery-1.2.2.min.js',
	                   'jquery.dump_filter.js',
	                   'jquery.markitup.js',
	                   'set.js',
	                   'jquery.thickbox.js',
	                   'jquery.blockUI.js',
	                   'jquery.admin_form.js'
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
		$r = $this->request;
		$menu = array('content'      => $r->genUrl('administration','content',FALSE),
		              'users'        => $r->genUrl('administration','users',FALSE));

		$this->setData('selected', $r->getGet('event'));
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
		//$this->content();
	}

	public function content()
	{
		if ($this->request->getPost('table')) $this->save();

		$r = $this->request;
		$submenu = array(
			'towns'    => array('label' => 'municipality',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' =>
			                                                'towns'))),
			'services' => array('label' => 'service',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' =>
			                                                'services'))),
			'turinfo'  => array('label' => 'turinfo',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' =>
			                                                'turinfo'))),
		);
		if (!($subselected = $r->getGet('subevent')))
			$subselected = $this
			               ->config
			               ->administration
			               ->content
			               ->default_subevent;

		$this->setData('subselected', $subselected);
		$this->setData('submenu', $submenu);

		$this->{$subselected}();
	}

	public function users()
	{
		$r = $this->request;
		$this->tplFile = 'admin_list.tpl.php';

		$this->request->setGet('subevent', 'users');

		$actions = array('add' => $r->forward(array('action'=>'add')));
		$this->setData('actions', $actions, TRUE);

		$this->setData('submenu', array());
		$this->setData('subselected', FALSE);

		if (!($action = $r->getGet('action'))) $action = 'dump';
		$this->{$action}('user');
	}

	public function logout()
	{
		$this->user->logout();

	}

	protected function dump($table)
	{
		new Component_DumpFilter($this,
		                         'dump_filter',
		                         array('table' => $table));
		new Component_Dump($this, 'dump', array('table' => $table));

	}

	protected function add($table)
	{
		$this->tplFile = 'admin_form.tpl.php';
		new Component_AdminForm(
			$this,
			'form',
			array('table'  => $table,
			      'action' => $this
			                  ->request
			                  ->genURL('administration',
			                           'content',
			                           FALSE,
			                           array('subevent'=> $this
			                                              ->request
			                                              ->getGet('subevent'))
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
			      'action' => $this
			                  ->request
			                  ->genURL('administration',
			                           'content',
			                           FALSE,
			                           array('subevent'=> $this
			                                              ->request
			                                              ->getGet('subevent'))
			                  )
			)
		);
	}

	public function save()
	{
		$class = 'ActiveRecord_' . ucfirst($this->request->getPost('table'));
		$id = $this->request->getPost('id');
		$model = new $class($id);
		$arr = $this->request->post();
		$html = FALSE;
		foreach ($arr as $key=>$val) {
			$model->{$key} = $val;
		}

		try{
			$model->save($id);
			if (count($_FILES) > 0) $model->saveFiles();

		} catch (Exception $ex) {
			echo $ex->getMessage();
			$this->setData('errors', $ex->getMessage());
		}
	}

	protected function activate($table)
	{
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
		$class = 'ActiveRecord_' . ucfirst($table);
		$model = new $class($this->request->getGet('id'));
		$model->delete();

		$this->dump($table);
	}
}
