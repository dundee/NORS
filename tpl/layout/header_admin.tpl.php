<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Core_Config::singleton()->encoding ?>" />
<meta name="Description" content="<?php echo $site['description'] ?>" />
<meta name="Keywords" content="<?php echo $site['keywords'] ?>" />
<meta name="Generator" content="NORS <?php echo norsVersion() ?>" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
<meta http-equiv="Expires" content="" />
<meta http-equiv="Last-Modified" content="" />
<meta http-equiv="Content-Style-Type" content="text/css2" />
<meta http-equiv="Window-Target" content="_blank" />
<meta name="robots" content="all, follow" />
<meta name="googlebot" content="index,follow,snippet,archive" />
<meta name="author" content="Daniel Milde" />
<link rel="shortcut icon" href="<?php echo APP_URL . '/styles/' . Core_Config::singleton()->style ?>/images/favicon.ico" />
<?php
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
?>
<title><?php echo $site['title'] ?></title>
</head>
<body>
<h1>NORS <?php echo norsVersion() ?></h1>
<div id="user_menu">
<?php
if($user){
	echo __('logged_in') . ' ' . $user . '.<br />';
	echo '<a href="' . $logout_url . '">' . __('log_out')  . '</a><br />';
	echo '<a href="' . APP_URL . '">'     . __('show_web') . '</a><br />';
}
?>
</div>

<?php
if (iterable($menu)) {
	echo '<ul id="menu">';
	foreach($menu as $name=>$url){
		$class = $name == $selected ? ' class="selected"' : '';
		echo '<li' . $class . '><a href="' . $url . '">' . __($name) . '</a></li>';
	}
	echo '</ul>';
}
?>
<div id="main">
