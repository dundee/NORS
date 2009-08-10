<?php

/**
* Upgrade
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Upgrade extends Core_Controller
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
		$this->tplFile = 'admin_upgrade.tpl.php';

		$db_version = $this->config->db->version;
		$version    = norsVersion();

		if ($db_version > $version) throw new Exception(__('Database_is_higher_version_than_the_application'));
		if ($db_version == $version) $this->router->redirect('post', '__default', 'default');

		$db = $this->config->db->database;
		$con = Core_DB::singleton();

		switch ($db_version) {
			default: //lower than 4.1.0
				$sql = "ALTER TABLE `" . tableName('post') . "` ADD `evaluated` INT UNSIGNED NOT NULL DEFAULT '0';";
				$con->silentQuery($sql);
			case '4.1.0':
				$sql = "RENAME TABLE `$db`.`" . tableName('cathegory') . "` TO `$db`.`" . tableName('category') . "` ;";
				$con->silentQuery($sql);
				$sql = "ALTER TABLE `" . tableName('post') . "` CHANGE `id_cathegory` `id_category` INT( 11 ) NULL DEFAULT NULL;";
				$con->silentQuery($sql);
				$sql = "ALTER TABLE `" . tableName('category') . "` CHANGE `id_cathegory` `id_category` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT;";
				$con->silentQuery($sql);
				$sql = "ALTER TABLE `" . tableName('category') . "` CHANGE `cathegory` `category` INT( 11 ) NULL DEFAULT NULL;";
				$con->silentQuery($sql);
				$sql = "ALTER TABLE `" . tableName('group') . "` CHANGE `cathegory_list` `category_list` TINYINT( 1 ) NOT NULL DEFAULT '0';";
				$con->silentQuery($sql);
				$sql = "ALTER TABLE `" . tableName('group') . "` CHANGE `cathegory_edit` `category_edit` TINYINT( 1 ) NOT NULL DEFAULT '0';";
				$con->silentQuery($sql);
				$sql = "ALTER TABLE `" . tableName('group') . "` CHANGE `cathegory_del` `category_del` TINYINT( 1 ) NOT NULL DEFAULT '0';";
				$con->silentQuery($sql);
				$sql = "ALTER TABLE `" . tableName('group') . "` CHANGE `id_cathegory` `id_category` INT( 11 ) NULL DEFAULT NULL;";
				$con->silentQuery($sql);
			case '4.2.0':
			case '4.2.1':
				$sql = "ALTER TABLE `" . tableName('post') . "` ADD `comments_allowed` TINYINT( 1 ) NOT NULL AFTER `active` ;";
				$con->silentQuery($sql);
			case '4.3.0':
			case '4.3.1':
		}

		//save config file
		include(APP_PATH . '/cache/' . $this->config->host . '.yml.php.cache.php');
		$config = $data;
		$config['db']['version'] = norsVersion();
		Core_Parser_YML::write($config, APP_PATH . '/config/' . $this->config->host . '.yml.php');


		$this->setData('version', $version);
		$this->setData('db_version', $db_version);
		$this->setData('url', $this->router->genUrl('post', '__default', 'default'));
	}
}
