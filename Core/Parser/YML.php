<?php

/**
* Core_Parser_YML
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Parser_YML
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_Parser_YML
{

	/**
	 * Lines of YML file
	 * @var string[] $lines
	 */
	private static $lines;

	private static $file;

	/**
	 * Reads the YML file and transforms it into PHP array
	 * @param string $file Path to YML file
	 * @param string $cacheFile Path to cache file
	 * @return bool
	 */
	public static function read($file, $cacheFile)
	{
		self::$lines = file($file);
		self::$file = $file;

		$indentionLength = self::getIndentionLength();

		//parse config file
		$content = '<?php' . ENDL;
		$content .= '$time = ' . filemtime($file);
		$content .= ';' . ENDL;
		$content .= '$data = array(' . ENDL;
		$deep = 0;

		$prev_deep = 0;
		$prev_key = FALSE;
		$is_array = FALSE;
		$empty = FALSE;
		foreach (self::$lines as $line) {
			$arr = explode('#', $line); //strip comments
			$line = $arr[0];

			if (!strlen(trim($line))) continue;
			$deep = (strlen($line) - strlen(ltrim($line))) / $indentionLength;

			if ($empty) { //previous value was empty. array?
				if ($deep > $prev_deep) $content .= '"' . $key . '" => array(';
				else $content .= '"' . $key . '" => "",';
				$content .= ENDL;
				$empty = FALSE;
			}

			//closing arrays
			if ($deep < $prev_deep) {
				for ($i=0; $i < $prev_deep - $deep; $i++) {
					for ($j=0; $j < $prev_deep - $i - 1; $j++)
						$content .= TAB;
					$content .= '),' . ENDL;
				}
			}

			$delimiter_position = strpos( $line, ':' );
			$key   = trim( substr($line, 0, $delimiter_position) );
			$value = trim( substr($line, $delimiter_position+1) );

			for($i=0; $i < $deep; $i++) $content .= TAB;

			if ($value) {
				$content .= '"' . $key . '" => "' . $value . '",';
				$content .= ENDL;
			} else $empty = TRUE;

			$prev_deep = $deep;
		}

		//closing end
		if ($prev_deep > 0) {
				for ($i=0; $i < $prev_deep; $i++) {
					for ($j=0; $j < $prev_deep - $i - 1; $j++)
						$content .= TAB;
					$content .= '),' . ENDL;
				}
		}
		$content .= ');' . ENDL;
		$content .= '?>';

		//write data to cache file
		$res = file_put_contents($cacheFile, $content);
		chmod($cacheFile, 0777);
		if (!$res) {
			echo 'Directory "cache" needs to be writable by anyone (777).';
			die();
		}

		include($cacheFile);
		return $data;
	}

	/**
	 * Writes PHP array to YML file
	 * @param mixed[] $data PHP array
	 * @param string $file Path to YML file
	 * @params string $indention How to indent YML file
	 * @return bool
	 */
	public static function write($data, $file, $indention = TAB)
	{
		$indentionDeep = 0;
		$content = '#<?php die(0); ?>' . ENDL;

		foreach ($data as $name => $value) {
			if (is_array($value)) {
				$content .= $name . ': ' . ENDL;
				$content .= self::writeArray($value, $indentionDeep + 1, $indention);
			} else {
				$content .= $name . ': ' . $value;
			}
		}

		file_put_contents($file, $content);
		return TRUE;
	}

	/**
	 * Returns number of columns of indention
	 * @return int
	 */
	private function getIndentionLength()
	{
		$i = 1;
		do {
			//break;
			if (substr(self::$lines[$i], 0, 1) == TAB) { //tabs
				$indentionLength = strlen(TAB);
				break;
			} elseif (substr(self::$lines[$i], 0, 1) == " ") { //spaces
				$k = 0;
				$indention = "";
				while (substr(self::$lines[$i], $k, 1) == " ") { //how many spaces?
					$k++;
					$indention .= " ";
				}
				$indentionLength = strlen($indention);
				break;
			} else {
				$i++;
				continue;
			}
		} while ($i < 10);

		if (!isset($indentionLength)) {
			throw new UnexpectedValueException("Wrong indention in config file " . self::$file);
		}

		return $indentionLength;
	}


	public static function writeArray($arr, $indentionDeep, $indention = TAB)
	{
		$content = '';
		foreach ($arr as $name => $value) {
			for ($i=0; $i < $indentionDeep; $i++) $content .= TAB;
			if (is_array($value)) {
				$content .= $name . ': ' . ENDL;
				$content .= self::writeArray($value, $indentionDeep + 1, $indention);
			} else {
				$content .= $name . ': ' . $value. ENDL;
			}
		}
		return $content;
	}
}
