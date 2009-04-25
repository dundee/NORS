<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../Core/Functions.php');

/************************************ DEFINITIONS ************************************/

$x = FALSE;
$y = "abc";
$z = array();

$a = array('aa'=>'bb');
$b = array('a'=>array());

$c = new stdclass;
$c->a = 1;
$c->b = 2;
$c->c = array(1,2);

class Iter implements Iterator
{
	private $data = array();
	private $pointer = 0;

	public function __construct($array = array())
	{
		$this->data = $array;
	}
	public function current(){return $this->data[$this->pointer];}
	public function key(){return $this->pointer;}
	public function next()
	{
		if ($this->pointer < count($this->data)) return $this->data[$this->pointer++];
		else return FALSE;
	}
	public function rewind(){$this->pointer = 0;}
	public function valid()
	{
		if ($this->pointer >= count($this->data)) return FALSE;
		return TRUE;
	}
}

$d = new Iter();
$e = new Iter(array(1,2,3));

/***************************************** TESTS ******************************/

//should be 0
echo iterable( NULL ) ? 1 : 0;
echo iterable( array() ) ? 1 : 0;
echo iterable( $x ) ? 1 : 0;
echo iterable( $y ) ? 1 : 0;
echo iterable( $z ) ? 1 : 0;
echo iterable( $b['a'] ) ? 1 : 0;
echo iterable( $c ) ? 1 : 0;
echo iterable( $d ) ? 1 : 0;

echo ENDL . '<br />' . ENDL;

//should be 1
echo iterable( array(1) ) ? 1 : 0;
echo iterable( $a ) ? 1 : 0;
echo iterable( $c->c ) ? 1 : 0;
echo iterable( $e ) ? 1 : 0;

echo ENDL;
?>
