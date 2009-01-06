<?php
$html = new Core_Helper_Html();
$form = new Core_Helper_Form();

if ($errors) echo '<span class="important">' . $errors . '</span>';

$form->form(NULL, '?save',__('import') . ' ' . __('from') . ' NORS 3', __('ok'), array('enctype' => 'multipart/form-data'));
$form->input(NULL, 'db',   'db.php ' . __('file'), 'file', FALSE, array('title' => __('File db.php from "library" directory in NORS 3')));

$form->render();
