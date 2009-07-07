<?php

/**
 * Localizes strings and dates. Base class for localization files.
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Locale
{
	/**
	 * $data
	 *
	 * @var array $data Array of texts
	 */
	protected $data = array();

	private static $instance = NULL;

	/**
	 * Creates appropriate locale
	 *
	 * @param string $type name of language
	 * @return mixed language instance
	 */
	static public function factory($locale = FALSE){
		if (self::$instance != NULL) return self::$instance;

		if(!$locale) $locale = Core_Config::singleton()->locale;
		$file = APP_PATH.'/locales/'.$locale.'.php';
		if (loadFile($file)) {
			$class = 'Locale_'.$locale;
			if (class_exists($class)) {
				$instance = new $class();
				if ($instance instanceof Core_Locale) {
					self::$instance = $instance;
					return $instance;
				}
				throw new RuntimeException('Invalid locale class: '.$locale);
			}
			throw new RuntimeException('Locale class not found: '.$locale);
		}
		throw new UnexpectedValueException('Locale file not found: '.$locale);
	}

	/**
	 * Change localization for this string
	 * @param string $key string
	 * @param string $val new value
	 */
	public function __set($key,$val){
		$this->data[$key] = $val;
	}

	/**
	 * Get localization for string
	 * @param string $key string to lokalize
	 * @return string lokalized string
	 */
	public function __get($key){
		if(!isset($this->data[$key])) return str_replace('_', ' ', $key);
		return $this->data[$key];
	}

	/**
	 * Get name of class
	 * @return string Name of class
	 */
	public function __toString(){
		$me = new ReflectionClass($this);
		return $me->getName();
	}

	/**
	 * Lokalize date
	 */
	public abstract function decodeDate($ymd_his);

	/**
	 * Lokalize datetime
	 */
	public abstract function decodeDatetime($ymd_his);


}
