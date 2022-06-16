<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly */
}

/**
 * Plugin Name:       Filter Gallery - 0.1.1
 * Plugin URI:        https://wpfrank.com/
 * Description:       Create multiple multi level filters and apply on gallery.
 * Version:           0.1.1
 * Requires at least: 4.0
 * Requires PHP:      4.0
 * Author:            FARAZFRANK
 * Author URI:        https://profiles.wordpress.org/farazfrank/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       filter-gallery
 * Domain Path:       /languages

Filter Gallery is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Filter Gallery is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Filter Gallery. If not, see https://wpfrank.com/.
*/

// custom image size
add_image_size( 'ufg_200_200', 200, 200, true );
add_image_size( 'ufg_300_300', 300, 300, true );
add_image_size( 'ufg_400_400', 400, 400, true );

/* FG activation */
function ufg_activation() {
	/* update current plugin version */
	if ( is_admin() ) {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$ufg_plugin_data = get_plugin_data( __FILE__ );
		if ( isset( $ufg_plugin_data['Version'] ) ) {
			$ufg_plugin_version = $ufg_plugin_data['Version'];
			update_option( 'ufg_current_version', $ufg_plugin_version );
		}
	}
}
register_activation_hook( __FILE__, 'ufg_activation' );

// FG deactivation
function ufg_deactivation(){
	// update last active plugin version
	$ufg_last_version = get_option('ufg_current_version');
	if($ufg_last_version !== ""){
		update_option('ufg_last_version', $ufg_last_version);
	}
}
register_deactivation_hook( __FILE__, 'ufg_deactivation' );

// FG uninstall
function ufg_uninstall(){
}
register_uninstall_hook(__FILE__, 'ufg_uninstall');

// load translation
function ufg_load_translation() {
	load_plugin_textdomain( 'filter-gallery', false, dirname( plugin_basename(__FILE__) ) .'/languages' );
}
add_action( 'plugins_loaded', 'ufg_load_translation');

// FG menu
function ufg_menu_page() {
	// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page(
		__( 'Filter Gallery', 'filter-gallery' ),
		'Filter Gallery',
		'manage_options',
		'filter-gallery',
		'ufg_main',
		'dashicons-format-gallery',
		65
	);
	
	//add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position )
	add_submenu_page( 'filter-gallery', 'Mange Gallery', 'Mange Gallery', 'manage_options', 'ufg-manage-gallery', 'ufg_manage_gallery' );
}
add_action( 'admin_menu', 'ufg_menu_page' );

// FG main page body
function ufg_main(){
	require 'admin/galleries.php';
}

// FG sub menu filters page body
function ufg_manage_gallery(){
	require 'admin/manage-gallery.php';
}

//get / create next gallery id
function ufg_get_next_id(){
	global $wpdb;
	$ufg_gallery_key = "ufg_gallery_";
	// reference : https://wordpress.stackexchange.com/questions/8825/how-do-you-properly-prepare-a-like-sql-statement
	$ufg_gallery_count_res = $wpdb->get_row(
		$wpdb->prepare("SELECT option_name FROM `$wpdb->options` WHERE `option_name` LIKE %s ORDER BY option_id DESC LIMIT 1", '%'.$ufg_gallery_key.'%'), ARRAY_N
	);
	
	if($wpdb->num_rows) {
		$ufg_gallery_last_key = $ufg_gallery_count_res[0];
		$ufg_underscore_pos = strrpos($ufg_gallery_last_key, '_');
		$ufg_last_slider_id = (int) substr($ufg_gallery_last_key, ($ufg_underscore_pos + 1));
		return ($ufg_last_slider_id + 1);
	} else {
		return 1;
	}
}

