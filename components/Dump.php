<?php

/**
* Component_Dump
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Component_Dump
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Component_Dump extends Core_Component_Auth
{
	public $helpers = array('Administration', 'AjaxPaging');

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
		$this->setData('max', Core_Config::singleton()->administration->items_per_page);
		$this->setData('table', $params['table']);

		$class = 'Table_' . ucfirst($params['table']);
		$model = new $class;

		$count = $model->getCount();
		$this->setData('count', $count);
	}

	public function filter()
	{
		$table = $this->request->getPost('table');
		$name  = $this->request->getPost('name');
		$page  = $this->request->getPost('page');
		$order = $this->request->getPost('order');
		$a     = $this->request->getPost('a');

		//save
		$this->response->setCookie('table', $table, 0);
		$this->response->setCookie('name', $name, 0);
		$this->response->setCookie('page', $page, 0);
		$this->response->setCookie('order', $order, 0);
		$this->response->setCookie('a', $a, 0);

		$html   = $this->administration->dump($table, TRUE, TRUE);

		$class = 'Table_' . ucfirst($table);
		$model = new $class;

		$count = $model->getCount($this->request->getPost('name'));

		$paging = $this->ajaxpaging->paging($count, Core_Config::singleton()->administration->items_per_page, TRUE);
		$this->setData('html', $html, TRUE);
		$this->setData('paging', $paging, TRUE);
	}
}
