<?php

/**
* Component_Rejstrik
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Nors4
*/

/**
* Component_Rejstrik
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors4
*/
class Component_Rejstrik extends Core_Component
{	
	public $helpers = array();
	
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
	}
	
	public function filter()
	{
		$region_id = $this->request->getPost('id');
		
		$model = new ActiveRecord_Town();
		
		$items = $model->getByRegion($region_id, 'nazev_nazvu', 'asc');
		
		$html = '';
		foreach ($items as $item) {
			$html .= '<option value="' . $item['rejstrik_mist_id'] . '">' . $item['name'] . ' ('.$item['kategorie'].')</option>';
		}
		
		$this->setData('html', $html, TRUE);
	}
}




?>
