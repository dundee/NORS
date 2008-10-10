<?php

$j = isset($_GET['j']) ? $_GET['j'] : 1;

switch($j){
	case '1':
		$name = 'photo';
		break;
	case '2':
		$name = 'pdf';
		break;
}

if (iterable($data[$name])){
	foreach($data[$name] as $file){
		echo "\t\t".'<div class="file">'."\n";
							
		if ($name=='photo') {
			echo "\t\t\t".'<a href="'.APP_URL.'/upload/'.$file['name'].'" title="'.$file['label'].'" class="thickbox">'."\n";
			echo "\t\t\t\t".'<img src="'.APP_URL.'/upload/'.$file['thubnail'].'" alt="'.$file['label'].'" />'."\n";
		} else {
			echo "\t\t\t".'<a href="'.APP_URL.'/upload/'.$file['name'].'" title="'.$file['label'].'">'."\n";
			echo $file['label'];
		}
					
		echo "\t\t\t".'</a>'."\n";
		echo "\t\t\t".'<a class="delete_file" onclick="return delete_file(this,'.$j.');" href="'.$file['del_link'].'">'.$data['locale']->delete.'</a>'."\n";
		echo "\t\t".'</div>'."\n";
	}
}

?>

