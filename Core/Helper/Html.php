<?php

/**
* Core_Helper_Html
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Helper_Html
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Helper_Html extends Core_Helper
{
	public function elem($parent, $tag, $params = NULL)
	{
		return new Core_Html_Element($parent, $tag, $params);
	}
	
	public function a($parent, $href, $params = NULL)
	{
		$params['href'] = $href;
		return new Core_Html_Element($parent, 'a', $params);	
	}
	
	public function div($parent, $params = NULL)
	{
		return new Core_Html_Element($parent, 'div', $params);
	}
	
	public function img($parent, $src, $alt = NULL, $params = NULL)
	{
		$params['src'] = $src;
		$params['alt'] = $alt;
		return new Core_Html_Element($parent, 'img', $params);
	}
	
	/**
	 * input
	 *
	 * @param Core_Html_Element $parent
	 * @param string $name
	 * @param string[] $params
	 * @return Core_Html_Input
	 */
	public function input($parent, $name, $params = NULL)
	{
		$input = new Core_Html_Input($parent, $name, $params);
		$input->setEmpty();
		return $input;	
	}
	
	/**
	 * textarea
	 *
	 * @param Core_Html_Element $parent
	 * @param string $name
	 * @param string $value
	 * @params string[] $params	 
	 * @return Core_Html
	 */
	public function textarea($parent, $name, $value, $params = NULL)
	{
		$params['id'] = $name;
		$params['name'] = $name;		
		
		$ta = new Core_Html_Element($parent, 'textarea', $params);
		$ta->setContent($value);
		return $ta;
	}
	
	/**
	 * button
	 *
	 * @param Core_Html_Element $parent
	 * @param string $name
	 * @param string $value	 
	 * @param string[] $params
	 * @return Core_Html
	 */
	public function button($parent, $name, $value, $params = NULL)
	{
		$params['id'] = $name;
		$params['name'] = $name;		
		
		$but = new Core_Html_Element($parent, 'button', $params);
		$but->setContent($value);
		return $but;
	}

	public function select($parent, $name, $params = NULL)
	{
		$select = new Core_Html_Select($parent, $name, $params);
		return $select;			
	}
}
