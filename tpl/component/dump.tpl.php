<?php
$administration->dump($table);

echo '<div id="paging">';
$ajaxpaging->paging($count, $max);
echo '</div>';
?>