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
class Core_User
{
	public $userName;
	public $id_user;
	public $group;
	public $password;

	public function __construct(Core_Table $userModel)
	{
		$id = Core_Request::factory()->getSession('id_user');
		if ($id) {
			list($user) = $userModel->findById($id);
			if (!$user instanceof Core_ActiveRecord) return;
			$this->id_user  = $user->id_user;
			$this->group = $user->group;
			$this->userName = $user->name;
			$this->password = $user->password;
		}
	}

	public function login(Core_Table $userModel, $name, $password)
	{
		$users = $userModel->findByName($name);
		$text_obj = new Core_Text();
		if (iterable($users)) {
			$user = $users[0];
			if ($user->password == $text_obj->crypt($password, $name)) {
				if ($user->active == 1) {
					$response = Core_Response::factory();
					$response->setSession('id_user', $user->id_user);
					$response->setSession('password', $user->password);
					return TRUE;
				} else throw new RuntimeException( __('account_not_active') );
			} else throw new RuntimeException( __('wrong_password') );
		} else throw new RuntimeException( __('user_not_exists') );
	}

	public function logged()
	{
		return Core_Request::factory()->getSession('id_user') ? TRUE : FALSE;
	}

	public function logout()
	{
		Core_Response::factory()->setSession('id_user', 0);
	}
}
