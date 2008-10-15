<?php

/**
* Core_DB_Pdo
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_DB_Pdo
*
* Integration tier class with connecting to database only when needed.
* Singleton pattern
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_DB_Pdo extends Core_DB
{

	protected function __construct($locale){
		if(!$locale instanceof Core_Locale) throw new Core_Exception('Invalid locale class: '.$locale);
		$this->locale = $locale;
		$this->log = new Core_Log();
		$this->data = $this->parseDNS(DB_DSN);
		$encoding = strtolower(ENCODING);
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
	* @return String connection
	*/
	protected function connect(){
		$this->connection = new PDO('mysql:host='.$this->data['host'].';dbname='.$this->data['database'], $this->data['user'], $this->data['password']);
		//@mysql_query("SET CHARACTER SET ".$this->charset,$this->connection);
		//@mysql_query("SET NAMES ".$this->charset,$this->connection);
		if (!$this->connection) throw new Core_Exception($this->locale->DB_connection_failed." : ".$this->connection->errorInfo(),
		                                                 $this->connection->errorCode());
		return true;
	}

	/**
	* sql_query
	*
	* Inner function - abstraction of mysql_query
	*
	* @param String $query
	* @return String MySQL result
	*/
	protected function sql_query($query){
		$this->counter++;
		//$log = new Core_Log(LOG_FILE);
		//$log->log($query);
		//echo $query;
		$this->query = $query;
		if(!$this->connection) $this->connect();

		$start_time = mtime();

		$this->result = $this->connection->query($query);

		$end_time = mtime();

		$this->queries[] = array('query'=>$query,
		                         'time'=>round($end_time-$start_time,4),
		                         'rows'=>$this->result->rowCount()
		                         );

		if($this->connection->error){
			$msg = $this->locale->DB_query_failed." : ".$this->connection->error.' - '.$query;
			$log = new Core_Log(LOG_FILE);
			$log->log($msg);
			throw new Core_Exception($msg,$this->connection->errno);
		}
		return $this->result;
	}

	/**
	* query
	*
	* @param String $query
	* @return String result
	*/
	public function query($query){
		$this->result = $this->sql_query($query);
		return $this;
	}

	/**
	* getRow
	*
	* @param String $query
	* @return Array result
	*/
	public function getRow($query = false){
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}

		return $result_link->fetch();
	}

	/**
	* getRows
	*
	* @param String $query
	* @return Array result
	*/
	public function getRows($query = false){
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}

		return $result_link->fetchAll();
	}

	/**
	* num
	*
	* @param String $query
	* @return int
	*/
	public function num($query = false){
		if ($query) {
			$result_link = $this->sql_query($query);
		} else {
			$result_link = $this->result;
		}

		return $result_link->rowCount();
	}

	/**
	* id
	*
	* @param String $query
	* @return int
	*/
	public function id($query = false){
		if ($query) {
			$this->sql_query($query);
		}
		return $this->connection->lastInsertId();
	}




	/**
	* Destructor
	* @access public
	*/
	public function __destruct(){
	}
}
