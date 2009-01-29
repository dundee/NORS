<?php


/**
* NOT_ALLOWED_ENTITIES
*
* Letters that can't be used in URL.
*
* @global string NOT_ALLOWED_ENTITIES Not allowed letters
*/
define('NOT_ALLOWED_ENTITIES', "ĚŠČŘŽÝÁÄÍÉŮÚÜÓÖŐŇŤĎĽľěščřžýáäíéůúüóöňťď. '`´;:=?!@#$%^&*+/|,<>{}()[]~\"¨§");
define('ALLOWED_ENTITIES',     "ESCRZYAAIEUUUOOONTDLlescrzyaaieuuuoontd----------------------------------");

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

	public function __construct($text = FALSE)
	{
		$this->text = $text;
	}

	public function getWords($words, $text = FALSE)
	{
		$text = $text ? $text : $this->text;
		$length = mb_strlen($text);
		$begin = 0;

		if (strpos($text," ") === FALSE) return $text;

		for($i=$words; $i>0; $i--){
			$position = mb_strpos($text," ", ++$begin);
			$begin = $position;
			//echor($begin.'-'.$i);
			if(!$begin){
				$position = $length;
				break;
			}
		}
		return mb_substr($text, 0, $position);
	}

	/**
	* Formats plain text into valid html.
	*
	* @param string $text
	* @return string forrmated html
	*/
	public function format_comment($text = FALSE) {
		$text = $text ? $text : $this->text;
		$text = strip_tags($text);
		$text = htmlspecialchars($text);
		$text = str_replace("\r", '', $text);

		$text = $this->clearAmpersand($text);
		$text = preg_replace('/(.+?)(?:\n\n|\z)/s', '<p>$1</p>' . ENDL, $text); //paragraphs
		$text = preg_replace('%(?<!</p>)\s*\n%', '<br />' . ENDL, $text); //newline into break but not after </p>

		return $text;
	}

	/**
	* Formats html into valid html.
	*
	* @param string $text
	* @return string forrmated html
	*/
	public function format_html($text = FALSE) {
		$text = $text ? $text : $this->text;
		$text = str_replace("\r", '', $text);

		$text = $this->clearAmpersand($text);

		//tables
		$start = strpos($text, '||');
  		while (!($start === false)) {
			$length = strpos( substr($text, $start + 1, strlen($text) - ($start+1)) , '||');
			$length += 3;
			$table = trim(substr($text, $start, $length));

			$output = '<table rules="all" border="1">';
			$rows = explode(ENDL, $table);
			for ($i = 1; $i < (count($rows) - 1); $i++) {
				if ($i == 1) $tag = 'th';
				else $tag = 'td';

				$rows[$i] = str_replace('|', '</' . $tag . '><' . $tag . '>', $rows[$i]);
				$output .= ENDL . '<tr><' . $tag . '>' . $rows[$i] . '</' . $tag . '></tr>';
			}
			$output .= ENDL . '</table>';
			$text = str_replace($table, $output, $text);

			$start = FALSE;
			$start = strpos($text, '||');
		}

		//pictures - needed for NORS 3 posts
		$content = $text;
		$i=0;
		$start = strpos($content, "<img");
		while(!($start===false)){
			$length = strpos( substr($content, $start + 1, strlen($content) - ($start + 1)) , ">");
			$length += 2;
			$img = trim(substr($content, $start,  $length));
			$path = eregi_replace('^<img +src="([^"]*)".*>$',"\\1", $img);
			$alt  = eregi_replace('^<img +src="[^"]+" +alt="([^"]+)".*>$', "\\1", $img);

			$arr = explode("/", $path);
			$filename = $arr[count($arr)-1];
			$arr[count($arr) - 1] = 'thub';
			$arr[] = $filename;
			$thub_path = implode("/", $arr);

			$thub = '<div class="thumbnail">
	<a href="' . APP_URL . '/' . $path . '" class="thickbox" title="' . $alt . '">
		<img src="' . APP_URL . '/' . $thub_path . '" alt="' . $alt . '" />
	</a>
	<div class="caption">
		<a href="' . APP_URL . '/'  . $path . '" class="thickbox" title="' . $alt . '">' . $alt . '</a>
	</div>
</div>';
			$text = str_replace($img, $thub, $text);

			$start = strpos($content, "<img", $start + $length);
		}

		/*for($i=0;$i<count($img);$i++){
		$picture = new Picture($img[$i]);
		$img[$i] = $picture->show();
		}*/

		$text .= ENDL;

		//make space around some tags (due to paragraphs)
		$text = preg_replace('!(<(?:code|table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', ENDL . '$1', $text);
		$text = preg_replace('!(</(?:code|table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', '$1' . ENDL . ENDL, $text);

		$text = preg_replace('/(.+?)(?:\n\n\s*|\z\s*)/s', '<p>$1</p>' . ENDL, $text); //paragraphs
		//$text = preg_replace('%(?<!</p>)\s*\n%', '<br />' . ENDL, $text); //newline into break but not after </p>

		//remove <p> around tags
		$text = preg_replace('!<p>\s*(</?(?:code|table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $text);
		$text = preg_replace('!(</?(?:code|table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>|</div>"!', "$1", $text);

		/*//source code
		while (preg_match('/<code>(.*)<br \/>(.*)<\/code>/s', $text)) {
			$text = preg_replace('/<code>(.*)<br \/>(.*)<\/code>/s', '<code>$1$2</code>', $text);
		}*/

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
		$text = $text ? $text : $this->text;
		$text = str_replace("–", "-", $text);
		$text = str_replace("—", "-", $text);

		$text        = iconv("utf-8", "iso-8859-2", $text);
		$not_allowed = iconv("utf-8", "iso-8859-2", NOT_ALLOWED_ENTITIES);
		$allowed     = iconv("utf-8", "iso-8859-2", ALLOWED_ENTITIES);

		$link = strtr($text, $not_allowed, $allowed);
		$link = iconv("iso-8859-2", "utf-8", $link);

		while (strpos($link, '--')) {
			$link = str_replace('--', '-', $link);
		}

		$link = strtolower($link);
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

	/**
	 * Replaces & by &amp; but ommit entities
	 * @param string $text
	 * @return string
	 */
	public function clearAmpersand($text)
	{
		return preg_replace('/&([^#])(?![a-z]{1,8};)/', '&amp;$1', $text);
	}
}
