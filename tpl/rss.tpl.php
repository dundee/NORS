<?php
$config = Core_Config::singleton();
echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>';
echo '<title>' . $config->name . ' - RSS ' . __('source') . '</title>
	<link>' . APP_URL . '</link>
	<description>' . $config->description . '</description>
	<language>cs</language>
	<generator>Nors ' . norsVersion() . '</generator>
	<copyright>' . date('Y') . '</copyright>
	<category>programing</category>
	<lastBuildDate>' . date("r") . '</lastBuildDate>

	<webMaster>daniel@milde.cz (Daniel Milde)</webMaster>';


if (iterable($posts)){
	$text = new Core_Text();
	foreach($posts as $post){
  		$x = $text->dateToTimeStamp($post->date);
  		
		echo '<item>
		<title>' . clearOutput($post->name) . '</title>
		<link>' . clearOutput($post->url) . '</link>
		<pubDate>' . date('r',$x) . '</pubDate> 
		</item>';
	}
}	
?>
</channel>
</rss>