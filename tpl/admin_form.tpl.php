<?php

$administration->submenu($submenu, $subselected);

$administration->actions($actions);

if (isset($errors) && iterable($errors)) {
	foreach ($errors as $error) {
		echo '<span class="important">' . $error . '</span>';
	}
}

echo '<div id="admin_form">';
echo $form;
echo '</div>';
