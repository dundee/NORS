<?php

/**
* Core_ActiveRecord_File
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_ActiveRecord_File
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_ActiveRecord_File extends Core_ActiveRecord
{
	/**
	* $fields
	*
	* Array of fields in table.
	* @var array $fields
	*/
	public $fields = array('id_file'     => array('visibility' => 0,  'type' => 'int'),
	                       'id_post'     => array('visibility' => 0,  'type' => 'int'),
	                       'id_product'  => array('visibility' => 0,  'type' => 'int'),
	                       'id_text'     => array('visibility' => 0,  'type' => 'int'),
	                       'name'        => array('visibility' => 1,  'type' => 'text'),
	                       'label'       => array('visibility' => 1,  'type' => 'text'),
	                       'type'        => array('visibility' => 0,  'type' => 'enum')
	);

	public function __construct($id=false){
		parent::__construct('file',$id);
	}

	/**
	* thubnail
	*
	* @return String file name
	*/
	public function thubnail($x = 100, $y = 0){
		if (!$this->name) return FALSE;
		$picture = new Core_Picture(APP_PATH.'/upload/'.$this->name);
		list($name,$type) = explode(".",$this->name);
		$thubnailName = APP_PATH.'/upload/'.$name.'_'.$x.'x'.$y.'.jpg';
		$picture->thubnail($thubnailName,$x,$y);
		return $name.'_'.$x.'x'.$y.'.jpg';
	}
	
	/**
	* getSufix
	*
	* @return String sufix of the file name (jpg, pdf, exe, etc.)
	*/
	public function getSufix($name = FALSE){
		if (!$name) $name = $this->name;
		$arr = explode(".",$name);
		return $arr[count($arr)-1];
	}
	
	/**
	* getType
	*
	* @return String type of file
	*/
	public function getType($name = FALSE){
		if (!$name){
			if (isset($this->data['type']) && $this->data['type']) return $this->data['type']; 
			$name = $this->name;
		}
		$sufix = $this->getSufix($name);
		$sufix = strtolower($sufix);
		switch($sufix){
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'bmp':
				$type = 'image';
				break;
			case 'doc':
			case 'pdf':
			case 'xls':
			case 'csv':
			case 'ppt':
			case 'odt':
			case 'txt':
			case 'htm':
			case 'html':
			case 'xml':
				$type = 'document';
				break;
			case 'exe':
			case 'com':
				$type = 'executable';
				break;
			default:
				$type = 'unknown';
				break;
		}
		return $type;
	}
	
	
	
	
	
	

	public function __destruct(){
		parent::__destruct();
	}
}

?>
