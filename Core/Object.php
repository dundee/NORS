<?php

/**
 * Core_Object
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 *
 */

/**
 * Core_Object
 *
 * Base class for most of Core classes. Adds important functionality and access to some instances.
 * Implements Iterator, ArrayAccess, Countable and setters/getters, so data can be accessed via $obj->getData('key'), $obj['key'] and foreach.
 * Features: reflection, attributes setting from Array
 * Access to instances: Core_Request, Core_Config
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Object implements Iterator, //foreach iteration of data
                                      ArrayAccess, //for offset access to data (object['key'])
                                      Countable //for counting of data
{
	/**
	 * $request
	 *
	 * @var Core_Request $request
	 */
	public $request;

	/**
	 * $config
	 *
	 * @var Core_Config
	 */
	protected $config;

	/**
	 * $me
	 *
	 * @var ReflectionClass $me
	 */
	protected $me;

	/**
	 * $data
	 *
	 * @var array $data
	 */
	protected $data;

	/**
	 * Constructor
	 */
	public function __construct(){
		$this->me       = new ReflectionClass($this);
		$this->request  = Core_Request::factory();
		$this->config   = Core_Config ::singleton();
	}

	/**
	 * setFromArray
	 *
	 * @param array $data
	 * @return void
	 */
	protected function setFromArray($data){
		if (iterable($data)) {
			$valid = get_class_vars(get_class($this));
			foreach ($valid as $var => $val) {
				if (isset($data[$var])) {
					$this->$var = $data[$var];
				}
			}
		}
	}

	/**
	 * setData
	 *
	 * @param string $key Name of variable
	 * @param mixed $value Value of variable
	 * @param boolean $allowHtml If false, <>& will be replaced by entities
	 * @return void
	 */
	public function setData($key,$value, $allowHtml = FALSE){
		$this->data[$key] = clearOutput($value, $allowHtml);
		return TRUE;
	}

	/**
	 * getData
	 *
	 * @return String[]
	 */
	public function getData(){
		return $this->data;
	}

	public function delData(){
		unset($this->data);
	}


	/**
	 * __toString
	 * @return String
	 */
	public function __toString(){
		return $this->me->getName();
	}


	//Iterator
	public function current(){
		return $this->data->current();
	}

	public function next(){
		return $this->data->next();
	}

	public function key(){
		return $this->data->key();
	}

	public function valid(){
		return $this->data->valid();
	}

	public function rewind(){
		return $this->data->rewind();
	}

	//ArrayAccess
	public function offsetExists($offset){
		return isset($this->data[$offset]);
	}

	public function offsetGet($offset){
		if (!isset($this->data[$offset])) return FALSE;
		return $this->data[$offset];
	}

	public function offsetSet($offset,$value){
		$this->data[$offset] = $value;
	}

	public function offsetUnset($offset){
		unset($this->data[$offset]);
	}

	//Countable
	public function count(){
		return count($this->data);
	}

	public function __destruct()
	{
	}
}
