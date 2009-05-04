<?php

/**
* Locale_En
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
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

	public function encodeDatetime($dmy_his)
	{
		if (!$dmy_his) return "0000-00-00 00:00:00";
		return $dmy_his;

		list($date, $time) = explode(' ', $dmy_his);
		list($d,$m,$y) = explode('.', $date);
		list($h,$i,$s) = explode(':', $time);
		return "$y-$m-$d $h:$i:$s";
	}

	public function encodeDate($dmy)
	{
		if (!$dmy) return "0000-00-00";

		list($d,$m,$y) = explode('.', $dmy);
		return "$y-$m-$d";
	}
}
?>
