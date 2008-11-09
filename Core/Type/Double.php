<?php
/**
 * Double
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Double
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Double extends Core_Type
{
	public function prepareForDB($value)
	{
		return is_numeric($value) ? $value : 0;
	}
	
	public function prepareForWeb($value)
	{
		return is_numeric($value) ? $value : 0;
	}
	
	public function getDefinition()
	{
		return 'double NOT NULL DEFAULT 0';
	}
}
