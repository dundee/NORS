<?php

/**
 * Base class for all controllers.
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Controller extends Core_Object
{
	/**
	 * $controllerName
	 *
	 * @var string $controllerName
	 */
	public $controllerName;

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
	public $rss = array(); //array( array('title' => '', 'src'=> '') )

	/**
	 * $js
	 *
	 * Array of js files which should be included before main content
	 *
	 * @var array $js
	 */
	public $js = array();

	/**
	 * $css
	 *
	 * Array of css files
	 *
	 * @var array $css
	 */
	public $css = array(
		'normal' => array(),
		'ie6'    => array(),
		'ie7'    => array(),
		'print'  => array(),
	);

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
		$this->controllerName = $this->me->getName();
-		$this->tplFile = strtolower($this->controllerName) . '.tpl.php';
		$this->style = $this->config->style;
		$this->setSiteData();

		if (!$this->request->view) $this->request->view = $this->defaultView;
		else {
			if (!in_array($this->request->view, $this->views))
				throw new UnexpectedValueException('View ' . $this->request->view . ' not allowed for ' . $this->controllerName . ' controller', 415);
		}
	}

	/**
	 * isValid
	 *
	 * returns true if controller is valid core controller
	 *
	 * @param Core_Controller $controller
	 * @return boolean
	 */
	public static function isValid($controller){
		return (is_object($controller) && $controller instanceof Core_Controller);
	}

	/**
	 * authenticates user
	 *
	 * @return boolean
	 */
	public function authenticate(){
		return TRUE;
	}

	/**
	 * checks if user have enough rigts for action
	 *
	 * @return boolean
	 */
	public function authorize(){
		return TRUE;
	}

	/**
	 * Prepares site data (description, keywords, title)
	 */
	public function setSiteData(){
		$site_data['description'] = $this->config->description;
		$site_data['keywords'] = $this->config->keywords;
		$site_data['name'] = $this->config->name;
		$site_data['title'] = $this->config->name;

		foreach($this->js as $js){
			$site_data['js'][] = array('src' => APP_URL.'/js/'.$js);
		}

		foreach($this->rss as $rss){
			$site_data['rss'][] = array('src'   => $this->router->genURL($rss['src'], FALSE),
			                            'title' => $rss['title']);
		}

		if (in_array('Rss', $this->views)) {
			$site_data['rss'][] = array('src'   => '?rss',
										'title' => $this->me->getName() . 's');
		}

		foreach($this->css as $type => $arr){
			foreach($arr as $css) {
				$site_data['css'][$type][] = array('src' => APP_URL.'/styles/'.$this->style.'/css/'.$css);
			}
		}

		$this->setData('site', $site_data);
	}

/* ==================== Controller methods ===================== */

	/**
	 * Default Action method
	 */
	public abstract function __default();

	/**
	 * Is called before Action is run
	 */
	public function beforeAction(){}

	/**
	 * Is called after Action is run
	 */
	public function afterAction(){}

	/**
	 * Is called before footer is added
	 */
	public function beforeFooter(){}
}
