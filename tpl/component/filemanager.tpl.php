<?php
if (iterable($files)) {
	echo '<div class="filemanager">' . ENDL;
	foreach ($files as $file) {
		echo "\t\t".'<div class="file">'."\n";
		echo "\t\t\t".'<a href="'.$file['src'].'" title="'.$file['label'].'" class="thickbox">'."\n";
		echo "\t\t\t".'<img src="'.$file['thub'] . '" />';
		echo "\t\t\t".'</a>'."\n";
		echo "\t\t\t".'<input type="text" name="label" class="label" value="'.$file['label'].'" />'."\n";
		
		if (isset($file['kategorie_id'])) {
			echo '<select class="kategorie_foto">';
			if (isset($kategorie)) {
				foreach ($kategorie as $kat) {
					echo '<option value="'.$kat['kategorie_mist_id'].'" ';
					if ($file['kategorie_id'] == $kat['kategorie_mist_id']) echo 'selected="selected"';
					echo '>'.$kat['nazev_kategorie_mista_cz'].'</option>';
				}
				echo '</select>';
			}
		}
		
		echo "\t\t\t".'<a class="update_label" title="'.$file['name'].'" href="#">'. __('save').'</a>'."\n";
		echo "\t\t\t".'<a class="delete_file" title="'.$file['name'].'" href="#">'. __('delete').'</a>'."\n";
		echo "\t\t".'</div>'."\n";
	}
	echo '</div>' . ENDL;
}