<?php

/**
*
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

echo $doctype;
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">'."\n";
echo '<head>'."\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset='.Core_Config::singleton()->encoding.'" />'."\n";
echo '<meta name="Description" content="'.$site['description'].'" />'."\n";
echo '<meta name="Keywords" content="'.$site['keywords'].'" />'."\n";
echo '<meta name="Generator" content="Core framework '.core_version().'" />'."\n";
echo '<meta http-equiv="Pragma" content="no-cache" />'."\n";
echo '<meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />'."\n";
echo '<meta http-equiv="Expires" content="" />'."\n";
echo '<meta http-equiv="Last-Modified" content="" />'."\n";
echo '<meta http-equiv="Content-Style-Type" content="text/css2" />'."\n";
echo '<meta http-equiv="Window-Target" content="_blank" />'."\n";
echo '<meta name="robots" content="all, follow" />'."\n";
echo '<meta name="googlebot" content="index,follow,snippet,archive" />'."\n";
echo '<meta name="author" content="Daniel Milde" />'."\n";
echo '<link rel="shortcut icon" href="'.APP_URL.'/styles/'.Core_Config::singleton()->style.'/images/favicon.ico" />'."\n";

if (isset($site['js'])) {
	foreach($site['js'] as $js)
	echo '<script type="text/javascript" src="'.$js['src'].'"></script>'."\n";
}


if (isset($site['rss'])) {
	foreach($site['rss'] as $rss)
	echo '<link rel="alternate" type="application/rss+xml" title="'.$rss['title'].'" href="'.$rss['src'].'" />'."\n";
}

if (isset($site['css'])) {
	foreach($site['css'] as $css)
	echo '<link rel="Stylesheet" type="text/css" href="'.$css['src'].'" />'."\n";
}

echo '<title>'.$site['title'].'</title>'."\n";
echo '</head>'."\n".'
<body>'."\n".'
  <div id="inner"><!-- inner - creates 750px wide slide in the middle of the browser window  -->'."\n".'
    <h1><a href="'.APP_URL.'">'.$site['name'].'<span>&nbsp;</span></a></h1><!-- main title - name of web -->'."\n".'
    <div id="content"><!-- content -->'."\n";