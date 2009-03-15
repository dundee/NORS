<?php

/**
* Component_Post
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Component_Post
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Component_Post extends Core_Component
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

	public function evaluate($params = FALSE)
	{
		$val = $this->request->getPost('value');
		$url = $this->request->getPost('url');
		
		$arr = explode('/', $url);
		foreach ($arr as $i=>$item) {
			if ($item == 'post') {
				$id = intval($arr[$i + 1]); 
			}
		}
		
		$post = new ActiveRecord_Post($id);
		
		if (!$this->request->getCookie('eval')) {
			$karma       = $post->karma * $post->evaluated++;
			$post->karma = ($karma + $val) / $post->evaluated;
			$post->save();
		}
		
		$this->response->setCookie('eval', $val);
		echo 'Karma: ' . round($post->karma, 2);
	}
}
