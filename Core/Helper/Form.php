<?php

/**
* Core_Helper_Form
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Helper_Form
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Helper_Form extends Core_Helper
{
	public $helpers = array('Html');

	protected $form;

	public $root;

	protected $submitValue;

	public function form($parent = NULL, $action, $legend = FALSE, $submitValue = FALSE,$params = NULL)
	{
		$this->submitValue = $submitValue;
		$params['action'] = $action;
		if (!isset($params['method'])) $params['method'] = 'post';
		$this->root = $this->form = $this->html->elem($parent, 'form', $params);
		if ($legend) {
			$fieldset = $this->html->elem($this->form, 'fieldset');
			$this->root = $fieldset;
			$this->html->elem($this->root, 'legend')->setContent($legend);
		}

		return $this->form;
	}

	public function input($parent = NULL, $name, $label = FALSE, $type = 'text', $defaultValue = FALSE, $params = array())
	{
		if (!$parent) $parent = $this->root;

		$div = $this->html->div($parent);

		$params['type']  = $type;
		$params['value'] = $defaultValue;

		if ($type == 'text' || $type == 'password' || $type == 'file' || $type == 'checkbox') { //text inputs - label before input
			$this->html->elem($div, 'label', array('for'=>$name))->setContent($label);
			$input = $this->html->input($div, $name,  $params);
		/*} elseif ($type == 'radio' || $type == 'checkbox') { //radio, checkbox - text after input
			$input = $this->html->input($div, $name,  $params);
			$this->html->elem($div, 'label', array('for'=>$name))->setContent($label);
		*/} else { //other (hidden, submit) - without label
			$input = $this->html->input($div, $name,  $params);
		}
		return $input;
	}

	/**
	 * select
	 *
	 * @param Core_Html_Element $parent
	 * @param string $name
	 * @param string $label
	 * @param string[] $params
	 * @return Core_Html_Element
	 */
	public function select($parent = NULL, $name, $label = FALSE, $params = array())
	{
		if (!$parent) $parent = $this->root;

		$div = $this->html->div($parent);

		$this->html->elem($div, 'label', array('for'=>$name))->setContent($label);
		$input = $this->html->select($div, $name, $params);

		return $input;
	}

	public function textarea($parent = NULL, $name, $label = FALSE, $value = FALSE, $params = array())
	{
		if (!$parent) $parent = $this->root;

		$div = $this->html->div($parent);

		if ($label) $this->html->elem($div, 'label', array('for'=>$name))->setContent($label);
		$ta = $this->html->textarea($div, $name, $value, $params);
		$p  = $this->html->elem($div, 'p')->setContent('<small>ctrl + enter = &lt;br /&gt; shift + enter = &lt;p&gt;&lt;p/&gt;</small>');

		return $div;
	}

	public function render($indention = 0, $return = FALSE, $not_validate = FALSE)
	{
		if ($this->submitValue) {
			$this->input(NULL, 'send', FALSE, 'submit', $this->submitValue, array('class'=>'submit'));
		}

		if (!$not_validate) $this->form->setParam('onsubmit','return validate(this);');

		$output  = $this->indent($indention)   . '<script type="text/javascript">' . ENDL;
		$output .= $this->indent($indention)   . 'function validate(form){' . ENDL;
		$output .= $this->indent($indention+1) . 'var errors = \'\';' . ENDL;
		$output .= $this->renderValidation($this->form->getChilds(), $indention+1, TRUE);
		$output .= $this->indent($indention+1) . 'if (errors.length > 0) {' . ENDL;
		$output .= $this->indent($indention+2) . 'window.alert(errors);' . ENDL;
		$output .= $this->indent($indention+2) . 'return false;' . ENDL;
		$output .= $this->indent($indention+1) . '} else {' . ENDL;
		$output .= $this->indent($indention+2) . 'return true;' . ENDL;
		$output .= $this->indent($indention+1) . '}' . ENDL;
		$output .= $this->indent($indention)   . '}' . ENDL;
		$output .= $this->indent($indention)   . '</script>' . ENDL;

		$request = Core_Request::factory();
		if ($request->getPost('send')) {
			$output .= $this->indent($indention)   . '<div class="saved">'. __('saved') .'</div>' . ENDL;
			$output .= $this->indent($indention)   . '<script type="text/javascript">' . ENDL;
			$output .= $this->indent($indention+1) . 'window.setTimeout("hide_saved()", 2000);' . ENDL;
			$output .= $this->indent($indention+1) . 'function hide_saved()' . ENDL;
			$output .= $this->indent($indention+1)   . '{ $(".saved").hide("slow"); }' . ENDL;
			$output .= $this->indent($indention)   . '</script>' . ENDL;
		}

		if (!$return) echo $output;
		return $output . $this->form->render($indention, $return, FALSE);
	}

	protected function renderValidation($elements, $indention, $return = FALSE)
	{
		$output = '';
		foreach ($elements as $element) {
			if ($element instanceof Core_Html_Input ||
			    $element instanceof Core_Html_Select
			) {
				$output .= $element->renderValidation($indention, TRUE);
			} elseif (iterable($element->getChilds())) {
				$output .= $this->renderValidation($element->getChilds(), $indention, TRUE);
			}
		}
		if ($return) return $output;
		echo $output;
	}
}
