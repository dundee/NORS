<?php
$config = Core_Config::singleton();
echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="'. Core_Request::factory()->getUrl() .'" rel="self" type="application/rss+xml" />
';
echo '<title>' . $config->name . '</title>
	<link>' . APP_URL . '</link>
	<description>' . $config->description . '</description>
	<language>' . $config->locale . '</language>
	<generator>Nors ' . norsVersion() . '</generator>
	<copyright>' . date('Y') . '</copyright>
	<lastBuildDate>' . date("r") . '</lastBuildDate>

	<webMaster>daniel@milde.cz (Daniel Milde)</webMaster>';


if (iterable($posts)){
	$text = new Core_Text();
	foreach($posts as $post){
		$x = $text->dateToTimeStamp($post->date);

		echo ENDL . ENDL . TAB . TAB . '<item>
		<title>' . $post->name . '</title>
		<link>' . $post->url . '</link>
		<guid>' . $post->url . '</guid>
		<description><![CDATA['  . $post->text . '...]]></description>
		<comments>' . $post->url . '#comments</comments>
		<category>' . str_replace('&amp;', '', $post->cathegory_name) .'</category>
		<pubDate>' . date('r',$x) . '</pubDate>
		</item>';
	}
}
?>
</channel>
</rss>
