<?php

/**
* Core_User
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*
*/

/**
* Core_User
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_User extends Core_Object
{
	public $userName;
	public $id_user;
	public $id_group;
	protected $password;
	protected $logged;

	/**
	* Constructor
	* @access public
	*/
	public function __construct(Core_ActiveRecord $userModel)
	{
		parent::__construct();
		$id = $this->request->getSession('id_user');
		if ($id) {
			$user = $userModel->findById($id);
			if (!$user instanceof Core_ActiveRecord) return;
			$this->id_user  = $user->id_user;
			$this->id_group = $user->id_group;
			$this->userName = $user->name;
			$this->password = $user->password;
		}
	}

	public function login(Core_ActiveRecord $userModel, $name, $password)
	{
		$user = $userModel->findByName($name);
		$text_obj = new Core_Text();
		if (is_object($user)) {
			if ($user->password == $text_obj->crypt($password,
			                                        $name)) {
			    if ($user->active == 1) {
			    	$this->request->setSession('id_user', $user->id_user);
					return TRUE;
			    } else throw new RuntimeException( __('account_not_active') );
			} else throw new RuntimeException( __('wrong_password') );
		} else throw new RuntimeException( __('user_not_exists') );
	}

	public function logged()
	{
		return $this->request->getSession('id_user') ? TRUE : FALSE;
	}

	public function logout()
	{
		$this->request->setSession('id_user', 0);
	}
}
