<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_toc_config');
function ultp_toc_config( $config ) {
	$configuration = array(
		'name' => __( 'Table of Contents', 'ultimate-post' ),
		'desc' => __( 'Add a Customizable Table of Contents into your blog posts and custom post types.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/table-of-content.svg',
		'is_pro' => false
	);
	$config['ultp_table_of_content'] = $configuration;
	return $config;
}

add_filter( 'rank_math/researches/toc_plugins', function( $toc_plugins ) {
	if ( has_block( 'ultimate-post/table-of-content' ) ) {
		$toc_plugins['ultimate-post/ultimate-post.php'] = 'PostX';
	}
 	return $toc_plugins;
});