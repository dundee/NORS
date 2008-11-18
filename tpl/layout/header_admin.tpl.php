<?php

/**
*
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">' . "\n";
echo '<head>' . "\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset=' . Core_Config::singleton()->encoding . '" />' . "\n";
echo '<meta name="Description" content="' . $site['description'] . '" />' . "\n";
echo '<meta name="Keywords" content="' . $site['keywords'] . '" />' . "\n";
echo '<meta name="Generator" content="Core framework' . coreVersion() . '" />' . "\n";
echo '<meta http-equiv="Pragma" content="no-cache" />' . "\n";
echo '<meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />' . "\n";
echo '<meta http-equiv="Expires" content="" />' . "\n";
echo '<meta http-equiv="Last-Modified" content="" />' . "\n";
echo '<meta http-equiv="Content-Style-Type" content="text/css2" />' . "\n";
echo '<meta http-equiv="Window-Target" content="_blank" />' . "\n";
echo '<meta name="robots" content="all, follow" />' . "\n";
echo '<meta name="googlebot" content="index,follow,snippet,archive" />' . "\n";
echo '<meta name="author" content="Daniel Milde" />' . "\n";
echo '<link rel="shortcut icon" href="'.APP_URL.'/styles/'.Core_Config::singleton()->style.'/images/favicon.ico" />'."\n";


if (isset($site['js'])) {
	foreach($site['js'] as $js)
	echo '<script type="text/javascript" src="' . $js['src'] . '"></script>' . "\n";
}


if (isset($site['rss'])) {
	foreach($site['rss'] as $rss)
	echo '<link rel="alternate" type="application/rss+xml" title="' . $rss['title'] . '" href="' . $rss['src'] . '" />' . "\n";
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

echo '<title>' . $site['title'] . '</title>' . "\n";
echo '</head>
<body>
<h1>NORS ' . norsVersion() . '</h1>
<div id="user_menu">';
if($user){
	echo __('logged_in') . ' ' . $user . '.<br />';
	echo '<a href="' . $logout_url . '">' . __('log_out')  . '</a><br />';
	echo '<a href="' . APP_URL . '">'     . __('show_web') . '</a><br />';
}
echo '</div>';



echo '
<ul id="menu">';
if (iterable($menu)) {
	foreach($menu as $name=>$url){
		$class = $name == $selected ? ' class="selected"' : '';
		echo '<li' . $class . '><a href="' . $url . '">' . __($name) . '</a></li>';
	}
}
echo '</ul>
<div id="main">';
