<?php

/**
 * Datetime
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Datetime extends Core_Type
{
	public function prepareForDB($value)
	{
		$locale = Core_Locale::factory();
		return $locale->encodeDatetime($value);
	}

	public function prepareForWeb($value)
	{
		$locale = Core_Locale::factory();
		return $locale->decodeDatetime($value);
	}

	public function getDefinition()
	{
		return 'datetime NOT NULL';
	}

	public function getDefaultValue()
	{
		return date('Y-m-d H:i:s');
	}
}
