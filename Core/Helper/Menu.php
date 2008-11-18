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

	public function prepare($pages)
	{
		$menu = array();

		$request = Core_Request::factory();
		$r       = Core_Router::factory();

		if (iterable($pages)) {
			$text_obj = new Core_Text();
			foreach ($pages as $page) {
				$selected = intval($request->getGet('page')) == $page->getID();
				$url = ($page->link && $page->link != 'http://')
				       ? $page->link
				       : $r->genUrl('page',
				                    FALSE,
				                    'page',
				                    array('page' => $page->getID() . '-' . $text_obj->urlEncode($page->name)
				                         )
				                    );
				$menu[] = array('label'    => __($page->name),
				                'url'      => $url,
				                'selected' => $selected,
				                );
			}
		}
		return $menu;
	}

	public function render($menu)
	{
		echo '<div id="menu">';
		echo ENDL . '<ul class="clearfix">' . ENDL;
		foreach ($menu as $item) {
			echo TAB . '<li>' . ENDL;
			echo TAB. TAB . '<a ' . ($item['selected'] ? 'class="selected" ' : '') . 'href="' . $item['url'] . '">' . ucwords($item['label']) . '</a>' . ENDL;
			echo TAB . '</li>' . ENDL;
		}
		echo '</ul>' . ENDL;
		echo '</div>';
	}
}
