<?php

/**
 * Core_Table
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_Table
 *
 * Base model class. Allows user to easily define table structure and create,
 * modify, select and delete DB rows.
 * Implements Iterator, ArrayAccess, Countable and magic setters/getters,
 * so data can be accessed via $obj->key, $obj['key'] and foreach.
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Table
{
	protected $fields = array();
	protected $ids = array();
	protected $indexes = array();

	/**
	 * $db
	 *
	 * Instance of Core_DB
	 * @var Core_DB $db
	 */
	protected $db;

	/**
	 * $table
	 *
	 * Name of table
	 * @var string $table
	 */
	protected $table;

	protected $engine;


	public function __construct($table)
	{
		$this->db    = Core_DB::singleton();
		$this->table = $table;

		$schema = self::getSchema($table);

		$this->fields  = $schema['fields'];
		$this->indexes = $schema['indexes'];
		$this->ids  = $schema['ids'];

	}

	/**
	 * create
	 *
	 * Creates new tables according to fields['db'] values.
	 * @return void
	 */
	public function create()
	{
		$sql = "CREATE TABLE IF NOT EXISTS `".tableName($this->table)."` (";

		foreach ($this->fields as $name=>$type) {
			$class = 'Core_Type_' . ucfirst($type['type']);
			$obj = new $class;
			$definition = $obj->getDefinition();
			if (!$definition) continue;
			if ($name == 'id_' . $this->table) {
				$sql .= "`" . $name
				      . "` int(11) unsigned NOT NULL AUTO_INCREMENT, ";
			} else {
				$sql .= "`" . $name . "` " . $definition . ", ";
			}
		}

		$sql .= " PRIMARY KEY(`id_" . $this->table . "`)"
		      .") TYPE = MYISAM";
		$this->db->query($sql);

		foreach ($this->indexes as $name=>$items) {
			$columns = '';
			foreach(explode(',', $items) as $val) {
				$columns .= ($columns ? ', ' : '') . "`" . trim($val) . "`";
			}
			$sql = "ALTER TABLE `".tableName($this->table)."`
			        ADD INDEX `" . $name . "` ( " . $columns . " )";
			$this->db->query($sql);
		}


	}



	/**
	 * __call
	 *
	 * Allows methods like findById, findByName, etc. to be easily called.
	 * @return Core_ActiveRecord[]
	 */
	public function __call($function, $arguments)
	{
		//findByColumn
		if (substr($function, 0, 6) == 'findBy') {
			$name = strtolower(substr($function, 6));
			if ($name == 'id') $name = 'id_' . $this->table;
			$value = $arguments[0];

			$sql = "SELECT *
			        FROM `" . tableName($this->table) . "`
			        WHERE `" . $name . "` = '" . clearInput($value) . "'
			        ORDER BY `id_" . $this->table . "` ASC";
			try {
				$lines = $this->db->getRows($sql);
			} catch(RuntimeException $ex) {
				if ($ex->getCode() == 1146) {
					self::create();
					return FALSE;
				}
				else throw new RuntimeException($ex->getMessage(),
				                                $ex->getCode());
			}
			$instances = array();
			if (iterable($lines)) {
				foreach ($lines as $line) {
					if ($line['id_' . $this->table] > 0) {
						$class = 'ActiveRecord_' . ucfirst($this->table);
						$instances[] = new $class($line['id_' . $this->table]);
						//$instance->data = $line;
					}
				}
			}

			if (count($instances) >= 1) return $instances;
			else return array();
		}

		throw new BadMethodCallException('Method ' . $function . ' not exists.');
	}

	/**
	 * getAll
	 *
	 * Returns all rows of table as ActiveRecord instances.
	 *
	 * @return Core_ActiveRecord[]
	 */
	public function getAll($orderBy=FALSE, $order=FALSE, $limit = FALSE)
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

	/**
	 * getList
	 *
	 * Returns ID and name of all records.
	 *
	 * @return String[][]
	 */
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

		$sql = "SELECT `id_" . $this->table . "`, `name`
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

	/**
	 * getCount
	 *
	 *
	 * @return int
	 */
	public function getCount($name = FALSE)
	{
		$sql = "SELECT count(*) AS count
		        FROM `" . tableName($this->table) . "`";

		if ($name) {
			$sql .= " WHERE `name` LIKE '%" . clearInput($name) . "%' OR `id_" . $this->table . "` = '" . clearInput($name) . "'";
		}

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

	/**
	 * Returns array of fields
	 * @param string $table
	 * @return mixed[]
	 */
	public static function getSchema($table)
	{
		$cacheFile = APP_PATH . '/cache/' . ucfirst($table) . '.yml.php.cache.php';
		$i = 0;
		while ($i < 2) {
			if (file_exists($cacheFile)) {
				include($cacheFile);
				if ($time >= filemtime(APP_PATH . '/models/schemas/' . ucfirst($table) . '.yml.php')) { //cache valid
					break;
				}
			}
			Core_ModelGenerator::generate('tables_' . ucfirst($table));
			++$i;
		}

		//prepare data
		$fields = array();
		foreach ($data['fields'] as $name => $value) {
			$visibility = 1;
			$required = 0;
			if (substr($name, 0, 1) == '-') {
				$name = ltrim($name, '-');
				$visibility = 0;
			} elseif (substr($name, 0, 1) == '+') {
				$name = ltrim($name, '+');
				$required = 1;
			}
			$fields[$name] = array('type'       => $value,
			                       'visibility' => $visibility,
			                       'required'   => $required);
		}

		$ids = array_map('trim', explode(',', $data['ids']));

		if (isset($data['indexes'])) $indexes = $data['indexes'];
		else $indexes = array();

		return array('fields'  => $fields,
		             'ids'     => $ids,
		             'indexes' => $indexes);
	}
}
