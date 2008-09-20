<div style="float:left;margin:10px;padding:2px;"><?php 
	echo '<div style="border:2px solid #ccc;">'.$postThumbnail->getThumbTag().'</div>';
	$hiddenImages = $postThumbnail->getImageLinks(true); //get images without thumbnail
	if($hiddenImages != '')
		echo '<div style="display: none">'.$hiddenImages.'</div>';
?></div>
<?php echo $pjContent; ?>
<div style="clear:both"></div>