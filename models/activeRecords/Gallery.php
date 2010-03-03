<?php

/**
 * ActiveRecord_Gallery
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * ActiveRecord_Gallery
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class ActiveRecord_Gallery extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('gallery',$id_user);
	}
		
	public function getFiles()
	{
	}
	
	public function saveFiles()
	{
	}
}