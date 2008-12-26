<?php
$c = Core_Config::singleton();

echo '</div><!-- /content -->';
echo '</div><!-- /main -->';

echo '<div id="header">';
echo TAB . '<div id="logobar" class="clearfix">';
echo TAB . TAB . '<div id="header-right"></div>';
echo TAB . TAB . '<a id="logo" href="' . APP_URL . '">' . $c->name . '</a>';
echo TAB . TAB . '<div id="description">' . $c->description . '</div>';
echo TAB . '</div>';
echo '</div><!-- /header -->';

echo '<hr />';

echo '<div id="footer" class="cleared">
	<div id="footer-main">
		<p>Powered by <a href="http://norsphp.com/">NORS '.norsVersion().'</a> &copy;2007-'.date("Y").' <a href="http://milde.cz">Daniel Milde</a> aka Dundee</p>

	</div><!-- footer-main -->
</div><!-- footer -->


</body>
</html>';
