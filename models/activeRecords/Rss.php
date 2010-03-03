<?php

/**
* ActiveRecord_Rss
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Rss_agregator
*/

/**
* ActiveRecord_Rss
*
* @author Daniel Milde <daniel@milde.cz>
* @package Rss_agregator
*/
class ActiveRecord_Rss extends Core_ActiveRecord
{
	/**
	* $fields
	*
	* Array of fields in table.
	* @var array $fields
	*/
	public $fields = array(
		'id_rss'    => array('visibility' => 0, 'type' => 'int',      'db' => 'int(11) unsigned'),
		'url'       => array('visibility' => 1, 'type' => 'text',      'db' => 'varchar(200) NOT NULL UNIQUE'),
		'title'     => array('visibility' => 1, 'type' => 'text',      'db' => 'varchar(200) NOT NULL'),
		'created'   => array('visibility' => 1, 'type' => 'datetime',  'db' => 'datetime NOT NULL'),
	);

	public function __construct($id_user=false){
		parent::__construct('rss',$id_user);
    }
}

?>
