<h1><?php echo $post->name?></h1>
<div id="post"><?php echo $post->text?></div>

<?php
foreach ($photos as $i => $photo) {
?>
<div class="thumbnail">
	<a href="<?php echo $photo->src?>" class="thickbox"><img src="<?php echo $photo->thub?>" alt="<?php echo $photo->label?>" /></a>
	<div class="caption"><a href="<?php echo $photo->src?>"><?php echo $photo->label?></a></div>
</div>
<?php
}
?>

<h3 id="comments"><?php echo __('comments')?></h3>

<?php
foreach ($comments as $i => $comment) {
?>
<div class="comment" id="post<?php echo $comment['id']?>">
	<div class="author">
		[<a href="#post<?php echo $comment['id']?>"><?php echo $i?></a>]
<?php if ($comment['href']) { ?>
		<a href="<?php echo $comment['href']?>"><?php echo $comment['user']?></a>
<?php } else {?>
		<?php echo $comment['user']?>
<?php } ?>
	</div>
	<div class="date"><?php echo $comment['date']?></div>
	<div class="text"><?php echo $comment['text']?></div>
	<div class="reply"><a href="#" title="<?php echo $i?>"><?php echo __('reply')?></a></div>
</div>
<?php
}

echo $comment_form
?>
