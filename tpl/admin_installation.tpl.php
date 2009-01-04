<?php
$html = new Core_Helper_Html();
$form = new Core_Helper_Form();

if ($errors) echo '<span class="important">' . $errors . '</span>';

$form->form(NULL, '#', __('database'));
$form->input(NULL, 'host',     __('host'),         'text', 'localhost',     array('title' => __('adress_of_database_server')))->setValidation()->setParam('value', $dbhost);
$form->input(NULL, 'user',     __('user'),         'text', FALSE,           array('title' => __('name_of_database_user')))->setValidation()->setParam('value', $dbuser);
$form->input(NULL, 'password', __('password'),     'password', FALSE,       array('title' => __('password_of_database_user')))->setValidation()->setParam('value', $dbpassword);
$form->input(NULL, 'db', __('database'),           'text', FALSE,           array('title' => __('name_of_database')))->setValidation()->setParam('value', $db);
$form->input(NULL, 'prefix',   __('table_prefix'), 'text', 'nors4_',        array('title' => __('prefix_of_nors_tables')))->setParam('value', $dbprefix);

echo '<h2>' . __('installation') . '</h2>';

$fieldset = $html->elem(NULL, 'fieldset');
$html->elem($fieldset, 'legend')->setContent(__('new_user'));
$form->input($fieldset, 'user_name',     __('name'),         'text', 'admin',     array('title' => __('name_of_new_nors_user')))->setValidation()->setParam('value', $user_name);
$form->input($fieldset, 'user_password', __('password'),     'password', FALSE,   array('title' => __('password_of_new_nors_user')))->setValidation()->setParam('value', $user_password);
$form->input($fieldset ,'send', FALSE, 'submit')
    ->setParam('value', __('save'))
    ->setParam('class', 'submit');
$form->root->getParent()->addChild($fieldset);


$form->render();
