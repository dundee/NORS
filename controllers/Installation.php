<?php

/**
* Installation
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Installation extends Core_Controller
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
		$this->tplFile = 'admin_installation.tpl.php';
		if ($this->config->db->user) $this->router->redirect('post', '__default', 'default');

		$host = $user = $pass = $db = $prefix = $user_name = $user_password = FALSE;
		$host = 'localhost';
		$prefix = 'nors4_';

		$errors = '';
		$fatal  = array();

		//check permissions
		$files = array('.htaccess', 'config', 'config/localhost.yml.php', 'cache', 'upload', 'tpl/cache', 'log', 'models/tables', 'models/activeRecords');
		foreach ($files as $file) {
			try {
				checkIfWritable(APP_PATH . '/' . $file);
			} catch (RuntimeException $e) {
				$fatal[] = $e->getMessage();
			}
		}

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
				include(APP_PATH . '/cache/' . $this->config->host . '.yml.php.cache.php');
				$config = $data;


				$config['db']['user']         = $user;
				$config['db']['password']     = $pass;
				$config['db']['host']         = $dbhost;
				$config['db']['database']     = $db;
				$config['db']['table_prefix'] = $prefix;

				Core_Parser_YML::write($config, APP_PATH . '/config/' . $this->config->host . '.yml.php');
				$this->config->read(APP_PATH . '/config/' . $this->config->host . '.yml.php', TRUE);

				//create tables
				$tables = array('User', 'Group', 'Category', 'Post', 'Comment', 'Page');
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

				//save .htaccess
				$dir = dirname($_SERVER['PHP_SELF']) . '/';
				if ($dir == '//') $dir = '';
				$text = '<IfModule rewrite_module>
	RewriteEngine on
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^(.*)$ ' . $dir . 'index.php [L]
	SetEnv REWRITE 1
</IfModule>';
				file_put_contents(APP_PATH . '/.htaccess', $text);
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
