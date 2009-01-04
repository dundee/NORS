<?php
if (iterable($posts)) {
	foreach ($posts as $post) {
?>
<div class="post">
	<h1><a href="<?php echo $post->url ?>"><?php echo $post->name ?></a></h1>
	<small>
	<?php echo $post->date?> |
	<a href="<?php echo $post->cathegory_url?>"><?php echo $post->cathegory_name?></a> |
	<a href="<?php echo $post->url?>#comments"><?php echo __('comments') . ': ' . $post->num_of_comments ?></a> |
	<?php echo $post->seen . 'x ' . __('seen')?>
	</small>
	<p><?php echo strip_tags($post->text)?></p>
</div>
<?php
	}
}
?>