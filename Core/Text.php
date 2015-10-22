<?php


/**
 * Letters that can't be used in URL.
 *
 * @global string NOT_ALLOWED_ENTITIES Not allowed letters
 */
define('NOT_ALLOWED_ENTITIES', "ĚŠČŘŽÝÁÄÍÉŮÚÜÓÖŐŇŤĎĽľěščřžýáäíéůúüóöňťď. '`´;:=?!@#$%^&*+/|,<>{}()[]~\"¨§");
define('ALLOWED_ENTITIES',     "ESCRZYAAIEUUUOOONTDLlescrzyaaieuuuoontd----------------------------------");

/**
 * Text manipulation class
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

	/**
	 * Gets perex from text
	 * @param int $maxWords Maximum number of words in perex
	 * @param string $text
	 * @return string
	 */
	public function getPerex($maxWords = 100, $text = FALSE)
	{
		$text = $text ? $text : $this->text;

		$per = $this->getParagraphs(1, $text);
		if (substr_count($per, " ") > $maxWords) {
			$per = $this->getWords($maxWords, $text) . '...';
		}
		return $per;
	}

	/**
	 * Gets paragraphs from text
	 * @param int $number Number of paragraphs
	 * @param string $text
	 * @return string
	 */
	public function getParagraphs($number, $text = FALSE)
	{
		$text = $text ? $text : $this->text;

		$text = preg_replace("/\n\s+\n/", "\n\n", $text);

		return $this->getTokens($text, $number, "\n\n");
	}

	/**
	 * Gets words from text
	 * @param int $number Number of words
	 * @param string $text
	 * @return string
	 */
	public function getWords($number, $text = FALSE)
	{
		$text = $text ? $text : $this->text;
		$text = str_replace(ENDL, " ", $text); //endlines split words as well
		$text = preg_replace("/\s{2,}/", " ", $text); //remove multiple spaces

		return $this->getTokens($text, $number, " ");
	}

	/**
	 * Formats plain text into valid html.
	 *
	 * @param string $text
	 * @return string forrmated html
	 */
	public function format_comment($text = FALSE) {
		$text = $text ? $text : $this->text;
		/*$text = strip_tags($text);*/
		$text = htmlspecialchars($text);
		$text = str_replace("\r", '', $text);

		$text = $this->clearAmpersand($text);
		$text = preg_replace('/(.+?)(?:\n\n|\z)/s', '<p>$1</p>' . ENDL, $text); //paragraphs
		$text = preg_replace('%(?<!</p>)\s*\n%', '<br />' . ENDL, $text); //newline into break but not after </p>

		while (preg_match('%\[code\](.*)</?p>(.*)\[/code\]%s', $text)) {
			$text = preg_replace('%\[code\](.*)<p>(.*)\[/code\]%s', "[code]$1$2[/code]", $text);
			$text = preg_replace('%\[code\](.*)</p>(.*)\[/code\]%s', "[code]$1\n$2[/code]", $text);
		}

		while (preg_match('%\[code\].*<br />.*\[/code\]%s', $text)) {
			$text = preg_replace('%\[code\](.*)<br />(.*)\[/code\]%s', "[code]$1$2[/code]", $text);
		}
		
		//XSS in url and img
		if (preg_match('%\[url\].+\[/url\]%U', $text)) {
			if (!preg_match('%\[url\]https?://.+\[/url\]%U', $text)) {
				$text = preg_replace('%\[url\](.+)\[/url\]%U', '', $text);
			}
		}
		if (preg_match('%\[img\].+\[/img\]%U', $text)) {
			if (!preg_match('%\[img\]https?://.+\[/img\]%U', $text)) {
				$text = preg_replace('%\[img\](.+)\[/img\]%U', '', $text);
			}
		}

		//tags
		$text = preg_replace('%\[url\](.+)\[/url\]%U', '<a href="$1">$1</a>', $text); //ungreedy
		$text = preg_replace('%\[img\](.+)\[/img\]%U', '<a href="$1">' . __('image') . '</a>', $text);
		$text = preg_replace('%\[b\](.+)\[/b\]%U', '<strong>$1</strong>', $text);
		$text = preg_replace('%\[i\](.+)\[/i\]%U', '<em>$1</em>', $text);
		$text = preg_replace('%\[code\](.*)\[/code\]%Us', '<pre class="brush: php">$1</pre>', $text);

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

		//code
		$code = array();
		$start = strpos($text, '<code>');
		$i = 0;
		while (!($start === false)) {
			$length = strpos( substr($text, $start + 1, strlen($text) - ($start+1)) , '</code>');
			$length += 3;
			$code[$i] = trim(substr($text, $start, $length));
			$text = str_replace($code, "#CODE$i#", $text);

			$start = FALSE;
			$start = strpos($text, '<code>');
			$i++;
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

			if (preg_match('%^\./%', $path)) {
				$arr = explode("/", $path);
				$filename = $arr[count($arr)-1];
				$arr[count($arr) - 1] = 'thub';
				$arr[] = $filename;
				$thub_path = implode("/", $arr);

				$thub = '<div class="thumbnail-old">
		<a href="' . APP_URL . '/' . $path . '" class="lightbox" title="' . $alt . '">
			<img src="' . APP_URL . '/' . $thub_path . '" alt="' . $alt . '" />
		</a>
	</div>';
				$text = str_replace($img, $thub, $text);
			}

			$start = strpos($content, "<img", $start + $length);
		}

		$text .= ENDL;

		//make space around some tags (due to paragraphs)
		$text = preg_replace('!(<(?:code|table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', ENDL . '$1', $text);
		$text = preg_replace('!(</(?:code|table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', '$1' . ENDL . ENDL, $text);

		$text = preg_replace('/(.+?)(?:\n\n\s*|\z\s*)/s', '<p>$1</p>' . ENDL, $text); //paragraphs
		//$text = preg_replace('%(?<!</p>)\s*\n%', '<br />' . ENDL, $text); //newline into break but not after </p>

		//remove <p> around tags
		$text = preg_replace('!<p>\s*(</?(?:code|table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $text);
		$text = preg_replace('!(</?(?:code|table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>|</div>"!', "$1", $text);

		if (iterable ($code)) {
			foreach ($code as $i=>$v) {
				$text = str_replace("#CODE$i#", $v, $text);
			}
		}

		//syntax highlighting
		$text = str_replace('<code>', '<pre class="brush: php">', $text);
		$text = str_replace('</code>', '</pre>', $text);

		return $text;
	}

	/**
	 * Hides mail away from bots
	 * @param string $text
	 * @return string
	 */
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

	/**
	 * MD5 crypt with soil
	 * @param string $text Text to be crypted
	 * @param string $soil
	 * @return string
	 */
	public function crypt($text, $soil){
		$pass = $soil.'+dfgyuI9'.$text;
		return md5($pass);
	}

	/**
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
		} elseif ( eregi('[0-9]{4}-[0-9]{2}-[0-9]{2}', $ymd_his) ){
			list($y,$m,$d) = explode("-",$ymd_his);
			$h = $i = $s = 0;
		} else {
			return time();
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

	/**
	 * Gets tokens from text
	 * @param string $text
	 * @param int $number Number of paragraphs
	 * @param string $delimiter
	 * @return string
	 */
	private function getTokens($text, $number, $delimiter = " ")
	{
		$length = mb_strlen($text);
		$begin = 0;

		if (strpos($text, $delimiter) === FALSE) return $text;

		for($i = $number; $i > 0; $i--) {
			$position = mb_strpos($text, $delimiter, ++$begin);
			$begin = $position;
			//echor($begin.'-'.$i);
			if(!$begin){
				$position = $length;
				break;
			}
		}
		return mb_substr($text, 0, $position);
	}

}
