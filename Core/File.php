<?php

/**
 * Class for file operations: uploading, thumbnail creation
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_File
{
	/**
	 * $fileName
	 *
	 * @var string $fileName
	 */
	public $fileName;

	/**
	 * $dir
	 *
	 * @var string $dir
	 */
	 public $dir;

	public function __construct($name = FALSE, $dir = FALSE)
	{
		if (!$name) return;

		if (!$dir) $dir = Core_Config::singleton()->upload_dir;
		$path = APP_PATH . '/' . $dir . '/' . $name;

		if (!file_exists($path)) return FALSE; //throw new UnexpectedValueException('File "' . $name . '" does not exist.');

		$this->fileName = $name;
		$this->dir = $dir;
	}

	/**
	 * upload function
	 *
	 * @param string $name Index of $_FILES array
	 * @param string $fileName Name of target file
	 * @param string $dir Name of target dir
	 * @return Core_File[]
	 */
	public function upload($name, $fileName = FALSE, $dir = FALSE)
	{
		if (!$dir)      $dir      = Core_Config::singleton()->upload_dir;
		if (!$fileName) $fileName = date("YmdHis").sprintf("%02d",rand(0,99));

		$ret = FALSE;

		if (!isset($_FILES[$name]["name"]) ||
		    !$_FILES[$name]["name"] ||
		    (is_array($_FILES[$name]["name"]) && !$_FILES[$name]["name"][0])) return FALSE;
		/*$log = new Core_Log();
		$log->log("dump:" . print_r($_FILES, TRUE));*/


		if (!is_array($_FILES[$name]["name"])) {
			$_FILES[$name]["name"] = array($_FILES[$name]["name"]);
			$_FILES[$name]["tmp_name"] = array($_FILES[$name]["tmp_name"]);
		}

		for ($i = 0; $i < count($_FILES[$name]["name"]); $i++) {
			$type = $this->getType($_FILES[$name]["name"][$i]);
			if ($type != 'image' && $type != 'document') {
				throw new Exception("Only upload of images and documents is allowed");
			}

			$fileName = date("YmdHis").sprintf("%02d",rand(0,99));
			$arr = explode('.', $_FILES[$name]["name"][$i]);
			$sufix = $arr[count($arr)-1];
			$path = APP_PATH . '/' . $dir . '/' . $fileName . '.' . $sufix;

			$res = move_uploaded_file($_FILES[$name]["tmp_name"][$i], $path);

			chmod($path, 0644);
			if (!$res) throw new Exception("Upload of " . $name . " failed");
			$ret[] = new Core_File($fileName . '.' . $sufix, $dir);
		}
	return $ret;
	}

	/**
	 * Creates thumbnail
	 * @param int $x Width of thumbnail
	 * @param int $y Height of thumbnail
	 */
	public function thubnail($x = 100, $y = 0){
		if (!$this->fileName || strpos($this->fileName, '.') <= 0) return FALSE;

		$picture = new Core_Picture($this->dir . '/' .  $this->fileName);
    	list($name,$type) = explode(".", $this->fileName);
    	$thubnailName = $name . '_' . $x . 'x' . $y . '.' . $type;
    	$picture->thubnail($this->dir . '/' . $thubnailName, $x, $y);
    	return $name . '_' . $x . 'x' . $y . '.' . $type;
    }

	/**
	 * Returns suffix of file
	 * @param string $name Filename
	 * @return string sufix of the file name (jpg, pdf, exe, etc.)
	 */
	public function getSufix($name = FALSE){
		if (!$name) $name = $this->name;
		$arr = explode(".",$name);
		return $arr[count($arr)-1];
	}

	/**
	 * Returns type of file (document, image, executable, unknown)
	 * @param string $name Filename
	 * @return string type of file
	 */
	public function getType($name = FALSE){
		if (!$name) $name = $this->fileName;

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
}
