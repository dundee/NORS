<?php

/**
* Login
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Login
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Component_Login extends Core_Component
{
	public $helpers = array('Form');

	protected function beforeInit(){
		//$this->tplFile = 'basic_form.tpl.php';
	}

	/**
	* init
	*
	* @return void
	*/
	public function init($params = FALSE){

		if ($this->request->getPost('username')){

			$userModel = new Table_User();
			$user = new Core_User($userModel);
			try {
				$user->login($userModel, $this->request->getPost('username'), $this->request->getPost('password'));
				if ($this->request->getSession('request')) {
					$url = $this->request->getSession('request', TRUE);
				} else {
					$url = $this->router->genUrl('administration', '__default', 'default', FALSE);
				}
				header('Location: ' . $url);
			} catch (RuntimeException $ex) {
				$errors[] = $ex->getMessage();
			}

		}

		isset($errors) && $this->setData('errors',$errors) || $this->setData('errors',array(''));
	}
}




?>
