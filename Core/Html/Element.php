<?php

/**
* Core_Html_Element
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Html_Element
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Html_Element
{
	protected $childs = array();
	
	protected $parent = NULL;
	
	protected $tag;
	
	protected $params;
	
	protected $empty = FALSE;
	
	protected $mixedContent = FALSE;
	
	public function __construct(Core_Html_Element $parent = NULL, $tag = 'div', $params = NULL)
	{
		if ($parent != NULL) $parent->addChild($this);
		$this->parent = $parent;
		$this->tag = $tag;
		$this->params = $params;
	}
	
	public function setEmpty()
	{
		$this->empty = TRUE;
	}
	
	public function setContent($content)
	{
		new Core_Html_Text($this, $content);
		$this->mixedContent = TRUE;
		return $this;
	}
	
	public function setParam($key, $value)
	{
		$this->params[$key] = $value;
		return $this;
	}
	
	public function addChild(Core_Html_Element $child)
	{
		$this->childs[] = $child;
		return $this;
	}
	
	public function getChilds()
	{
		return $this->childs;
	}
	
	public function getParent()
	{
		return $this->parent;
	}
	
	public function render($indention = 0, $return = FALSE, $inMixedContent = FALSE)
	{	
		$innerEndline   = $outerEndline   = ENDL;
		$innerIndention = $outerIndention = $indention;
		
		$this->mixedContent = $this->mixedContent ? TRUE : $inMixedContent; 
		
		if ($this->mixedContent) $innerEndline = $innerIndention = FALSE;
		if ($inMixedContent)     $outerEndline = $outerIndention = FALSE;
		
		$output = $this->indent($outerIndention) . '<' . $this->tag . $this->renderParams();
		
		if ($this->empty) {
			$output .= ' />' . $outerEndline ;
		} else {
			$output .= '>' . $innerEndline;
			$output .= $this->renderChilds($innerIndention);
			$output .= $this->indent($innerIndention) . '</' . $this->tag . '>' . $outerEndline;
		}
		
		if ($return) return $output;
		echo $output;
	}
	
	protected function renderParams()
	{
		if (!iterable($this->params)) return FALSE;
		$output = '';
		foreach ($this->params as $k=>$v) $output .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '"'; 
		return $output;
	}
	
	protected function indent($indention)
	{
		$output = '';
		for ($i=0; $i < $indention; $i++) {
			$output .= TAB;
		}
		return $output;
	}
	
	protected function renderChilds($indention)
	{
		if (!iterable($this->childs)) return FALSE;
		
		$output = '';
		$indention++;
		foreach ($this->childs as $child) {
			$output .= $child->render($indention, TRUE, $this->mixedContent);
		}
		return $output;
	}
}