<?php
if (iterable($items)) {
	foreach ($items as $item) {
?>
<div class="post">
	<h1><a href="<?php echo $item->url ?>"><?php echo $item->name ?></a></h1>
	<small>
	<?php echo $item->date?> |
	<a href="<?php echo $item->cathegory_url?>"><?php echo $item->cathegory_name?></a> |
	<a href="<?php echo $item->url?>#comments"><?php echo __('comments') . ': ' . $item->num_of_comments ?></a> |
	<?php if ($item->user_name) { echo __('author') . ': ' . $item->user_name ?> |<?php } ?>
	<?php echo $item->seen . 'x ' . __('seen')?>
	</small>
	<p><?php echo $item->text?></p>
</div>
<?php
	}
}
?>
<div id="paging"><?php echo $paging?></div>


