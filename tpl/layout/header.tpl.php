<?php echo $doctype ?>
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
<meta name="author" content="design and code: Petr Sobotka, Daniel Milde" />
<link rel="shortcut icon" href="<?php echo APP_URL?>/styles/<?php echo Core_Config::singleton()->style ?>/images/favicon.ico" />
<?php
if (isset($site['js'])) {
	foreach ($site['js'] as $js)
		echo '<script type="text/javascript" src="' . $js['src'] . '"></script>' . ENDL;
}

if (isset($site['rss'])) {
	foreach ($site['rss'] as $rss)
		echo '<link rel="alternate" type="application/rss+xml" title="' . $rss['title'] . '" href="' . $rss['src'] . '" />' . ENDL;
}

if (isset($site['css']['normal'])) {
	foreach ($site['css']['normal'] as $css)
		echo '<link rel="Stylesheet" media="screen,projection" type="text/css" href="' . $css['src'] . '" />' . ENDL;
}

if (isset($site['css']['print'])) {
	foreach ($site['css']['print'] as $css)
		echo '<link rel="Stylesheet" media="print" type="text/css" href="' . $css['src'] . '" />' . ENDL;
}

if (isset($site['css']['ie6'])) {
	echo '<!--[if lte IE 6]>' . ENDL;
	foreach ($site['css']['ie6'] as $css)
		echo '<link rel="Stylesheet" media="screen,projection" type="text/css" href="' . $css['src'] . '" />' . ENDL;
	echo '<![endif]-->' . ENDL;
}

if (isset($site['css']['ie7'])) {
	echo '<!--[if lte IE 7]>' . ENDL;
	foreach ($site['css']['ie7'] as $css)
		echo '<link rel="Stylesheet" media="screen,projection" type="text/css" href="' . $css['src'] . '" />' . ENDL;
	echo '<![endif]-->' . ENDL;
}
?>
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=RobotoDraft:300,400&subset=latin,latin-ext"/>
<title><?php if (isset($title)) echo $title . ' - '; echo $site['title'] ?></title>
</head>
<body>
	<div id="main" class="clearfix">
		<a href="#menu" class="hidden"><?php echo __('jump_to_navigation')?></a>
		<div id="content">
