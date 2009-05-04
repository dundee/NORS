<?php

/**
 * ActiveRecord_Category
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class ActiveRecord_Category extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('category',$id_user);
	}

}
