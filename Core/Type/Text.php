<?php

/**
 * Text
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Text extends Core_Type
{
	public function getDefinition()
	{
		return 'text NOT NULL';
	}
}
