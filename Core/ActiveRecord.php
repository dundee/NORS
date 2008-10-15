<?php

/**
 * Core_ActiveRecord
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_ActiveRecord
 *
 * Base model class. Allows user to easily define table structure and create,
 * modify, select and delete DB rows.
 * Implements Iterator, ArrayAccess, Countable and magic setters/getters,
 * so data can be accessed via $obj->key, $obj['key'] and foreach.
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_ActiveRecord extends Core_Object
{
	/**
	 * $fields
	 *
	 * Array of fields in table. Visibility and type affect administration.
	 * @var string[][] $fields
	 */
	protected $fields = array(
		'id_site' => array('visibility' => 0,
		                   'type' => 'int',
		                   'db' => 'int(11) unsigned'),
		'name'    => array('visibility' => 1,
		                   'type' => 'text',
		                   'db' => 'varchar(100) NOT NULL')
	);

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


	public function __construct($table,$id = false)
	{
		parent::__construct();
		$this->db    = Core_DB::singleton();
		$this->table = $table;
		if($id) $this->load($id);
	}

	/**
	 * load
	 *
	 * Loads object attributes with one row specified by ID.
	 *
	 * @param String $id
	 * @return boolean
	 */
	public function load($id)
	{
		if ($id < 1) return FALSE;
		try {
			$result = $this
			          ->db
			          ->getRow("SELECT *
			                   FROM `" . table_name($this->table) . "`
			                   WHERE `id_".$this->table . "` = '"
			                   . clearInput($id,TRUE) . "'");
		} catch(RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$this->create();
				return FALSE;
			} else {
				throw new RuntimeException($ex->getMessage(),
				                           $ex->getCode());
			}
		}
		$this->data = $result;
		return TRUE;
	}

	/**
	 * save
	 *
	 * Saves object attributes to DB. (UPDATE or INSERT).
	 * If object has valid ID, UPDATE will be performed, else INSERT.
	 *
	 * @return void
	 */
	public function save($id = 0)
	{
		isset($this->data['id_' . $this->table])
		&& $id = $this->data['id_' . $this->table];

		//prepare data
		foreach ($this->fields as $name=>$field) {
			if ($field['type'] == 'url') {
				$text_obj = new Core_Text();
				$this->data[$name] = $text_obj->urlEncode($this->data['name']);
			}
			if ($field['type'] == 'password') {
				$text_obj = new Core_Text();
				$this->data[$name] = $text_obj->crypt($this->data[$name], $this->data['name']);
			}
			if ($name == 'id_user' && $this->table != 'user') {
				$this->data[$name] = Core_Session::singleton()->id_user;
			}
			if ($field['type']=='checkbox') {
				$this->data[$name] = $this->data[$name] ? 1 : 0;
			}
		}

		//save data
		try {
			if (isset($id) && $id > 0) 	{
				$items = '';
				foreach ($this->fields as $name=>$field) {
					if (!isset($this->data[$name])) continue;
					$items .= ($items?', ':'') . "`" . $name
					        . "` = '"
					        . clearInput($this->data[$name],
					                $field['type'] == 'int')
					        . "'";
				}
				$sql = "UPDATE `" . table_name($this->table) . "`
				        SET " . $items . "
				        WHERE `id_" . $this->table . "` = '"
				        . clearInput($id, TRUE) . "'";
				$this->db->query($sql);
			} else {
				$items = '';
				$fields = '';
				foreach ($this->fields as $name=>$field) {
					if (!isset($this->data[$name])) continue;
					$items .= ($items?', ':'') . "'"
					        . clearInput($this->data[$name],
					                     $field['type'] == 'int') . "'";
					$fields .= ($fields?', ':'') . "`" . $name . "`";
				}
				$sql = "INSERT INTO `" . table_name($this->table) . "`
				        (" . $fields . ")
				        VALUES (" . $items . ")";
				$this->db->query($sql);
			}
		} catch(RuntimeException $ex) {
				if($ex->getCode()==1146) {
					$this->create();
					return FALSE;
				}
				else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
	}

	/**
	 * getID
	 *
	 * Returns ID
	 *
	 * @return int
	 */
	public function getID()
	{
		if (!isset($this->data['id_' . $this->table])) return FALSE;
		return $this->data['id_' . $this->table];
	}

	/**
	 * __get($var)
	 *
	 * Returns the requested attribute.
	 *
	 * @return mixed Returns the value of $this->data[$var]
	 */
	public function __get($key)
	{
		if (!isset($this->data[$key])) return false;
		return $this->data[$key];
	}

	/**
	 * __set
	 *
	 * Sets the value of requested attribute.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return boolean
	 */
	public function __set($key,$value)
	{
		return ($this->data[$key] = $value);
	}

	/**
	 * activates/deactivates instance
	 */
	public function activate()
	{
	}

	/**
	 * delete
	 *
	 * Deletes object from DB.
	 *
	 * @return void
	 */
	public function delete()
	{
		$sql = "DELETE
		        FROM `" . table_name($this->table) . "`
		        WHERE `id_" . $this->table . "` = '"
		        . clearInput($this->data['id_' . $this->table], 1)
		        . "' LIMIT 1";
		$this->db->query($sql);
		$this->__destruct();
	}
}
