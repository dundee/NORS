<?php

/**
* Core_Module_Auth
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Module_Auth
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
abstract class Core_Module_Auth extends Core_Module
{
	/**
	* $user
	*
	* @var Core_User $user
	*/
	protected $user;

	/**
	* Constructor
	* @access public
	*/
	public function __construct(){
		parent::__construct();
		$userModel = new Table_User();
		$this->user = new Core_User($userModel);
	}

	/**
	* authenticate
	*
	* @return boolean
	*/
	public function authenticate(){
		return ($this->user->logged());
	}

	/**
	* checkRights
	*
	* @return boolean
	*/
	public function checkRights(){
		$group = new ActiveRecord_Group($this->user->group);

		if (!isset($_GET['subevent'])) {
			if ($group->{$_GET['event'].'_list'} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);
			else return TRUE;
		}

		if ($group->{$_GET['subevent'].'_list'} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);

		if (!isset($_GET['action'])) return TRUE;

		if ($group->{$_GET['subevent'].'_'.$_GET['action']} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);
		}
}
