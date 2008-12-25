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

class Core_DB_Test extends Core_DB
{
	protected function connect(){}
	protected function sql_query($query){}
	public function query($query){}
	public function getRow($query = false){}
	public function num($query = false){}
	public function id($query = false){}
	public function __destruct(){}
	public function getRows($query = false)
	{
		if (strpos($query, 'user')) {
			return array(
				array('id_user' => 1,
				      'name' => 'dundee'),
				array('id_user' => 2,
				      'name' => 'test'),
			);
		} else {
			return array(
				array('id_cathegory' => 1,
				      'name' => 'test',
				      'cathegory' => 0)
			);
		}
	}
}

Core_DB::singleton();
Core_DB::_setInstance(new Core_DB_Test);

$admin = new Core_Helper_Administration();

$r = $router;
$submenu = array(
			'cathegory'    => array('label' => 'cathegories',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' => 'cathegory'))),
			'post'    => array('label' => 'posts',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('subevent' => 'post'))),
		);

$admin->submenu($submenu, 'post');
echo ENDL . '<br />';

$actions = array('add' => $r->forward(array('action'=>'add')), 'cron' => $r->forward(array('action'=>'cron')));
$admin->actions($actions);
echo ENDL . '<br />';

$admin->dump('user');

echo ENDL . '<br />';
$admin->form('#', 'cathegory');

echo ENDL;
?>
