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
class Component_Item extends Core_Component
{	
	public $cache = 0;
	
	protected function beforeInit(){
		$this->tplFile = 'item.tpl.php';
	}
	
	/**
	* init
	*
	* @return void
	*/
	public function init($params = FALSE){
		$this->setData('text',$params['text'].'qxr');
		$this->setData('id',$params['id']);
	}
	
	public function detail($params = FALSE){
		$id = $this->request->getPost('id');
		$text = 'snědl jsem zavařenou hrušku a bylo mi krapet špatně - '.date('i-s');
		echo json_encode( array('id'=>$id,'text'=>$text) );
	}
}




?>
