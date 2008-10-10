<?php
if (iterable($errors)) foreach ($errors as $error) echo '<span class="important">' . $error . '</span><br />';

$f = $form->form(NULL, $action, __('new_post'), __('ok'), array('id'=>'rss'));
$form->input(NULL, 'source', __('source'))->setValidation();
$form->input(NULL, 'title', FALSE, 'hidden');

$form->render();