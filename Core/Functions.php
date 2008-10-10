<?php

define('ENDL',"\n");
define('TAB',"\t");

/**
 * Functions
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * version
 *
 * Return the version of Core
 *
 * @return string
 */
function nors_version() {
	return "4.0";
}

function core_version(){
	return "1.0";
}

/**
 * set_url_path
 * 
 * Defines the URL pointing to index.php
 * 
 * @return void
 */
function set_url_path()
{
	//running HTTP server
	if (isset($_SERVER['HTTP_HOST'])) {
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	} else { //running CLI
		$url = 'http://localhost';
	}
	define('APP_URL', $url);
}

if (!function_exists('__autoload')) {
	/**
	 * __autoload
	 *
	 * Autoload is called by PHP5 when it can't find a class.
	 *
	 * @param string $class Class name
	 * @return void
	 */
	function __autoload($class) 
	{
		//rules
		$class = str_replace('ActiveRecord_', 'activeRecords_', $class);
		$class = str_replace('Component_', 'components_', $class);
			
		$file = str_replace('_', '/', $class) . '.php';
		if (!file_exists(APP_PATH . '/' . $file)) {
			$log = new Core_Log();
			$log->log($class);
			throw new UnexpectedValueException("Class $class can not be found.");
		}
		loadFile(APP_PATH . '/' . $file);
	}
}

if (!function_exists('loadFile')) {
	/**
	 * loadFile
	 *
	 * @param string $class Class name
	 * @return boolean
	 */
	function loadFile($file) 
	{
		global $includes;

		$start_time   = get_mtime();
		$start_memory = memory_get_usage();

		$result = require_once($file);
		
		if (!$result) throw new UnexpectedValueException("File $file not found");
			
		$end_time   = get_mtime();
		$end_memory = memory_get_usage();
			
		$includes[] = array('name'   => $file,
		                    'time'   => round($end_time-$start_time, 4),
		                    'memory' => round(($end_memory-$start_memory) / 1024));
		return TRUE;
	}
}

/**
 * table_name
 *
 * Return the name of DB table including prefix
 *
 * @param string $table Table name
 * @return string Table name with prefix
 */
function table_name($table) 
{
	global $db_prefix;
	if (!isset($db_prefix)) $db_prefix = Core_Config::singleton()->db->table_prefix; 
	return $db_prefix . $table;// .'s';
}

/**
 * get_mtime
 *
 * @return float
 */
function get_mtime()
{
	list($mili, $sec) = explode(" ",microtime());
	return ($sec + $mili);
}

function iterable($array){
	return isset($array) && is_array($array) && count($array) > 0;
}

function clearInput($val, $numeric = FALSE)
{
	if ($numeric) return intval($val);
	return addslashes($val);
}

function clearOutput($val, $allowHtml = FALSE)
{
	if (is_array($val)) {
		foreach ($val as $k=>$v) {
			$val[$k] = clearOutput($v, $allowHtml);
		}
		return $val;
	} elseif (is_string($val)) {
		$val = stripslashes($val);
		if (!$allowHtml) return htmlspecialchars($val);
		return $val;
	} else {
		return $val;
	}
}

/*----------------------- SHORTCUTS -----------------*/

/**
 * __
 *
 * @param string $key
 * @return string translated term
 */
function __($key)
{
	global $locale;
	if (!$locale) $locale = Core_Locale::factory();
	return $locale->{$key};
}


function dump($var){
	Core_Debug::dump($var);
}

function echor($string)
{
	echo '<br />-' . $string . '-<br />';	
}

if (!function_exists('json_encode')) {

	function json_encode_string($in_str)
	{
		$in_str = addslashes($in_str);
		mb_internal_encoding("UTF-8");
		$convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
		$str = "";
		for($i=mb_strlen($in_str)-1; $i>=0; $i--)
		{
			$mb_char = mb_substr($in_str, $i, 1);
			if(mb_ereg("&#(\\d+);", mb_encode_numericentity($mb_char, $convmap, "UTF-8"), $match))
			{
				$str = sprintf("\\u%04x", $match[1]) . $str;
			}
			else
			{
				$str = $mb_char . $str;
			}
		}
		$str = str_replace("\r\n", '\r\n', $str);
		$str = str_replace("\t", '\t', $str);
		return $str;
	}
	function json_encode($arr)
	{
		$json_str = "";
		if(is_array($arr))
		{
			$pure_array = true;
			$array_length = count($arr);
			for($i=0;$i<$array_length;$i++)
			{
				if(! isset($arr[$i]))
				{
					$pure_array = false;
					break;
				}
			}
			if($pure_array)
			{
				$json_str ="[";
				$temp = array();
				for($i=0;$i<$array_length;$i++)
				{
					$temp[] = sprintf("%s", php_json_encode($arr[$i]));
				}
				$json_str .= implode(",",$temp);
				$json_str .="]";
			}
			else
			{
				$json_str ="{";
				$temp = array();
				foreach($arr as $key => $value)
				{
					$temp[] = sprintf("\"%s\":%s", $key, json_encode($value));
				}
				$json_str .= implode(",",$temp);
				$json_str .="}";
			}
		}
		else
		{
			if(is_string($arr))
			{
				$json_str = "\"". json_encode_string($arr) . "\"";
			}
			else if(is_numeric($arr))
			{
				$json_str = $arr;
			}
			else
			{
				$json_str = "\"". json_encode_string($arr) . "\"";
			}
		}
		return $json_str;
	}

}

/**
 * Applies function for each index of array
 *
 * @param string[] $obj
 * @param string $function
 * @return string[]
 */
function apply($obj, $function)
{
	if (is_array($obj)) {
		foreach ($obj as $k=>$v) $obj[$k] = $function($v);	
	} else $obj = $function($obj);
	
	return $obj;
}