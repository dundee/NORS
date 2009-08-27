<?php

/**
 * Table_Category
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors
 */
class Table_Category extends Core_Table
{
	public function __construct()
	{
		parent::__construct('category');
	}

	public function getActive($orderBy=FALSE, $order=FALSE, $limit = FALSE)
	{
		$inst = parent::getAll($orderBy, $order, $limit);
		return $inst;
	}
}
