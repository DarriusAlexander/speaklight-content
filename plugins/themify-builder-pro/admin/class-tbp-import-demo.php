<?php

/**
 * Handles importing sample themes.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 */
/**
 * 
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 * @author     Themify <themify@themify.me>
 */
class Tbp_Import_Demo {

    /**
     * Creates or returns an instance of this class.
     *
     * @return    A single instance of this class.
     */
    public static function get_instance() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new self;
		}
		return $instance;
    }

    private function __construct() {
		add_action( 'wp_ajax_tbp_import_term', array( $this, 'wp_ajax_tbp_import_term' ) );
		add_action( 'wp_ajax_tbp_import_post', array( $this, 'wp_ajax_tbp_import_post' ) );
		add_action( 'wp_ajax_tbp_import_thumbnail', array( $this, 'wp_ajax_tbp_import_thumbnail' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	function admin_init() {
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === Tbp_Themes::$post_type ) {
			if ( isset( $_GET['tbp_erase_demo'] ) && $_GET['tbp_erase_demo'] ) {
				$this->erase_demo();
			}
		}
	}

	function wp_ajax_tbp_import_term() {
		$term = $_POST['term'];
		$old_id = $term['term_id'];

		if ( $term_id = term_exists( $term['slug'], $term['taxonomy'] ) ) {
			if ( is_array( $term_id ) ) $term_id = $term_id['term_id'];
			if ( isset( $term['term_id'] ) ) {
				update_term_meta( $term_id, '_tbp_demo_id', $old_id );
				wp_send_json_error();
			}
		}

		if ( empty( $term['parent'] ) ) {
			$parent = 0;
		} else {
			$new_id = $this->get_term_by_old_id( $term['parent'] );
			$parent = term_exists( $new_id, $term['taxonomy'] );
			if ( is_array( $parent ) ) $parent = $parent['term_id'];
		}

		$id = wp_insert_term( $term['name'], $term['taxonomy'], array(
			'parent' => $parent,
			'slug' => $term['slug'],
			'description' => $term['description'],
		) );
		if ( ! is_wp_error( $id ) ) {

			update_term_meta( $id['term_id'], '_tbp_demo_id', $old_id );

			wp_send_json_success( array(
				'oid' => $old_id, // original ID
				'id' => $id['term_id'],
				'type' => 'term'
			) );
		} else {
			wp_send_json_error( $id );
		}
		die;
	}

	function wp_ajax_tbp_import_post() {
		$post = $_POST['post'];
		$old_id = $post['ID'];

		$post['post_author'] = (int) get_current_user_id();
		$post['post_status'] = 'publish';
		unset( $post['ID'] );

		/* Menu items don't have reliable post_title, skip the post_exists check */
		/* With tbp_template, different Themes can have duplicate templates so skip post_exists */
		if ( $post['post_type'] !== 'nav_menu_item' && $post['post_type'] !== 'tbp_template' ) {
			$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );
			if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
				update_post_meta( $post_id, '_tbp_demo_id', $old_id );
				wp_send_json_success( array(
					'oid' => $old_id, // original ID
					'id' => $post_exists,
					'type' => 'post'
				) );
			}
		}

		if ( $post['post_type'] == 'nav_menu_item' ) {
			if ( ! isset( $post['tax_input']['nav_menu'] ) || ! term_exists( $post['tax_input']['nav_menu'], 'nav_menu' ) ) {
				wp_send_json_error();
			}
			$_menu_item_type = $post['meta_input']['_menu_item_type'];
			$_menu_item_object_id = $post['meta_input']['_menu_item_object_id'];

			if ( 'taxonomy' == $_menu_item_type ) {
				$new_id = $this->get_term_by_old_id( intval( $_menu_item_object_id ) );
				if ( $new_id ) {
					$post['meta_input']['_menu_item_object_id'] = $new_id;
				}
			} else if ( 'post_type' == $_menu_item_type ) {
				$new_id = $this->get_post_by_old_id( intval( $_menu_item_object_id ) );
				if ( $new_id ) {
					$post['meta_input']['_menu_item_object_id'] = $new_id;
				}
			}
		}

		$post_parent = ( $post['post_type'] == 'nav_menu_item' ) ? $post['meta_input']['_menu_item_menu_item_parent'] : (int) $post['post_parent'];
		$post['post_parent'] = 0;
		if ( $post_parent ) {
			// if we already know the parent, map it to the new local ID
			$new_id = $this->get_post_by_old_id( $post_parent );
			if ( $new_id ) {
				if( $post['post_type'] == 'nav_menu_item' ) {
					$post['meta_input']['_menu_item_menu_item_parent'] = $new_id;
				} else {
					$post['post_parent'] = $new_id;
				}
			}
		}

		/**
		 * for hierarchical taxonomies, IDs must be used so wp_set_post_terms can function properly
		 * convert term slugs to IDs for hierarchical taxonomies
		 */
		if ( ! empty( $post['tax_input'] ) ) {
			foreach( $post['tax_input'] as $tax => $terms ) {
				if( is_taxonomy_hierarchical( $tax ) ) {
					$terms = explode( ', ', $terms );
					$post['tax_input'][ $tax ] = array_map( 'self::get_term_id_by_slug', $terms, array_fill( 0, count( $terms ), $tax ) );
				}
			}
		}

		$post_id = wp_insert_post( $post, true );
		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( $post_id );
		} else {

			update_post_meta( $post_id, '_tbp_demo_id', $old_id );

			// download featured image
			if ( isset( $post['thumb'] ) ) {
				$fetch = $this->fetch_remote_file( $post['thumb'], $post_id );
				if ( ! is_wp_error( $fetch ) ) {
					set_post_thumbnail( $post_id, $fetch );
				}
			}

			// Home page
			if ( isset( $post['is_home'] ) ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $post_id );
			}

			if ( $post['post_type'] === 'tbp_theme' ) {
				Tbp_Utils::set_active_theme( $post_id );
			}

			if ( $post['post_type'] === 'tbp_template' ) {
				$theme = (int) sanitize_text_field( $_POST['theme_id'] );
				$theme_post = get_post( $theme );
				update_post_meta( $post_id, 'tbp_associated_theme', $theme_post->post_name );
			}

			wp_send_json_success( array(
				'oid' => $old_id, // original ID
				'id' => $post_id,
				'type' => 'post'
			) );
		}
	}

	function wp_ajax_tbp_import_thumbnail() {
		$post_id = (int) $_POST['post_id'];
		$url = sanitize_text_field( $_POST['thumb'] );

		$fetch = $this->fetch_remote_file( $url, $post_id );
		if ( is_wp_error( $fetch ) ) {
			wp_send_json_error( $fetch );
		}

		set_post_thumbnail( $post_id, $fetch );
		wp_send_json_success();
	}

	/**
	 * Remove all demo posts, signified by the existence of _tbp_demo_id meta
	 *
	 * @return null
	 */
	function erase_demo() {
		$terms = get_terms( array(
			'taxonomy' => null,
			'hide_empty' => false,
			'number' => 0,
			'meta_query' => array(
				array(
					'key' => '_tbp_demo_id',
					'compare' => 'EXISTS'
				),
			),
			'suppress_filters' => true,
		) );
		foreach ( $terms as $term ) {
			wp_delete_term( $term->term_id, $term->taxonomy );
		}

		$posts = get_posts( array(
			'posts_per_page' => -1,
			'post_type' => 'any',
			'posts_status' => 'any',
			'meta_query' => array(
				array(
					'key' => '_tbp_demo_id',
					'compare' => 'EXISTS'
				),
			),
			'suppress_filters' => true,
			'fields' => 'ids',
		) );
		foreach ( $posts as $post_id ) {
			wp_delete_post( $post_id, true );
		}
	}

	/**
	 * Recieves a post_id from sample file and returns the same post that has been imported into the site
	 *
	 * @return int|bool
	 */
	function get_post_by_old_id( $old_id ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '_tbp_demo_id' AND meta_value = %d LIMIT 1",
			$old_id
		), ARRAY_A );

		if ( isset( $result[0]['post_id'] ) ) {
			return $result[0]['post_id'];
		}

		return false;
	}

	function get_term_by_old_id( $old_id ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$wpdb->termmeta} WHERE meta_key = '_tbp_demo_id' AND meta_value = %d LIMIT 1",
			$old_id
		), ARRAY_A );

		if ( isset( $result[0]['term_id'] ) ) {
			return $result[0]['term_id'];
		}

		return false;
	}

	/**
	 * Download an image from external URL and returns the file
	 *
	 * @param $post_id Attachments may be associated with a parent post or page.
	 *
	 * @return WP_Error|int ID of created attachment, or WP_Error
	 */
	function fetch_remote_file( $url, $post_id = null ) {
		// extract the file name and extension from the url
		$file_name = basename( $url );

		// get placeholder file in the upload dir with a unique, sanitized filename
		$upload = wp_upload_bits( $file_name, 0, '' );
		if ( $upload['error'] )
			return new WP_Error( 'upload_dir_error', $upload['error'] );

		// fetch the remote url and write it to the placeholder file
		$remote_response = wp_safe_remote_get( $url, array(
			'timeout' => 300,
			'stream' => true,
			'filename' => $upload['file'],
		) );

		$headers = wp_remote_retrieve_headers( $remote_response );

		// request failed
		if ( ! $headers ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', __('Remote server did not respond', 'themify') );
		}

		$remote_response_code = wp_remote_retrieve_response_code( $remote_response );

		// make sure the fetch was successful
		if ( $remote_response_code != '200' ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', sprintf( __('Remote server returned error response %1$d %2$s', 'themify'), esc_html( $remote_response_code ), get_status_header_desc( $remote_response_code ) ) );
		}

		$filesize = filesize( $upload['file'] );

		if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
			/* note: this is intentionally disabled, if gZip is enabled, $headers['content-length'] and $filesize do not necessarily match */
		}

		if ( 0 == $filesize ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', __('Zero size file downloaded', 'themify') );
		}

		$post = array(
			'post_title' => '',
			'post_content' => '',
			'post_status' => 'inherit',
		);
		if ( $info = wp_check_filetype( $upload['file'] ) )
			$post['post_mime_type'] = $info['type'];
		else
			return new WP_Error( 'mime_type_error', __('Invalid file type', 'themify') );

		$attach_id = wp_insert_attachment( $post, $upload['file'], $post_id );
		wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $upload['file'] ) );

		return $attach_id;
	}

	public static function get_term_id_by_slug( $slug, $tax ) {
		$term = get_term_by( 'slug', $slug, $tax );
		if( $term ) {
			return $term->term_id;
		}

		return false;
	}
}