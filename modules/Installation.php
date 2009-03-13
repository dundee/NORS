<?php

/**
* Installation
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Installation
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Installation extends Core_Module
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
		$this->tplFile = 'admin_installation.tpl.php';
		if ($this->config->db->user) throw new RuntimeException('Allready installed', 401);

		$host = $user = $pass = $db = $prefix = $user_name = $user_password = FALSE;
		$host = 'localhost';
		$prefix = 'nors4_';

		$errors = '';
		$fatal  = array();

		//check permissions
		if (getFilePerms(APP_PATH . '/config') != '777') $fatal[] = __('directory') . ' "config" '. __('needs to be writable by anyone') . ' (777).';
		if (getFilePerms(APP_PATH . '/config/config.yml.php') != '777') $fatal[] = __('file') . ' "config/config.yml.php" ' . __('needs to be writable by anyone') . ' (777).';
		if (getFilePerms(APP_PATH . '/cache') != '777') $fatal[] = __('directory') . ' "cache" ' . __('needs to be writable by anyone') . ' (777).';
		if (getFilePerms(APP_PATH . '/upload') != '777') $fatal[] = __('directory') . ' "upload" '. __('needs to be writable by anyone') . ' (777).';
		if (getFilePerms(APP_PATH . '/tpl/cache') != '777') $fatal[] = __('directory') . ' "tpl/cache" '. __('needs to be writable by anyone') . ' (777).';
		if (getFilePerms(APP_PATH . '/log') != '777') $fatal[] = __('directory') . ' "log" '. __('needs to be writable by anyone') . ' (777).';
		if (getFilePerms(APP_PATH . '/db/tables') != '777') $fatal[] = __('directory') . ' "db/tables" '. __('needs to be writable by anyone') . ' (777).';
		if (getFilePerms(APP_PATH . '/db/activeRecords') != '777') $fatal[] = __('directory') . ' "db/activeRecords" '. __('needs to be writable by anyone') . ' (777).';


		if ($this->request->getPost('send')) {
			do {
				$this->config->debug->error_reporting = E_ERROR;

				$dbhost        = $this->request->getPost('host');
				$user          = $this->request->getPost('user');
				$pass          = $this->request->getPost('password');
				$db            = $this->request->getPost('db');
				$prefix        = $this->request->getPost('prefix');
				$user_name     = $this->request->getPost('user_name');
				$user_password = $this->request->getPost('user_password');

				mysql_connect($dbhost, $user, $pass);
				if (mysql_error()) {
					$errors .= __('wrong_db_user') . ': ' . mysql_error();
					break;
				}

				mysql_select_db($db);
				if (mysql_error()) {
					$errors .= __('wrong_db_name') . ': ' . mysql_error();
					break;
				}

				//everythink ok, saving

				$this->config->debug->error_reporting = E_ALL;

				//save new configuration
				include(APP_PATH . '/cache/config.yml.php.cache.php');
				$config = $data;
				$host = $this->config->host;

				$config[$host]['db']['user']         = $user;
				$config[$host]['db']['password']     = $pass;
				$config[$host]['db']['host']         = $dbhost;
				$config[$host]['db']['database']     = $db;
				$config[$host]['db']['table_prefix'] = $prefix;

				Core_Parser_YML::write($config, APP_PATH . '/config/config.yml.php');
				//chmod(APP_PATH . '/config/config.yml.php', 0777);
				$this->config->read(APP_PATH . '/config/config.yml.php', TRUE);

				//create tables
				$tables = array('User', 'Group', 'Cathegory', 'Post', 'Comment', 'Page');
				foreach ($tables as $table) {
					$class = 'Table_' . $table;
					$user = new $class();
					$user->create();
				}

				$user = new ActiveRecord_User();
				$user->name = $user_name;
				$user->password = $user_password;
				$user->active = 1;
				$user->save();

				$this->router->redirect('post', '__default', 'default');
			} while (FALSE);

			$this->setData('dbhost', $host);
			$this->setData('dbuser', $user);
			$this->setData('dbpassword', $pass);
			$this->setData('db', $db);
			$this->setData('dbprefix', $prefix);
			$this->setData('user_name', $user_name);
			$this->setData('user_password', $user_password);
		}

		if ($errors) $this->response->setPost('send', FALSE);

		$this->setData('fatal', $fatal);
		$this->setData('errors', $errors);

		$this->setData('dbhost', $host);
		$this->setData('dbuser', $user);
		$this->setData('dbpassword', $pass);
		$this->setData('db', $db);
		$this->setData('dbprefix', $prefix);
		$this->setData('user_name', $user_name);
		$this->setData('user_password', $user_password);
	}
}
