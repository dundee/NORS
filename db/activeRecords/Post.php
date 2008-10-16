<?php

/**
* ActiveRecord_Post
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* ActiveRecord_Post
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class ActiveRecord_Post extends Core_ActiveRecord
{
	/**
	* $fields
	*
	* Array of fields in table.
	* @var array $fields
	*/
	public $fields = array('id_post'     => array('visibility' => 0,  'type' => 'int',				'db' => 'int(11) unsigned'	),
						   'id_user'     => array('visibility' => 0,  'type' => 'int',				'db' => 'int(11)'	),
						   'name'        => array('visibility' => 1,  'type' => 'text',				'db' => 'varchar(100) NOT NULL'	),
						   'id_category' => array('visibility' => 1,  'type' => '->category',		'db' => 'int(11)'	),
						   'url' 		 => array('visibility' => 0,  'type' => 'url',				'db' => 'varchar(100) NOT NULL UNIQUE'	),
						   'perex'       => array('visibility' => 1,  'type' => 'text',				'db' => 'text'	),
						   'text'        => array('visibility' => 1,  'type' => 'textarea',			'db' => 'text'	),
						   'date'        => array('visibility' => 1,  'type' => 'datetime',			'db' => 'datetime'	),
						   'active'      => array('visibility' => 1,  'type' => 'checkbox',			'db' => 'int(11) unsigned default "0"'	),
						   'count'       => array('visibility' => 0,  'type' => 'int',				'db' => 'int(11) unsigned default "0"'	),
						   'karma'       => array('visibility' => 0,  'type' => 'int',				'db' => 'int(11) unsigned default "0"'	),
						   'photo'       => array('visibility' => 1,  'type' => 'file')
						  );

	public function __construct($id=false){
		parent::__construct('post',$id);
    }
}

?>
