<?php

/**
 * Logs events to file
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Log
{
	/**
	 * $file
	 *
	 * @var string $file
	 */
	private $file;

	/**
	 * $pg
	 *
	 * @var string $pg
	 */
	private $pg;

	/**
	 * $status
	 *
	 * @var string $status
	 */
	private $status = 'closed';




	/**
	 * Constructor
	 * @access public
	 */
	public function __construct(){
		$this->file = APP_PATH.'/log/'.Core_Config::singleton()->log->file;
		$this->open();
	}

	/**
	 * open
	 *
	 * @return void
	 */
	public function open(){
		if ( !Core_Config::singleton()->log->enabled ) return;
		$this->pg = @fopen($this->file,'a');
		$this->status = 'opened';
	}

	/**
	 * log
	 *
	 * @param String text
	 * @return boolean
	 */
	public function log($text){
		if ( !Core_Config::singleton()->log->enabled ) return;
		$log = date("[Y-m-d G:i:s]").' '.$text."\n";
		$result = @fwrite($this->pg, $log);
	}

	/**
	 * close
	 *
	 * @return void
	 */
	public function close(){
		if ($this->status == 'opened') @fclose($this->pg);
		$this->status = 'closed';
		$this->__destruct();
	}

	/**
	 * Destructor
	 * @access public
	 */
	public function __destruct(){
	}
}
