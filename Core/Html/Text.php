<?php

/**
* Core_Html_Text
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Html_Text
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Html_Text extends Core_Html_Element
{
	protected $parent = NULL;
	
	protected $content;
	
	public function __construct(Core_Html_Element $parent = NULL, $content)
	{
		if ($parent != NULL) $parent->addChild($this);
		$this->parent = $parent;
		$this->content = $content;
	}
	
	public function addChild(Core_Html_Element $child)
	{
		throw new BadMethodCallException;
	}
	
	public function render($indention = 0, $return = NULL)
	{
		$output = $this->content;
		
		if ($return) return $output;
		echo $output;
	}
}