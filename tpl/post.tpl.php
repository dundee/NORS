<h1><?php echo $post->name?></h1>
<div id="post"><?php echo $post->text?></div>

<?php
if (iterable($photos)) {
	foreach ($photos as $photo) {
?>
<div class="thumbnail">
	<a href="<?php echo $photo->src?>" class="lightbox" title="<?php echo $photo->label?>"><img src="<?php echo $photo->thub?>" alt="<?php echo $photo->label?>" /></a>
</div>
<?php
	}
}
?>

<div>
	<iframe style="border: none;height: 30px;" src="http://www.facebook.com/plugins/like.php?href=<?php echo $url ?>&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>
</div>

<h3 id="evaluation"><?php echo __('evaluation')?></h3>
<div id="eval">
<?php echo $eval ?>
</div>

<h3 id="comments"><?php echo __('comments')?></h3>

<?php
$allow = $post->comments_allowed;
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
	<div class="text">
		<?php if ($item->gravatar) { ?><div class="gravatar" title="<?php echo $item->gravatar ?>"></div><?php } ?>
		<?php echo $item->text?>
	</div>
	<?php if ($allow) { ?><div class="reply"><a href="#" title="<?php echo $i?>"><?php echo __('reply')?></a></div><?php } ?>
</div>
<?php
	}
}

if ($allow) echo $comment_form;
else echo '<strong>' . __('comments_closed') . '</strong>';
?>
<script>
$('pre').each(function(i, block) {
  hljs.highlightBlock(block);
});
</script>
