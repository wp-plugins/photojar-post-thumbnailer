<div style="float:left;margin:10px;border:2px solid #ccc;">
	<?php 
	echo PJPostThumbnail::getThumbTag();
	$hiddenImages = PJPostThumbnail::getImageLinks(true); //get images without thumbnail
	if($hiddenImages != '')
		echo '<div style="display: none">'.$hiddenImages.'</div>';
?>
</div>
<?php echo $content; ?>
<div style="clear:both"></div>
