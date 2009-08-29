<?php

/**
* Import
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Import extends Core_Controller
{
	public $headerTplFile = 'header_admin.tpl.php';
	public $footerTplFile = 'footer_admin.tpl.php';

	public $css = array(
		'normal' => array('admin.css', 'forms.css'),
		'ie6'    => array(),
		'ie7'    => array(),
		'print'  => array(),
	);

	public $js = array('jquery.js',
	                   'help.js');

	public $helpers = array('Form', 'Menu');

	public $cache = 0;

	public function beforeAction()
	{
		$this->setData('user', '');
	}

	/**
	* __default
	*
	* @return void
	*/
	public function __default()
	{
		$this->tplFile = 'admin_import.tpl.php';

		$errors = '';
		if ($this->request->getPost('send')) {
			do {
				$table = new Table_Category();
				$table2 = new Table_Post();
				$table3 = new Table_Comment();
				if (iterable($table->getAll())
				    || iterable($table2->getAll())
				    || iterable($table3->getAll())) {
					$errors .= 'Target tables are not empty';
					break;
				}

				require_once($_FILES['db']['tmp_name']);
				$host = $DB['host'];
				$user = $DB['user'];
				$pass = $DB['pass'];
				$db   = $DB['db'];
				$prefix = $DB['prefix'];

				$c = mysql_connect($host, $user, $pass, $db);

				if (mysql_errno() == 1045) {
					$errors .= __('wrong_db_user') . ': ' . mysql_error();
					break;
				}

				mysql_select_db($db, $c);
				if (mysql_errno() == 1049) {
					$errors .= __('wrong_db_name') . ': ' . mysql_error();
					break;
				}

				mysql_query("SET CHARACTER SET UTF8", $c);
				if (mysql_errno() == 1049) {
					$errors .= mysql_error();
					break;
				}

				//set connection encoding
				$res = mysql_query("SHOW VARIABLES LIKE 'version'", $c);
				$line = mysql_fetch_array($res);
				$version = substr($line['Value'], 0, 3);
				if ($version > '4.0') mysql_query("SET NAMES 'UTF8'", $c);

				/* ============== everythink ok, saving ================= */

				$sql = "SELECT * FROM `".$prefix."rubrika`";
				$result = mysql_query($sql, $c);
				while ($line = mysql_fetch_array($result)) {
					//dump($line);
					$cat = new ActiveRecord_Category();
					$cat->id_category   = clearInput($line['id_rubrika'], 1);
					$cat->name          = clearInput($line['name']);
					$cat->insert();
				}

				if (mysql_errno()) {
					$errors .= mysql_error();
					break;
				}

				$sql = "SELECT * FROM `".$prefix."clanek`";
				$result = mysql_query($sql, $c);
				while ($line = mysql_fetch_array($result)) {
					//dump($line);
					$post = new ActiveRecord_Post();
					$post->id_post      = clearInput($line['id_clanek'], 1);
					$post->id_category  = clearInput($line['id_rubrika'], 1);
					$post->id_user      = 1;
					$post->name         = clearInput($line['name']);
					$post->text         = clearInput($line['text']);
					$post->date         = clearInput($line['date']);
					$post->seen         = clearInput($line['counter'], 1);
					$post->active       = 1;
					$post->insert();
				}

				if (mysql_errno()) {
					$errors .= mysql_error();
					break;
				}

				$sql = "SELECT * FROM `".$prefix."komentar`";
				$result = mysql_query($sql, $c);
				while ($line = mysql_fetch_array($result)) {
					//dump($line);
					$com = new ActiveRecord_Comment();
					$com->id_comment    = clearInput($line['id_komentar'], 1);
					$com->id_post       = clearInput($line['id_clanek'], 1);
					$com->user          = clearInput($line['user']);
					$com->email         = clearInput($line['mail']);
					$com->www           = clearInput($line['web']);
					$com->ip            = clearInput($line['ip']);
					$com->text          = clearInput($line['text']);
					$com->date          = clearInput($line['date']);
					$com->insert();
				}

				if (mysql_errno()) {
					$errors .= mysql_error();
					break;
				}

				$sql = "SELECT * FROM `".$prefix."sablona`";
				$result = mysql_query($sql, $c);
				while ($line = mysql_fetch_array($result)) {
					//dump($line);
					$page = new ActiveRecord_Page();
					$page->id_page      = clearInput($line['id_sablona'], 1);
					$page->name         = clearInput($line['name']);
					$page->text         = clearInput($line['text']);
					$page->active       = 1;
					$page->insert();
				}

				if (mysql_errno()) {
					$errors .= mysql_error();
					break;
				}

				$this->router->redirect('post', '__default', 'default');
			} while (FALSE);

		}

		if ($errors) $this->response->setPost('send', FALSE);

		$this->setData('errors', $errors);
	}
}
