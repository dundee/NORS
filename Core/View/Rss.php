<?

/**
* Core_View_Rss
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_View_Rss
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_View_Rss extends Core_View
{
	/**
	* Constructor
	* @access public
	*/
	public function __construct(Core_Module $module,$event){
		parent::__construct($module,$event);
	}

	/**
	* display
	*
	*/
	public function display(){
		
		$request = $this->module->request;
		$createCache = FALSE;  //should we create cache file?
		$cacheLifeTime = $this->module->cache;
		
		if ($cacheLifeTime > 0){ //caching allowed 
			$cacheFileName = $request . "";
			$cacheFilePath = APP_PATH.'/tpl/cache/'.$cacheFileName;
			if (file_exists($cacheFilePath)){
				$time = filemtime($cacheFilePath); //unix timestamp
				$request->setVar('cacheTime', $time);
				$age = time() - $time; 
				if($age < $cacheLifeTime){ //cache not expired
					headers();
					include($cacheFilePath); //display cache
					return TRUE;					
				} else $createCache = TRUE; //cache expired
			} else $createCache = TRUE; //cache not exists
		}
		
		$request->setVar('caching', $createCache);
		if ($createCache) ob_start(); //start output buffer
		
		//load helpers
		if (iterable($this->module->helpers)) {
			foreach ($this->module->helpers as $helper) {
				$class = 'Core_Helper_' . ucfirst($helper);
				$this->module->setData(strtolower($helper), new $class);
			}
		}
		
		//execute event
		if (method_exists($this->module,'beforeEvent')) $this->module->beforeEvent();
		$event = $this->moduleEvent;
		$this->module->$event();
		if (method_exists($this->module,'afterEvent')) $this->module->afterEvent();
		
		$data = $this->module->getData();
		
		$request->sendHeaders('application/xml');
		
		foreach($data as $k=>$v){
			${$k} = $v;
		}
		unset($data);
		
		include(APP_PATH.'/tpl/rss.tpl.php');
		
		//write cache
		if ($createCache) {  //create cache file
			$buffer = ob_get_contents(); //write buffer to String
			ob_end_clean(); //clear buffer
			file_put_contents($cacheFilePath,$buffer);
			include($cacheFilePath);
			return TRUE;
		}
	}

	/**
	* Destructor
	* @access public
	*/
	public function __destruct(){

	}
}

?>
