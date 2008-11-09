<?php

/**
 * Table_Citate
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Table_Citate
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Table_Citate extends Core_Table
{
	public function __construct()
	{
		parent::__construct('citate');
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

		$sql = "SELECT `id_" . $this->table . "`, `text`, `author`
		        FROM `" . tableName($this->table) . "`
		        " . ($name ? "WHERE `name` LIKE '"
		        . clearInput($name) . "%'" : '') . "
				ORDER BY " . $orderBy . " " . $order . " "
				. ($limit ? "LIMIT " . clearInput($limit) : '');
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