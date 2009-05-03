<?php

/**
 * Base class for HTML (and other) helpers
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

	/**
	 * Creates indention
	 * @param int $indention Deep of indention
	 * @return string
	 */
	protected function indent($indention)
	{
		$output = '';
		for ($i=0; $i < $indention; $i++) {
			$output .= TAB;
		}
		return $output;
	}
}
