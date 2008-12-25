<?php
/**
 * Password
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Password
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Password extends Core_Type
{
	public function getDefinition()
	{
		return 'VARCHAR(200) NOT NULL';
	}
}
