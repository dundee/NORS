<?php

/**
* Rss
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Rss_agregator
*/

/**
* Rss
*
* @author Daniel Milde <daniel@milde.cz>
* @package Rss_agregator
*/
class Component_Rss extends Core_Component
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

		if ($this->request->getPost('username') &&
		    $this->request->getPost('password')){
			

		}
		
		$action = $this->request->genUrl(FALSE, FALSE, array('command'=>'rss-parse'));
		
		$this->setData('action', $action);
		isset($errors) && $this->setData('errors',$errors) || $this->setData('errors',array(''));
	}
	
	public function parse()
	{
		$url2 = $this->request->getPost('source');
		unset($_POST['source']);
		unset($_POST['title']);
		foreach ($_POST as $k=>$v) {
			$url2 .= '&'.$k.'='.$v;
		}
		
		$url = str_replace('http://','',$url2);
		$arr = explode('/',$url);
		$host = $arr[0];
		$url = str_replace($host, '', $url);
		$source = '';
		$out = "GET ".$url." HTTP/1.1\r\n";
    	$out .= "Host: ".$host."\r\n";
    	$out .= "Connection: Close\r\n\r\n";

   	 	$fp = fsockopen($host, 80, $errno, $err, 10);
		fwrite($fp, $out);
		while (!feof($fp)) {
        	$source .= fgets($fp, 1000);
    	}
		
		$pos1 = strpos($source, '<title>');
		$pos1 += strlen('<title>');
		$pos2 = strpos($source, '</title>');
		$title = trim(substr($source, $pos1, $pos2 - $pos1));
		
		$html = '<fieldset>
		<legend>' . __('new_post') . '</legend>
		<div>
			<label for="title">' . __('title') . '</label>

			<input type="text" value="' . $title . '" name="title" id="title" class="required" />
		</div>
		<div>
			<input class="submit" type="submit" value="' . __('save') . '" name="send" id="send" />
		</div>
	</fieldset>
	<input type="hidden" value="' . $url2 . '" name="source" id="source" />';
	
		$action = $this->request->genUrl(FALSE, FALSE, array('command'=>'rss-save'));
		
		$output = array('html'=>$html, 'action'=>$action);
		echo json_encode($output);
	}
	
	public function save()
	{
		$url = $this->request->getPost('source');
		$title = $this->request->getPost('title');
		unset($_POST['source']);
		unset($_POST['title']);
		foreach ($_POST as $k=>$v) {
			$url .= '&'.$k.'='.$v;
		}
		
		$rss = new ActiveRecord_Rss();
		$rss->url   = $url;
		$rss->title = $title;
		$rss->created = date("Y-m-d H:i:s");
		$rss->save(0);
		
		$action = $this->request->genUrl(FALSE, FALSE, array('command'=>'rss-parse'));
		$html = '<fieldset>
		<legend>' . __('new_post') . ' - ' . __('saved') . '</legend>
		<div>
			<label for="source">' . __('source') . '</label>
			<input type="text" value="" name="source" id="source" class="required" />
		</div>
		<div>
			<input type="hidden" value="" name="title" id="title" />
		</div>
		<div>
			<input class="submit" type="submit" value="' . __('ok') . '" name="send" id="send" />
		</div>
		</fieldset>';
		$output = array('html'=>$html, 'action'=>$action);
		echo json_encode($output);
	}
}




?>
