<?php

/**
 * Table_Post
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Table_Post
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Table_Post extends Core_Table
{
	public function __construct()
	{
		parent::__construct('post');
	}
	
	public function getByCathegory($cathegory, $orderBy = FALSE, $order = FALSE)
	{
		if ($orderBy === FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order === FALSE) {
			$order = 'desc';
		}

		$order = ( $order=='asc' ? 'asc' : 'desc' );

		$sql = "SELECT p.*,
		               c.`name` AS cathegory_name,
		               count(*) AS num_of_comments
		        FROM `" . tableName($this->table) . "` AS p
				LEFT JOIN `" . tableName('cathegory') . "` AS c ON (c.`id_cathegory`=p.`cathegory`)
		        LEFT JOIN `" . tableName('comment') . "` AS co ON (co.`post`=p.`id_post`)
		        WHERE p.`cathegory` = '" . intval($cathegory) . "'
		        GROUP BY `id_post`
		        ORDER BY `" . clearInput($orderBy) . "` " . strtoupper($order);
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

		$sql = "SELECT p.`id_" . $this->table . "`,
		               p.`name`,
		               COUNT(*) AS num_of_comments,
		               p.`seen` AS num_of_visits
		        FROM `" . tableName($this->table) . "` AS p
				LEFT JOIN `" . tableName('comment') . "` AS c ON (c.`post`=p.`id_post`)
		        " . ($name ? "WHERE `user` LIKE '"
		        . clearInput($name) . "%'" : '') . "
				GROUP BY p.`id_post`
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

	public function getAll($orderBy=FALSE, $order=FALSE)
	{
		if ($orderBy==FALSE) {
			$orderBy = 'id_' . $this->table;
		}
		if ($order===FALSE) {
			$order = 'desc';
		}

		$order = ( $order=='asc' ? 'asc' : 'desc' );

		$sql = "SELECT p.*,
		               c.`name` AS cathegory_name,
		               count(`id_comment`) AS num_of_comments
		        FROM `" . tableName($this->table) . "` AS p
		        LEFT JOIN `" . tableName('cathegory') . "` AS c ON (c.`id_cathegory`=p.`cathegory`)
		        LEFT JOIN `" . tableName('comment') . "` AS co ON (co.`post`=p.`id_post`)
		        GROUP BY `id_post`
		        ORDER BY `" . clearInput($orderBy) . "` " . strtoupper($order);
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
}
