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
class ActiveRecord_Null extends Core_ActiveRecord
{
	/**
	* $fields
	*
	* Array of fields in table.
	* @var array $fields
	*/
	public $fields = array(
						  );

	public function __construct($id=false)
	{
		parent::__construct('null',$id);
    }
    
    public function getAll($order=FALSE, $a=FALSE)
    {
    	return array();	
    }
    

}