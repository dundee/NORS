<?php

/**
* Component_DumpFilter
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Component_DumpFilter
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Component_DumpFilter extends Core_Component_Auth
{
	public $helpers = array('Form');

	/**
	* init
	*
	* @return void
	*/
	public function init($params = FALSE)
	{
		$this->setData('table', $params['table']);
	}
}
