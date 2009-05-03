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

	public function getPages($orderBy=FALSE, $order=FALSE, $limit = FALSE)
	{
		if ($orderBy==FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order===FALSE) {
			$order = 'desc';
		}

		$order = ( $order=='asc' ? 'asc' : 'desc' );

		$sql = "SELECT *
		        FROM `" . tableName($this->table) . "`
		        WHERE `active` = 1
		        ORDER BY `" . clearInput($orderBy) . "` " . strtoupper($order)
		        . ($limit ? " LIMIT " . clearInput($limit) : '');
		try{
			$lines = $this->db->getRows($sql);
		} catch(RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$this->create();
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

	public function getList($orderBy = FALSE,
	                        $order = FALSE,
	                        $limit = FALSE,
	                        $name = FALSE)
	{

		if ($orderBy == FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order === FALSE) {
			$order = 'desc';
		}
		$order = ($order == 'asc' ? 'asc' : 'desc');

		$sql = "SELECT `id_" . $this->table . "`,
		               `name`,
		               `active`
		        FROM `" . tableName($this->table) . "`";

		if ($name) {
			$sql .= " WHERE `name` LIKE '%" . clearInput($name) . "%' OR `id_" . $this->table . "` = '" . clearInput($name) . "'";
		}

		$sql .= " ORDER BY " . $orderBy . " " . $order . " " . ($limit ? "LIMIT " . clearInput($limit) : '');

		try{
			$lines = $this->db->getRows($sql);
		} catch (RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		return $lines;
	}
}
