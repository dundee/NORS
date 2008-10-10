<?php

if (!isset($validate)) {
	$validation = "<script type='text/javascript'>
// <![CDATA[
function validate(formular) {
	var errors = '';\n";
	$i=0;
	if(!iterable($inputs)){
		$validation .= "\treturn true;";
	} else {
		foreach($inputs as $input){
  			switch($input['name']){
    			case 'name':
    			case 'user':
    			case 'text':
    			case 'url':
    				$validation .= "\tif (formular.".$input['name'].".value=='') {\n";
    				$validation .= "\t\terrors += 'Položka ".$input['label']." je povinná.\\n';\n";
					$validation .= "\t\tformular.".$input['name'].".focus();\n";
					$validation .= "\t\t$('#".$input['name']."').addClass('required');\n";
    				$validation .= "\t}\n";
    				$i = 1;
    				break;
    			case 'password':
	    			$validation .= "\tif (formular.".$input['name'].".value=='') {\n";
	        		$validation .= "\t\terrors += 'Heslo je povinná položka.\\n';\n";
	        		$validation .= "\t\tformular.".$input['name'].".focus();\n";
	        		$validation .= "\t\t$('#".$input['name']."').addClass('required');\n";
	      			$validation .= "\t}\n\n";
	      			$validation .= "\tif (formular.".$input['name']."_check && formular.".$input['name'].".value != formular.".$input['name']."_check.value) {\n";
	    			$validation .= "\t\terrors += 'Hesla se neshodují.\\n';\n";
	        		$validation .= "\t\tformular.".$input['name'].".focus();\n";
	        		$validation .= "\t\t$('#".$input['name']."').addClass('required');\n";
	      			$validation .= "\t}\n\n";
					break;
	  		}
		}
		$validation .= "\tif (errors.length > 0) {\n";
		$validation .= "\t\t".'alert(errors);'."\n";
		$validation .= "\t\t$(formular).hide('slow');\n";
		$validation .= "\t\t$(formular).show('slow');\n";
		$validation .= "\t\t".'return false;'."\n";
	    $validation .= "\t} else {\n";
		$validation .= "\t\t$(formular).hide('slow');\n";
		$validation .= "\t\treturn true;\n";
		$validation .= "\t}\n";
	}//end no fields
	$validation .= "}\n";
  	$validation .= "// ]]>\n";
	$validation .= "</script>";

} else $validation = $validate;

echo $validation;

if (isset($errors) && count($errors) > 0) {
	foreach($errors as $error) echo '<span class="important">'.$error.'</span>';
}

echo '<form action="'.( isset($action) ? $action : '#').'" method="post" onsubmit="return validate(this)">';
echo '<fieldset>';
if (isset($legend)) echo '<legend>'.$legend.'</legend>';

$i = 1;
if (iterable($inputs)){
	foreach($inputs as $input){
		echo '<div>';
		echo '<label for="'.$input['name'].'">'.$input['label'].'</label>';
		echo '<input name="'.$input['name'].'" tabindex="'.(isset($input['tabindex']) ? $input['tabindex'] : $i).'" id="'.$input['name'].'" type="'.(isset($input['type']) ? $input['type'] : 'text').'" value="'.(isset($input['value']) ? $input['value'] : (isset($_POST[$input['name']]) ? output($_POST[$input['name']],TRUE) : '')).'" />';
		echo '</div>';
		$i++;
	}
}

echo '<div class="center"><input class="submit" type="submit" name="send" value="'.(isset($submit) ? $submit : 'OK').'" /></div>';

echo '</fieldset>';
echo '</form>';
?>
