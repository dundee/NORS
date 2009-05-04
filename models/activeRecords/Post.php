<?php

/**
 * ActiveRecord_Post
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class ActiveRecord_Post extends Core_ActiveRecord
{
	public function __construct($id = false)
	{
		parent::__construct('post', $id);
	}
}
