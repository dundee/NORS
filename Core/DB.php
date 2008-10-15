<?php

/**
* Core_DB
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_DB
*
* Integration tier class with connecting to database only when needed.
* Singleton pattern
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
abstract class Core_DB
{
	/**
	* $instance
	*
	* @var Core_DB $instance
	*/
	protected static $instance;

	/**
	* $connection
	*
	* @var string $connection
	*/
	protected $connection;

	/**
	* $data
	*
	* @var array $data
	*/
	protected $data = array('host'=>'',
	                        'user'=>'',
	                        'password'=>'',
	                        'database'=>''
	                      );

	/**
	* $charset
	*
	* @var string $charset
	*/
	protected $charset;

	/**
	* $result
	*
	* @var string $result
	*/
	protected $result;

	/**
	* $query
	*
	* Last query
	*
	* @var string $query
	*/
	public $query;

	/**
	* $counter
	*
	* @var int $counter
	*/
	public $counter;

	public $queries = array();

	/**
	* singleton
	*
	* @return Core_DB instance
	*/
	public static final function singleton(){
		if (!isset(self::$instance)) {
			$config = Core_Config::singleton();
			$class = 'Core_DB_' . ucfirst($config->db->connector);
			$instance = new $class($config);
			if (!$instance instanceof Core_DB)
				throw new UnexpectedValueException("Wrong DB class");
			self::$instance = $instance;
		}
		return self::$instance;
	}

	/**
	* connect
	*
	* @return String connection
	*/
	protected abstract function connect();

	/**
	* sql_query
	*
	* Inner function - abstraction of mysql_query
	*
	* @param String $query
	* @return String MySQL result
	*/
	protected abstract function sql_query($query);

	/**
	* query
	*
	* @param String $query
	* @return String result
	*/
	public abstract function query($query);

	/**
	* getRow
	*
	* @param String $query
	* @return String[][] result
	*/
	public abstract function getRow($query = false);

	/**
	* getRows
	*
	* @param String $query
	* @return String[][] result
	*/
	public abstract function getRows($query = false);

	/**
	* num
	*
	* @param String $query
	* @return int
	*/
	public abstract function num($query = false);

	/**
	* id
	*
	* @param String $query
	* @return int
	*/
	public abstract function id($query = false);

	/**
	* Destructor
	* @access public
	*/
	public abstract function __destruct();
}
