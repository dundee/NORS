<?php

/**
 * ActiveRecord_Citate
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * ActiveRecord_Citate
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class ActiveRecord_Citate extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('citate',$id_user);
	}
}