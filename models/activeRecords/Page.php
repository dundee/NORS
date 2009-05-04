<?php

/**
 * ActiveRecord_Page
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class ActiveRecord_Page extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('page',$id_user);
	}
}
