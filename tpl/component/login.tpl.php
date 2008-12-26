<?php
if (iterable($errors)) {
	foreach ($errors as $error) {
		if ($error) echo '<span class="important">' . $error . '</span><br />';
	}
}

$action = $request->getUrl();

$f = $form->form(NULL, $action, __('login'), __('log_in'));
$form->input(NULL, 'username', __('username'))->setValidation();
$form->input(NULL, 'password', __('password'), 'password')->setValidation();

$form->render();
