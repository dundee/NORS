<?php

/**
* Logout
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Nors
*/
class Logout extends Core_Controller_Auth
{
	public $css = array(
		'normal' => array('layout.css'),
		'ie6'    => array('ie6.css'),
		'ie7'    => array('ie7.css'),
		'print'  => array('print.css'),
	);
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
