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
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">'.ENDL;
echo '<head>'.ENDL;
echo '<meta http-equiv="Content-Type" content="text/html; charset='.Core_Config::singleton()->encoding.'" />'.ENDL;
echo '<meta name="Description" content="'.$site['description'].'" />'.ENDL;
echo '<meta name="Keywords" content="'.$site['keywords'].'" />'.ENDL;
echo '<meta name="Generator" content="Core framework '.coreVersion().'" />'.ENDL;
echo '<meta http-equiv="Pragma" content="no-cache" />'.ENDL;
echo '<meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />'.ENDL;
echo '<meta http-equiv="Expires" content="" />'.ENDL;
echo '<meta http-equiv="Last-Modified" content="" />'.ENDL;
echo '<meta http-equiv="Content-Style-Type" content="text/css2" />'.ENDL;
echo '<meta http-equiv="Window-Target" content="_blank" />'.ENDL;
echo '<meta name="robots" content="all, follow" />'.ENDL;
echo '<meta name="googlebot" content="index,follow,snippet,archive" />'.ENDL;
echo '<meta name="author" content="design and code: Petr Sobotka, www.czechline.cz" />'.ENDL;
echo '<link rel="shortcut icon" href="'.APP_URL.'/styles/'.Core_Config::singleton()->style.'/images/favicon.ico" />'.ENDL;

if (isset($site['js'])) {
	foreach($site['js'] as $js)
	echo '<script type="text/javascript" src="'.$js['src'].'"></script>'.ENDL;
}


if (isset($site['rss'])) {
	foreach($site['rss'] as $rss)
	echo '<link rel="alternate" type="application/rss+xml" title="'.$rss['title'].'" href="'.$rss['src'].'" />'.ENDL;
}

if (isset($site['css']['normal'])) {
	foreach($site['css']['normal'] as $css)
	echo '<link rel="Stylesheet" media="screen,projection" type="text/css" href="'.$css['src'].'" />'.ENDL;
}

if (isset($site['css']['print'])) {
	foreach($site['css']['print'] as $css)
	echo '<link rel="Stylesheet" media="print" type="text/css" href="'.$css['src'].'" />'.ENDL;
}

if (isset($site['css']['ie6'])) {
	echo '<!--[if lte IE 6]>'.ENDL;
	foreach($site['css']['ie6'] as $css)
		echo '<link rel="Stylesheet" media="screen,projection" type="text/css" href="'.$css['src'].'" />'.ENDL;
	echo '<![endif]-->'.ENDL;
}

if (isset($site['css']['ie7'])) {
	echo '<!--[if lte IE 7]>'.ENDL;
	foreach($site['css']['ie7'] as $css)
		echo '<link rel="Stylesheet" media="screen,projection" type="text/css" href="'.$css['src'].'" />'.ENDL;
	echo '<![endif]-->'.ENDL;
}

echo '<title>'.$site['title'].'</title>'.ENDL;
echo '</head>'.ENDL.'
<body>'.ENDL.'
  <div id="main" class="clearfix">
	<div id="content">'.ENDL;
