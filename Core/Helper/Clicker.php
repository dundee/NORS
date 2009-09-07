<?php

/**
 * Provides clicable editor of YAML-like configuration
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Helper_Clicker extends Core_Helper
{
	public $helpers = array('Form', 'Html');

	public function generate($data, $return = FALSE, $path = FALSE)
	{
		$path = $path ? $path . '__' : '';
		$output = '<ul>';
		foreach ($data as $name => $value) {
			if (is_array($value)) {
				$output .= '<li class="opened">';
				$output .= '<a href="#" class="clicker">' . __($name) . '</a>';
				$output .= $this->generate($value, TRUE, $path . $name);
			} else {
				$output .= '<li class="item">';
				$output .= $this->generateItem($name, $value, $path . $name);
			}
			$output .= '</li>';
		}
		$output .= '</ul>';
		if ($return) return $output;
		echo $output;
	}

	public function generateItem($name, $value, $path)
	{
		$output = '<label for="' . $path . '">' . __($name) . '</label>';
		$output .= '<input name="' . $path . '" type="text" value="' . $value . '" />';
		return $output;
	}
}
