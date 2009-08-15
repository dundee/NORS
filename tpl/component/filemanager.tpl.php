<?php
if (iterable($files)) {
	echo '<div class="filemanager">' . ENDL;
	$select = '';
	$i = 1;
	foreach ($files as $file) {
		echo "\t\t".'<div class="file">' . ENDL;
		echo "\t\t\t".'<a href="'.$file->src.'" title="'.$file->label.'" class="lightbox">' . ENDL;
		echo "\t\t\t".'<img src="'.$file->thub . '" />';
		echo "\t\t\t".'</a>' . ENDL;
		echo "\t\t\t".'<input type="text" name="label" class="label" value="'.$file->label.'" />' . ENDL;

		echo "\t\t\t".'<a class="update_label" title="'.$file->name.'" href="#">'. __('update_label').'</a>' . ENDL;
		echo "\t\t\t".'<a class="delete_file" title="'.$file->name.'" href="#">'. __('delete').'</a>' . ENDL;
		echo "\t\t".'</div>' . ENDL;
		$select .= '<a href="#" onclick="insert_img(' . $i . ')"><img src="'.$file->thub . '" /></a>';
		$i++;
	}
	echo '</div>' . ENDL;
	echo '<div title="' . __('image') . '" id="files" class="hidden">' . $select . '</div>';
} else echo '<div title="' . __('image') . '" id="files" class="hidden">' . __('no_items') . '</div>';
