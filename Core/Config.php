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
	public $host;
	protected $data;
	protected static $instance;

	protected function __construct()
	{
	}

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
	 * @param boolean $force_renew Force to reload the cache
	 * @return void
	 */
	public function read($file, $force_renew = FALSE){
		if ($this->data && !$force_renew) return TRUE;

		$arr = explode('/', $file);
		$name = $arr[count($arr) - 1];
		$cacheFile = APP_PATH . '/cache/' . $name . '.cache.php';

		if (!$force_renew && file_exists($cacheFile)) {
			include($cacheFile);
			if (rand(0, 10) < 8 || $time >= filemtime($file)) { //cache valid
				$this->data = $data;
				$this->prepareData();
				return TRUE;
			}
		}

		$this->data = Core_Parser_YML::read($file, $cacheFile);
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

		$this->host = $host;

		//no configuration for this host, copy default
		if (!isset($array[$host])) {
			$array[$host] = $array['localhost'];
			Core_Parser_YML::write($array, APP_PATH . '/config/config.yml.php');
		}

		$array = $array[$host];
		$array['host'] = $host;

		$data = convertArrayToObject($array);
		$this->data = $data;
	}
}
