<?php
echo '<a href="' . $administration . '">administrace (jméno: test, heslo: test)</a>

<h1 class="hlavni">Články<span>&nbsp;</span></h1>';

if (iterable($posts)) {
	foreach ($posts as $post) {
		echo '<h2><a href="' . $post->url . '">'.$post->name.'</a></h2>';
		echo '<p>' . strip_tags($post->text) . '</p>';
	}
}