// 1. save filters ajax
function ufg_gallery_filters_callback(){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'add-filters' ) ) {
			// save filters
			//print_r($_POST);
			$ufg_gallery_id = sanitize_text_field(($_POST['id']));
			$ufg_gallery_name = sanitize_text_field($_POST['gallery_name']);
			$filters = (json_decode(stripslashes($_POST['filters'])));

			// making same name fitters unique start
			$counter = 1;
			if(is_array($filters) && $filters_count = count($filters)) {
				for($i = 0; $i < $filters_count; $i++){
					$parent_filters = $filters[$i]->title;
					if(!strrpos($parent_filters, "-".$counter)) {
						$filters[$i]->title = $parent_filters."-".$counter;
					}
					//check level one children
					$child_count_level_one = count($filters[$i]->children);
					if($child_count_level_one) {
						$level_one_array = $filters[$i]->children;
						for($j = 0; $j < $child_count_level_one; $j++){
							$value_level_one = $level_one_array[$j]->title;
							if(!strrpos($value_level_one, "-".$counter)) {
								$level_one_array[$j]->title = $value_level_one."-".$counter;
							}
							$counter++;
						}
					}
					$counter++;
				}
			}
			// making same name fitters unique end
			update_option("ufg_filters_".$ufg_gallery_id, $filters);
			
			$ufg_details = array('ufg_gallery_id' => $ufg_gallery_id, 'gallery_name' => $ufg_gallery_name);
			update_option("ufg_details_".$ufg_gallery_id, $ufg_details);
		} else {
			die;
		}
		die;
	}
}
add_action( 'wp_ajax_ufg_gallery_filters', 'ufg_gallery_filters_callback' );

