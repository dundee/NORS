<?php

/**
 * ActiveRecord_Group
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class ActiveRecord_Group extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('group',$id_user);
	}
	
}