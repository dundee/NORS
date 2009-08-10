<?php

/**
 * Basic functions
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Endline
 */
define('ENDL',"\n");

/**
 * Tabulator
 */
define('TAB',"\t");

/**
 * Return the version of NORS
 *
 * @return string
 */
function norsVersion() {
	return "4.3.1";
}

if (!function_exists('memory_get_usage')) {
	throw new Exception('PHP version at least 5.2 needed');
}

/**
 * Defines the URL pointing to index.php
 *
 * @return void
 */
function setUrlPath()
{
	//running HTTP server
	if (isset($_SERVER['HTTP_HOST'])) {
		$url = 'http://' . $_SERVER['HTTP_HOST'];

		//index not redirected to subdirectory
		if (strpos($_SERVER['REQUEST_URI'], dirname($_SERVER['PHP_SELF'])) !== FALSE) {
			$url .= dirname($_SERVER['PHP_SELF']);
		}

	} else { //running CLI
		$url = 'http://localhost';
	}
	$url = rtrim($url, '/');
	define('APP_URL', $url);
}

if (!function_exists('__autoload')) {
	/**
	 * Loads class
	 * @param string $class Class name
	 * @return void
	 */
	function __autoload($class)
	{
		//rules
		$class = preg_replace('/^ActiveRecord_/', 'models_activeRecords_', $class);
		$class = preg_replace('/^Table_/', 'models_tables_', $class);
		$class = preg_replace('/^Component_/', 'components_', $class);

		$file = str_replace('_', '/', $class) . '.php';
		if (!file_exists(APP_PATH . '/' . $file)) {
			do {
				if (substr($class, 0, 7) == 'models_') {
					$res = Core_ModelGenerator::generate($class);
					if ($res) break;
				}

				$log = new Core_Log();
				$log->log($class);
				throw new UnexpectedValueException("Class $class can not be found.");
			} while (FALSE);
		}
		loadFile(APP_PATH . '/' . $file);
	}
}

if (!function_exists('loadFile')) {
	/**
	 * Loads file and counts memory and time usage
	 *
	 * @param string $class Class name
	 * @return boolean
	 */
	function loadFile($file)
	{
		global $included_files;

		if (!HIGH_PERFORMANCE) {
			$start_time   = mtime();
			$start_memory = memory_get_usage();
		}

		$result = require_once($file);

		if (!$result) throw new UnexpectedValueException("File $file not found");

		if (!HIGH_PERFORMANCE) {
			$end_time   = mtime();
			$end_memory = memory_get_usage();

			$included_files[] = array('name'   => $file,
								'time'   => round($end_time-$start_time, 4),
								'memory' => round(($end_memory-$start_memory) / 1024)
								);
		}
		return TRUE;
	}
}

/**
 * Return the name of DB table including prefix
 *
 * @param string $table Table name
 * @return string Table name with prefix
 */
function tableName($table)
{
	if (!defined('DB_PREFIX')) {
		$prefix = Core_Config::singleton()->db->table_prefix;
		define('DB_PREFIX', $prefix);
	}
	return DB_PREFIX . $table;
}

/**
 * Returns UNIX timestamp with miliseconds
 *
 * @return float
 */
function mtime()
{
	list($mili, $sec) = explode(" ", microtime());
	return ($sec + $mili);
}

/**
 * Is object/array iterable (can be used in foreach)?
 * @return boolean
 */
function iterable($array){
	if (isset($array) && is_array($array) && count($array) > 0) return TRUE; //array

	if (!is_array($array) && is_object($array)) { //Iterable object
		$impl = class_implements($array);
		if (isset($impl['Iterator']) && $impl['Iterator'] && $array->valid() !== FALSE) return TRUE;
	}

	return FALSE;
}

/**
 * Clears value before inserting to DB
 */
function clearInput($val, $numeric = FALSE)
{
	global $db_object;
	if ($numeric) return intval($val);
	if (!$db_object) $db_object = Core_DB::singleton();
	return $db_object->escape($val);
}

/**
 * Clears value before sending to client
 */
function clearOutput($val, $allowHtml = FALSE)
{
	if (is_array($val)) {
		foreach ($val as &$v) {
			$v = clearOutput($v, $allowHtml);
		}
		return $val;
	} elseif (is_string($val)) {
		$val = stripslashes($val); //for backward-compatibility reasons (magic quotes)
		if (!$allowHtml) return htmlspecialchars($val);
		return $val;
	} elseif ($val instanceof Core_ActiveRecord) {
		return $val->setFromArray(clearOutput($val->getData(), $allowHtml));
	} else {
		return $val;
	}
}

