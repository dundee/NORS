<?php


/**
* NOT_ALLOWED_ENTITIES
*
* Letters that can't be used in URL.
*
* @global string NOT_ALLOWED_ENTITIES Not allowed letters
*/
define('NOT_ALLOWED_ENTITIES', "ĚŠČŘŽÝÁÄÍÉŮÚÜÓÖŐŇŤĎĽľěščřžýáäíéůúüóöňťď. '`´?!@#$%^&*+/|,<>{}()[]~\"");
define('ALLOWED_ENTITIES',     "ESCRZYAAIEUUUOOONTDLlescrzyaaieuuuoontd----------------------------");

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
	function format_text($text = FALSE, $br = FALSE) {
    	$text = $text ? $text : $this->text;
		if (eregi("<code>", $text)) $code = eregi_replace(".*<code>(.+)</code>.*", "\\1", $text); //zachovani podoby
    	// by rADo
    	$text = trim($text);
    	$text = str_replace("\r", '', $text);
    	$text = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&amp;$1', $text);
    	if ($text == "") return "";
    	$text = $text . "\n"; // just to make things a little easier, pad the end
    	$text = preg_replace('|<br/>\s*<br/>|', "\n\n", $text);
    	$text = preg_replace('!(<(?:table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', "\n$1", $text); // Space things out a little
    	$text = preg_replace('!(</(?:table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', "$1\n", $text); // Space things out a little
    	$text = preg_replace("/(\r\n|\r)/", "\n", $text); // cross-platform newlines
    	$text = preg_replace("/\n\n+/", "\n\n", $text); // take care of duplicates
    	$text = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "\t<p>$1</p>\n", $text); // make paragraphs, including one at the end
    	$text = preg_replace('|<p>\s*?</p>|', '', $text); // under certain strange conditions it could create a P of entirely whitespace
    	$text = preg_replace("|<p>(<li.+?)</p>|", "$1", $text); // problem with nested lists
    	// blockquote
    	$text = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $text);
    	$text = str_replace('</blockquote></p>', '</p></blockquote>', $text);
    	// now the hard work
    	$text = preg_replace('!<p>\s*(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $text);
    	$text = preg_replace('!(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>|</div>"!', "$1", $text);
    	if ($br) {
        	// optionally make line breaks
        	$text = preg_replace('/>\s+</', '><', $text); // remove spaces between list items
        	$text = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $text);
    	}
    	$text = preg_replace('!(</?(?:table|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*<br/>!', "$1", $text);
    	$text = preg_replace('!<br/>(\s*</?(?:p|li|div|th|pre|td|ul|ol)>)!', '$1', $text);
    	// some cleanup
    	$text = str_replace('</p><br />', '</p>', $text);
    	$text = str_replace('<br /></p>', '</p>', $text);
    	$text = str_replace("<br />\n</p>", '</p>', $text);

    	if (eregi("<code>", $text)) {
        	$text = eregi_replace("<code>(.+)</code>", "CODE", $text); //nahrazeni puvodnim obsahem
        	$text = str_replace("CODE", "<code>" . $code . "</code>", $text); // -||-
    	}
    	return $text;
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
