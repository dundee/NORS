<?php

/**
*
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

if (isset($submenu)) $administration->submenu($submenu, $subselected);

if (isset($actions)) $administration->actions($actions);

if (isset($dump_filter)) echo $dump_filter;

if (isset($errors)) {
	foreach ($errors as $error) {
		echo '<span class="important">' . $error . '</span>';
	}
}

if (isset($dump)) {
	echo '<div id="dump">';
	echo $dump;
	echo '</div>';
}