/*----------------------- SHORTCUTS -----------------*/

/**
 * Localize string
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

/**
 * Print object to screen
 */
function dump($var){
	Core_Debug::dump($var);
}

/**
 * Print string to screen nicely
 */
function echor($string)
{
	echo '<br />-' . $string . '-<br />' . ENDL;
}

if (!function_exists('json_encode')) {

	/**
	 * Encode string to JSON
	 */
	function json_encode_string($in_str)
	{
		$in_str = addslashes($in_str);
		mb_internal_encoding("UTF-8");
		$convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
		$str = "";
		for ($i = mb_strlen($in_str)-1; $i >= 0; $i--) {
			$mb_char = mb_substr($in_str, $i, 1);
			if (mb_ereg("&#(\\d+);", mb_encode_numericentity($mb_char, $convmap, "UTF-8"), $match)) {
				$str = sprintf("\\u%04x", $match[1]) . $str;
			} else {
				$str = $mb_char . $str;
			}
		}
		$str = str_replace("\r\n", '\r\n', $str);
		$str = str_replace("\t", '\t', $str);
		return $str;
	}

	/**
	 * Encode Array to JSON
	 */
	function json_encode($arr)
	{
		$json_str = "";
		if (is_array($arr)) {
			$pure_array = true;
			$array_length = count($arr);
			for ($i = 0; $i < $array_length; $i++) {
				if (! isset($arr[$i])) {
					$pure_array = false;
					break;
				}
			}
			if ($pure_array) {
				$json_str ="[";
				$temp = array();
				for ($i = 0; $i < $array_length; $i++) {
					$temp[] = sprintf("%s", php_json_encode($arr[$i]));
				}
				$json_str .= implode(",",$temp);
				$json_str .= "]";
			} else {
				$json_str ="{";
				$temp = array();
				foreach ($arr as $key => $value) {
					$temp[] = sprintf("\"%s\":%s", $key, json_encode($value));
				}
				$json_str .= implode(",",$temp);
				$json_str .="}";
			}
		} else {
			if(is_string($arr)) {
				$json_str = "\"". json_encode_string($arr) . "\"";
			} elseif (is_numeric($arr)) {
				$json_str = $arr;
			} else {
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
		foreach ($obj as &$v) $v = $function($v);
	} else $obj = $function($obj);

	return $obj;
}

/**
 * converts Array to StdObject
 *
 * @param string[] $arr
 * @return StdObject
 */
function convertArrayToObject($arr)
{
	foreach($arr as &$v){
		if (is_array($v)) $v = convertArrayToObject($v);
	}
	return (object) $arr;
}

/**
 * converts StdObject to Array
 *
 * @param StdObject $object
 * @return string[]
 */
function convertObjectToArray($object)
{
	$array = array();
	$class = new ReflectionObject($object);
	$properties = $class->getProperties();

	foreach ($properties as $property) {
		if ($object->{$property->name} instanceof StdClass) {
			$array[$property->name] = convertObjectToArray($object->{$property->name});
		} else {
			$array[$property->name] = $object->{$property->name};
		}
	}

	return $array;
}

/**
 * Tests if environment covers application requirements
 */
function testEnvironment()
{
	if (substr(phpversion(), 0, 3) < 5.2) die('Required PHP version at least 5.2');
	if (!isset($_SERVER['REWRITE'])
	    && !isset($_SERVER['REDIRECT_STATUS']))  die('Controller "mod_rewrite" needs to be enabled.');
}

if (!function_exists('posix_getuid')) {
	/**
	 * Hack for M$ Windows users
	 */
	function posix_getuid()
	{
		return 0;
	}
}

/**
 * Checks if file is writable
 * @param string $file
 */
function checkIfWritable($file)
{
	if (!isWritable($file))  throw new RuntimeException('"' . $file . '" ' . __('needs to be writable'));
}

/**
 * Is file writable?
 * @param string $file
 * @return boolean
 */
function isWritable($file)
{
	$perms = getFilePerms($file);
	$owner = fileowner($file);
	$uid   = posix_getuid();

	$req = is_file($file) ? 6 : 7; //Directory needs to be 7, file 6

	if ($uid == $owner) {
		if (substr($perms, 0, 1) >= $req) return TRUE;
	} else {
		if (substr($perms, -1) >= $req) return TRUE;
	}
	return FALSE;
}

/**
 * Gets file perms in normal format (UGO...777)
 */
function getFilePerms($file)
{
	if (!file_exists($file)) die('File or directory ' . $file . ' doesn\'t exist. Please create it.');

	return substr(sprintf('%o', fileperms($file)), -3);
}
