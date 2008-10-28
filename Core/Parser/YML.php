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

	/**
	 * Reads the YML file and transforms it into PHP array
	 * @param string $file Path to YML file
	 * @param string $cacheFile Path to cache file
	 * @return bool
	 */
	public static function read($file, $cacheFile)
	{
		self::$lines = file($file);

		$indentionLength = self::getIndentionLength();

		//parse config file
		$content = '<?php' . ENDL;
		$content .= '$time = ' . filemtime($file);
		$content .= ';' . ENDL;
		$content .= '$data = array(' . ENDL;
		$deep = 0;

		$prev_deep = 0;
		$prev_key = false;
		$is_array = false;
		foreach (self::$lines as $line) {
			$arr = explode('#', $line); //strip comments
			$line = $arr[0];

			if (!strlen(trim($line))) continue;
			$deep = (strlen($line) - strlen(ltrim($line))) / $indentionLength;

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

			if ( $value !== '' )
				$content .= '"' . $key . '" => ' . $value . ',';
			else $content .= '"' . $key . '" => array(';
			$content .= ENDL;
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
		if (!$res)
			throw new RuntimeException("Config cache could not be written.");

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
		$content = '#<?php die(0); ?>';
		
		foreach ($data as $name => $value) {
			if (is_array($value)) {
				$content .= self::writeArray($value, $indentionDeep);
			} else {
				$content .= $name . ': ' . $value;
			}
		}
		
		dump($content);
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
			throw new UnexpectedValueException("Wrong indention in config file.");
		}

		return $indentionLength;
	}
	
	public static writeArray($arr, $indentionDeep)
	{
		//for($i=0; $i < $deep; $i++) $content .= TAB;
	}
}
