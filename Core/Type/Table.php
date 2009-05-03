<?php

/**
 * Table
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Table extends Core_Type
{
	public function prepareForDB($value)
	{
		return intval($value);
	}

	public function prepareForWeb($value)
	{
		return intval($value);
	}

	public function getDefinition()
	{
		return 'int NULL';
	}
}
