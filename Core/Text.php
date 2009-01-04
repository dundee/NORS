<?php


/**
* NOT_ALLOWED_ENTITIES
*
* Letters that can't be used in URL.
*
* @global string NOT_ALLOWED_ENTITIES Not allowed letters
*/
define('NOT_ALLOWED_ENTITIES', "ĚŠČŘŽÝÁÄÍÉŮÚÜÓÖŐŇŤĎĽľěščřžýáäíéůúüóöňťď. '`´?!@#$%^&*+/|,<>{}()[]~\"¨§");
define('ALLOWED_ENTITIES',     "ESCRZYAAIEUUUOOONTDLlescrzyaaieuuuoontd------------------------------");

/**
* Classes_Text
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Classes_Text
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Text
{
	protected $text;

	public function __construct($text = FALSE){
		$this->text = $text;
	}

	public function getWords($words,$text = FALSE){
		$text = $text ? $text : $this->text;
		$length = strlen($text);
		$begin = 0;

		if (strpos($text," ") === FALSE) return $text;

		for($i=$words;$i>0;$i--){
			$position = strpos($text," ",++$begin);
			$begin = $position;
			//echor($begin.'-'.$i);
			if(!$begin){
				$position = $length;
				break;
			}
		}
		return substr($text,0,$position);
	}

	/**
	* format_text
	*
	* Formats plain text with html tags into valid html.
	*
	* @param string $text Text
	* @param boolean $br Makes line breaks
	* @return string Text Forrmated html
	*/
	public function format_comment($text = FALSE) {
    	$text = $text ? $text : $this->text;
		$text = htmlspecialchars($text);
		$text = str_replace("\r", '', $text);

		$text = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&amp;$1', $text); //replace & by &amp; but ommit entities
		$text = preg_replace('/(.+?)(?:\n\n|\z)/s', '<p>$1</p>' . ENDL, $text); //paragraphs
		$text = preg_replace('%(?<!</p>)\s*\n%', '<br />' . ENDL, $text); //newline into break but not after </p>
		
    	return $text;
	}

	public function hideMail($text = FALSE) {
		$text = $text ? $text : $this->text;
		$return = '';
		for($i=0; $i < strlen($text); $i++){
			$x = substr($text, $i, 1);
			$return .= "&#" . ord($x) . ";";
		}
		return $return;
	}
	
	/**
 	 * urlEncode
 	 *
 	 * Generates text prepared to be displayed as URL.
 	 *
 	 * @param string $text Non-formated text
 	 * @return string Text formated for URL
 	 */
	public function urlEncode($text = FALSE)
	{
		$encoding = Core_Config::singleton()->encoding;
		$text = $text ? $text : $this->text;
	    $link = str_replace("–", "-", $text);
	    $link = str_replace("—", "-", $link);

	    if (!$encoding || $encoding == 'utf-8') {
	        $bezcs = iconv("utf-8", "iso-8859-2", $link);
	        $cs = iconv("utf-8", "iso-8859-2", NOT_ALLOWED_ENTITIES);
	        $bez = iconv("utf-8", "iso-8859-2", ALLOWED_ENTITIES);
	        $link = StrTr($bezcs, $cs, $bez);
	        $link = iconv("iso-8859-2", "utf-8", $link);
	    } else {
	        $link = StrTr($link, NOT_ALLOWED_ENTITIES, ALLOWED_ENTITIES);
	    } while (strpos($link, '--')) {
	        $link = str_replace('--', '-', $link);
	    }
	    $link = StrToLower($link);
	    $link = clearOutput($link);
	    $link = str_replace("&quot;", "", $link);
	    $link = str_replace("'", "", $link);
	    return $link;
	}

	public function crypt($text, $soil){
		$pass = $soil.'+dfgyuI9'.$text;
		return md5($pass);
	}
	/**
	 * dateToTimeStamp
	 *
	 * Creates a timestamp from date string in format "yyyy-mm-dd hh:ii:ss" or "yyyy-mm-dd"
	 * @param String $ymd_his Date string
	 * @return int Unix timestamp
	 */
	public function dateToTimeStamp($ymd_his = FALSE){
		$ymd_his = $ymd_his ? $ymd_his : $this->text;
		if ( eregi('[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}', $ymd_his)) {
			list($date,$time) = explode(" ",$ymd_his);
			list($y,$m,$d) = explode("-",$date);
			list($h,$i,$s) = explode(":",$time);
		} elseif ( eregi('[0-9]{4}-[0-9]{2}-[0-9]{2}') ){
			list($y,$m,$d) = explode("-",$ymd_his);
			$h = $i = $s = 0;
		}
		return mktime ($h, $i, $s, $m, $d, $y);
	}
}
