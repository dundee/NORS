<?php

/**
* Core_Helper_AjaxPaging
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Helper_AjaxPaging
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Helper_AjaxPaging extends Core_Helper
{
	//public $helpers = array('Form');
	protected $module;

	public function  __construct($module = FALSE) {
		parent::__construct();
		$this->module = $module;
	}

	/**
	 * @param int $count Number of items
	 * @param int $itemsPerPage Number of items that will be displayer
	 * @param boolean $return No output, return HTML
	 * @return mixed
	 */
	public function paging($count, $itemsPerPage, $return = FALSE)
	{
		if ($count < $itemsPerPage) return '';

		$output = '';

		if ($this->module) $output .= '<div id="paging-module" class="hidden" title="' . $this->module . '"></div>';

		$r = Core_Request::factory();

		$page = $r->getPost('page');

		if ($page) {
			$output .= '<a href="#" title="0">&laquo;</a> ';
			$output .= '<a href="#" title="'. ($page-1) .'">' . __('previous') . '</a> ';
		}

		for ($i=0; $i * $itemsPerPage < $count; $i++) {
			if ($page != $i) $output .= '<a href="#" title="'.$i.'">' . ($i+1) . '</a> ';
			else $output .= '<span>' . ($i+1) . '</span> ';
		}

		if ($count > ($page+1) * $itemsPerPage) {
			$output .= '<a href="#" title="'. ($page+1) .'">' . __('next') . '</a> ';
			$output .= '<a href="#" title="' . ($i-1) . '">&raquo;</a>';
		}

		if ($return) return $output;
		echo $output;
	}
}
