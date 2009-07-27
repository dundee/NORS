<?php

/**
* Component_Post
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
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
		foreach ($arr as $i => $item) {
			if ($item == 'post') {
				$id = intval($arr[$i + 1]);
				break;
			}
		}

		$post = new ActiveRecord_Post($id);

		$post_eval = base64_decode($this->request->getCookie('post_eval'));
		if ($post_eval) $post_evals = explode(';', $post_eval);

		if (!isset($post_evals) || !in_array($id, $post_evals)) {
			$post->evaluated = $post->evaluated ? $post->evaluated : 0;
			$karma       = $post->karma * $post->evaluated++;
			$post->karma = ($karma + $val) / $post->evaluated;
			$post->save();
			$post_evals[] = $id;
		}

		$post_eval = implode(';', $post_evals);
		$this->response->setCookie('post_eval', base64_encode($post_eval));
		echo 'Karma: ' . round($post->karma, 2);
	}
}
