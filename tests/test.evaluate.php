<?php
$_SERVER = array();
$_SERVER['PHP_SELF'] = '/nors4/index.php';
$_SERVER['REQUEST_URI'] = '/nors4/post/';
$_SERVER['SCRIPT_NAME'] = '/nors4/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';
$_GET['module'] = 'post';


define('HIGH_PERFORMANCE', 0);
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

define('APP_PATH', dirname(__FILE__) . '/..');
require_once('../Core/Functions.php');

setUrlPath();

Core_Config::singleton()->read(APP_PATH . '/config/config.yml.php');


$router   = Core_Router::factory();
$request  = Core_Request ::factory();

$router->decodeUrl($request, FALSE);

/* ************* TEST ************** */

$_COOKIE['post_eval'] = '';
$_POST['url'] = 'http://localhost/nors4/post/1-test/';
$_POST['value'] = 1;

$component = new Component_Post();
$component->evaluate();

echo ENDL;
?>
