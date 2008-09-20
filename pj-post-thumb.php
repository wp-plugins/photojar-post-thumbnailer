<?php
// Place this template in your theme's directory for it to take effect 

//the next two lines will hide all single images inserted with the [image] shortcode when a post thumbnail is shown
remove_shortcode('image');
add_shortcode('image', create_function('$a','return "";'));

//get the thumbnail html tag
$postThumbTag = $postThumbnail->getThumbTag();

//get html for the additional gallery images (if applicable)
$hiddenImages = $postThumbnail->getImageLinks(true);
?>

<div style="float:left;margin:10px;padding:2px;">
	<div style="border:0px;"><?php echo $postThumbTag;?></div>
	<?php
	//hide image links so they are displayed in the viewer but not on the page
	if($hiddenImages != '') { ?>
		<div style="display: none"><?php echo $hiddenImages; ?></div>
	<?php } ?>
</div>
<?php echo $pjContent; //post content or excerpt ?>
<div style="clear:both"></div>