<?php
/**
 * Plugin Name: PhotoJAR: Post Thumbnail
 * Plugin URI: http://www.jarinteractive.com/code/photojar/photojar-post-thumbnail
 * Description: Creates a single gallery thumbnail for posts.
 * Version: 1.0 Beta-4
 * Author: James Rantanen
 * Author URI: http://www.jarinteractive.com
 */
 
/*
    Copyright (C) 2008 James Rantanen (JARinteractive)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
 
//Check PHP version
if (version_compare(phpversion(), "5.0.0", "<"))
{
	die('PhotoJAR requires PHP5, your PHP version is '.phpversion());
}

define('PJ_THUMB_PLUGIN_PATH', dirname(__FILE__).'/');

class PJPostThumbnail
{
	private static $currentGallery = null;
	
	public static function getThumb()
	{
		$gallery = self::$currentGallery;
		return $gallery->getThumbnail();
	}
	
	public static function getImages()
	{
		$gallery = self::$currentGallery;
		return $gallery->getItems();
	}
	
	public static function getImageLinks($excludeThumb = true)
	{
		$tags = '';
			
		if(get_option('pj_post_thumb_linkto') == 'viewer')
		{
			$gallery = self::$currentGallery;
			$images = $gallery->getItems();
			$thumb = $gallery->getThumbnail();
			foreach($images as $image)
			{
				if($excludeThumb && $image->imageID == $thumb->imageID)
					continue;
				$imageSrc = image_downsize($image->imageID, 'thumbnail');
				$tags .= '<a href="'.LinkUtility::imageLink($image->imageID, 'full').'" title="'.$image->title.'" ><span title="'.$image->title.'">'.$image->title.'</span></a>'."\n";
			}
		}
		return $tags;
	}
	
	public static function getThumbTag($withLink = true, $size = null)
	{
		$thumb = self::getThumb();
		if($size == null)
		{
			$size = get_option('pj_post_thumb_size');
			if($size == 'custom')
			{
				$size = get_option('pj_custom_post_thumb_width').'x'.get_option('pj_custom_post_thumb_height');
				if('crop' == get_option('pj_post_thumb_crop'))
					$size .= 'xcrop';
			}
		}
		$imageSrc = image_downsize($thumb->imageID, $size);
		$tag = '<img src="'.$imageSrc[0].'" width="'.$imageSrc[1].'" height="'.$imageSrc[2].'" title="'.$thumb->title.'" alt="'.$thumb->title.'" />';
		
		if($withLink)
		{
			$linkto = array_shift(split('-', get_option('pj_post_thumb_linkto')));
			$gallery = self::$currentGallery;
			$atts = $gallery->getAttributes();
			if($linkto == 'permalink' || $atts['showchildren'] == 'true')
				$linkto = get_permalink();
				
			$tag = '<a href="'.LinkUtility::imageLink($thumb->imageID, $linkto).'">'.$tag.'</a>';
		}
		return $tag;
	}
	
	public static function getThumbLink()
	{
		$thumb = self::getThumb();
		return $thumb->linkto;
	}

	public static function processContent($content, $excerpt = false)
	{
		global $post;
		$thumbnailPost = $post;
		$thumbnailPost->the_content = $content;
		$imageString='';
		if(is_home() || is_archive() || is_search())
		{
			$gallery = PJGallery::getGalleryFromPost($thumbnailPost);
			self::$currentGallery = $gallery;
			if($excerpt)
				$content = wp_trim_excerpt($content);
			if($gallery)
			{
				$thumb = $gallery->getThumbnail();
				remove_shortcode('gallery');
				add_shortcode('gallery', create_function('$a','return "";'));
				
				//bloginfo('stylesheet_directory');
				
				ob_start(); // Enable output buffering
				include(PJ_THUMB_PLUGIN_PATH.'default-theme.php');
				$content = ob_get_contents(); //grab the buffer contents
				ob_end_clean(); //clear & close the buffer
				if(get_option('pj_post_thumb_linkto') == 'viewer' || get_option('pj_post_thumb_linkto') == 'viewer-single')
					$content = LinkUtility::whateverBox($content);
			}
		}
		else if($excerpt)
		{
			$content = wp_trim_excerpt($content);
		}
		return $content;
	}

	public static function processExcerpt($content)
	{
		remove_filter('the_content', array(PJPostThumbnail, 'processContent'), 0, 1);
		$content = PJPostThumbnail::processContent($content, true);
		add_filter('the_content', array(PJPostThumbnail, 'processContent'), 0, 1);
		return $content;
	}
	
	public static function options()
	
	{
		$pjPostThumbLinkTo = get_option('pj_post_thumb_linkto');
		$pjPostThumbSize = get_option('pj_post_thumb_size');
		$pjCustomPostThumbWidth = get_option('pj_custom_post_thumb_width');
		$pjCustomPostThumbHeight = get_option('pj_custom_post_thumb_height');
		$pjCustomPostThumbCrop = get_option('pj_custom_post_thumb_crop');
		$pjCustomPostThumbClass = get_option('pj_post_thumb_class');
		?>
		<h2><?php _e('PhotoJAR: Post Thumbnail') ?></h2>
		<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Link Post Thumbnail To') ?></th>
			<td>
				<select name="pj_post_thumb_linkto" id="pj_post_thumb_linkto">
					<option value="permalink" <?php if($pjPostThumbLinkTo=='permalink'){echo 'selected';}?>>Post Permalink</option>
					<option value="viewer" <?php if($pjPostThumbLinkTo=='viewer'){echo 'selected';}?>>Javascript Viewer</option>
					<option value="full" <?php if($pjPostThumbLinkTo=='full'){echo 'selected';}?>>Full Size Image (Single Image)</option>
					<option value="viewer-single" <?php if($pjPostThumbLinkTo=='viewer-single'){echo 'selected';}?>>Javascript Viewer (Single Image)</option>
				</select><br />
				<label>Post Permalink will be used if <code>showchildren="true"</code>.</label>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Post Thumbnail Size') ?></th>
			<td>
				<select name="pj_post_thumb_size" id="pj_post_thumb_size" onchange="pjOnChange(this.form.pj_post_thumb_size, 'pj_custom_post_thumb_size');">
					<option value="thumbnail" <?php if($pjPostThumbSize=='thumbnail'){echo 'selected';}?>>Thumbnail</option>
					<option value="medium" <?php if($pjPostThumbSize=='medium'){echo 'selected';}?>>Medium</option>
					<option value="full" <?php if($pjPostThumbSize=='full'){echo 'selected';}?>>Full</option>
					<option value="custom" <?php if($pjPostThumbSize=='custom'){echo 'selected';}?>>Custom</option>
				</select>
				<span id="pj_custom_post_thumb_size" style="display: <?php echo ($pjPostThumbSize=='custom')?'inline':'none';?>;"><br />
					<label>width x height</label>
					<input type="text" name="pj_custom_post_thumb_width" size="5" value="<?php echo $pjCustomPostThumbWidth;?>" /> x
					<input type="text" name="pj_custom_post_thumb_height" size="5" value="<?php echo $pjCustomPostThumbHeight;?>" /> - Crop 
					<input type="checkbox" name="pj_custom_post_thumb_crop" value="true" <?php if($pjCustomPostThumbCrop=='true'){echo 'checked';}?>/>
				</span>
			</td>
		</tr>
		</table><?php
	}
	
	public static function updateOptions()
	{
		update_option('pj_post_thumb_linkto', $_POST['pj_post_thumb_linkto']);
		update_option('pj_post_thumb_size', $_POST['pj_post_thumb_size']);
		if($_POST['pj_post_thumb_size']=='custom')
		{
			update_option('pj_custom_post_thumb_width', $_POST['pj_custom_post_thumb_width']);
			update_option('pj_custom_post_thumb_height', $_POST['pj_custom_post_thumb_height']);
			update_option('pj_custom_post_thumb_crop', $_POST['pj_custom_post_thumb_crop']);
		}
	}
	
	public static function install()
	{
		if(get_option('pj_post_thumb_linkto')=='')
		{
			update_option('pj_post_thumb_linkto', 'viewer');
		}
		if(get_option('pj_post_thumb_size')=='')
		{
			update_option('pj_post_thumb_size', 'thumbnail');
		}
	}
}
$postThumbnailer= new PJPostThumbnail();

add_filter('the_content', array(PJPostThumbnail, 'processContent'), 0, 1);
add_filter('the_excerpt', array(PJPostThumbnail, 'processExcerpt'), 0, 1);
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_action('activate_pj-post-thumbnail/pj-post-thumbnail.php', array(PJPostThumbnail, 'install'));
add_action('pj_config', array(PJPostThumbnail, 'options'));
add_action('pj_config_post', array(PJPostThumbnail, 'updateOptions'));