<?php

/**
* Component_AdminForm
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Nors4
*/

/**
* Component_AdminForm
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors4
*/
class Component_AdminForm extends Core_Component
{	
	public $helpers = array('Administration');
	
	public $responseType = 'html';
	
	protected function beforeInit()
	{
		//$this->tplFile = 'basic_form.tpl.php';
	}
	
	/**
	* init
	*
	* @return void
	*/
	public function init($params = FALSE)
	{
		//$this->setData('action', $this->request->genUrl(FALSE, FALSE, FALSE, array('command'=>'adminForm-save')));
		$this->setData('action', $params['action']);
		$this->setData('table', $params['table']);
		if (!isset($params['id'])) $params['id'] = 0; 
		$this->setData('id', $params['id']);
	}
	
	public function save()
	{
		$class = 'ActiveRecord_' . ucfirst($this->request->getPost('table'));
		$id = $this->request->getPost('id');
		$model = new $class($id);
		$arr = $this->request->post();
		$html = FALSE;
		foreach ($arr as $key=>$val) {
			$model->{$key} = $val; 
		}
		
		try{
			$model->save($id);
			if (count($_FILES) > 0) $model->saveFiles();
			
		} catch (Exception $ex) {
			echo $ex->getMessage();
			$this->setData('html', $ex->getMessage());
		}
		$this->setData('html', $html);
	} 
	
	
}




?>
