<?php

/**
* Locale_En
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Locale_En
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Locale_En extends Core_Locale
{

	public $data = array();
						
	public function decodeDate($ymd_his){
		$text = new Core_Text($ymd_his);
		return date("Y/m/d",$text->dateToTimeStamp());
	}

	public function decodeDatetime($ymd_his){
		$text = new Core_Text($ymd_his);
		return date("Y/m/d H:i:s",$text->dateToTimeStamp());
	}
}
?>
