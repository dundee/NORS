<?php

/**
* Core_Html_Input
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Html_Input
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Html_Input extends Core_Html_Element
{
	protected $validations;

	public function __construct(Core_Html_Element $parent = NULL, $name, $params = NULL)
	{
		$params['name'] = $name;
		$params['id'] = isset($params['id']) ? $params['id'] : $name; 
		parent::__construct($parent, 'input', $params);
	}
	
	public function setValidation($cond = FALSE, $message = FALSE)
	{
		if (!$cond) $this->params['class'] = isset($this->params['class']) ? $this->params['class'] . ' required' : 'required';   
		
		$this->validations[] = new Core_Html_Validation($this->params['id'],$cond, $message);
	}
	
	public function renderValidation($indention = 0, $return = NULL)
	{
		if (!iterable($this->validations)) return FALSE;
		$output = '';
		foreach ($this->validations as $validation) {
			$output .= $validation->render($indention, TRUE);
		}
		
		if ($return) return $output;
		echo $output ;
	}
}