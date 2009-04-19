<?php

/**
 * Core_Controller_Auth
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 */

/**
 * Adds authentication and authorization to Controller class
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Controller_Auth extends Core_Controller
{
	/**
	* Current user
	* @var Core_User $user
	*/
	protected $user;

	public function __construct(){
		parent::__construct();
		$userModel = new Table_User();
		$this->user = new Core_User($userModel);
	}

	/**
	* authenticate the user
	*
	* @return boolean
	*/
	public function authenticate(){
		return ($this->user->logged());
	}

	/**
	* authorize the user
	*
	* @return boolean
	*/
	public function authorize(){
		$group = new ActiveRecord_Group($this->user->group);

		if (!isset($_GET['subaction'])) {
			if ($group->{$_GET['action'].'_list'} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);
			else return TRUE;
		}

		if ($group->{$_GET['subaction'].'_list'} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);

		if (!isset($_GET['action'])) return TRUE;

		if ($group->{$_GET['subaction'].'_'.$_GET['action']} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);
		}
}
