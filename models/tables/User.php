<?php

/**
 * ActiveRecord_User
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class Table_User extends Core_Table
{
	public function __construct($id_user=false)
	{
		parent::__construct('user',$id_user);
	}
}
