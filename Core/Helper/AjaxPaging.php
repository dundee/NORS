<?php

/**
 * Shortcut, returns link to page
 * @param int $i Number of page
 */
function page($i)
{
	return Core_Router::factory()->forward(array('p'=>($i)));
}

/**
 * Provides AJAX based paging
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */
class Core_Helper_AjaxPaging extends Core_Helper
{
	//public $helpers = array('Form');
	protected $controller;

	public function  __construct($controller = FALSE) {
		parent::__construct();
		$this->controller = $controller;
	}

	/**
	 * @param int $count Number of items
	 * @param int $itemsPerPage Number of items that will be displayer
	 * @param boolean $return No output, return HTML
	 * @return mixed
	 */
	public function paging($count, $itemsPerPage, $return = FALSE)
	{
		$limit = 5;

		if ($count <= $itemsPerPage) return '';

		$output = '';

		if ($this->controller) $output .= '<div id="paging-controller" class="hidden" title="' . $this->controller . '"></div>';

		$r  = Core_Request::factory();

		$page = $r->getPost('p');
		if ($page === FALSE) $page = $r->getGet('p');

		if ($page) {
			$output .= '<a href="'.page(0).'" title="0">&laquo;</a> ';
			$output .= '<a href="'.page($page-1).'" title="' . ($page - 1) . '">' . __('previous') . '</a> ';
		}

		$i = $page - $limit; //supper limit
		if ($i < 0) $i = 0; //do not display below zero
		for ($i; $i * $itemsPerPage < $count && $i - $page <= $limit; $i++) {
			if ($page != $i) $output .= '<a href="'.page($i).'" title="' . $i . '">' . ($i + 1) . '</a> ';
			else $output .= '<span>' . ($i + 1) . '</span> ';
		}

		if ($count > ($page+1) * $itemsPerPage) {
			$output .= '<a href="'.page($page+1).'" title="'. ($page + 1) . '">' . __('next') . '</a> ';
			$output .= '<a href="'.page($i-1).'" title="' . ($i - 1) . '">&raquo;</a>';
		}

		if ($return) return $output;
		echo $output;
	}
}
