<?php

/**
* Core_Locale
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Locale
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

	private static $instance;

	/**
    * factory
    *
    * @access public
    * @param string $type Language
    * @return mixed Exception on erroe or a valid language instance
    * @static
    */
    static public function factory($locale = FALSE){
        if (isset(self::$instance)) return self::$instance;

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

    public function __set($key,$val){
		$this->data[$key] = $val;
	}

	public function __get($key){
		if(!isset($this->data[$key])) return str_replace('_', ' ', $key);
		return $this->data[$key];
	}

	public function __toString(){
		$me = new ReflectionClass($this);
		return $me->getName();
	}

	public function opera_date($datetime){
		if (!eregi("[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}",$datetime)) return $datetime;
		list($date,$time) = explode(" ",$datetime);
		list($y,$m,$d) = explode("-",$date);
		list($g,$i,$s) = explode(":",$time);
		return $y."-".$m."-".$d."T".$g.":".$i."Z";
	}

	public abstract function decodeDate($ymd_his);
	public abstract function decodeDatetime($ymd_his);


}
