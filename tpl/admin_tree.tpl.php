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

$administration->tree($table, $parent);