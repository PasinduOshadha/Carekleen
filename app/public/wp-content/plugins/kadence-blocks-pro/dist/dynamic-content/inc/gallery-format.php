<?php
/**
 * Handle gallery Format Rendering.
 *
 * @package Kadence Blocks Pro
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Gallery Format Rendering.
 *
 * @param mixed  $value the meta key.
 * @param string $size the image size - optional.
 *
 * @return array Returns the images in a gallery with meta data.
 */
function kbp_dynamic_content_gallery_format( $value, $size = '' ) {
	$output = $value;
	if ( $value && is_array( $value ) ) {
		$final_output = array();
		$i = 0;
		foreach ( $value as $key => $image ) {
			$image_id = isset( $image['ID'] ) ? $image['ID'] : '';
			if ( empty( $image_id ) ) {
				$image_id = isset( $image['id'] ) ? $image['id'] : '';
			}
			if ( ! empty( $image_id ) ) {
				$final_output[] = $image;
				if ( ! empty( $size ) ) {
					$image_array = wp_get_attachment_image_src( $image_id, $size );
					if ( ! empty( $image_array ) ) {
						$final_output[ $i ]['url'] = $image_array[0];
						$final_output[ $i ]['width'] = $image_array[1];
						$final_output[ $i ]['height'] = $image_array[2];
					}
				}
				$full_image_array = wp_get_attachment_image_src( $image_id, 'full' );
				if ( ! empty( $full_image_array ) ) {
					$final_output[ $i ]['fullUrl'] = $full_image_array[0];
				}
			}
			$i ++;
		}
		$output = $final_output;
	}
	return $output;
}
