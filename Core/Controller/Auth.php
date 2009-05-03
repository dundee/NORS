<?php

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

		if (!$this->user->group) return TRUE; //ACL not set

		$group = new ActiveRecord_Group($this->user->group);

		if (!isset($_GET['event'])) {
			if ($group->{$_GET['action'].'_list'} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);
			else return TRUE;
		}

		if ($group->{$_GET['event'].'_list'} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);

		if (!isset($_GET['command'])) return TRUE;

		if ($group->{$_GET['event'].'_'.$_GET['command']} === '0') throw new RuntimeException("You have not enough rights for this action.", 401);
		}
}
