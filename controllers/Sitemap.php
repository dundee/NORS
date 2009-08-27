<?php

/**
* Sitemap
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Sitemap extends Core_Controller
{
	public $css = array();
	public $helpers = array();
	public $js = array();

	public $cache = 0;

	public $views = array('Default');
	public $headerTplFile = FALSE;
	public $footerTplFile = FALSE;

	public function __default()
	{
		$date = date("Y-m-d\TH:i:s+00:00", time() - 36000);
		$this->setData('date', $date);

		$text_obj = new Core_Text();

		$parts = array('category'  => 0.6,
		               'post'      => 1.0,
		               'page'      => 0.5
		);

		$all = array();
		foreach ($parts as $part => $priority) {
			$class = 'Table_' . ucfirst($part);
			$table = new $class();
			$items = $table->getActive();
			if (iterable($items)) {
				foreach ($items as &$item) {
					$item->url = $this->router->genUrl($part, FALSE, $part, array($part => $item->getID() . '-' . $text_obj->urlEncode($item->name)));
					if (!$item->date) $item->date = $date;
					else {
						$item->date = date("Y-m-d\TH:i:s+00:00", $text_obj->dateToTimeStamp($item->date));
					}
					$item->priority = sprintf("%.2f", $priority);
				}
				$all = array_merge($all, $items);
			}
		}
		$this->setData('items', $all);
	}
}
