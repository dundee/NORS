<?php
/**
 * Administration
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Nors4
 */

/**
 * Administration
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Nors4
 */
class Core_ModelGenerator
{
	/**
	 * Generates model classes from YML schema
	 * @param string $class
	 * @return bool
	 */
	static public function generate($class)
	{
		$class = preg_replace('/^models_/', '', $class);
		list($dir, $name) = explode('_', $class);
		$fileName = APP_PATH . '/models/schemas/' . $name . '.yml.php';
		if (!file_exists($fileName)) return FALSE;

		do {
			$cacheFileName = APP_PATH . '/cache/' . $name . '.yml.php.cache.php';
			if (file_exists($cacheFileName)) {
				include($cacheFileName);
				if ($time >= filemtime($fileName)) { //cache valid
					break;
				}
			}
			$data = Core_Parser_YML::read($fileName, $cacheFileName);
		} while (FALSE);

		$class = $name;
		$table = strtolower($class);

		foreach($data['fields'] as $type) {
			if ($type == 'file') $files = <<<EOF

	public function getFiles()
	{
	}

	public function saveFiles()
	{
	}
EOF;
			else $files = '';
		}

		$activeRecord_temp = <<<EOF
<?php

/**
 * ActiveRecord_$class
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * ActiveRecord_$class
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class ActiveRecord_$class extends Core_ActiveRecord
{
	public function __construct(\$id_user=false)
	{
		parent::__construct('$table',\$id_user);
	}
	$files
}
EOF;

		$table_temp = <<<EOF
<?php

/**
 * Table_$class
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Table_$class
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Table_$class extends Core_Table
{
	public function __construct()
	{
		parent::__construct('$table');
	}
}
EOF;
		if (!file_exists(APP_PATH . '/models/activeRecords/' . $class . '.php')) {
			file_put_contents(APP_PATH . '/models/activeRecords/' . $class . '.php', $activeRecord_temp);
			chmod(APP_PATH . '/models/activeRecords/' . $class . '.php', 0777);
		}
		if (!file_exists(APP_PATH . '/models/tables/' . $class . '.php')) {
			file_put_contents(APP_PATH . '/models/tables/' . $class . '.php', $table_temp);
			chmod(APP_PATH . '/models/tables/' . $class . '.php', 0777);
		}

		require_once(APP_PATH . '/models/activeRecords/' . $class . '.php');
		require_once(APP_PATH . '/models/tables/' . $class . '.php');
		return TRUE;
	}
}
