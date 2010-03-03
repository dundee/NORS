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

	public function getByCathegory($cathegory, $orderBy = FALSE, $order = FALSE, $limit = FALSE)
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
		               count(co.`id_comment`) AS num_of_comments,
		               IF(u.`fullname` != '', u.`fullname`, u.`name`) AS user_name
		        FROM `" . tableName($this->table) . "` AS p
		        LEFT JOIN `" . tableName('cathegory') . "` AS c USING (`id_cathegory`)
		        LEFT JOIN `" . tableName('comment') . "` AS co USING (`id_post`)
		        LEFT JOIN `" . tableName('user') . "` AS u USING (`id_user`)
		        WHERE p.`active` = 1 AND
		              p.`date` <= '" . date("Y-m-d H:i:00") . "' AND
		              p.`id_cathegory` = '" . intval($cathegory) . "'
		        GROUP BY `id_post`
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

	public function getPosts($orderBy=FALSE, $order=FALSE, $limit = FALSE)
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
		               count(`id_comment`) AS num_of_comments,
		               IF(u.`fullname` != '', u.`fullname`, u.`name`) AS user_name
		        FROM `" . tableName($this->table) . "` AS p
		        LEFT JOIN `" . tableName('cathegory') . "` AS c USING (`id_cathegory`)
		        LEFT JOIN `" . tableName('comment') . "` AS co USING (`id_post`)
		        LEFT JOIN `" . tableName('user') . "` AS u USING (`id_user`)
		        WHERE p.`active` = 1 AND p.`date` <= '" . date("Y-m-d H:i:00") . "'
		        GROUP BY `id_post`
		        ORDER BY `" . clearInput($orderBy) . "` " . strtoupper($order)
		        . ($limit ? " LIMIT " . clearInput($limit) : '');
		try{
			$lines = $this->db->getRows($sql);
		} catch(RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$this->create();
				return FALSE;
			} else throw new RuntimeException($ex->getMessage(), $ex->getCode());
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

	public function getCountByCathegory($cathegory)
	{
		$sql = "SELECT count(*) AS count
		        FROM `" . tableName($this->table) . "`
		        WHERE `active` = 1 AND
		              `date` <= '" . date("Y-m-d H:00:00") . "' AND
		              `id_cathegory` = '" . intval($cathegory) . "'";
		try{
			$line = $this->db->getRow($sql);
		} catch (RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		return $line['count'];
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
		               COUNT(`id_comment`) AS num_of_comments,
		               p.`seen` AS num_of_visits,
		               p.`date`,
		               p.`active`
		        FROM `" . tableName($this->table) . "` AS p
				LEFT JOIN `" . tableName('comment') . "` AS c USING (`id_post`)";

		if ($name) {
			$sql .= " WHERE `name` LIKE '%" . clearInput($name) . "%' OR `id_" . $this->table . "` = '" . clearInput($name) . "'";
		}

		$sql .= " GROUP BY p.`id_post`
		          ORDER BY " . $orderBy . " " . $order . " "
		          . ($limit ? "LIMIT " . clearInput($limit) : '');

		try{
			$lines = $this->db->getRows($sql);
		} catch (RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				if (strpos($ex->getMessage(), "comment' doesn't exist") !== FALSE) {
					$comment = new Table_Comment();
					$comment->create();
				} else $this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		return $lines;
	}

	public function getAll($orderBy=FALSE, $order=FALSE, $limit = FALSE)
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
		        LEFT JOIN `" . tableName('cathegory') . "` AS c USING (`id_cathegory`)
		        LEFT JOIN `" . tableName('comment') . "` AS co USING (`id_post`)
		        GROUP BY `id_post`
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
}
