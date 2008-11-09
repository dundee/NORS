<?php
$administration->submenu($submenu, $subselected);

echo '<form action="#" method="post">';

$clicker->generate($settings);

echo '<input name="send" type="submit" value="' . __('save') . '" />';
echo '</form>';
?>
