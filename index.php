<?php
/**
 * index.php
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/* ************************************** */
/**
 * basic user settings
 */
define('HIGH_PERFORMANCE', 0);
error_reporting(E_ERROR | E_PARSE);
ini_set('magic_quotes_gpc', 0);
ini_set('magic_quotes_runtime', 0);

/* ************************************** */

define('APP_PATH', dirname(__FILE__));
require_once('./Core/Functions.php');

testEnvironment();

setUrlPath();

Core_Config::singleton()->init();

define('STYLE_URL', APP_URL . '/styles/' . Core_Config::singleton()->style);

$app = new Core_Application();
$app->run();
