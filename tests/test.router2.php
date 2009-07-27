<?php
$_SERVER = array();
$_SERVER['PHP_SELF'] = '/nors4/index.php';
$_SERVER['REQUEST_URI'] = 'http://localhost/nors4/post/'; //!!!!!!!!!!!!! also possible
$_SERVER['SCRIPT_NAME'] = '/nors4/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';

define('HIGH_PERFORMANCE', 0);
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

define('APP_PATH', dirname(__FILE__) . '/..');
require_once('../Core/Functions.php');

setUrlPath();

Core_Config::singleton()->init();


$router   = Core_Router::factory();
$request  = Core_Request ::factory();

$router->decodeUrl($request, FALSE);

/* ************* TEST ************** */

echo $router->genUrl('administration', 'edit', 'default', array('id'=>'5')) . ENDL . "<br />";
echo $router->genUrl('administration', 'add', FALSE, array('x'=>4)) . ENDL . "<br />";
echo $router->forward(array('id'=>10)) . ENDL . "<br />";
echo $router->forward(array('id'=>FALSE)) . ENDL . "<br />";

echo ENDL;
?>
