<?php

/**
*
*
* @author Daniel Milde <daniel@milde.cz>
* @copyright Daniel Milde <daniel@milde.cz>
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Core
*/

$administration->submenu($submenu, $subselected);

$administration->actions($actions);

echo $dump_filter;
    
echo '<div id="dump">';
echo $dump;
echo '</div>';