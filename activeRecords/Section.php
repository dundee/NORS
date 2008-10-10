<?php

/**
* ActiveRecord_Section
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

/**
* ActiveRecord_Section
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class ActiveRecord_Section extends Core_ActiveRecord
{
	/**
	* $fields
	*
	* Array of fields in table.
	* @var array $fields
	*/
	public $fields = array('id_section'   => array('visibility' => 0, 'type' => 'int',       'db' => 'int(11) unsigned'),
	                       'id_parent'    => array('visibility' => 1, 'type' => '->section', 'db' => 'int(11) unsigned DEFAULT 0'),
	                       'name'         => array('visibility' => 1, 'type' => 'text',      'db' => 'varchar(100) NOT NULL'),
	                       'url'          => array('visibility' => 0, 'type' => 'url',       'db' => 'varchar(100) NOT NULL'),
	                       'location'     => array('visibility' => 1, 'type' => 'text',      'db' => 'int(11) unsigned'),
	);

	public function __construct($id=false){
		parent::__construct('section',$id);
	}
	
	/**
	 * getList
	 *
	 * Returns ID, name and location of all records.
	 * 
	 * @return String[][]	 	 
	 */	 
	public function getList($order = FALSE, $a = FALSE, $limit = FALSE, $name = FALSE)
	{
		if ($order==FALSE) {
			$order = 'id_'.$this->table;
		} 
		if ($a===FALSE) {
			$a = 'desc';
		}
		$a = ( $a=='asc' ? 'asc' : 'desc' );
		
		$sql = "SELECT s.`id_" . $this->table . "`, s.`name`, ps.`name` AS `parent`, s.`location`
		        FROM `" . table_name($this->table) . "` s
		        LEFT JOIN `" . table_name($this->table) . "` ps ON (s.`id_parent`=ps.`id_" . $this->table . "`)
		        " . ($name ? "WHERE s.`name` LIKE '" . clearInput($name) . "%'" : '') . "
				ORDER BY " . $order . " " . $a . " " . ($limit ? "LIMIT " . clearInput($limit) : '');
		try{
			$lines = $this->db->getRows($sql);
		} catch(RuntimeException $ex) {
			if($ex->getCode()==1146) {
				$this->create();
				return FALSE;
			}
			else throw new RuntimeException($ex->getMessage(), $ex->getCode());
		}
		return $lines;
	}

	/**
	* getChildren
	*
	* @param int $id
	* @return String[][]
	*/
	public function getChildren($id=FALSE,$order=FALSE,$a=FALSE){
		if (!$id) {
			$id = $this->data['id_section'];
		}
		if ($order==FALSE) {
			$order = 'id_'.$this->table;
		}
		if ($a===FALSE) {
			$a = 'desc';
		}

		$a = ( $a=='asc' ? 'asc' : 'desc' );

		$sql = "SELECT *
		        FROM `".table_name($this->table)."`
		        WHERE `id_parent` = '".input($id,1)."'
		        ORDER BY ".input($order)." ".strtoupper($a);
		return $this->db->getRows($sql);
	}

	function category_tree($arr,$id = false){
		if ($id === false) $id = isset($_GET['id']) ? $_GET['id'] : '0';
		if (!iterable($arr)) return FALSE;
	
		$tree = array();
	
		foreach($arr as $item){
			if ($item['id_parent']==$id){
				$tree[$item['name']] = category_tree($arr,$item['id_category']);
			}
		}
		return $tree;
	}
}

?>
