<?php

/**
* Import
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Import
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Import extends Core_Module
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

	public function beforeEvent()
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
				$table = new Table_Cathegory();
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
					$cat = new ActiveRecord_Cathegory();
					$cat->id_cathegory  = $line['id_rubrika'];
					$cat->name          = $line['name'];
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
					$post->id_post      = $line['id_clanek'];
					$post->id_cathegory = $line['id_rubrika'];
					$post->id_user      = $line['id_admin'];
					$post->name         = $line['name'];
					$post->text         = $line['text'];
					$post->date         = $line['date'];
					$post->seen         = $line['counter'];
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
					$com->id_comment    = $line['id_komentar'];
					$com->id_post       = $line['id_clanek'];
					$com->user          = $line['user'];
					$com->email         = $line['mail'];
					$com->www           = $line['web'];
					$com->ip            = $line['ip'];
					$com->text          = $line['text'];
					$com->date          = $line['date'];
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
					$page->id_page      = $line['id_sablona'];
					$page->name         = $line['name'];
					$page->text         = $line['text'];
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
