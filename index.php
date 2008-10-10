<?php
/**
 * index.php
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

error_reporting(E_ERROR | E_PARSE);//self::$config->debug->error_reporting);
ini_set('display_errors', 1);

define('APP_PATH', dirname(__FILE__));
require_once('./Core/Functions.php');

set_url_path();

Core_Config::singleton()->read('config.yml.php');

define('STYLE_URL', APP_URL . '/styles/' . Core_Config::singleton()->style);

$app = new Core_Application();
$app->run();
$r = Core_Request::factory();
