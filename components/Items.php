<?php

/**
* Component_Items
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Component_Items extends Core_Component
{
	public $helpers = array();

	public $responseType = 'html';

	/**
	* init
	*
	* @return void
	*/
	public function init($params = FALSE)
	{
	}

	public function paging($params = FALSE)
	{
		$controller  = $this->request->getPost('controller');

		$class = ucfirst($controller);
		require_once(APP_PATH . '/controllers/' . $class . '.php');
		$controller = new $class();
		$controller->list_items();
		$data = $controller->getData();

		$items = $data['items'];
		$paging = $data['paging'];

		include(APP_PATH . '/tpl/posts.tpl.php');
	}
}
