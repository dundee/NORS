<?php
echo '</div>
<div class="cleaner2"></div>';

echo '<div id="feet"><!-- feet -->'."\n".'
  <div id="feet_in"></div>'."\n";
Core_Debug::showInfo();
echo '<br />Powered by <a href="http://core-php.com/">NORS '.norsVersion().'</a> &copy; 2007-'.date("Y").' Daniel Milde aka Dundee | '."\n";
$lang = Core_Request::factory()->locale == 'Cs' ? 'en' : 'cs';
echo '<a id="lang" href="?lang=' . $lang . '" title="' . $lang . '"><img src="' . STYLE_URL . '/images/' . $lang . '.gif" alt="' . $lang . '" /></a>';
echo '</div><!-- end feet -->'."\n";

//echo '</div><!-- end inner -->'."\n";
echo '</body>'."\n";
echo '</html>'."\n";
