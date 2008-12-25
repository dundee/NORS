<?php

/**
 * Core_Component_Auth
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Adds authentication and authorization to Component class
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Component_Auth extends Core_Component
{
	/**
	* Current user
	* @var Core_User $user
	*/
	protected $user;

	public function __construct(Core_Module $module = NULL,
	                            $name = FALSE,
	                            $params = FALSE)
	{
		parent::__construct($module, $name, $params);
		$userModel = new Table_User();
		$this->user = new Core_User($userModel);
	}

	public function auth()
	{
		if (!$this->user->logged()) return FALSE;
		return TRUE;
	}
}
