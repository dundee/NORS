<?php

/**
* Component_Items
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Component_Items
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
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
		$module  = $this->request->getPost('module');

		$class = ucfirst($module);
		require_once(APP_PATH . '/modules/' . $class . '.php');
		$module = new $class();
		$module->list_items();
		$data = $module->getData();

		$posts = $data['posts'];
		$paging = $data['paging'];

		include(APP_PATH . '/tpl/posts.tpl.php');
	}
}
