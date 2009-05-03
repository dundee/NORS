<?php

/**
 * URL
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Url extends Core_Type
{
	public function prepareForDB($value)
	{
		if (strpos($value, 'http') !== 0) $value = 'http://' . $value;
		return addslashes($value);
	}

	public function getDefaultValue()
	{
		return 'http://';
	}

	public function getDefinition()
	{
		return 'VARCHAR(200) NOT NULL';
	}
}
