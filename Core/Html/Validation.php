<?php

/**
* Core_Html_Validation
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Html_Validation
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Html_Validation
{
	protected $id;
	protected $cond;
	protected $message;

	public function __construct($id, $cond, $message)
	{
		$this->id      = $id;
		$this->cond    = $cond;
		$this->message = $message ? $message : __('item') . ' ' . __($id) . ' ' . __('required');
	}
	
	public function render($indention = 0, $return = NULL)
	{ 
		$condition = $this->renderCondition();
		
		$output = '';
		
		$output .= $this->indent($indention)   . 'if ($(\'#'.$this->id.'\').val()'.$condition.') {'.ENDL;
		$output .= $this->indent($indention+1) . 'errors += \''.$this->message.'\\\n\';'.ENDL;
		$output .= $this->indent($indention+1) . '$(\'#'.$this->id.'\').focus();'.ENDL;
		$output .= $this->indent($indention+1) . '$(\'#'.$this->id.'\').addClass(\'focused\');'.ENDL;
		$output .= $this->indent($indention)   . '}'.ENDL;
		
		if ($return) return $output;
		echo $output ;
	}
	
	protected function indent($indention)
	{
		$output = '';
		for ($i=0; $i < $indention; $i++) {
			$output .= TAB;
		}
		return $output;
	}
	
	protected function renderCondition()
	{
		$output = ' ';
		switch ($this->cond) {
		
			default:
				$output .= '== \'\'';
		}
		return $output;
	}
}