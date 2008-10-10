<?php
echo '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="'.APP_URL.'/rss_xsl.xml" media="screen" ?>
<rss version="2.0">
<channel>';
												//<? kvuli editoru
echo '<title>PHP User Group - RSS zdroj</title> 
	<link>'.APP_URL.'</link>
	<description>Rss zdroj článků o PHP</description>
	<language>cs</language>
	<generator>Core framework '.core_version().'</generator>
	<copyright>2008</copyright>
	<category>programing</category>
	<lastBuildDate>'.date("r").'</lastBuildDate>

	<webMaster>daniel@milde.cz (Daniel Milde)</webMaster>';


if (iterable($posts)){
	foreach($posts as $post){
  		$datetime = htmlspecialchars($post['created']);
  		list($date, $time) = explode(" ", $datetime);
  		list($y,$m,$d) = explode('-',$date);
  		list($h,$i,$s) = explode(':',$time);
  		$x = mktime ( $h, $i, $s, $m, $d, $y);
  		
		/*echo '<item>
		<title>' . htmlspecialchars(stripslashes($post['title'])) . '</title> 
		<link>' . htmlspecialchars($post['url']) . '</link> 
		<pubDate>' . date('r',$x) . '</pubDate>';
  		$datetime = htmlspecialchars($post['created']);
  		list($date, $time) = explode(" ", $datetime);
  		list($y,$m,$d) = explode('-',$date);
  		list($h,$i,$s) = explode(':',$time);
  		$x = mktime ( $h, $i, $s, $m, $d, $y);*/
  		
		echo '<item>
		<title>' . htmlspecialchars($post['title']) . '</title> 
		<link>' . htmlspecialchars($post['url']) . '</link> 
		<pubDate>' . date('r',$x) . '</pubDate> 
		</item>';
	}
}	
?>
</channel>
</rss>