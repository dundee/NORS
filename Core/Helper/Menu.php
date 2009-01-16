<?php

/**
* Core_Helper_Menu
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Helper_Menu
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Helper_Menu extends Core_Helper
{
	public $helpers = array();

	public function prepare($items, $name = 'page')
	{
		$menu = array();

		$request = Core_Request::factory();
		$r       = Core_Router::factory();

		if (iterable($items)) {
			$text_obj = new Core_Text();
			foreach ($items as $item) {
				$selected = intval($request->getGet($name)) == $item->getID();
				$url = ($item->link && $item->link != 'http://')
				       ? $item->link
				       : $r->genUrl($name,
				                    FALSE,
				                    $name,
				                    array($name => $item->getID() . '-' . $text_obj->urlEncode($item->name)
				                         )
				                    );
				$menu[] = array('label'    => __(clearOutput($item->name)),
				                'url'      => $url,
				                'selected' => $selected,
				                );
			}
		}
		return $menu;
	}

	public function render($menu, $indention = 0)
	{
		$in = '';
		for($i=0; $i < $indention; $i++) $in .= TAB;

		$output = '';
		if (iterable($menu)) {
			$output .= $in . '<ul>' . ENDL;
			foreach ($menu as $item) {
				$output .= $in . TAB . '<li><a ' . ($item['selected'] ? 'class="selected" ' : '') . 'href="' . $item['url'] . '">' . ucwords($item['label']) . '</a></li>' . ENDL;
			}
			$output .= $in . '</ul>' . ENDL;
		}
		return $output;
	}
}
