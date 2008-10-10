<?php

/**
* Core_ActiveRecord_Text
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_ActiveRecord_Text
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_ActiveRecord_Text extends Core_ActiveRecord
{
	/**
	* $fields
	*
	* Array of fields in table.
	* @var array $fields
	*/
	public $fields = array('id_post'     => array('visibility' => 0,  'type' => 'int'),
						   'id_user'     => array('visibility' => 0,  'type' => 'int'),
						   'name'        => array('visibility' => 1,  'type' => 'text'),
						   'id_category' => array('visibility' => 1,  'type' => '->category'),
						   'url' 		 => array('visibility' => 0,  'type' => 'url'),
						   'text'        => array('visibility' => 1,  'type' => 'textarea'),
						   'date'        => array('visibility' => 1,  'type' => 'datetime'),
						   'active'      => array('visibility' => 1,  'type' => 'checkbox'),
						   'count'       => array('visibility' => 0,  'type' => 'int'),
						   'karma'       => array('visibility' => 0,  'type' => 'int'),
						   'photo'       => array('visibility' => 1,  'type' => 'file')
						  );

	public function __construct($id=false){
		parent::__construct('text',$id);
    }

    public function __destruct(){
        parent::__destruct();
    }
}

?>
