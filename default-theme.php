<div style="float:left;margin:10px;padding:2px;"><?php 
	echo '<div style="border:2px solid #ccc;">'.PJPostThumbnail::getThumbTag().'</div>';
	$hiddenImages = PJPostThumbnail::getImageLinks(true); //get images without thumbnail
	if($hiddenImages != '')
		echo '<div style="display: none">'.$hiddenImages.'</div>';
?></div>
<?php echo $content; ?>
<div style="clear:both"></div>