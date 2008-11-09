<?php

/**
* Logout
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/




class Logout extends Core_Module_Auth
{
	/**
	* Constructor
	* @access public
	*/
	public function __construct(){
		parent::__construct();
		$this->tplFile = 'basic_form.tpl.php';
	}

	/**
	* __default
	*
	* @return void
	*/
	public function __default(){
		$this->session->destroy();

		$this->router->redirect('administration', 'login');
	}

	/**
	* Destructor
	* @access public
	*/
	public function __destruct(){
		parent::__destruct();
	}
}




?>
