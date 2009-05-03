<?php
$administration->submenu($submenu, $subselected);
?>
<form action="#" method="post">
<?php
$clicker->generate($settings);
?>
<input name="send" type="submit" value="<?php echo __('save') ?>" />
</form>
