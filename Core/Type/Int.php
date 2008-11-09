<?php
/**
 * Int
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Int
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Int extends Core_Type
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
		return 'int NOT NULL DEFAULT 0';
	}
}
