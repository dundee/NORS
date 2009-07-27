<?php
$_SERVER = array();
$_SERVER['PHP_SELF'] = '/nors4/index.php';
$_SERVER['REQUEST_URI'] = '/nors4/post/';
$_SERVER['SCRIPT_NAME'] = '/nors4/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';
$_GET['controller'] = 'post';


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

$items = array('sdfsdf \' dd sad', 'sdsd \" sd " sdd', 'sdasd <strong>ff</strong>');

$inst = new ActiveRecord_Post();
$inst->text =  'asas \" sdsd <hr />';

foreach($items as $item) dump(clearOutput($item));
echo clearOutput($inst)->text;

echo ENDL . '<br />';
echo ENDL . '<br />';

$inst->text =  'asas \" sdsd <hr />';

foreach($items as $item) dump(clearOutput($item, TRUE));
echo clearOutput($inst, TRUE)->text;

echo ENDL;
?>
