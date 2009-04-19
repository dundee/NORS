<?php

/**
 * Core_Object
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @copyright Daniel Milde <daniel@milde.cz>
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package Core
 *
 */

/**
 * Base class for Controller and Component classes. Adds important functionality and access to some instances.
 * Features: reflection, array for data
 * Access to instances: Core_Request, Core_Response, Core_Router, Core_Config
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
abstract class Core_Object
{
	/**
	 * @var Core_Request $request
	 */
	public $request;

	/**
	 * @var Core_Response $response
	 */
	public $response;

	/**
	 * @var Core_Router $router
	 */
	public $router;

	/**
	 * $me
	 *
	 * @var ReflectionClass $me
	 */
	protected $me;

	/**
	 * $data
	 *
	 * @var array $data
	 */
	protected $data;

	/**
	 * Constructor
	 */
	public function __construct(){
		$this->me        = new ReflectionClass($this);
		$this->request   = Core_Request ::factory();
		$this->response  = Core_Response::factory();
		$this->router    = Core_Router  ::factory();
		$this->config    = Core_Config ::singleton();
	}

	/**
	 * setData
	 *
	 * @param string $key Name of variable
	 * @param mixed $value Value of variable
	 * @param boolean $allowHtml If false, <>& will be replaced by entities
	 * @return void
	 */
	public final function setData($key,$value, $allowHtml = FALSE){
		$this->data[$key] = clearOutput($value, $allowHtml);
		return TRUE;
	}

	/**
	 * getData
	 *
	 * @return String[]
	 */
	public final function getData(){
		return $this->data;
	}

	public final function delData(){
		unset($this->data);
		$this->data = array();
	}

	/**
	 * __toString
	 * @return String
	 */
	public final function __toString(){
		return $this->me->getName();
	}
}
