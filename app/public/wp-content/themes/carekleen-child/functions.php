<?php
// services custom post type
include_once 'inc/services-cpt.php';

/**
 * Enqueue child styles.
 */
function child_enqueue_styles() {
	wp_enqueue_style( 'carekleen-child-theme-styles', get_stylesheet_directory_uri() . '/assets/dist/css/style.css', array(), '1.0.0', 'all' );
	wp_enqueue_style( 'carekleen-theme', get_stylesheet_directory_uri() . '/style.css', array(), true );

	wp_enqueue_script('carekleen-child-theme-scripts', get_stylesheet_directory_uri() . '/assets/dist/js/bundle.js', array(), true );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles' ); // Remove the // from the beginning of this line if you want the child theme style.css file to load on the front end of your site.

/**
 * Add custom functions here
 */
