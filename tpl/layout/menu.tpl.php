<?php
echo '</div><!-- content -->';

echo '<hr />

	<div id="breadcrumbs-bar" class="clearfix">
		<div id="breadcrumbs" class="clearfix">
			<a href="#">Úvodní strana</a> <span>&raquo;</span> <a href="#">Vision document</a> <span>&raquo;</span> <strong>Verze 1.0</strong>
		</div><!-- breadcrumbs -->';

echo '<div id="languages">';
if ($lang == 'Cs') {
	echo '<a href="?lang=en"><img src="'.$images_dir.'/flag-britain.gif" alt="English" width="16" height="11" /><span>english</span></a>';
	echo '<strong><img src="'.$images_dir.'/flag-czech.gif" alt="Czech" width="16" height="11" /><span>czech</span></strong>';
}
else {
	echo '<strong><img src="'.$images_dir.'/flag-britain.gif" alt="English" width="16" height="11" /><span>english</span></strong>';
	echo '<a href="?lang=cs"><img src="'.$images_dir.'/flag-czech.gif" alt="Czech" width="16" height="11" /><span>czech</span></a>';
}


echo '</div><!-- languages -->';
echo 		'<hr />
	</div><!-- breadcrumbs-bar -->

	<div id="sidebar">
		<div id="news">';

if ($lang == 'Cs') echo '<h2>Poslední Novinky</h2>';
else echo '<h2>Latest News</h2>';

echo '<div class="news-message clearfix">
				<p class="date">23<span> Sep</span></p>
				<p class="text">Nunc malesuada viverra orci. Duis blandit, orci accumsan cursus euismod, tellus orci hendrerit mi, non malesuada leo lectus ut dolor. Sed turpis sapien, consequat ac, vulputate at, tristique eu..</p>
			</div><!--  news-message -->
			<div class="news-message clearfix">
				<p class="date">30<span> Aug</span></p>
				<p class="text">Sed quam. Fusce ullamcorper quam vel massa. Aliquam volutpat ultrices purus. Pellentesque tincidunt, ipsum quis ultricies imperdiet, lacus lectus placerat lorem, a tristique mi lacus sit amet.</p>
			</div><!--  news-message -->
			<div class="news-message clearfix">
				<p class="date">02<span> Jul</span></p>
				<p class="text">Donec sed dolor quis metus tincidunt ornare. In fermentum metus et purus consequat euismod. Ut fringilla magna euismod ligula. In nunc leo, elementum nec, mollis eu, interdum non, ligula.</p>
			</div><!--  news-message -->
		</div><!-- news -->
	</div><!-- sidebar -->
</div><!-- main -->

<div id="header">
	<div id="logobar" class="clearfix">
		<a href="'.APP_URL.'" id="logo"><img src="'.$images_dir.'/logo.gif" alt="Logo NORS" width="126" height="45" /></a>';

$menu->render($menu_items);

echo '</div><!-- logobar -->
</div><!-- header -->

<hr />';
