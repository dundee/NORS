<?php
$f = $form->form(NULL,
                 $router->genUrl(FALSE, FALSE, FALSE, array('subaction'=>$request->getGet('event'),
                                                            'command'=>'dump-filter'), FALSE, TRUE),
                 __('filter'),
                 FALSE
                 )->setParam('id', 'filter_form')
                  ->setParam('onsubmit', 'return false;');
$form->input(NULL, 'filter', __('name') . ':')->setParam('value', $value);
$form->input(NULL, 'table_name', FALSE, 'hidden')->setParam('value', $table)->setParam('class', 'hidden');


$form->render(0, FALSE, TRUE);
?>
