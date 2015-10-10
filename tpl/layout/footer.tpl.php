
		</div><!-- /content -->

		<hr />

		<div id="sidebar">
			<div id="menu">
				<h2><?php echo __('categories') ?></h2>
<?php echo $categories ?>
			</div>
		</div><!-- /sidebar -->
	</div><!-- /main -->

	<hr />

	<div id="header">
		<div id="logobar" class="clearfix">
			<div id="header-right"></div>
			<a id="logo" href="<?php echo APP_URL ?>"><?php echo $name ?></a>
			<div id="description"><?php echo $description ?></div>
		</div>
	</div><!-- /header -->

	<hr />

	<div id="footer" class="cleared">
		<div id="footer-main">
			<?php Core_Debug::showInfo(); ?>
			<p>Powered by NORS <?php echo norsVersion() ?>&copy;2007-2015 <a href="http://milde.cz">Daniel Milde</a> aka Dundee |
			<?php
			$langs = array('En', 'Cs', 'Sk');
			foreach ($langs as $lang) {
				if ($lang == Core_Request::factory()->locale) continue;
				$lang = strtolower($lang);
				echo '<a class="lang" href="?lang=' . $lang . '" title="' . $lang . '"><img src="' . STYLE_URL . '/images/' . $lang . '.gif" alt="' . $lang . '" /></a> |';
			}
			?></p>
		</div><!-- /footer-main -->
	</div><!-- /footer -->

</body>
</html>
