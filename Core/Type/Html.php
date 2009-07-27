<?php

/**
 * Html
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Html extends Core_Type
{
	public function prepareForDB($value)
	{
		return clearInput($value);
	}

	public function prepareForWeb($value)
	{
		//return stripslashes($value);
		return $value;
	}

	public function getDefinition()
	{
		return 'text NOT NULL';
	}
}
