<?php
echo '</div>
<div class="cleaner2"></div>';

echo '<div id="feet"><!-- feet -->'."\n".'
  <div id="feet_in"></div>'."\n";
Core_Debug::showInfo();
echo '<br />Powered by <a href="http://core-php.com/">Core framework '.core_version().'</a> &copy; 2005-'.date("Y").' Daniel Milde aka Dundee'."\n".'
</div><!-- end feet -->'."\n";

if (isset($data['site']['js_after'])) {
	foreach($data['site']['js_after'] as $js)
	echo '<script type="text/javascript" src="'.$js['src'].'"></script>'."\n";
}

//echo '</div><!-- end inner -->'."\n";
echo '</body>'."\n";
echo '</html>'."\n";