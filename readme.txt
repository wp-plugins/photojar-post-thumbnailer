=== Plugin Name ===
Contributors: jarinteractive
Donate link: http://www.jarinteractive.com/code/photojar
Tags: thumbnail, images, post thumbnail, gallery, photo, lightbox, thickbox, slimbox, image, photoJAR
Requires at least: 2.5.1
Tested up to: 2.6.1
Stable tag: 1.0b3

PhotoJAR: Post thumbnailer displays a post thumbnail for posts with galleries - additional options when paired with a javascript viewer.

== Description ==

PhotoJAR: Post Thumbnailer displays a post thumbnail for posts with galleries.  When paired with a javascript viewer, the full gallery can be displayed
when the thumbnail is clicked.  PhotoJAR: Post Thumbnail requires PhotoJAR: Base.

For additional info visit [JARinteractive](http://www.jarinteractive.com/code/photojar).

== Installation ==

This section describes how to install the plugin and get it working.
Requires PHP5 and [PhotoJAR: Base](http://www.jarinteractive.com/code/photojar/).

1. Upload the `pj-post-thumbnail` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Adjust settings under the 'Settings -> PhotoJAR' menu in Wordpress.
4. Optional: Install a javascript viewer plugin (Ex: [Lightbox](http://stimuli.ca/lightbox/), [ThickBox](http://mezzomondo.nelblog.it/2007/05/28/thickbox3/), etc.) 
	if you want to make use of that feature.  PhotoJAR will handle the markup to enable this on your images.
	
== Frequently Asked Questions ==

= Can I change the display of the thumbnail? =

You can change the template used to display thumbnails by editing the default-theme.php file in the plugin directory.  
Note: Back-up yourchanges to default-theme.php, as they will be overwritten when PhotoJAR: Post Thumbnailer is updated  (This is only Beta 1!). 
Future versions will define a location for custom themes. 

= What if I find a bug? =

Please contact me via the [PhotoJAR website](http://www.jarinteractive.com/code/photojar).  
Include the version of Wordpress you are using, current PhotoJAR settings, and details to recreate the bug if possible.