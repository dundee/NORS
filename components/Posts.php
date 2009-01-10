<?php

/**
* Component_Posts
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Component_Posts
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Component_Posts extends Core_Component
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
		require_once(APP_PATH . '/modules/Post.php');
		$module = new Post();
		$module->list_posts();
		$data = $module->getData();

		$posts = $data['posts'];
		$paging = $data['paging'];

		include(APP_PATH . '/tpl/posts.tpl.php');
	}
}
