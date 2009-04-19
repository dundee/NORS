<h1><?php echo $post->name?></h1>
<div id="post"><?php echo $post->text?></div>

<?php
if (iterable($photos)) {
	foreach ($photos as $i => $photo) {
?>
<div class="thumbnail">
	<a href="<?php echo $photo->src?>" class="thickbox" rel="gal" title="<?php echo $photo->label?>"><img src="<?php echo $photo->thub?>" alt="<?php echo $photo->label?>" /></a>
	<div class="caption"><a href="<?php echo $photo->src?>"><?php echo $photo->label?></a></div>
</div>
<?php
	}
}
?>

<h3><?php echo __('evaluation')?></h3>
<br /><div id="eval">
<?php echo $eval ?>
</div>

<h3 id="comments"><?php echo __('comments')?></h3>

<?php
if (iterable($items)) {
	foreach ($items as $i => $item) {
?>
<div class="comment" id="post<?php echo $item->id ?>">
	<div class="author">
		[<a href="#post<?php echo $item->id?>"><?php echo $i?></a>]
<?php if ($item->href) { ?>
		<a href="<?php echo $item->href?>"><?php echo $item->user?></a>
<?php } else {?>
		<?php echo $item->user?>
<?php } ?>
	</div>
	<div class="date"><?php echo $item->date?></div>
	<div class="text"><?php echo $item->text?></div>
	<div class="reply"><a href="#" title="<?php echo $i?>"><?php echo __('reply')?></a></div>
</div>
<?php
	}
}

echo $comment_form
?>
