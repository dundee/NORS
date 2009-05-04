<?php

/**
 * ActiveRecord_Comment
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class ActiveRecord_Comment extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('comment',$id_user);
	}

}
