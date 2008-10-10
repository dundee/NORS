<?php

/**
* Core_Helper
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Helper
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Helper
{
	protected $helpers = array();
	
	public function __construct()
	{
		if (iterable($this->helpers)) {
			foreach ($this->helpers as $helper) {
				$class = 'Core_Helper_' . ucfirst($helper);
				$this->{strtolower($helper)} = new $class;
			}
		}
	}
	
	protected function indent($indention)
	{
		$output = '';
		for ($i=0; $i < $indention; $i++) {
			$output .= TAB;
		}
		return $output;
	}
}