// 2. add images to the gallery
function ufg_li_generate_ajax_callback() {
	if ( isset($_POST['attachment_id']) && isset($_POST['ufg_gallery_id']) ) {
		wp_enqueue_script( 'ufg-uploader-js', plugins_url( 'assets/js/ufg-uploader.js', __FILE__ ), array('jquery'), '1.10.0' );

		//defaults
		$ufg_title = $ufg_alt = $ufg_description = $ufg_url = "";
		//load values
		$ufg_attachment_id = sanitize_text_field($_POST['attachment_id']);
		$ufg_title = get_the_title($ufg_attachment_id);
		$ufg_alt = get_post_meta($ufg_attachment_id, '_wp_attachment_image_alt', TRUE);
		//wp_get_attachment_image_src ( int $ufg_attachment_id, string|array $size = 'thumbnail', bool $icon = false )
		//thumb, thumbnail, medium, large, post-thumbnail
		$medium = wp_get_attachment_image_src($ufg_attachment_id, 'medium', true); // attachment medium URL
		$attachment = get_post( $ufg_attachment_id );
		$ufg_description = $attachment->post_content; // attachment description
		//get saved filters
		$ufg_gallery_id = sanitize_text_field($_POST['ufg_gallery_id']);
		$filters = get_option("ufg_filters_".$ufg_gallery_id);
		?>
		<script>
		jQuery(document).ready(function () {
			jQuery(function(jQuery) {
				jQuery('.ufg-image-filters').multiselect({
					buttonWidth: '100%',
					enableFiltering: true,
					nonSelectedText: "<?php esc_html_e( 'Select Filters', 'filter-gallery' ); ?>"
				});
			});
		});
		</script>
		<li class="col-md-2 border border-dark rounded-lg bg-light p-2 m-4 ufg-image-<?php echo esc_attr($ufg_attachment_id); ?>" data-position="<?php echo esc_attr($ufg_attachment_id); ?>">
			<div class="form-group">
				<input type="hidden" class="form-control ufg-attachment-id" id="ufg-attachment-id" name="ufg-attachment-id[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_attachment_id); ?>">
				<img src="<?php echo esc_url($medium[0]); ?>" class="card-img-top" width="150px" height="150px">
				<span class="badge badge-primary">Image ID: <?php echo esc_attr($ufg_attachment_id); ?></span>
			</div>
			<div class="form-group">
				<input type="text" class="form-control ufg-title" id="ufg-title" name="ufg-title[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_title); ?>" placeholder="<?php esc_html_e( 'Image Title', 'filter-gallery' ); ?>">
			</div>
			<div class="form-group">
				<input type="text" class="form-control ufg-alt" id="ufg-alt" name="ufg-alt[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_alt); ?>" placeholder="<?php esc_html_e( 'Image Alternative Text', 'filter-gallery' ); ?>">
			</div>
			<div class="form-group">
				<?php
				$ufg_get_filter_list_results = ufg_get_filter_list($ufg_attachment_id, $filters, array());
				$ufg_get_filter_list_allowed = array(
					'select' => array( 'id' => array(), 'name' => array(), 'class' => array(), 'data-max' => array(), 'multiple' => array() ),
					'option' => array ( 'value' => array(), 'selected' => array()),
				);
				echo wp_kses($ufg_get_filter_list_results, $ufg_get_filter_list_allowed);
				?>
			</div>
			<div class="form-group text-center">
				<button type="button" id="ufg-remove-image" onclick="return removeImage('<?php echo esc_attr($ufg_attachment_id); ?>');" class="btn btn-sm btn-danger"><?php esc_html_e( 'Remove', 'filter-gallery' ); ?></button>
			</div>
		</li>
		<?php
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
add_action( 'wp_ajax_ufg_image_id', 'ufg_li_generate_ajax_callback' );

// generate filter select list
function ufg_get_filter_list($ufg_attachment_id, $filters, $selected_filters){
	$ufg_filters_list = "";
	if(is_array($filters) && $filters_count = count($filters)) {
		$ufg_filters_list .= '<select id="ufg-image-filters" name="ufg-image-filters['.$ufg_attachment_id.'][]" class="ufg-image-filters" data-max="" multiple="multiple">';
		for($i = 0; $i <= 4; $i++){
			$text_zero = sanitize_text_field($filters[$i]->text);
			$value_zero = str_replace(" ","-", strtolower($filters[$i]->title));
			if(in_array($value_zero, $selected_filters) === TRUE) $selected = "selected=selected"; else $selected = "";
			if($text_zero != "") $ufg_filters_list .= "<option value='$value_zero' $selected>$text_zero</option>";
		}
		$ufg_filters_list .=  '</select>';
		return $ufg_filters_list;
	}
}

// 3. save gallery images
function ufg_save_gallery_callback(){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'save-gallery' ) ) {
			// defaults
			$ufg_gallery_id = sanitize_text_field($_POST['id']);
			$ufg_image_id = $ufg_image_title = $ufg_image_alt = $ufg_image_filters = $ufg_gallery = array();
			
			// save gallery
			// Good idea to make sure things are set before using them
			$ufg_image_id = isset( $_POST['image_id'] ) ? (array) array_map('sanitize_text_field', $_POST['image_id']) : array();
			$ufg_image_title = isset( $_POST['image_title'] ) ? (array) array_map('sanitize_text_field', $_POST['image_title']) : array();
			$ufg_image_alt = isset( $_POST['image_alt'] ) ? (array) array_map('sanitize_text_field', $_POST['image_alt']) : array();
			$ufg_image_filters = isset( $_POST['image_filters'] ) ? (array) array_map('sanitize_text_field', $_POST['image_filters']) : array();

			sanitize_text_field(parse_str(($_POST['image_id']), $ufg_image_id));
			sanitize_text_field(parse_str(($_POST['image_title']), $ufg_image_title));
			sanitize_text_field(parse_str(($_POST['image_alt']), $ufg_image_alt));
			sanitize_text_field(parse_str(($_POST['image_filters']), $ufg_image_filters));
			
			//update attachment meta - title, alt, description
			$i = 0;
			foreach($ufg_image_id['ufg-attachment-id'] as $ufg_id) {
				$ufg_title = $ufg_image_title['ufg-title'][$ufg_id];
				$ufg_alt = $ufg_image_alt['ufg-alt'][$ufg_id];
				$ufg_image_update = array(
					'ID'           => $ufg_id,
					'post_title'   => $ufg_title,
				);
				wp_update_post( $ufg_image_update );
				update_post_meta( $ufg_id, '_wp_attachment_image_alt', sanitize_text_field( $ufg_alt ) );
				$i++;
			}
			
			$ufg_gallery = array_merge($ufg_image_id, $ufg_image_title, $ufg_image_alt, $ufg_image_filters);
			update_option("ufg_gallery_".$ufg_gallery_id, $ufg_gallery);
			die;
		} else {
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_save_gallery', 'ufg_save_gallery_callback' );

// 4. load gallery images
function ufg_load_gallery_callback($ufg_gallery_id){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'load-gallery' ) ) {
			//get / create next gallery id
			if(isset($_POST['id'])){
				$ufg_gallery_id = sanitize_text_field($_POST['id']); 
				
				// load filters and gallery options
				$ufg_filters = get_option("ufg_filters_".$ufg_gallery_id);
				$ufg_gallery = get_option("ufg_gallery_".$ufg_gallery_id);
				
				//defaults
				$ufg_title = $ufg_alt = $ufg_description = $ufg_url = "";
				foreach ($ufg_gallery['ufg-attachment-id'] as $key => $value){
					$ufg_attachment_id = $key;
					//load values
					$ufg_title = get_the_title($ufg_attachment_id);
					$ufg_alt = get_post_meta($ufg_attachment_id, '_wp_attachment_image_alt', TRUE);
					//wp_get_attachment_image_src ( int $ufg_attachment_id, string|array $size = 'thumbnail', bool $icon = false )
					//thumb, thumbnail, medium, large, post-thumbnail
					$medium = wp_get_attachment_image_src($ufg_attachment_id, 'medium', true); // attachment medium URL
					$attachment = get_post( $ufg_attachment_id );
					$ufg_description = $attachment->post_content; // attachment description
					//get saved filters
					$filters = get_option("ufg_filters_".$ufg_gallery_id);
					?>
					<li class="col-md-2 border border-dark rounded-lg bg-light p-2 m-4 ufg-image-<?php echo esc_attr($ufg_attachment_id); ?>" data-position="<?php echo esc_attr($ufg_attachment_id); ?>">
						<div class="form-group">
							<input type="hidden" class="form-control ufg-attachment-id" id="ufg-attachment-id" name="ufg-attachment-id[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_attachment_id); ?>">
							<img src="<?php echo esc_url($medium[0]); ?>" class="card-img-top" width="150px" height="150px">
							<span class="badge badge-primary">Image ID: <?php echo esc_attr($ufg_attachment_id); ?></span>
						</div>
						<div class="form-group">
							<input type="text" class="form-control ufg-title" name="ufg-title[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_title); ?>" placeholder="<?php esc_html_e( 'Image Title', 'filter-gallery' ); ?>">
						</div>
						<div class="form-group">
							<input type="text" class="form-control ufg-alt" name="ufg-alt[<?php echo esc_attr($ufg_attachment_id); ?>]" value="<?php echo esc_attr($ufg_alt); ?>" placeholder="<?php esc_html_e( 'Image Alternative Text', 'filter-gallery' ); ?>">
						</div>
						<div class="form-group">
							<?php $selected_filters = $ufg_gallery['ufg-image-filters'][$key]; ?>
							<?php 
							$ufg_get_filter_list_results = ufg_get_filter_list($ufg_attachment_id, $filters, $selected_filters);
							$ufg_get_filter_list_allowed = array(
								'select' => array( 'id' => array(), 'name' => array(), 'class' => array(), 'data-max' => array(), 'multiple' => array() ),
								'option' => array ( 'value' => array(), 'selected' => array()),
							);
							echo wp_kses($ufg_get_filter_list_results, $ufg_get_filter_list_allowed);
							?>
						</div>
						<div class="form-group text-center">
							<button type="button" id="ufg-remove-image" onclick="return removeImage('<?php echo esc_attr($ufg_attachment_id); ?>');" class="btn btn-sm btn-danger"><?php esc_html_e( 'Remove', 'filter-gallery' ); ?></button>
						</div>
					</li>
					<?php
				}
				?>
				<script>
				jQuery(document).ready(function () {
					jQuery(function(jQuery) {
						jQuery('.ufg-image-filters').multiselect ({
							buttonWidth: '100%',
							enableFiltering: true,
							nonSelectedText: "<?php esc_html_e( 'Select Filters', 'filter-gallery' ); ?>",
						});
					});
				});
				</script>
				<?php
				die;
			}
		} else {
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_load_gallery', 'ufg_load_gallery_callback' );


// 5. save gallery settings
function ufg_save_setting_callback(){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'save-setting' ) ) {
			$ufg_gallery_id = sanitize_text_field($_POST['ufg_gallery_id']);
			$settings = array(
				//gallery details
				'ufg_gallery_id' => sanitize_text_field($_POST['ufg_gallery_id']),
				
				//filters settings
				'show_filters' => sanitize_text_field($_POST['show_filters']),
				'show_all_button' => sanitize_text_field($_POST['show_all_button']),
				'all_button_text' => sanitize_text_field($_POST['all_button_text']),
				'all_button_color' => sanitize_text_field($_POST['all_button_color']),
				'all_button_bg_color' => sanitize_text_field($_POST['all_button_bg_color']),
				'parent_button_color' => sanitize_text_field($_POST['parent_button_color']),
				'parent_button_bg_color' => sanitize_text_field($_POST['parent_button_bg_color']),
				
				//gallery settings
				'columns_desktop' => sanitize_text_field($_POST['columns_desktop']),
				'columns_tab' => sanitize_text_field($_POST['columns_tab']),
				'columns_mobile_landscape' => sanitize_text_field($_POST['columns_mobile_landscape']),
				'columns_mobile_portrait' => sanitize_text_field($_POST['columns_mobile_portrait']),
				'thumbnail_image_size' => sanitize_text_field($_POST['thumbnail_image_size']),
				'thumbnail_border' => sanitize_text_field($_POST['thumbnail_border']),
				'thumbnail_border_thickness' => sanitize_text_field($_POST['thumbnail_border_thickness']),
				'thumbnail_border_color' => sanitize_text_field($_POST['thumbnail_border_color']),
				'image_title' => sanitize_text_field($_POST['image_title']),
				'image_title_font_size' => sanitize_text_field($_POST['image_title_font_size']),
				'image_title_color' => sanitize_text_field($_POST['image_title_color']),
				'image_title_bg_color' => sanitize_text_field($_POST['image_title_bg_color']),
				'image_sorting' => sanitize_text_field($_POST['image_sorting']),
				'custom_css' => sanitize_text_field($_POST['custom_css']),

				//lightbox settings
				'lightbox' => sanitize_text_field($_POST['lightbox']),
				'lightbox_title' => sanitize_text_field($_POST['lightbox_title']),
			);
			update_option("ufg_settings_".$ufg_gallery_id, $settings);
			die;
		} else {
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_save_setting', 'ufg_save_setting_callback' );

/* 6. remove gallery/galleries start */
function ufg_remove_gallery_callback() {
	if ( current_user_can( 'manage_options' ) ) {
		if ( $_POST['nonce'] && wp_verify_nonce( $_POST['nonce'], 'ufg-remove-gallery' ) ) {
			/* verified action */
			if(isset($_POST['ufg_gallery_id']) && isset($_POST['do_action'])){
				
				$ufg_gallery_id = $_POST['ufg_gallery_id'];
				$ufg_do_action = sanitize_text_field($_POST['do_action']);

				/* single gallery delete. */
				if ( $ufg_do_action == 'single' ) {
					delete_option( 'ufg_filters_' . $ufg_gallery_id );
					delete_option( 'ufg_gallery_' . $ufg_gallery_id );
					delete_option( 'ufg_settings_' . $ufg_gallery_id );
				}

				/* multiple gallery delete. */
				if ( $ufg_do_action == 'multiple' ) {
					foreach ( $ufg_gallery_id as $ufg_single_id ) {
						delete_option( 'ufg_filters_' . $ufg_single_id );
						delete_option( 'ufg_gallery_' . $ufg_single_id );
						delete_option( 'ufg_settings_' . $ufg_single_id );
					}
				}
			}
			wp_die();
		} else {
			echo esc_html( 'Nonce not verified.' );
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_remove_gallery', 'ufg_remove_gallery_callback' );
/* 6. remove gallery/galleries end */

// 7. clone gallery start
function ufg_clone_gallery_callback(){
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'ufg-clone-gallery' ) ) {
			// verified action
			if(isset( $_POST['ufg_gallery_id'] ) && isset( $_POST['ufg_gallery_counter'] )){
				$ufg_gallery_id = sanitize_text_field($_POST['ufg_gallery_id']);
				$ufg_gallery_counter = sanitize_text_field($_POST['ufg_gallery_counter']);

				//get cloning gallery data
				$ufg_cloning_filters = get_option("ufg_filters_".$ufg_gallery_id);
				$ufg_cloning_gallery = get_option("ufg_gallery_".$ufg_gallery_id);
				$ufg_cloning_setting = get_option("ufg_settings_".$ufg_gallery_id);
				$ufg_cloning_details = get_option("ufg_details_".$ufg_gallery_id);
				
				//generate new gallery id for clone
				$new_ufg_gallery_edit_nonce = wp_create_nonce( 'edit-gallery' );
				$new_ufg_gallery_id = ufg_get_next_id();
				$new_ufg_gallery_name = sanitize_text_field($ufg_cloning_details['gallery_name'].' - Clone');
				
				// update clone id into gallery data
				foreach($ufg_cloning_gallery as $key => $value){
					$ufg_cloning_setting['ufg_gallery_id'] = sanitize_text_field($new_ufg_gallery_id);
				}
				
				// update gallery details
				$ufg_cloning_details = array('ufg_gallery_id' => $ufg_gallery_id, 'gallery_name' => $new_ufg_gallery_name);
				
				if($new_ufg_gallery_id > $ufg_gallery_id){
					add_option('ufg_filters_'.$new_ufg_gallery_id, $ufg_cloning_filters);
					add_option('ufg_gallery_'.$new_ufg_gallery_id, $ufg_cloning_gallery);
					add_option('ufg_settings_'.$new_ufg_gallery_id, $ufg_cloning_setting);
					update_option('ufg_details_'.$new_ufg_gallery_id, $ufg_cloning_details);
					$ufg_do_action = "'single'";
					
					echo ('
					<tr id='.esc_attr( $new_ufg_gallery_id ).'>
						<th scope="row">'.esc_attr( $ufg_gallery_counter ).'</th>
						<td>'.esc_attr( $new_ufg_gallery_name ).'</td>
						<td>
							<input type="text" id="ufg-shortcode-'.esc_attr( $new_ufg_gallery_id ).'" class="btn btn-info btn-sm" value="[ufg id='.esc_attr($new_ufg_gallery_id).']">
							<button type="button" id="ufg-shortcode-'.esc_attr($new_ufg_gallery_id).'" class="btn btn-info btn-sm" title="Click To Copy Gallery Shortcode" onclick="return UFGCopyShortcode('.esc_attr($new_ufg_gallery_id).')">Copy</button>
							<button class="btn btn-sm btn-success d-none ufg-copied-'.esc_attr($new_ufg_gallery_id).'">Copied</button>
						</td>
						<td>
							<button type="button" id="ufg-clone" class="btn btn-warning btn-sm" title="Clone Gallery" value="'.esc_attr($new_ufg_gallery_id).'" onclick="return UFGCloneGallery('.esc_attr($new_ufg_gallery_id).', '.esc_attr($ufg_gallery_counter).');"><i class="fas fa-copy"></i></button>
							<a href="?page=ufg-manage-gallery&id='.esc_attr($new_ufg_gallery_id).'&ufg-nonce='.esc_attr($new_ufg_gallery_edit_nonce).'" class="btn btn-primary btn-sm" href="#"><i class="fas fa-edit"></i></a>
							<button id="ufg-delete-gallery" class="btn btn-danger btn-sm" title="Delete Gallery" value="'.esc_attr($new_ufg_gallery_id).'" onclick="return UFGRemoveGallery('.esc_attr($new_ufg_gallery_id).', '.esc_attr($ufg_do_action).');"><i class="fas fa-trash-alt"></i></button>
						</td>
						<td class="text-center">
							<input type="checkbox" id="ufg-gallery-id" name="ufg-gallery-id" value="'.esc_attr($new_ufg_gallery_id).'" title="Select Gallery">
						</td>
					</tr>
					');
				}
			}
			wp_die();
		} else {
			echo esc_html("Nonce not verified action.");
			die;
		}
	}
}
add_action( 'wp_ajax_ufg_clone_gallery', 'ufg_clone_gallery_callback' );
// 7. clone gallery end

// register sf scripts
function ufg_register_scripts(){
	wp_enqueue_script('jquery');
	wp_register_style( 'ufg-bootstrap-frontend-css', plugin_dir_url(__FILE__).'admin/assets/bootstrap-4.6.0/css/ufg-bootstrap-frontend-min.css');
	wp_register_style( 'ufg-fontawesome-css', plugin_dir_url(__FILE__). 'admin/assets/fontawesome-free-5.3.1-web/css/all.min.css');
	//lightbox JS and CSS
	wp_register_style( 'ufg-lightbox-css', plugin_dir_url(__FILE__). 'admin/assets/lightbox/lokesh/css/lightbox.css');
	wp_register_script( 'ufg-lightbox-js', plugin_dir_url(__FILE__). 'admin/assets/lightbox/lokesh/js/lightbox.js', array('jquery'), '4.5.2' );
}
add_action( 'wp_enqueue_scripts', 'ufg_register_scripts' );

include('shortcode.php');

// Gallery Text Widget Support
add_filter( 'widget_text', 'do_shortcode' );