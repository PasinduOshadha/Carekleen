<?php
// services custom post type
include_once 'inc/services-cpt.php';

/**
 * Enqueue child styles.
 */
function child_enqueue_styles()
{
	wp_enqueue_style('owl-styles', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', array(), '2.3.4', 'all');
	wp_enqueue_style('carekleen-child-theme-styles', get_stylesheet_directory_uri() . '/assets/dist/css/style.css', array(), '1.0.0', 'all');
	wp_enqueue_style('carekleen-theme', get_stylesheet_directory_uri() . '/style.css', array(), true);

	wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), '3.6.0', false);
	wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array(), '2.3.4', true);
	wp_enqueue_script('carekleen-child-theme-scripts', get_stylesheet_directory_uri() . '/assets/dist/js/bundle.js', array(), true);
}

add_action('wp_enqueue_scripts', 'child_enqueue_styles'); // Remove the // from the beginning of this line if you want the child theme style.css file to load on the front end of your site.

/**
 * Add custom functions here
 */

// Allow SVG
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

	global $wp_version;
	if ($wp_version !== '4.7.1') {
		return $data;
	}

	$filetype = wp_check_filetype($filename, $mimes);

	return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
	];
}, 10, 4);

function cc_mime_types($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function fix_svg()
{
	echo '<style type="text/css">
		  .attachment-266x266, .thumbnail img {
			   width: 100% !important;
			   height: auto !important;
		  }
		  </style>';
}
//add_action( 'admin_head', 'fix_svg' );

function popup()
{
?>
	<div class="popup-wrapper">
		<div class="popup-overlay"></div>
		<div class="popup-container">
			<div class="popup-row">
				<div class="popup-col-left"></div>
				<div class="popup-col-right">
					<span class="popup-close-btn">Close</span><?php echo do_shortcode('[gravityform id="2" title="false" description="false" ajax="true" tabindex="49"]') ?></div>
			</div>
		</div>
	</div>
<?php


}

add_action('wp_footer', 'popup');
