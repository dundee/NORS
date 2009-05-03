<?php

/**
 * Bool
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Bool extends Core_Type
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
		return 'tinyint(1) NOT NULL DEFAULT 0';
	}
}
