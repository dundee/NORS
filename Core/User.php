<?php

/**
 * User class provides login funkcionality
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
			$this->group    = $user->id_group;
			$this->userName = $user->name;
			$this->password = $user->password;
		}
	}

	/**
	 * Logs user in or throw Exception
	 * @param Core_Table $userModel
	 * @param string $name Username
	 * @param string $password
	 * @return boolean
	 */
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

					//session fixation protection
					$session = Core_Session::singleton();
					$session->renewId();

					return TRUE;
				} else throw new RuntimeException( __('account_not_active') );
			} else throw new RuntimeException( __('wrong_password') );
		} else throw new RuntimeException( __('user_not_exists') );
	}

	/**
	 * Is user logged?
	 * @return boolean
	 */
	public function logged()
	{
		return Core_Request::factory()->getSession('id_user') ? TRUE : FALSE;
	}

	/**
	 * Log out the user
	 */
	public function logout()
	{
		Core_Response::factory()->setSession('id_user', 0);
	}
}
