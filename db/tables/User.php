<?php

/**
 * ActiveRecord_User
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * ActiveRecord_User
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Table_User extends Core_Table
{
	public function __construct($id_user=false)
	{
		parent::__construct('user',$id_user);
	}
}
