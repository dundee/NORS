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
abstract class Core_ActiveRecord
{
	/**
	 * $fields
	 *
	 * Array of fields in table. Visibility and type affect administration.
	 * @var string[][] $fields
	 */
	public $fields = array();

	/**
	 * $db
	 *
	 * Instance of Core_DB
	 * @var Core_DB $db
	 */
	protected $db;

	/**
	 * $data
	 *
	 * @var array $data
	 */
	protected $data;

	/**
	 * $table
	 *
	 * Name of table
	 * @var string $table
	 */
	protected $table;

	public function __construct($table,$id = false)
	{
		$this->db    = Core_DB::singleton();
		$this->table = $table;

		$schema = Core_Table::getSchema($table);
		$this->fields = $schema['fields'];

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
			$result = $this->db->getRow("SELECT *
			                            FROM `" . tableName($this->table) . "`
			                            WHERE `id_".$this->table . "` = '" . clearInput($id,TRUE) . "'");
		} catch (RuntimeException $ex) {
			if ($ex->getCode() == 1146) {
				$class = 'Table_' . ucfirst($this->table);
				$table = new $class;
				$table->create();
				return FALSE;
			} else {
				throw new RuntimeException($ex->getMessage(),
				                           $ex->getCode());
			}
		}

		foreach($this->fields as $key => $val) {
			if (!isset($result[$key])) continue;
			$this->data[$key] = $result[$key];
		}
		return TRUE;
	}

	/**
	 * save
	 *
	 * Saves object attributes to DB. (UPDATE or INSERT).
	 * If object has valid ID, UPDATE will be performed, else INSERT.
	 *
	 * @return int ID
	 */
	public function save($id = 0)
	{
		isset($this->data['id_' . $this->table]) && $id = $this->data['id_' . $this->table];

		//prepare data
		foreach ($this->fields as $name=>$field) {
			if ($field['type'] == 'password') {

				//password not filled
				if (!$this->data[$name]) {
					unset($this->fields[$name]);
					continue;
				}

				$text_obj = new Core_Text();
				$this->data[$name] = $text_obj->crypt($this->data[$name], $this->data['name']);
			}
			if ($name == 'id_user' && $this->table != 'user') {
				$this->data[$name] = Core_Session::singleton()->id_user;
			}
			//if ($field['type'] == 'file') unset($this->fields[$name]);

			if (!isset($this->data[$name])) continue;

			$type       = $field['type'];
			$type_class = 'Core_Type_' . ucfirst($type);
			$type_obj   = new $type_class;
			$this->data[$name] = $type_obj->prepareForDB($this->data[$name]);
		}

		//save data
		try {
			if (isset($id) && $id > 0) 	{
				$this->update($id);
			} else {
				$id = $this->insert();
			}
		} catch(RuntimeException $ex) {
				if($ex->getCode()==1146) {
					$class = 'Table_' . ucfirst($this->table);
					$table = new $class;
					$table->create();
					return FALSE;
				}
				else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		return $id;
	}

	public function update($id)
	{
		$items = '';
		foreach ($this->fields as $name=>$field) {
			if (!isset($this->data[$name])) continue;
			$items .= ($items?', ':'') . "`" . $name
				. "` = '" . $this->data[$name] . "'";
		}
		$sql = "UPDATE `" . tableName($this->table) . "`
		        SET " . $items . "
		        WHERE `id_" . $this->table . "` = '" . clearInput($id, TRUE) . "'";
		$this->db->query($sql);
	}

	public function insert()
	{
		$items = '';
		$fields = '';
		foreach ($this->fields as $name=>$field) {
			if (!isset($this->data[$name])) continue;
			$items  .= ($items?', ':'') . "'" . $this->data[$name] . "'";
			$fields .= ($fields?', ':'') . "`" . $name . "`";
		}
		$sql = "INSERT INTO `" . tableName($this->table) . "`
			(" . $fields . ")
			VALUES (" . $items . ")";
		$this->data['id_' . $this->table] = $this->db->id($sql);
		return $this->data['id_' . $this->table];
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
	 * Sets data from array
	 *
	 * @param string[] $array
	 */
	public function setFromArray($array)
	{
		$this->data = $array;
		return $this;
	}

	public function getData()
	{
		return $this->data;
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
	 * delete
	 *
	 * Deletes object from DB.
	 *
	 * @return void
	 */
	public function delete()
	{
		$sql = "DELETE
		        FROM `" . tableName($this->table) . "`
		        WHERE `id_" . $this->table . "` = '"
		        . clearInput($this->data['id_' . $this->table], 1)
		        . "' LIMIT 1";
		$this->db->query($sql);
	}

	public function getFiles()
	{
		$request = Core_Request::factory();
		$id = $this->getID() ? $this->getID() : $request->getGet('id');

		$table = new Table_File();
		$method = 'findById_' . $this->table;
		$files = $table->$method($id);
		$path  = APP_URL . '/' . Core_Config::singleton()->upload_dir . '/';

		for ($i=0; $i < count($files) && is_array($files); $i++) {
			$file = new Core_File($files[$i]->name);
			$files[$i]->thub = $path . $file->thubnail();
			$files[$i]->src  = $path . $files[$i]->name;
		}
		return $files;
	}

	/**
	 * Saves files from $_FILES
	 */
	public function saveFiles()
	{
		$file = new Core_File();
		$request = Core_Request::factory();

		foreach($this->fields as $name => $field) {
			if ($field['type'] == 'file') {
				$name = str_replace('[]', '', $name);

				$items = $file->upload($name);
				if (!$items) continue;
				if (!is_array($items)) $items = array($items);

				foreach ($items as $i => $item) {
					$arr = $request->getPost($name . '_title');
					$label = $arr[$i];
					$instance = new ActiveRecord_File();
					$instance->{'id_' . $this->table} = $request->getPost('id');
					$instance->name           = $item->fileName;
					$instance->label          = $label;
					$instance->type           = $item->getType();
					$instance->save();
				}
			}
		}
	}

	public function updateFile($name, $file, $params)
	{
		$label     = $params['label'];

		$table = new Table_File();
		list($instance) = $table->findByName($file);
		$instance->label = $label;
		$instance->save();
	}

	public function deleteFile($name, $file)
	{
		$table = new Table_File();
		list($instance) = $table->findByName($file);
		$instance->delete();
	}

	public function activate()
	{
		$this->active = isset($this->active) && $this->active ? 0 : 1;
		$this->save();
	}
}
