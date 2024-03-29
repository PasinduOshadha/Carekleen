<?php

class Kadence_Blocks_Dynamic_Content_Controller extends WP_REST_Controller {

	/**
	 * Query property name.
	 */
	const PROP_SOURCE = 'source';

	/**
	 * Type property name.
	 */
	const PROP_ORIGIN = 'origin';

	/**
	 * Query property name.
	 */
	const PROP_GROUP = 'group';

	/**
	 * Query property name.
	 */
	const PROP_RELATE = 'relate';

	/**
	 * Query property name.
	 */
	const PROP_RELCUSTOM = 'relcustom';
	/**
	 * Query property name.
	 */
	const PROP_FIELD = 'field';
	/**
	 * Query property name.
	 */
	const PROP_CUSTOM = 'custom';

	/**
	 * Query property name.
	 */
	const PROP_PARA = 'para';

	/**
	 * Query property name.
	 */
	const PROP_FORCE_STRING = 'force_string';

	/**
	 * Query property name.
	 */
	const PROP_BEFORE = 'before';

	/**
	 * Query property name.
	 */
	const PROP_AFTER = 'after';

	/**
	 * Query property name.
	 */
	const PROP_FALLBACK = 'fallback';

	/**
	 * Type property name.
	 */
	const PROP_CURRENT = 'current';

	/**
	 * Query property name.
	 */
	const PROP_TYPE = 'type';

	const POST_GROUP = 'post';

	const ARCHIVE_GROUP = 'archive';

	const AUTHOR_GROUP = 'author';

	const RELATIONSHIP_GROUP = 'relationship';

	const SITE_GROUP = 'site';

	const COMMENTS_GROUP = 'comments';

	const MEDIA_GROUP = 'media';

	const OTHER_GROUP = 'other';

	const TEXT_CATEGORY = 'text';

	const NUMBER_CATEGORY = 'number';

	const IMAGE_CATEGORY = 'image';

	const DATE_CATEGORY = 'date';

	const AUDIO_CATEGORY = 'audio';

	const VIDEO_CATEGORY = 'video';

	const URL_CATEGORY = 'url';

	const HTML_CATEGORY = 'html';

