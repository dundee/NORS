<?php
if (iterable($files)) {
	echo '<div class="filemanager">' . ENDL;
	foreach ($files as $file) {
		echo "\t\t".'<div class="file">' . ENDL;
		echo "\t\t\t".'<a href="'.$file->src.'" title="'.$file->label.'" class="lightbox">' . ENDL;
		echo "\t\t\t".'<img src="'.$file->thub . '" />';
		echo "\t\t\t".'</a>' . ENDL;
		echo "\t\t\t".'<input type="text" name="label" class="label" value="'.$file->label.'" />' . ENDL;

		echo "\t\t\t".'<a class="update_label" title="'.$file->name.'" href="#">'. __('save').'</a>' . ENDL;
		echo "\t\t\t".'<a class="delete_file" title="'.$file->name.'" href="#">'. __('delete').'</a>' . ENDL;
		echo "\t\t".'</div>' . ENDL;
	}
	echo '</div>' . ENDL;
}
