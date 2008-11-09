<?php
/**
 * Url
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Url
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Type_Url extends Core_Type
{
	public function prepareForDB($value)
	{
		$text = new Core_Text();
		$value = $text->urlEncode();
		return addslashes($value);
	}
	
	public function prepareForWeb($value)
	{
		return htmlspecialchars($value);
	}
	
	public function getDefinition()
	{
		return 'VARCHAR(200) NOT NULL';
	}
}
