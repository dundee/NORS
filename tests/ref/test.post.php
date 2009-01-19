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

define('STYLE_URL', APP_URL . '/styles/' . Core_Config::singleton()->style);

$router   = Core_Router::factory();
$request  = Core_Request ::factory();
$router->decodeUrl($request, FALSE);

Core_Config::singleton()->front_end->posts_per_page = 2;

/* ************* TEST ************** */

class Core_DB_Test extends Core_DB
{
	protected function connect(){}
	protected function sql_query($query){}
	public function query($query){}
	public function getRow($query = false)
	{
		return array('count' => 25);
	}
	public function num($query = false){}
	public function id($query = false){}
	public function __destruct(){}
	public function getRows($query = false){
		return array(0 => Array('id_post' => 1,
		                        'id_cathegory' => 1,
		                        'id_page' => 1,
		                        'name' => 'jhfsd',
		                        'cathegory' => 4,
		                        'text' => 'dfd
df
sdf \"dfdsf

? & 5645',
		                        'date' => '2008-12-21 14:38:29',
		                        'active' => 0,
		                        'karma' => 0,
		                        'seen' => 0),
		             1 => Array('id_post' => 2,
		                        'id_cathegory' => 2,
		                        'id_page' => 2,
		                        'name' => 'název \" dsdsd \' sffs',
		                        'cathegory' => 4,
		                        'text' => 'fsjdfh \"f sdf \'
d

df
<h1>sdfsdf \" \'\' sdsd</h1>',
		                        'date' => '2008-12-21 14:37:50',
		                        'active' => 0,
		                        'karma' => 0,
		                        'seen' => 0));
	}
}

Core_DB::singleton();
Core_DB::_setInstance(new Core_DB_Test);

require(APP_PATH . '/modules/Post.php');
$instance = new Post();
$view = Core_View::factory($request->view, $instance, $_GET['event']);
$view->display();
?>
