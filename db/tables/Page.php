<?php

/**
 * Table_Page
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Table_Page
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Table_Page extends Core_Table
{
	public function __construct()
	{
		parent::__construct('page');
	}

	public function getAll($orderBy=FALSE, $order=FALSE)
	{
		if ($orderBy==FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order===FALSE) {
			$order = 'desc';
		}

		$lang = Core_Request::factory()->locale;

		$order = ( $order=='asc' ? 'asc' : 'desc' );

		$sql = "SELECT *
		        FROM `" . tableName($this->table) . "` AS p
		        JOIN `" . tableName('languages') . "` USING (`id_language`)
		        ORDER BY `" . clearInput($orderBy) . "` " . strtoupper($order);
		try{
			$lines = $this->db->getRows($sql);
		} catch(RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				//$this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		$class = 'ActiveRecord_' . ucfirst($this->table);
		if ( !iterable($lines) ) return NULL;
		foreach ($lines as $line) {
			$current = new $class();
			$current->setFromArray($line);
			$instances[] = $current;
		}
		return $instances;
	}
}