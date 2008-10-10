<?php

/**
* ActiveRecord_User
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* ActiveRecord_User
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class ActiveRecord_User extends Core_ActiveRecord
{
	/**
	* $fields
	*
	* Array of fields in table.
	* @var array $fields
	*/
	public $fields = array(
		'id_user'   => array('visibility' => 0, 'type' => 'int',       'db' => 'int(11) unsigned'),
		'id_group'  => array('visibility' => 1, 'type' => '->group',   'db' => 'int(11) unsigned'),
		'name'      => array('visibility' => 1, 'type' => 'text',      'db' => 'varchar(100) NOT NULL'),
		'password'  => array('visibility' => 1, 'type' => 'password',  'db' => 'varchar(100) NOT NULL'),
		'fullname'  => array('visibility' => 1, 'type' => 'text',      'db' => 'varchar(100) NOT NULL'),
		'phone'     => array('visibility' => 1, 'type' => 'text',      'db' => 'varchar(50) NULL'),
		'email'     => array('visibility' => 1, 'type' => 'text',      'db' => 'varchar(100) NULL'),
		'created'   => array('visibility' => 1, 'type' => 'datetime',  'db' => 'datetime NULL'),
//		'active'    => array('visibility' => 1, 'type' => 'checkbox',  'db' => 'int(11) unsigned default "0"'),
	);

	protected $table = 'user';

	public function __construct($id_user=false)
	{
		parent::__construct('user',$id_user);
	}
}
