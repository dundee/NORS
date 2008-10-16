<?php

/**
* ActiveRecord_Group
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* ActiveRecord_Group
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class ActiveRecord_Group extends Core_ActiveRecord
{
	/**
	* $fields
	*
	* Array of fields in table.
	* @var array $fields
	*/
	public $fields = array(
		'id_group'      => array('visibility' => 0, 'type' => 'int',      'db' => 'int(11) unsigned'),
		'name'          => array('visibility' => 1, 'type' => 'text',     'db' => 'varchar(100) NOT NULL'),
		'created'       => array('visibility' => 1, 'type' => 'datetime', 'db' => 'datetime NOT NULL'),
		'post_list'     => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'post_edit'     => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'post_del'      => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'text_list'     => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
	    'text_edit'     => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'text_del'      => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'category_list' => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'category_edit' => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'category_del'  => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'user_list'     => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'user_edit'     => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'user_del'      => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'group_list'    => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'group_edit'    => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'group_del'     => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'settings_list' => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'settings_edit' => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
		'settings_del'  => array('visibility' => 1, 'type' => 'checkbox', 'db' => 'int(11) unsigned'),
	);

	public function __construct($id=false){
		parent::__construct('group',$id);
	}
}
?>