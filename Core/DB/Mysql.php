<?php

/**
 * Core_DB_Mysql
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Core_DB_Mysql
 *
 * Integration tier class with connecting to database only when needed.
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_DB_Mysql extends Core_DB
{


	protected function __construct(Core_Config $config){
		$this->config = $config;
		$this->data = $this->config->db;
		$encoding = strtolower($this->config->encoding);
		switch($encoding){
			case 'utf-8':
				$this->charset = 'UTF8';
				break;
			case 'iso-8859-2':
				$this->charset = 'LATIN2';
				break;
			case 'iso-8859-1':
				$this->charset = 'LATIN1';
				break;
			case 'windows-1250':
				$this->charset = 'CP1250';
				break;
			default:
				$this->charset = 'UTF8';
		}
	}

	/**
	 * connect
	 *
	 * Creates a connection to DB. Can't be called directly.
	 *
	 * @return boolean
	 */
	protected function connect(){
		$this->connection = @mysql_connect($this->data->host,
		                                   $this->data->user,
		                                   $this->data->password
		                                   );
		@mysql_select_db($this->data->database,$this->connection);
		@mysql_query("SET CHARACTER SET ".$this->charset,$this->connection);

		//set connection encoding
		$res = @mysql_query("SHOW VARIABLES LIKE 'version'");
		$line = mysql_fetch_array($res);
		$version = substr($line['Value'],0,3);
		if ($version > '4.0') @mysql_query("SET NAMES '".$this->charset."'",$this->connection);

		if(!@mysql_error()) return TRUE;
		else throw new RuntimeException($this->locale->DB_connection_failed." : ".@mysql_error(),@mysql_errno());
		return false;
	}

	/**
	 * sql_query
	 *
	 * Wrapper for mysql_query function with additional features.
	 *
	 * @param String $query SQL query
	 * @return String MySQL result
	 */
	protected function sql_query($query){
		$this->counter++;

		$this->query = $query;
		if(!$this->connection) $this->connect();

		list($mili, $sec) = explode(" ",microtime());
		$start_time = $sec + $mili;

		$this->result = @mysql_query($query,$this->connection);

		if (Core_Config::singleton()->debug->enabled) {
			list($mili, $sec) = explode(" ",microtime());
			$end_time = $sec + $mili;

			$this->queries[] = array('query'=>$query,
									 'time'=>round($end_time-$start_time,4),
									 'rows'=>@mysql_affected_rows()
									 );
		}

		if(@mysql_error()){
			$msg = __('DB_query_failed')." : ".@mysql_error().' - '.$query;
			throw new RuntimeException($msg,@mysql_errno());
		}
		return $this->result;
	}

	/**
	 * query
	 *
	 * Basic function for executing a SQL query. Allows chaining $db->query(...)->query->();.
	 *
	 * @param String $query SQL query
	 * @return Core_DB
	 */
	public function query($query){
		$this->result = $this->sql_query($query);
		return $this;
	}

	/**
	 * getRow
	 *
	 * Executes query and returnes one asociative row of the result.
	 *
	 * @param String $query SQL query
	 * @return String[] result
	 */
	public function getRow($query = false){
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}
		return @mysql_fetch_array($result_link);
	}

	/**
	 * getRows
	 *
	 * Executes query and returnes all asociative rows of the result.
	 *
	 * @param String $query SQL query
	 * @return String[][] result
	 */
	public function getRows($query = false){
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}

		while($row = @mysql_fetch_array($result_link)){
			$return[] = $row;
		}
		return isset($return) ? $return : FALSE;
	}

	/**
	 * num
	 *
	 * Executes query and returns number of rows.
	 *
	 * @param String $query SQL query
	 * @return int
	 */
	public function num($query = false){
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}

		return @mysql_num_rows($result_link);
	}

	/**
	 * id
	 *
	 * Executes query and returns last inserted ID.
	 *
	 * @param String $query SQL query
	 * @return int
	 */
	public function id($query = false){
		if ($query) {
			$this->sql_query($query);
		}
		return @mysql_insert_id($this->connection);
	}

	public function __destruct(){
		if($this->connection) @mysql_close($this->connection);
	}
}
