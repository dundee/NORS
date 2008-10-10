<?php

/**
*
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n";
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">'."\n";
echo '<head>'."\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset='.ENCODING.'" />'."\n";
echo '<meta name="Description" content="'.$data['site']['description'].'" />'."\n";
echo '<meta name="Keywords" content="'.$data['site']['keywords'].'" />'."\n";
echo '<meta name="Generator" content="NORS '.version().'" />'."\n";
echo '<meta http-equiv="Pragma" content="no-cache" />'."\n";
echo '<meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />'."\n";
echo '<meta http-equiv="Expires" content="" />'."\n";
echo '<meta http-equiv="Last-Modified" content="" />'."\n";
echo '<meta http-equiv="Content-Style-Type" content="text/css2" />'."\n";
echo '<meta http-equiv="Window-Target" content="_blank" />'."\n";
echo '<meta name="robots" content="all, follow" />'."\n";
echo '<meta name="googlebot" content="index,follow,snippet,archive" />'."\n";
echo '<meta name="author" content="Daniel Milde" />'."\n";
echo '<link rel="shortcut icon" href="'.APP_URL.'/images/favicon.ico" />'."\n";

if (isset($data['site']['js_before'])) {
	foreach($data['site']['js_before'] as $js)
	echo '<script type="text/javascript" src="'.$js['src'].'"></script>'."\n";
}


if (isset($data['site']['rss'])) {
	foreach($data['site']['rss'] as $rss)
	echo '<link rel="alternate" type="application/rss+xml" title="'.$rss['title'].'" href="'.$rss['src'].'" />'."\n";
}

if (isset($data['site']['css'])) {
	foreach($data['site']['css'] as $css)
	echo '<link rel="Stylesheet" type="text/css" href="'.$css['src'].'" />'."\n";
}

echo '<title>'.$data['site']['title'].'</title>'."\n";
echo '</head>
<body>
<h1>NORS '.version().'</h1>
<div id="user_menu">';
if($data['user']){
	echo $data['locale']->logged_in.' '.$data['user'].'.<br />';
	echo '<a href="'.gen_url(array('model'=>'logout')).'">'.$data['locale']->log_out.'</a><br />';
	echo '<a href="export.php">'.$data['locale']->backup_db.'</a><br />';
	echo '<a href="'.APP_URL.'">'.$data['locale']->show_web.'</a><br />';
}
echo '</div>';

$event = isset($_GET['event']) ? $_GET['event'] : '';
if ($event == 'edit' || $event == 'del') {
	$event = $_GET['item'];
}
if ($event == 'category' || $event == 'category_tree') {
	$event = 'post';
}
$selected[$event] = ' class="selected" ';

echo '
<ul id="menu">';
if (iterable($data['menu'])) {
	foreach($data['menu'] as $menu){
		$selected[$menu['event']] = isset($selected[$menu['event']]) ? $selected[$menu['event']] : '';
		$args = array('model'=>'administration');
		$menu['event'] && $args['event'] = $menu['event'];
		$url = gen_url($args);
		echo '<li'.$selected[$menu['event']].'><a href="'.$url.'">'.$menu['title'].'</a></li>';
	}
}
echo '</ul>
<div id="main">';

loadFile($data['tplFile']);

echo '</div>
<div class="cleaner2"></div>';

echo '<div id="feet"><!-- feet -->'."\n".'
  <div id="feet_in"></div>'."\n";
statistics($data);
echo '<br />Powered by <a href="http://core-php.com/">Core framework '.core_version().'</a> &copy; 2005-'.date("Y").' Daniel Milde aka Dundee'."\n".'
</div><!-- end feet -->'."\n";

if (isset($data['site']['js_after'])) {
	foreach($data['site']['js_after'] as $js)
	echo '<script type="text/javascript" src="'.$js['src'].'"></script>'."\n";
}

//echo '</div><!-- end inner -->'."\n";
echo '</body>'."\n";
echo '</html>'."\n";
?>
