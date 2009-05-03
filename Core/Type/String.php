<?php

/**
 * String
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_String extends Core_Type
{
	public function getDefinition()
	{
		return 'VARCHAR(200) NOT NULL';
	}
}