	const EMBED_CATEGORY = 'embed';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->namespace = 'kbp-dynamic/v1';
		$this->base = 'render';
		$this->background_image_base = 'image_render';
		$this->image_base = 'image_data';
		$this->gallery_base = 'gallery_data';
		$this->custom_fields = 'custom_fields';
		$this->link_label = 'link_label';
		$this->list_base = 'list_data';
		$this->html_base = 'html_data';
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_render_content' ),
					'permission_callback' => array( $this, 'get_permission_check' ),
					'args'                => $this->get_render_params(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->background_image_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_background_image_content' ),
					'permission_callback' => array( $this, 'get_permission_check' ),
					'args'                => $this->get_render_params(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->image_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_image_content' ),
					'permission_callback' => array( $this, 'get_permission_check' ),
					'args'                => $this->get_render_params(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->gallery_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_gallery_content' ),
					'permission_callback' => array( $this, 'get_permission_check' ),
					'args'                => $this->get_render_params(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->custom_fields,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_custom_fields' ),
					'permission_callback' => array( $this, 'get_permission_check' ),
					'args'                => $this->get_render_params(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->link_label,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_link_label' ),
					'permission_callback' => array( $this, 'get_permission_check' ),
					'args'                => $this->get_render_params(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->list_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_list_content' ),
					'permission_callback' => array( $this, 'get_permission_check' ),
					'args'                => $this->get_render_params(),
				),
			)
		);
		register_rest_route(
			$this->namespace,
			'/' . $this->html_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_html_content' ),
					'permission_callback' => array( $this, 'get_permission_check' ),
					'args'                => $this->get_render_params(),
				),
			)
		);
		// register_rest_route(
		// 	$this->namespace,
		// 	'/' . $this->image_base,
		// 	array(
		// 		array(
		// 			'methods'             => WP_REST_Server::READABLE,
		// 			'callback'            => array( $this, 'get_image_items' ),
		// 			'permission_callback' => array( $this, 'get_items_permission_check' ),
		// 			'args'                => $this->get_image_params(),
		// 		),
		// 	)
		// );
	}
	/**
	 * Checks if a given request has access to search content.
	 *
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has search access, WP_Error object otherwise.
	 */
	public function get_permission_check( $request ) {
		return current_user_can( 'edit_posts' );
	}
	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_render_content( $request ) {
		$source        = $request->get_param( self::PROP_SOURCE );
		$group         = $request->get_param( self::PROP_GROUP );
		$origin        = $request->get_param( self::PROP_ORIGIN );
		$field         = $request->get_param( self::PROP_FIELD );
		$custom        = $request->get_param( self::PROP_CUSTOM );
		$para          = $request->get_param( self::PROP_PARA );
		$force_string  = $request->get_param( self::PROP_FORCE_STRING );
		$before        = $request->get_param( self::PROP_BEFORE );
		$after         = $request->get_param( self::PROP_AFTER );
		$fallback      = $request->get_param( self::PROP_FALLBACK );
		$relate        = $request->get_param( self::PROP_RELATE );
		$relcustom     = $request->get_param( self::PROP_RELCUSTOM );
		if ( empty( $field ) ) {
			return rest_ensure_response( esc_html__( 'No Content', 'kadence-blocks-pro' ) );
		}
		$field_split = explode( '|', $field, 2 );
		if ( isset( $field_split[0] ) && isset( $field_split[1] ) ) {
			$args = array(
				'source'    => ( $source ? $source : 'current' ),
				'type'  => 'text',
				'field' => $field_split[1],
				'group'    => $field_split[0],
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		} else {
			$args = array(
				'source'    => ( $source ? $source : 'current' ),
				'type'  => 'text',
				'field' => $field,
				'group'    => 'post',
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		}
		$dynamic_class = Kadence_Blocks_Pro_Dynamic_Content::get_instance();
		$response      = $dynamic_class->get_content( $args );
		return rest_ensure_response( $response );
	}
	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_background_image_content( $request ) {
		$source        = $request->get_param( self::PROP_SOURCE );
		$group         = $request->get_param( self::PROP_GROUP );
		$origin        = $request->get_param( self::PROP_ORIGIN );
		$field         = $request->get_param( self::PROP_FIELD );
		$custom        = $request->get_param( self::PROP_CUSTOM );
		$para          = $request->get_param( self::PROP_PARA );
		$force_string  = $request->get_param( self::PROP_FORCE_STRING );
		$before        = $request->get_param( self::PROP_BEFORE );
		$after         = $request->get_param( self::PROP_AFTER );
		$fallback      = $request->get_param( self::PROP_FALLBACK );
		$relate        = $request->get_param( self::PROP_RELATE );
		$relcustom     = $request->get_param( self::PROP_RELCUSTOM );
		if ( empty( $field ) ) {
			return '';
		}
		$field_split = explode( '|', $field, 2 );
		if ( isset( $field_split[0] ) && isset( $field_split[1] ) ) {
			$args = array(
				'source'   => ( $source ? $source : 'current' ),
				'type'     => 'background',
				'field'    => $field_split[1],
				'group'    => $field_split[0],
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		} else {
			$args = array(
				'source'   => ( $source ? $source : 'current' ),
				'type'     => 'background',
				'field'    => $field,
				'group'    => 'post',
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		}
		$dynamic_class = Kadence_Blocks_Pro_Dynamic_Content::get_instance();
		$response      = $dynamic_class->get_content( $args );
		if ( is_array( $response ) ) {
			$response = $response[0];
		}
		return rest_ensure_response( $response );
	}
	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_image_content( $request ) {
		$source        = $request->get_param( self::PROP_SOURCE );
		$group         = $request->get_param( self::PROP_GROUP );
		$origin        = $request->get_param( self::PROP_ORIGIN );
		$field         = $request->get_param( self::PROP_FIELD );
		$custom        = $request->get_param( self::PROP_CUSTOM );
		$para          = $request->get_param( self::PROP_PARA );
		$force_string  = $request->get_param( self::PROP_FORCE_STRING );
		$before        = $request->get_param( self::PROP_BEFORE );
		$after         = $request->get_param( self::PROP_AFTER );
		$fallback      = $request->get_param( self::PROP_FALLBACK );
		$relate        = $request->get_param( self::PROP_RELATE );
		$relcustom     = $request->get_param( self::PROP_RELCUSTOM );
		if ( empty( $field ) ) {
			return '';
		}
		$field_split = explode( '|', $field, 2 );
		if ( isset( $field_split[0] ) && isset( $field_split[1] ) ) {
			$args = array(
				'source'   => ( $source ? $source : 'current' ),
				'type'     => 'image',
				'field'    => $field_split[1],
				'group'    => $field_split[0],
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		} else {
			$args = array(
				'source'   => ( $source ? $source : 'current' ),
				'type'     => 'image',
				'field'    => $field,
				'group'    => 'post',
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		}
		$dynamic_class = Kadence_Blocks_Pro_Dynamic_Content::get_instance();
		$response      = $dynamic_class->get_content( $args );
		return rest_ensure_response( $response );
	}
	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_list_content( $request ) {
		$source        = $request->get_param( self::PROP_SOURCE );
		$group         = $request->get_param( self::PROP_GROUP );
		$origin        = $request->get_param( self::PROP_ORIGIN );
		$field         = $request->get_param( self::PROP_FIELD );
		$custom        = $request->get_param( self::PROP_CUSTOM );
		$para          = $request->get_param( self::PROP_PARA );
		$force_string  = $request->get_param( self::PROP_FORCE_STRING );
		$before        = $request->get_param( self::PROP_BEFORE );
		$after         = $request->get_param( self::PROP_AFTER );
		$fallback      = $request->get_param( self::PROP_FALLBACK );
		$relate        = $request->get_param( self::PROP_RELATE );
		$relcustom     = $request->get_param( self::PROP_RELCUSTOM );
		if ( empty( $field ) ) {
			return '';
		}
		$field_split = explode( '|', $field, 2 );
		if ( isset( $field_split[0] ) && isset( $field_split[1] ) ) {
			$args = array(
				'source'   => ( $source ? $source : 'current' ),
				'type'     => 'list',
				'field'    => $field_split[1],
				'group'    => $field_split[0],
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		} else {
			$args = array(
				'source'   => ( $source ? $source : 'current' ),
				'type'     => 'list',
				'field'    => $field,
				'group'    => 'post',
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		}
		$dynamic_class = Kadence_Blocks_Pro_Dynamic_Content::get_instance();
		$response      = $dynamic_class->get_content( $args );
		return rest_ensure_response( $response );
	}
	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_html_content( $request ) {
		$source        = $request->get_param( self::PROP_SOURCE );
		$group         = $request->get_param( self::PROP_GROUP );
		$origin        = $request->get_param( self::PROP_ORIGIN );
		$field         = $request->get_param( self::PROP_FIELD );
		$custom        = $request->get_param( self::PROP_CUSTOM );
		$para          = $request->get_param( self::PROP_PARA );
		$force_string  = $request->get_param( self::PROP_FORCE_STRING );
		$before        = $request->get_param( self::PROP_BEFORE );
		$after         = $request->get_param( self::PROP_AFTER );
		$fallback      = $request->get_param( self::PROP_FALLBACK );
		$relate        = $request->get_param( self::PROP_RELATE );
		$relcustom     = $request->get_param( self::PROP_RELCUSTOM );
		if ( empty( $field ) ) {
			return '';
		}
		if ( 'post|post_content' === $field ) {
			$response = array();
			$args = array(
				'source'   => ( $source ? $source : 'current' ),
				'type'     => 'html',
				'field'    => 'post_id',
				'group'    => 'post',
				'before'   => $before,
				'after'    => $after,
				'fallback' => $fallback,
				'para'     => $para,
				'custom'   => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
			$dynamic_class         = Kadence_Blocks_Pro_Dynamic_Content::get_instance();
			$response['content']   = 'post_content';
			$response['post_id']   = $dynamic_class->get_content( $args );
			$args['field']         = 'post_type';
			$response['post_type'] = $dynamic_class->get_content( $args );
		} else {
			$field_split = explode( '|', $field, 2 );
			if ( isset( $field_split[0] ) && isset( $field_split[1] ) ) {
				$args = array(
					'source'   => ( $source ? $source : 'current' ),
					'type'     => 'html',
					'field'    => $field_split[1],
					'group'    => $field_split[0],
					'before'   => $before,
					'after'    => $after,
					'fallback' => $fallback,
					'para'     => $para,
					'custom'   => $custom,
					'relate'    => $relate,
					'relcustom' => $relcustom,
				);
			} else {
				$args = array(
					'source'   => ( $source ? $source : 'current' ),
					'type'     => 'html',
					'field'    => $field,
					'group'    => 'post',
					'before'   => $before,
					'after'    => $after,
					'fallback' => $fallback,
					'para'     => $para,
					'custom'   => $custom,
					'relate'    => $relate,
					'relcustom' => $relcustom,
				);
			}
			$dynamic_class = Kadence_Blocks_Pro_Dynamic_Content::get_instance();
			$response      = $dynamic_class->get_content( $args );
		}
		return rest_ensure_response( $response );
	}
	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_gallery_content( $request ) {
		$source        = $request->get_param( self::PROP_SOURCE );
		$group         = $request->get_param( self::PROP_GROUP );
		$origin        = $request->get_param( self::PROP_ORIGIN );
		$field         = $request->get_param( self::PROP_FIELD );
		$custom        = $request->get_param( self::PROP_CUSTOM );
		$para          = $request->get_param( self::PROP_PARA );
		$force_string  = $request->get_param( self::PROP_FORCE_STRING );
		$before        = $request->get_param( self::PROP_BEFORE );
		$after         = $request->get_param( self::PROP_AFTER );
		$fallback      = $request->get_param( self::PROP_FALLBACK );
		$relate        = $request->get_param( self::PROP_RELATE );
		$relcustom     = $request->get_param( self::PROP_RELCUSTOM );
		if ( empty( $field ) ) {
			return '';
		}
		$field_split = explode( '|', $field, 2 );
		if ( isset( $field_split[0] ) && isset( $field_split[1] ) ) {
			$args = array(
				'source'    => ( $source ? $source : 'current' ),
				'type'      => 'gallery',
				'field'     => $field_split[1],
				'group'     => $field_split[0],
				'before'    => $before,
				'after'     => $after,
				'fallback'  => $fallback,
				'para'      => $para,
				'custom'    => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		} else {
			$args = array(
				'source'    => ( $source ? $source : 'current' ),
				'type'      => 'gallery',
				'field'     => $field,
				'group'     => 'post',
				'before'    => $before,
				'after'     => $after,
				'fallback'  => $fallback,
				'para'      => $para,
				'custom'    => $custom,
				'relate'    => $relate,
				'relcustom' => $relcustom,
			);
		}
		$dynamic_class = Kadence_Blocks_Pro_Dynamic_Content::get_instance();
		$response      = $dynamic_class->get_content( $args );
		return rest_ensure_response( $response );
	}
	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_link_label( $request ) {
		$source        = $request->get_param( self::PROP_SOURCE );
		$group         = $request->get_param( self::PROP_GROUP );
		$current       = $request->get_param( self::PROP_CURRENT );
		$field         = $request->get_param( self::PROP_FIELD );
		$custom        = $request->get_param( self::PROP_CUSTOM );
		$para          = $request->get_param( self::PROP_PARA );
		$force_string  = $request->get_param( self::PROP_FORCE_STRING );
		$before        = $request->get_param( self::PROP_BEFORE );
		$after         = $request->get_param( self::PROP_AFTER );
		$fallback      = $request->get_param( self::PROP_FALLBACK );
		$relate        = $request->get_param( self::PROP_RELATE );
		$relcustom     = $request->get_param( self::PROP_RELCUSTOM );
		if ( empty( $field ) ) {
			return rest_ensure_response( esc_html__( 'No Link', 'kadence-blocks-pro' ) );
		}
		$response = esc_html__( 'No Link', 'kadence-blocks-pro' );
		$field_split = explode( '|', $field, 2 );
		if ( isset( $field_split[0] ) && isset( $field_split[1] ) ) {
			$response = $this->get_field_link_label( ( $source ? $source : 'current' ), $field_split[0], $field_split[1], $para, $custom, $current, $relate, $relcustom );
		}
		return rest_ensure_response( $response );
	}
	/**
	 * Get the content output.
	 *
	 * @param object $post the post.
	 * @param string $source the source for the content.
	 * @param string $group the group of the content.
	 * @param string $field the field of the content.
	 * @param string $para the para of the content.
	 * @param string $custom the custom of the content.
	 * @param string $current the current of the content.
	 */
	public function get_field_link_label( $source, $group, $field, $para, $custom, $current, $relate, $relcustom ) {
		// Render Core.
		if ( self::RELATIONSHIP_GROUP === $group ) {
			$output = ' | ';
			if ( 'current' === $current ) {
				$output .= 'Current Post';
			} else {
				$source = intval( $source );
				$output .= get_the_title( $source );
			}
			$output .= ' | Relationship';
			// $new_source = '';
			// if ( ! empty( $relate ) ) {
			// 	if ( 'kb_custom_input' === $relate ) {
			// 		if ( ! empty( $relcustom ) ) {
			// 			$output = get_post_meta( $post->ID, $relcustom, true );
			// 		}
			// 	} else if ( strpos( $relate, '|' ) !== false ) {
			// 		list( $meta_type, $actual_key ) = explode( '|', $relate );
			// 		switch ( $meta_type ) {
			// 			case 'mb_meta':
			// 			case 'mb_option':
			// 				$new_source = kbp_dynamic_content_metabox( $actual_key, $meta_type, $type, $post->ID, $args );
			// 				break;
			// 			case 'acf_meta':
			// 			case 'acf_option':
			// 				$new_source = kbp_dynamic_content_acf( $actual_key, $meta_type, $type, $post->ID, $args );
			// 				break;
			// 		}
			// 	} else {
			// 		$new_source = get_post_meta( $post->ID, $para, true );
			// 	}
			// 	if ( ! empty( absint( $new_source ) ) ) {
			// 		$group   = self::POST_GROUP;
			// 		$source  = absint( $new_source );
			// 	}
			// }
		}
		if ( self::POST_GROUP === $group ) {
			$output = ' | ';
			if ( 'current' === $current ) {
				$output .= 'Current Post';
			} else {
				$source = intval( $source );
				$output .= get_the_title( $source );
			}
			switch ( $field ) {
				case 'post_url':
					$output = __( 'Post URL', 'kadence-blocks-pro' ) . $output;
					break;
				case 'post_custom_field':
					if ( ! empty( $para ) ) {
						if ( 'kb_custom_input' === $para ) {
							if ( ! empty( $custom ) ) {
								$output = $custom . $output;
							}
						} else if ( strpos( $para, '|' ) !== false ) {
							list( $meta_type, $actual_key ) = explode( '|', $para );
							switch ( $meta_type ) {
								case 'mb_meta':
								case 'mb_option':
									$output = $actual_key . $output;
									break;
								case 'acf_meta':
									if ( function_exists( 'get_field_object' ) ) {
										$field_object = get_field_object( $actual_key, $source );
										$output = $field_object['label'] . $output;
									}
									break;
							}
						} else {
							$output = $para . $output;
						}
					}
					break;
				case 'post_featured_image_url':
					$output = __( 'Featured Image URL', 'kadence-blocks-pro' ) . $output;
					break;
				default:
					$output = apply_filters( "kadence_dynamic_link_display_label_{$field}", '', $source, $group, $field, $para, $custom );
					break;
			}
		} elseif ( self::ARCHIVE_GROUP === $group ) {
			$output = ' | ';
			if ( 'current' === $source || '' === $source ) {
				$output .= 'Current Archive';
			} else {
				$source = intval( $source );
				$output .= get_the_archive_title( $source );
			}
			switch ( $field ) {
				case 'archive_url':
					$output = __( 'Archive URL', 'kadence-blocks-pro' ) . $output;
					break;
				case 'archive_custom_field':
					if ( ! empty( $para ) ) {
						if ( 'kb_custom_input' === $para ) {
							if ( ! empty( $custom ) ) {
								$output = $custom . $output;
							}
						} else if ( strpos( $para, '|' ) !== false ) {
							list( $meta_type, $actual_key ) = explode( '|', $para );
							switch ( $meta_type ) {
								case 'mb_meta':
								case 'mb_option':
									$output = $actual_key . $output;
									break;
								case 'acf_meta':
									$output = $actual_key . $output;
									break;
							}
						} else {
							$output = $para . $output;
						}
					}
					break;
				default:
					$output = apply_filters( "kadence_dynamic_link_display_label_{$field}", '', $source, $group, $field, $para, $custom );
					break;
			}
		} elseif ( self::SITE_GROUP === $group ) {
			switch ( $field ) {
				case 'site_url':
					$output = __( 'Site URL', 'kadence-blocks-pro' );
					break;
				case 'user_info':
					$output = __( 'User Info', 'kadence-blocks-pro' );
					$user = wp_get_current_user();
					if ( 0 === $user->ID ) {
						$output .= '';
						break;
					}
					if ( empty( $custom ) ) {
						$output .= '';
						break;
					}
					switch ( $custom ) {
						case 'email':
							$output .= isset( $user->user_email ) ? $user->user_email : '';
							break;
						case 'website':
							$output .= isset( $user->user_url ) ? $user->user_url : '';
							break;
						case 'meta':
							if ( ! empty( $para ) ) {
								$output .= $para;
							}
							break;
					}
					break;
				default:
					$output .= '';
					break;
			}
		}
		return apply_filters( 'kadence_dynamic_link_display_label', $output, $source, $group, $field, $para, $custom );
	}
	/**
	 * Retrieves the query params for the search results collection.
	 *
	 * @return array Collection parameters.
	 */
	public function get_render_params() {
		$query_params  = parent::get_collection_params();
		$query_params[ self::PROP_ORIGIN ] = array(
			'description' => __( 'The origin of content.', 'kadence-blocks-pro' ),
			'type' => 'string',
			'default' => 'core',
		);

		$query_params[ self::PROP_SOURCE ] = array(
			'description' => __( 'The source of the content.', 'kadence-blocks-pro' ),
			'type'        => 'string',
			'default'     => 'current',
		);

		$query_params[ self::PROP_GROUP ] = array(
			'description' => __( 'The group for source.', 'kadence-blocks-pro' ),
			'type'        => 'string',
			'default' => 'post',
		);

		$query_params[ self::PROP_FIELD ] = array(
			'description' => __( 'The dynamic field', 'kadence-blocks-pro' ),
			'type'        => 'string',
		);

		$query_params[ self::PROP_CUSTOM ] = array(
			'description' => __( 'The custom field setting.', 'kadence-blocks-pro' ),
			'type'        => 'string',
		);
		$query_params[ self::PROP_PARA ] = array(
			'description' => __( 'The custom field Key.', 'kadence-blocks-pro' ),
			'type'        => 'string',
		);
		$query_params[ self::PROP_FORCE_STRING ] = array(
			'description' => __( 'For a string return', 'kadence-blocks-pro' ),
			'type'        => 'boolean',
			'default'     => false,
		);
		$query_params[ self::PROP_BEFORE ] = array(
			'description' => __( 'Text Before Item.', 'kadence-blocks-pro' ),
			'type'        => 'string',
		);
		$query_params[ self::PROP_AFTER ] = array(
			'description' => __( 'Text After Item.', 'kadence-blocks-pro' ),
			'type'        => 'string',
		);
		$query_params[ self::PROP_FALLBACK ] = array(
			'description' => __( 'Fallback.', 'kadence-blocks-pro' ),
			'type'        => 'string',
		);
		$query_params[ self::PROP_CURRENT ] = array(
			'description' => __( 'If the content is current.', 'kadence-blocks-pro' ),
			'type' => 'string',
		);
		$query_params[ self::PROP_RELCUSTOM ] = array(
			'description' => __( 'The custom field setting.', 'kadence-blocks-pro' ),
			'type'        => 'string',
		);
		$query_params[ self::PROP_RELATE ] = array(
			'description' => __( 'The custom field Key.', 'kadence-blocks-pro' ),
			'type'        => 'string',
		);
		return $query_params;
	}
	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_custom_fields( $request ) {
		$source        = $request->get_param( self::PROP_SOURCE );
		$group         = $request->get_param( self::PROP_GROUP );
		$origin        = $request->get_param( self::PROP_ORIGIN );
		$field         = $request->get_param( self::PROP_FIELD );
		$custom        = $request->get_param( self::PROP_CUSTOM );
		$para          = $request->get_param( self::PROP_PARA );
		$force_string  = $request->get_param( self::PROP_FORCE_STRING );
		$before        = $request->get_param( self::PROP_BEFORE );
		$after         = $request->get_param( self::PROP_AFTER );
		$fallback      = $request->get_param( self::PROP_FALLBACK );
		$type          = $request->get_param( self::PROP_TYPE );
		$relate        = $request->get_param( self::PROP_RELATE );
		$relcustom     = $request->get_param( self::PROP_RELCUSTOM );
		$field_split   = explode( '|', $field, 2 );
		$meta_group    = isset( $field_split[0] ) && ! empty( $field_split[0] ) ? $field_split[0] : 'post';
		if ( empty( $type ) ) {
			$type = 'text';
		}
		if ( 'image' === $type ) {
			$types = array(
				'image',
				// Meta box types.
				'single_image',
			);
		} elseif ( 'background' === $type ) {
			$types = array(
				'image',
				// Meta box types.
				'single_image',
			);
		} elseif ( 'gallery' === $type ) {
			$types = array(
				'gallery',
				// Meta box types.
				'image_advanced',
			);
		} elseif ( 'list' === $type ) {
			$types = array(
				'select',
				// ACF types.
				'checkbox',
				// Meta box types.
				'checkbox_list',
				'text_list',
				'select_advanced',
				'button_group',
				'key_value',
			);
		} elseif ( 'relationship' === $type ) {
			$types = array(
				// Meta box types.
				'post',
				// ACF types.
				'relationship',
				'post_object',
			);
		} elseif ( 'html' === $type ) {
			$types = array(
				'text',
				'textarea',
				'number',
				'range',
				'email',
				'url',
				'password',
				'wysiwyg',
				'oembed',
				'select',
				'date_picker',
				'time_picker',
				'date_time_picker',
			);
		} elseif ( 'url' === $type ) {
			$types = array(
				'text',
				'email',
				'image',
				'file',
				'page_link',
				'url',
				'link',
			);
		} else {
			$types = array(
				'text',
				'textarea',
				'number',
				'range',
				'email',
				'url',
				'password',
				'wysiwyg',
				'select',
				'checkbox',
				'radio',
				'true_false',
				//'oembed',
				//'google_map',
				'date_picker',
				'time_picker',
				'date_time_picker',
				'color_picker',
				'post',
			);
		}
		$options = array();
		$already_captured = array();
		// Get Meta Box.
		if ( function_exists( 'rwmb_get_registry' ) ) {
			$meta_box_registry = rwmb_get_registry( 'meta_box' );
			if ( 'post' === $meta_group || 'relationship' === $meta_group ) {
				$setting_meta_box = rwmb_get_registry( 'field' )->get_by_object_type( 'setting' );
				if ( ! empty( $setting_meta_box ) && is_array( $setting_meta_box ) ) {
					foreach ( $setting_meta_box as $setting_ky => $setting_fields ) {
						if ( ! empty( $setting_fields ) && is_array( $setting_fields ) ) {
							foreach ( $setting_fields as $field_ky => $field ) {
								if ( empty( $field['id'] ) ) {
									continue;
								}
								$already_captured[] = $field['id'];
								if ( ! in_array( $field['type'], $types, true ) ) {
									continue;
								}
								$field_key = 'mb_option|' . $setting_ky . ':' . $field['id'];
								$smb_options[] = array(
									'value' => $field_key,
									'label' => __( 'Site Option:', 'kadence-blocks-pro' ) . ' ' . $field['name'],
								);
							}
						}
					}
					if ( ! empty( $smb_options ) ) {
						$options[] = array(
							'label'   =>  __( 'Setting:', 'kadence-blocks-pro' ) . ' ' . $setting_ky,
							'options' => $smb_options,
						);
					}
				}
			}
			$meta_boxes = $meta_box_registry->all();
			if ( ! empty( $meta_boxes ) && is_array( $meta_boxes ) ) {
				// Loop through each metabox.
				foreach ( $meta_boxes as $mb_key => $mb_group ) {
					$mb_type = $mb_group->get_object_type();
					if ( empty( $mb_type ) ) {
						continue;
					}
					if ( ! empty( $mb_group->meta_box ) && is_array( $mb_group->meta_box ) && ! empty( $mb_group->meta_box['fields'] ) && is_array( $mb_group->meta_box['fields'] ) ) {
						$send_back_group = false;
						if ( 'archive' === $meta_group && ( 'term' === $mb_type ) ) {
							$send_back_group = true;
						} elseif ( 'author' === $meta_group && ( 'user' === $mb_type ) ) {
							$send_back_group = true;
						} elseif ( ( 'post' === $meta_group || 'relationship' === $meta_group ) && ( 'post' === $mb_type ) ) {
							$send_back_group = true;
						} elseif ( 'media' === $meta_group && 'post' === $mb_type ) {
							foreach ( $mb_group->meta_box['post_types'] as $ptype_ky => $ptype ) {
								if ( 'attachment' === $ptype ) {
									$send_back_group = true;
									continue;
								}
							}
						} elseif ( 'user' === $meta_group && ( 'user' === $mb_type ) ) {
							$send_back_group = true;
						}
						if ( ! $send_back_group ) {
							continue;
						}
						$mb_options = array();
						foreach ( $mb_group->meta_box['fields'] as $field_ky => $field ) {
							if ( empty( $field['id'] ) ) {
								continue;
							}
							$already_captured[] = $field['id'];
							if ( ! in_array( $field['type'], $types, true ) ) {
								continue;
							}
							$field_key = 'mb_meta|' . $field['id'];
							$mb_options[] = array(
								'value' => $field_key,
								'label' => ( $field['name'] ? $field['name'] : $field_key ),
							);
						}
						if ( ! empty( $mb_options ) ) {
							$options[] = array(
								'label'   => ( ! empty( $mb_group->meta_box['title'] ) ? $mb_group->meta_box['title'] : __( 'Meta Box', 'kadence-blocks-pro' ) ),
								'options' => $mb_options,
							);
						}
					}
				}
			}
		}
		// GET ACF.
		if ( class_exists( 'ACF' ) && function_exists( 'acf_get_field_groups' ) ) {
			$acf_groups = acf_get_field_groups();
			$options_pages_group_ids = array();
			// Make sure there are some groups.
			if ( $acf_groups ) {
				// Create an array of Site wide Options pages groups.
				if ( function_exists( 'acf_options_page' ) ) {
					$options_pages = acf_options_page()->get_pages();
					foreach ( $options_pages as $slug => $page ) {
						$options_page_groups = acf_get_field_groups( array( 'options_page' => $slug ) );
						foreach ( $options_page_groups as $options_page_group ) {
							$options_pages_group_ids[] = $options_page_group['ID'];
						}
					}
				}
				// Loop through each group.
				foreach ( $acf_groups as $acf_group ) {
					// Lets check for location taxonomy.
					if ( 'archive' === $meta_group ) {
						$send_back_group = false;
						if ( isset( $acf_group['location'] ) && is_array( $acf_group['location'] ) ) {
							foreach ( $acf_group['location'] as $sub_location_key => $sub_locations ) {
								if ( isset( $sub_locations ) && is_array( $sub_locations ) ) {
									foreach ( $sub_locations as $location_key => $location ) {
										if ( isset( $location ) && is_array( $location ) ) {
											if ( isset( $location['param'] ) && 'taxonomy' === $location['param'] ) {
												$send_back_group = true;
											}
										}
									}
								}
							}
						}
						if ( ! $send_back_group ) {
							continue;
						}
					} elseif ( 'author' === $meta_group ) {
						$send_back_group = false;
						$only_these_fields = array(
							'current_user',
							'current_user_role',
							'user_form',
							'user_role',
						);
						if ( isset( $acf_group['location'] ) && is_array( $acf_group['location'] ) ) {
							foreach ( $acf_group['location'] as $sub_location_key => $sub_locations ) {
								if ( isset( $sub_locations ) && is_array( $sub_locations ) ) {
									foreach ( $sub_locations as $location_key => $location ) {
										if ( isset( $location ) && is_array( $location ) ) {
											if ( isset( $location['param'] ) && in_array( $location['param'], $only_these_fields, true ) ) {
												$send_back_group = true;
											}
										}
									}
								}
							}
						}
						if ( ! $send_back_group ) {
							continue;
						}
					} elseif ( 'post' === $meta_group || 'relationship' === $meta_group ) {
						$send_back_group  = false;
						$not_these_fields = array(
							'attachment',
							'taxonomy',
							'comment',
							'widget',
							'nav_menu',
							'nav_menu_item',
							'current_user',
							'current_user_role',
							'user_form',
							'user_role',
						);
						if ( isset( $acf_group['location'] ) && is_array( $acf_group['location'] ) ) {
							foreach ( $acf_group['location'] as $sub_location_key => $sub_locations ) {
								if ( isset( $sub_locations ) && is_array( $sub_locations ) ) {
									foreach ( $sub_locations as $location_key => $location ) {
										if ( isset( $location ) && is_array( $location ) ) {
											if ( isset( $location['param'] ) && ! in_array( $location['param'], $not_these_fields, false ) ) {
												$send_back_group = true;
											}
										}
									}
								}
							}
						}
						if ( ! $send_back_group ) {
							continue;
						}
					} elseif ( 'media' === $meta_group ) {
						$send_back_group = false;
						$only_these_fields = array(
							'attachment',
						);
						if ( isset( $acf_group['location'] ) && is_array( $acf_group['location'] ) ) {
							foreach ( $acf_group['location'] as $sub_location_key => $sub_locations ) {
								if ( isset( $sub_locations ) && is_array( $sub_locations ) ) {
									foreach ( $sub_locations as $location_key => $location ) {
										if ( isset( $location ) && is_array( $location ) ) {
											if ( isset( $location['param'] ) && in_array( $location['param'], $only_these_fields, true ) ) {
												$send_back_group = true;
											}
										}
									}
								}
							}
						}
						if ( ! $send_back_group ) {
							continue;
						}
					} elseif ( 'user' === $meta_group ) {
						$send_back_group  = false;
						$only_these_fields = array(
							'current_user',
							'current_user_role',
							'user_form',
							'user_role',
						);
						if ( isset( $acf_group['location'] ) && is_array( $acf_group['location'] ) ) {
							foreach ( $acf_group['location'] as $sub_location_key => $sub_locations ) {
								if ( isset( $sub_locations ) && is_array( $sub_locations ) ) {
									foreach ( $sub_locations as $location_key => $location ) {
										if ( isset( $location ) && is_array( $location ) ) {
											if ( isset( $location['param'] ) && in_array( $location['param'], $only_these_fields, false ) ) {
												$send_back_group = true;
											}
										}
									}
								}
							}
						}
						if ( ! $send_back_group ) {
							continue;
						}
					}
					if ( isset( $acf_group['ID'] ) && ! empty( $acf_group['ID'] ) ) {
						$fields = acf_get_fields( $acf_group['ID'] );
					} else {
						$fields = acf_get_fields( $acf_group );
					}
					// If no fields move on.
					if ( ! is_array( $fields ) ) {
						continue;
					}
					$is_option_page = in_array( $acf_group['ID'], $options_pages_group_ids, true );
					$acf_options = array();
					foreach ( $fields as $field ) {
						if ( empty( $field['name'] ) ) {
							continue;
						}
						$already_captured[] = $field['name'];
						if ( ! in_array( $field['type'], $types, true ) ) {
							continue;
						}
						if ( $is_option_page ) {
							$field_key = 'acf_option|' . $field['name'];
							$acf_options[] = array(
								'value' => $field_key,
								'label' => __( 'Options', 'kadence-blocks-pro' ) . ':' . $field['label'],
							);
						}
						$field_key = 'acf_meta|' . $field['name'];
						$acf_options[] = array(
							'value' => $field_key,
							'label' => ( $field['label'] ? $field['label'] : $field_key ),
						);
					}
					$options[] = array(
						'label'   => $acf_group['title'],
						'options' => $acf_options,
					);
				}
			}
		}

		// GET OTHER CUSTOM FIELDS.
		if ( 'post' === $meta_group || 'relationship' === $meta_group ) {
			$custom_keys = get_post_custom_keys( ( $source ? $source : null ) );
			if ( ! empty( $custom_keys ) ) {
				$other_options = array();
				foreach ( $custom_keys as $custom_key ) {
					if ( '_' !== substr( $custom_key, 0, 1 ) && ! in_array( $custom_key, $already_captured, true ) && 'kt_blocks_editor_width' !== $custom_key ) {
						$other_options[] = array(
							'value' => $custom_key,
							'label' => $custom_key,
						);
					}
				}
				if ( ! empty( $other_options ) ) {
					$options[] = array(
						'label'   => __( 'Custom Fields', 'kadence-blocks-pro' ),
						'options' => $other_options,
					);
				}
			}
		} elseif ( 'author' === $meta_group || 'user' === $meta_group ) {
			if ( $source ) {
				$author_id   = get_post_field( 'post_author', $source );
				$custom_keys = get_user_meta( $author_id );
				if ( ! empty( $custom_keys ) ) {
					$other_options = array();
					$user_exclude_list = array(
						'nickname',
						'first_name',
						'last_name',
						'description',
						'rich_editing',
						'syntax_highlighting',
						'comment_shortcuts',
						'admin_color',
						'use_ssl',
						'show_admin_bar_front',
						'locale',
						'wp_capabilities',
						'wp_user_level',
						'dismissed_wp_pointers',
						'show_welcome_panel',
						'session_tokens',
						'wp_user-settings',
						'wp_user-settings-time',
						'wp_dashboard_quick_press_last_post_id',
						'community-events-location',
						'last_update',
						// Woocommerce.
						'wc_last_active',
						'woocommerce_admin_activity_panel_inbox_last_read',
						'wp_woocommerce_product_import_mapping',
						'wp_product_import_error_log',
						// Elementor.
						'elementor_introduction',
						//Others.
						'icl_admin_language_migrated_to_wp47',
						'nav_menu_recently_edited',
						'tribe_setDefaultNavMenuBoxes',
						'managenav-menuscolumnshidden',
						'rtladminbar',
						'learndash-last-login',
						'closedpostboxes_',
						'metaboxhidden_',
						'enable_custom_fields',
						'metaboxhidden_nav-menus',
					);
					foreach ( $custom_keys as $custom_user_key => $custom_user_data ) {
						if ( '_' !== substr( $custom_user_key, 0, 1 ) && 'wp_' !== substr( $custom_user_key, 0, 3 ) && ( strlen( $custom_user_key ) <= 10 || strlen( $custom_user_key ) > 10 && 'manageedit' !== substr( $custom_user_key, 0, 10 ) ) && ! in_array( $custom_user_key, $already_captured, true ) && ! in_array( $custom_user_key, $user_exclude_list, true ) ) {
							$other_options[] = array(
								'value' => $custom_user_key,
								'label' => $custom_user_key,
							);
						}
					}
					if ( ! empty( $other_options ) ) {
						$options[] = array(
							'label'   => __( 'Custom Fields', 'kadence-blocks-pro' ),
							'options' => $other_options,
						);
					}
				}
			}
		}

		// Add Option to manually add key.
		$options[] = array(
			'label'   => __( 'Manual', 'kadence-blocks-pro' ),
			'options' => array(
				array(
					'value' => 'kb_custom_input',
					'label' => __( 'Custom Input', 'kadence-blocks-pro' ),
				),
			),
		);
		return rest_ensure_response( $options );
	}
}
