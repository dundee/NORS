<?php

/**
* Core_Config
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Config
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Config
{
	protected $data;
	protected static $instance;

	/**
	 * singleton
	 *
	 * @return Core_Config
	 */
	public static function singleton(){
		if (!self::$instance){
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}

	/**
	 * __get
	 *
	 * @param $key string
	 * @return string
	 */
	public function __get($key){
		if (!isset($this->data->{$key})) return FALSE;
		return $this->data->{$key};
	}

	/**
	 * read
	 *
	 * Reads configuration from a file.
	 *
	 * @param string $file
	 * @return void
	 */
	public function read($file){

		if ($this->data) return TRUE;

		$cacheFile = APP_PATH . '/cache/' . $file . '.cache.php';

		if (file_exists()) {
			include($cacheFile);
			if ( $time == filemtime($cacheFile) ) { //cache valid
				$this->data = $data;
				$this->prepareData();
				return;
			}
		}

		Core_Parser_YML::read($file, $cacheFile);

		include($cacheFile);
		$this->data = $data;
		$this->prepareData();
		return TRUE;
	}

	/**
	 * prepareData
	 *
	 * Prepares configuration data
	 *
	 * @return void
	 */
	private function prepareData()
	{
		$array = $this->data;
		$host = isset($_SERVER['HTTP_HOST'])
		        ? $_SERVER['HTTP_HOST']
		        : 'localhost';
		$host = str_replace('www.', '', $host);

		if (!isset($array[$host]))
			throw new RuntimeException('No configuration for "' . $host . '"');

		$array = $array[$host];
		$array['host'] = $host;

		$data = $this->convertArrayToObject($array);
		$this->data = $data;
	}

	/**
	 * convertArrayToObject
	 *
	 * @param string[] $arr
	 * @return StdObject
	 */
	private function convertArrayToObject($arr){
		foreach($arr as $k => $v){
			if (is_array($v)) $arr[$k] = $this->convertArrayToObject($v);
		}
		return (object) $arr;
	}

}
?>
