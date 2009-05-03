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
		return addslashes($value);
	}

	public function prepareForWeb($value)
	{
		return stripslashes($value);
	}

	public function getDefinition()
	{
		return 'text NOT NULL';
	}
}
