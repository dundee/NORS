<?php

/**
* ActiveRecord_User
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class ActiveRecord_User extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('user',$id_user);
	}
}
