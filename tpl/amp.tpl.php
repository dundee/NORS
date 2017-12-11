<h1><?php echo $post->name?></h1>
<div id="post"><?php echo $post->text?></div>

<?php
if (iterable($photos)) {
	foreach ($photos as $photo) {
?>
<div class="thumbnail">
    <a href="<?php echo $photo->src?>" class="lightbox2" data-lightbox="lightbox" title="<?php echo $photo->label?>"><img src="<?php echo $photo->thub?>" alt="<?php echo $photo->label?>" /></a>
</div>
<?php
	}
}
?>
