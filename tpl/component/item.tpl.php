<?php

echo '<div id="'.$id.'" class="component">';
echo 'component '.$text;
echo ' <a href="?command=item-detail&amp;id='.$id.'" class="ajax">detail - '.date('i:s').'</a>';
echo '<span>'.$detail.'</span>';
echo '</div>';

?>