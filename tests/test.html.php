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

$html = new Core_Helper_Html();

$div = $html->div(NULL, array('class'=>'blue'));
$a   = $html->a($div, '#');
$a->setContent('odkaz');
$a->setParam('onclick', "window.alert('baf!')");
$html->textarea($div, 'text', '');
$html->button($div, 'button', 'ok');

$div->render(1);
echo ENDL;
?>
