
		</div><!-- /content -->
		
		<hr />
		
		<div id="sidebar">
			<div id="menu">
				<h2><?php echo __('cathegories') ?></h2>
<?php echo $cathegories ?>
				<h2><?php echo __('pages') ?></h2>
<?php echo $pages ?>
				<h2><?php echo __('other') ?></h2>
				<ul>
					<li><a href="<?php echo $administration ?>"><?php echo __('administration')?></a></li>
				</ul>
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
			<p>Powered by <a href="http://norsphp.com/">NORS <?php echo norsVersion() ?></a> &copy;2007-<?php echo date("Y") ?> <a href="http://milde.cz">Daniel Milde</a> aka Dundee |
			<?php 
			$lang = Core_Request::factory()->locale == 'Cs' ? 'en' : 'cs';
			echo '<a id="lang" href="?lang=' . $lang . '" title="' . $lang . '"><img src="' . STYLE_URL . '/images/' . $lang . '.gif" alt="' . $lang . '" /></a>';
			?></p>
		</div><!-- /footer-main -->
	</div><!-- /footer -->

</body>
</html>
