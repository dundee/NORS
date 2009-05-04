<?php

/**
* Component_FileManager
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Component_FileManager extends Core_Component_Auth
{
	public $helpers = array();

	public $responseType = 'html';

	protected function beforeInit()
	{
		//$this->tplFile = 'basic_form.tpl.php';
	}

	/**
	* init
	*
	* @return void
	*/
	public function init($params = FALSE)
	{
		$db = Core_DB::singleton();
		$model   = $params['model'];

		$files = $model->getFiles($params['name']);

		if ($model instanceof ActiveRecord_Town) {
			$cat = new ActiveRecord_KategorieMist();
			$cats = $cat->getAll('nazev_kategorie_mista_cz', 'asc');
			$this->setData('kategorie', $cats);
		}

		$this->setData('files', $files);
	}

	public function del()
	{
		$name = $this->request->getPost('name');
		$url  = $this->request->getPost('url');
		$file  = $this->request->getPost('file');

		list($x, $url) = explode('?', $url);
		$arr = explode(';', $url);
		$params = array();
		foreach ($arr as $val) {
			list($k, $v) = explode('=', $val);
			$params[$k] = $v;
		}

		$name = preg_replace('/_div$/', '', $name);
		$modelName = rtrim($params['subaction'], 's');
		$class = 'ActiveRecord_' . ucfirst($modelName);
		$model = new $class($params['id']);

		$model->deleteFile($name, $file);

		$files = $model->getFiles();
		include(APP_PATH . '/tpl/component/filemanager.tpl.php');

		//$this->setData('html', $html);
	}

	public function update()
	{
		$name      = $this->request->getPost('name');
		$url       = $this->request->getPost('url');
		$file      = $this->request->getPost('file');
		$label     = $this->request->getPost('label');
		$category = $this->request->getPost('category');

		list($x, $url) = explode('?', $url);
		$arr = explode(';', $url);
		$params = array();
		foreach ($arr as $val) {
			list($k, $v) = explode('=', $val);
			$params[$k] = $v;
		}

		$name = preg_replace('/_div$/', '', $name);
		$modelName = rtrim($params['subaction'], 's');
		$class = 'ActiveRecord_' . ucfirst($modelName);
		$model = new $class($params['id']);

		$model->updateFile($name, $file, array('label'=>$label, 'category'=>$category));
	}

}


?>
