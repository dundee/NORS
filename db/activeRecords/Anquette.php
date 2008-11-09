<?php

/**
 * ActiveRecord_Anquette
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * ActiveRecord_Anquette
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class ActiveRecord_Anquette extends Core_ActiveRecord
{
	public function __construct($id_user=false)
	{
		parent::__construct('anquette',$id_user);
	}
}