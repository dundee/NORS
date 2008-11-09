<?php
/**
 * Type
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Type
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type
{
	public function prepareForDB($value)
	{
		return addslashes($value);
	}
	
	public function prepareForWeb($value)
	{
		return htmlspecialchars(stripslashes($value));
	}
	
	public function getDefinition()
	{
		return 'VARCHAR(100) NOT NULL';
	}
	
	public function getDefaultValue()
	{
		return '';
	}
}
