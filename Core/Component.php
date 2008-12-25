<?php
/**
* Core_Object
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* Core_Object
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
abstract class Core_Component extends Core_Object
{
	public $cache = 0;

	public $tplFile;

	public $helpers;

	public $responseType = 'json';

	public function __construct(Core_Module $module = NULL,
	                            $name = FALSE,
	                            $params = FALSE)
	{
		parent::__construct();
		if ($module && $name) $module->setData($name, $this->render( $params ), TRUE);
	}

	/**
	 * authenticate and authorize current user
	 */
	public function auth()
	{
		return TRUE;
	}

	/**
	 * initialize the component
	 * @param mixed[] $params
	 */
	public abstract function init($params = FALSE);

	/**
	 * render the component to page
	 * @param mixed[] $params
	 */
	public final function render($params = FALSE)
	{
		if (method_exists($this, 'beforeInit')) $this->beforeInit();

		if(!$this->tplFile){
			$file = $this->me->getName() . '.tpl.php';
			$file = strtolower(str_replace('Component_', '', $file));
			$this->tplFile = $file;
		}

		if ($this->request->getVar('caching')
		    && !$this->cache) {                   //without caching component

			$class = $this->me->getName();
			$content = ENDL . '<?php $component = new ' . $class . '();';
			if (iterable($params)) {
				$vars = false;
				foreach ($params as $key=>$param) {
					$vars .= ($vars ? ', ':'') . '"' . $key
					       . '"=>"' . $param . '"';
				}
				$vars = 'array(' . $vars . ')';
			}

			//load helpers
			if (iterable($this->helpers)) {
				foreach ($this->helpers as $helper) {
					$class = 'Core_Helper_' . ucfirst($helper);
					$content .= ENDL . '$' . strtolower($helper)
					          . ' = new ' . $class . ';';
				}
			}

			$content .= ENDL . '$component->init(' . $vars . ');';
			$content .= ENDL . '$data = $component->getData();';
			$content .= ENDL . 'foreach($data as $k=>$v) ${$k} = $v;';
			$content .= ENDL . '?>';
			$content .= file_get_contents(APP_PATH . '/tpl/component/'
			          . $this->tplFile);
			return $content;

		} else {           //page not cached or caching of component allowed

			//load helpers
			if (iterable($this->helpers)) {
				foreach ($this->helpers as $helper) {
					$class = 'Core_Helper_' . ucfirst($helper);
					${strtolower($helper)} = new $class;
				}
			}
			$this->init($params);
			$data = $this->getData();
			if (iterable($data)) {
				foreach ($data as $k => $v) {
					${$k} = $v;
				}
			}
			unset($data);
			$this->delData();

			$request  = $this->request;
			$response = $this->response;
			$router   = $this->router;

			ob_start();
			include(APP_PATH . '/tpl/component/' . $this->tplFile);
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
	}

	/**
	 * load view helpers
	 */
	public function loadHelpers()
	{
		if (iterable($this->helpers)) {
			foreach ($this->helpers as $helper) {
				$class = 'Core_Helper_' . ucfirst($helper);
				$this->{strtolower($helper)} = new $class;
			}
		}
	}
}
