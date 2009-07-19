<?php
$config = Core_Config::singleton();
echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="'. Core_Request::factory()->getUrl() .'" rel="self" type="application/rss+xml" />
';
echo '<title>' . $config->name . (isset($title) ? ' - ' . $title : '' ) . '</title>
	<link>' . APP_URL . '</link>
	<description>' . $config->description . '</description>
	<language>' . $config->locale . '</language>
	<generator>Nors ' . norsVersion() . '</generator>
	<copyright>' . date('Y') . '</copyright>
	<lastBuildDate>' . date("r") . '</lastBuildDate>

	<webMaster>daniel@milde.cz (Daniel Milde)</webMaster>';


if (isset($items) && iterable($items)){
	$text = new Core_Text();
	foreach($items as $item){
		echo ENDL . ENDL . TAB . TAB . '<item>
		<title>' . $item->name . '</title>
		<link>' . $item->url . '</link>
		<guid>' . $item->url . '</guid>
		<description><![CDATA['  . strip_tags($item->text) . ']]></description>
		<pubDate>' . date('r', $item->timestamp) . '</pubDate>
		</item>';
	}
}
?>
</channel>
</rss>
