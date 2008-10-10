<?php
$f = $form->form(NULL, 
                 $request->genUrl(FALSE, FALSE, FALSE, array('subevent'=>$request->getGet('subevent'), 
                                                             'command'=>'dump-filter'), FALSE, TRUE),
                 __('filter'),
			     FALSE
			     )
			     ->setParam('id', 'filter_form')
			     ->setParam('onsubmit', 'return false;');
$form->input(NULL, 'filter', __('name') . ':');
$form->input(NULL, 'table_name', FALSE, 'hidden')->setParam('value', $table);


$form->render(0, FALSE, TRUE);
?>