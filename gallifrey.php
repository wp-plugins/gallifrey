<?php
/*
Plugin Name: Gallifrey
Plugin URI: http://www.bin-co.com/tools/wordpress/plugins/gallifrey/
Description: A gallery based on the Galleriffic jQuery plugin
Version: 1.00.0
Author: Binny V A
Author URI: http:/binnyva.com/
*/

add_shortcode( 'gallery', 'gallifrey_shortcode' );
function gallifrey_shortcode( $attr ) {
	global $post;
	$attachments = get_children("post_parent={$post->ID}&post_type=attachment&post_mime_type=image");
	$output = '';
	
	if(is_single()) {
		// Post view - gallifrey effect is on.
		$output = <<<END
<div id="gallifrey">
<div id="gallifrey-controls" class="controls"></div>
<div id="gallifrey-image" class="slideshow"></div>
<div id="gallifrey-details" class="embox">
	<div id="gallifrey-download" class="download"><a id="gallifrey-download-link">Download Original</a></div>
	<div id="gallifrey-title" class="image-title"></div>
	<div id="gallifrey-desc" class="image-desc"></div>
</div>
<div id="gallifrey-thumbs" class="navigation">
<ul class="noscript thumbs">
END;
	} else {
		// Archive view - Gallifrey effect is turned off.
		$output = <<<END
<style type="text/css">
.gallery {
	margin: auto;
}
.gallery-items li {
	float: left;
	margin: 10px 0 0 0;
	text-align: center;
	width: 30%;
}
.gallery img {
	border: 2px solid #cfcfcf;
}
</style>
<div class='gallery'>
<ul class="gallery-items">
END;
	}
	foreach($attachments as $img) {
		$thumbnail_image = preg_replace('/\.([a-z]+)$/', "-150x150.$1", $img->guid); //Auto generate the thumbnail image url based on the image src.
		$output .= <<<END
<li><a href="{$img->guid}" original="{$img->guid}" title="{$img->post_title}" description="{$img->post_content}"><img 
src="$thumbnail_image" alt="{$img->post_title}" /></a></li>
END;
	}
	
	if(is_single()) {
		$home = get_option('home');
		$output .= <<<END
</ul>
</div>
</div>
<script type="text/javascript" src="$home/wp-includes/js/jquery/jquery.js"></script>
<script type="text/javascript" src="$home/wp-content/plugins/gallifrey/galleriffic.js"></script>
<link type="text/css" href="$home/wp-content/plugins/gallifrey/white.css" rel="stylesheet" />
<script type="text/javascript">
jQuery(document).ready(function() {
	if(!document.getElementById("gallifrey")) return;
	var gallery = jQuery('#gallifrey').galleriffic('#gallifrey-thumbs', {
		delay:                2000,
		numThumbs:            12,
		imageContainerSel:    '#gallifrey-image',
		controlsContainerSel: '#gallifrey-controls',
		titleContainerSel:    '#gallifrey-title',
		descContainerSel:     '#gallifrey-desc',
		downloadLinkSel:      '#gallifrey-download-link'
	});
	
	gallery.onFadeOut = function() {
		jQuery('#gallifrey-details').fadeOut('fast');
	};
	
	gallery.onFadeIn = function() {
		jQuery('#gallifrey-details').fadeIn('fast');
	};
});
</script>
END;
	} else {
		$output .= '</ul></div>';
	}

	return $output;
}
