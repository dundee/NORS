<?php

/**
* Core_Module
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*
*/

/**
* Core_Module
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
abstract class Core_Module extends Core_Object
{
	/**
	* $moduleName
	*
	* @var string $moduleName
	*/
	public $moduleName;

	/**
	* $tplFile
	*
	* @var string $tplFile
	*/
	public $tplFile;

	/**
	* $headerTplFile
	*
	* @var string $headerTplFile
	*/
	public $headerTplFile = 'header.tpl.php';

	/**
	* $footerTplFile
	*
	* @var string $footerTplFile
	*/
	public $footerTplFile = 'footer.tpl.php';

	/**
	* $defaultView
	*
	* @var string $defaultView
	*/
	public $defaultView = 'Default';

	/**
	* $views
	*
	* @var string $views
	*/
	public $views = array('Default');

	/**
	* $cache
	*
	* @var int $cache lifetime
	* 0 = model will be not cached
	*
	*/
	public $cache = 0;

	/**
	* $style
	*
	* @var int $style name of style
	* 0 = model will be not cached
	*
	*/
	public $style = 'default';

	/**
	* $rss
	*
	* Array of rss feeds
	*
	* @var array $rss
	*/
	public $rss = array();

	/**
	* $js
	*
	* Array of js files which should be included before main content
	*
	* @var array $js
	*/
	public $js = array('jquery-1.2.2.min.js','jquery.my.js');

	/**
	* $css
	*
	* Array of css files
	*
	* @var array $css
	*/
	public $css = array();

	/**
	 * $helpers
	 *
	 * Array of view helpers used in template
	 * @var String[] $helpers
	 */
	public $helpers = array();

	/**
	* Constructor
	* @access public
	*/
	public function __construct(){
		parent::__construct();
		$this->style = $this->config->style;
		$this->setSiteData();

		if (!$this->request->view) $this->request->view = $this->defaultView;
		else {
			if (!in_array($this->request->view, $this->views))
				throw new UnexpectedValueException('View ' . $this->request->view . ' not allowed for ' . $this->moduleName . ' module', 415);
		}
	}

	/**
	* isValid
	*
	* returns true if model is valid core model
	*
	* @param Core_Model $model
	* @return boolean
	*/
	public static function isValid($module){
		return (is_object($module) && $module instanceof Core_Module);
	}

	/**
	* authenticate
	*
	* @return boolean
	*/
	public function authenticate(){
		return true;
	}

	public function setSiteData(){
		$site_data['description'] = $this->config->description;
		$site_data['keywords'] = $this->config->keywords;
		$site_data['name'] = $this->config->name;
		$site_data['title'] = $this->config->name;

		foreach($this->js as $js){
			$site_data['js'][] = array('src' => APP_URL.'/js/'.$js);
		}

		foreach($this->rss as $rss){
			$site_data['rss'][] = array('src' => gen_url(array('model'=>$rss)));
		}

		foreach($this->css as $css){
			$site_data['css'][] = array('src' => APP_URL.'/styles/'.$this->style.'/css/'.$css);
		}

		$this->setData('site',$site_data);
	}

  	/**
	* checkRights
	*
	* @return boolean
	*/
	public function checkRights(){
		return TRUE;
	}
}
