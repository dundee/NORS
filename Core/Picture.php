<?php

/**
 * Smart thumbnailing of pictures
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Picture
{
	/**
	 * $fileName
	 *
	 * @var String $fileName
	 */
	public $fileName;

	/**
	 * $x
	 *
	 * @var int $x
	 */
	public $x;

	/**
	* $y
	*
	* @var int $y
	*/
	public $y;


	/**
	* Constructor
	* @access public
	*/
	public function __construct($fileName){
		if(!file_exists(APP_PATH . '/' .  $fileName)) {
			echo 'Image file "'.APP_PATH. '/' .$fileName.'" does not exist.<br />';
			return;
			//throw new UnexpectedValueException('Image file "'.APP_PATH.$fileName.'" does not exist.');
		}
		$this->fileName = APP_PATH . '/' . $fileName;
	}

	/**
	 * Creates thumbnail
	 *
	 * @access public
	 * @param string $thubnailFileName Path to thubnail image
	 * @param int [$size_x] X-dimension of thubnail
	 * @param int [$size_y] Y-dimension of thubnail
	 * @param boolean [$cutBorders] Cut borders of image if needed
	 * @return boolean True if everything is OK.
	 */
	public function thubnail($thubnailFileName,$size_x = 0,$size_y = 0, $cutBorders = FALSE){

		if (file_exists(APP_PATH . '/' . $thubnailFileName)) return TRUE;
		if (!file_exists($this->fileName)) return FALSE;

		if (!extension_loaded("gd")) throw new RuntimeException("GD not supported.");
		$imageInfo = getimagesize ($this->fileName);
		switch ($imageInfo[2]) {
			case "1":
				$imageType="GIF";
				break;
			case "2":
				$imageType="JPEG";
				break;
			case "3":
				$imageType="PNG";
				break;
			default:
				throw new UnexpectedValueException("Image format of ".$this->fileName." is not supported.");
		}

		$this->x = $imageInfo[0];
		$this->y = $imageInfo[1];

		$createFunction = "ImageCreateFrom".$imageType;
		$imageFunction = "Image".$imageType;

		$imageSource = $createFunction($this->fileName);

		if (!$size_x && !$size_y){ //nothing set
			$size_x = 200; //default value
		}

		if (!$size_x){
			$size_x = ($this->x * $size_y)/$this->y;
		}
		if (!$size_y){
			$size_y = ($this->y * $size_x)/$this->x;
		}

		$ratio1 = round($this->x / $this->y, 2);
		$ratio2 = round($size_x / $size_y, 2);

		if ($cutBorders && $ratio1 != $ratio2){ //orizneme okraje, aby se fotka pri zmensovani nedeformovala
			if ($ratio1 > $ratio2){
				$new_x = $this->x / $ratio1 * $ratio2;
				$new_y = $this->y;
			}
			if ($ratio1 < $ratio2){
				$new_x = $this->x;
				$new_y = $this->y * $ratio1 / $ratio2;
			}
			$delta_x = $this->x - $new_x;
			$delta_y = $this->y - $new_y;

			$imageCut = ImageCreateTrueColor($new_x, $new_y);
			imagecopy($imageCut, $imageSource, 0, 0, $delta_x / 2, $delta_y / 2, $new_x, $new_y);
			$this->x = $new_x;
			$this->y = $new_y;
			$imageSource = $imageCut;
		}

		$imageNew = ImageCreateTrueColor($size_x,$size_y);
		imagecopyresampled($imageNew, $imageSource, 0, 0, 0, 0, $size_x, $size_y, $this->x, $this->y);
		$result = $imageFunction($imageNew,APP_PATH . '/' . $thubnailFileName);
		ImageDestroy($imageSource);
		ImageDestroy($imageNew);
		if(!$result)return FALSE;
		return TRUE;
	}
}
