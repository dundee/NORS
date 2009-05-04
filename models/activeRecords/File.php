<?php

/**
 * ActiveRecord_File
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class ActiveRecord_File extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('file',$id_user);
	}

}
