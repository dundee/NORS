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
	/** @var string Version of Mysql DB */
	private $mysqlVersion;

	/**
	 * Returns version of current Mysql DB
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->mysqlVersion;
	}

	/**
	 * Escapes special characters in a string
	 *
	 * @param string $val
	 * @return string
	 */
	public function escape($val)
	{
		if(!$this->connection) $this->connect();
		if (get_magic_quotes_gpc()) $val = stripslashes($val);
		return $this->connection->real_escape_string($val);
	}

	/**
	 * Basic function for executing a SQL query. Allows chaining $db->query(...)->query->();.
	 *
	 * @param string $query SQL query
	 * @return Core_DB
	 */
	public function query($query)
	{
		$this->result = $this->sql_query($query);
		return $this;
	}

	/**
	 * Silent version of query method.
	 *
	 * @param string $query SQL query
	 * @return Core_DB
	 */
	public function silentQuery($query)
	{
		$this->result = $this->sql_query($query, TRUE);
		return $this;
	}

	/**
	 * Executes query and returnes one asociative row of the result.
	 *
	 * @param string $query SQL query
	 * @return string[] result
	 */
	public function getRow($query = false)
	{
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}
		return $result_link->fetch_array();
	}

	/**
	 * Executes query and returnes all asociative rows of the result.
	 *
	 * @param string $query SQL query
	 * @return string[][] result
	 */
	public function getRows($query = false)
	{
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}

		while ($row = $result_link->fetch_array()) {
			$return[] = $row;
		}
		return isset($return) ? $return : FALSE;
	}

	/**
	 * Executes query and returns number of rows.
	 *
	 * @param string $query SQL query
	 * @return int
	 */
	public function num($query = false)
	{
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}

		return $result_link->num_rows();
	}

	/**
	 * Executes query and returns last inserted ID.
	 *
	 * @param string $query SQL query
	 * @return int
	 */
	public function id($query = false)
	{
		if ($query) {
			$this->sql_query($query);
		}
		return $this->connection->insert_id();
	}

	public function __destruct()
	{
		if($this->connection) $this->connection->close();
	}

	protected function __construct(Core_Config $config)
	{
		$this->data = $config->db;
		$encoding = strtolower($config->encoding);
		switch($encoding){
			case 'utf-8':
				$this->charset = 'utf8';
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
				$this->charset = 'utf8';
		}
	}

	/**
	 * Creates a connection to DB. Can't be called directly.
	 *
	 * @return boolean
	 */
	protected function connect()
	{
		$this->connection = mysqli_connect($this->data->host,
		                                  $this->data->user,
		                                  $this->data->password
		                                  );
		$res = $this->connection->select_db($this->data->database);
		if (!$res) throw new RuntimeException(__('DB_not_exists') . ": " . $this->data->database, $this->connection->errno());
		$this->connection->query("SET CHARACTER SET " . $this->charset);

		//set connection encoding
		$res = $this->connection->query("SHOW VARIABLES LIKE 'version'");
		$line = $res->fetch_array();
		$this->mysqlVersion = substr($line['Value'], 0, 3);
		if ($this->mysqlVersion < '4.1') {
			throw new Exception('MySQL version 4.1 or higher is required');
		}
		@$this->connection->query("SET NAMES '" . $this->charset . "'");

		if ($this->connection->error) throw new RuntimeException(__('DB_connection_failed') . " : " . $this->connection->error, $this->connection->errno);

		//timezone
		@$this->connection->query("SET time_zone = '" . Core_Config::singleton()->timezone . "'");

		return TRUE;
	}

	/**
	 * Wrapper for mysql_query function with additional features.
	 *
	 * @param string $query SQL query
	 * @param boolean $silent silent execution
	 * @return string MySQL result
	 */
	protected function sql_query($query, $silent = FALSE)
	{
		$this->counter++;

		$this->query = $query;
		if(!$this->connection) $this->connect();

		$start_time = mtime();

		$this->result = mysqli_query($this->connection, $query);

		if (!HIGH_PERFORMANCE && Core_Config::singleton()->debug->enabled) {
			$end_time = mtime();

			$this->queries[] = array('query' => $query,
									 'time'  => round($end_time-$start_time, 4),
									 'rows'  => mysqli_affected_rows($this->connection)
									 );
		}

		if (mysqli_error($this->connection) && !$silent) {
			$msg = __('DB_query_failed') . " : " . mysqli_error($this->connection) . ' - ' . $query;
			throw new RuntimeException($msg, mysqli_errno($this->connection));
		}
		return $this->result;
	}
}
