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
	* $session
	*
	* @var Core_Session $session
	*/
	protected $session;

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
		$group = new ActiveRecord_Group($this->user->id_group);
		if (!isset($_GET['event'])) return TRUE;
		if ($group->{$_GET['event'].'_list'} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);
		if (!isset($_GET['item'])) return TRUE;
		if ($group->{$_GET['item'].'_'.$_GET['event']} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);
	}

	/**
	* Destructor
	* @access public
	*/
	public function __destruct()
	{
		parent::__destruct();
	}
}
