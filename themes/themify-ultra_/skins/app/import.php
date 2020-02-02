<?php

defined( 'ABSPATH' ) or die;

$GLOBALS['processed_terms'] = array();
$GLOBALS['processed_posts'] = array();

require_once ABSPATH . 'wp-admin/includes/post.php';
require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

function themify_import_post( $post ) {
	global $processed_posts, $processed_terms;

	if ( ! post_type_exists( $post['post_type'] ) ) {
		return;
	}

	/* Menu items don't have reliable post_title, skip the post_exists check */
	if( $post['post_type'] !== 'nav_menu_item' ) {
		$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );
		if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
			$processed_posts[ intval( $post['ID'] ) ] = intval( $post_exists );
			return;
		}
	}

	if( $post['post_type'] == 'nav_menu_item' ) {
		if( ! isset( $post['tax_input']['nav_menu'] ) || ! term_exists( $post['tax_input']['nav_menu'], 'nav_menu' ) ) {
			return;
		}
		$_menu_item_type = $post['meta_input']['_menu_item_type'];
		$_menu_item_object_id = $post['meta_input']['_menu_item_object_id'];

		if ( 'taxonomy' == $_menu_item_type && isset( $processed_terms[ intval( $_menu_item_object_id ) ] ) ) {
			$post['meta_input']['_menu_item_object_id'] = $processed_terms[ intval( $_menu_item_object_id ) ];
		} else if ( 'post_type' == $_menu_item_type && isset( $processed_posts[ intval( $_menu_item_object_id ) ] ) ) {
			$post['meta_input']['_menu_item_object_id'] = $processed_posts[ intval( $_menu_item_object_id ) ];
		} else if ( 'custom' != $_menu_item_type ) {
			// associated object is missing or not imported yet, we'll retry later
			// $missing_menu_items[] = $item;
			return;
		}
	}

	$post_parent = ( $post['post_type'] == 'nav_menu_item' ) ? $post['meta_input']['_menu_item_menu_item_parent'] : (int) $post['post_parent'];
	$post['post_parent'] = 0;
	if ( $post_parent ) {
		// if we already know the parent, map it to the new local ID
		if ( isset( $processed_posts[ $post_parent ] ) ) {
			if( $post['post_type'] == 'nav_menu_item' ) {
				$post['meta_input']['_menu_item_menu_item_parent'] = $processed_posts[ $post_parent ];
			} else {
				$post['post_parent'] = $processed_posts[ $post_parent ];
			}
		}
	}

	/**
	 * for hierarchical taxonomies, IDs must be used so wp_set_post_terms can function properly
	 * convert term slugs to IDs for hierarchical taxonomies
	 */
	if( ! empty( $post['tax_input'] ) ) {
		foreach( $post['tax_input'] as $tax => $terms ) {
			if( is_taxonomy_hierarchical( $tax ) ) {
				$terms = explode( ', ', $terms );
				$post['tax_input'][ $tax ] = array_map( 'themify_get_term_id_by_slug', $terms, array_fill( 0, count( $terms ), $tax ) );
			}
		}
	}

	$post['post_author'] = (int) get_current_user_id();
	$post['post_status'] = 'publish';

	$old_id = $post['ID'];

	unset( $post['ID'] );
	$post_id = wp_insert_post( $post, true );
	if( is_wp_error( $post_id ) ) {
		return false;
	} else {
		$processed_posts[ $old_id ] = $post_id;

		if( isset( $post['has_thumbnail'] ) && $post['has_thumbnail'] ) {
			$placeholder = themify_get_placeholder_image();
			if( ! is_wp_error( $placeholder ) ) {
				set_post_thumbnail( $post_id, $placeholder );
			}
		}

		return $post_id;
	}
}

function themify_get_placeholder_image() {
	static $placeholder_image = null;

	if( $placeholder_image == null ) {
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		global $wp_filesystem;
		$upload = wp_upload_bits( $post['post_name'] . '.jpg', null, $wp_filesystem->get_contents( THEMIFY_DIR . '/img/image-placeholder.jpg' ) );

		if ( $info = wp_check_filetype( $upload['file'] ) )
			$post['post_mime_type'] = $info['type'];
		else
			return new WP_Error( 'attachment_processing_error', __( 'Invalid file type', 'themify' ) );

		$post['guid'] = $upload['url'];
		$post_id = wp_insert_attachment( $post, $upload['file'] );
		wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

		$placeholder_image = $post_id;
	}

	return $placeholder_image;
}

function themify_import_term( $term ) {
	global $processed_terms;

	if( $term_id = term_exists( $term['slug'], $term['taxonomy'] ) ) {
		if ( is_array( $term_id ) ) $term_id = $term_id['term_id'];
		if ( isset( $term['term_id'] ) )
			$processed_terms[ intval( $term['term_id'] ) ] = (int) $term_id;
		return (int) $term_id;
	}

	if ( empty( $term['parent'] ) ) {
		$parent = 0;
	} else {
		$parent = term_exists( $term['parent'], $term['taxonomy'] );
		if ( is_array( $parent ) ) $parent = $parent['term_id'];
	}

	$id = wp_insert_term( $term['name'], $term['taxonomy'], array(
		'parent' => $parent,
		'slug' => $term['slug'],
		'description' => $term['description'],
	) );
	if ( ! is_wp_error( $id ) ) {
		if ( isset( $term['term_id'] ) ) {
			$processed_terms[ intval($term['term_id']) ] = $id['term_id'];
			return $term['term_id'];
		}
	}

	return false;
}

function themify_get_term_id_by_slug( $slug, $tax ) {
	$term = get_term_by( 'slug', $slug, $tax );
	if( $term ) {
		return $term->term_id;
	}

	return false;
}

function themify_undo_import_term( $term ) {
	$term_id = term_exists( $term['slug'], $term['taxonomy'] );
	if ( $term_id ) {
		if ( is_array( $term_id ) ) $term_id = $term_id['term_id'];
		if ( isset( $term_id ) ) {
			wp_delete_term( $term_id, $term['taxonomy'] );
		}
	}
}

/**
 * Determine if a post exists based on title, content, and date
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param array $args array of database parameters to check
 * @return int Post ID if post exists, 0 otherwise.
 */
function themify_post_exists( $args = array() ) {
	global $wpdb;

	$query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
	$db_args = array();

	foreach ( $args as $key => $value ) {
		$value = wp_unslash( sanitize_post_field( $key, $value, 0, 'db' ) );
		if( ! empty( $value ) ) {
			$query .= ' AND ' . $key . ' = %s';
			$db_args[] = $value;
		}
	}

	if ( !empty ( $args ) )
		return (int) $wpdb->get_var( $wpdb->prepare($query, $args) );

	return 0;
}

function themify_undo_import_post( $post ) {
	if( $post['post_type'] == 'nav_menu_item' ) {
		$post_exists = themify_post_exists( array(
			'post_name' => $post['post_name'],
			'post_modified' => $post['post_date'],
			'post_type' => 'nav_menu_item',
		) );
	} else {
		$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );
	}
	if( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
		/**
		 * check if the post has been modified, if so leave it be
		 *
		 * NOTE: posts are imported using wp_insert_post() which modifies post_modified field
		 * to be the same as post_date, hence to check if the post has been modified,
		 * the post_modified field is compared against post_date in the original post.
		 */
		if( $post['post_date'] == get_post_field( 'post_modified', $post_exists ) ) {
			wp_delete_post( $post_exists, true ); // true: bypass trash
		}
	}
}

function themify_do_demo_import() {

	if ( isset( $GLOBALS["ThemifyBuilder_Data_Manager"] ) ) {
		remove_action( "save_post", array( $GLOBALS["ThemifyBuilder_Data_Manager"], "save_builder_text_only"), 10, 3 );
	}
$term = array (
  'term_id' => 2,
  'name' => 'Main Navigation',
  'slug' => 'main-navigation',
  'term_group' => 0,
  'taxonomy' => 'nav_menu',
  'description' => '',
  'parent' => 0,
);

if( ERASEDEMO ) {
	themify_undo_import_term( $term );
} else {
	themify_import_term( $term );
}

$post = array (
  'ID' => 6,
  'post_date' => '2018-05-25 20:19:29',
  'post_date_gmt' => '2018-05-25 20:19:29',
  'post_content' => '<!--themify_builder_static--><h1>Ultra App</h1> <p>Speed up your mobile app development now. Simple 5-min installation. Available both on iOS and Android.</p>
 
 <a href="https://themify.me/" >DOWNLOAD</a> 
 
 <a href="https://www.youtube.com/watch?v=P8Lte26BBN8" > <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/ipad-app-689x506.png" width="689" alt="ipad app" srcset="https://themify.me/demo/themes/ultra-app/files/2018/05/ipad-app.png 689w, https://themify.me/demo/themes/ultra-app/files/2018/05/ipad-app-300x220.png 300w" sizes="(max-width: 689px) 100vw, 689px" /> </a> 
 <h2>Quick Prototypes</h2> <p>Perfect for presenting your apps, websites and prototypes. Mainstream your design and development process without hassle exports. Simply export it any time as you want. Available to view online or any mobile device.</p>
 
 <a href="https://themify.me/" >WATCH IT</a> 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/app-interface-670x481.png" width="670" alt="app interface" srcset="https://themify.me/demo/themes/ultra-app/files/2018/05/app-interface-670x481.png 670w, https://themify.me/demo/themes/ultra-app/files/2018/05/app-interface-300x216.png 300w, https://themify.me/demo/themes/ultra-app/files/2018/05/app-interface-768x552.png 768w, https://themify.me/demo/themes/ultra-app/files/2018/05/app-interface.png 948w" sizes="(max-width: 670px) 100vw, 670px" /> 
 <h2>Testimonials</h2>
 <ul data-id="testimonial-slider-0-" data-visible="3" data-scroll="1" data-auto-scroll="off" data-speed="1" data-wrap="yes" data-arrow="yes" data-pagination="yes" data-effect="scroll" data-height="variable" data-pause-on-hover="resume" > <li> 
 <figure> <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/testimonial-client-1-100x100.jpg" width="100" height="100" alt="testimonial-client-1" /> </figure> <p>Thanks Plentific for helping us stay on top of a very stressful process! Finally exchanged and looking forward to complete.</p> Hendry Bradshaw Evernote </li> <li> 
 <figure> <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/testimonial-client-2-100x100.jpg" width="100" height="100" alt="testimonial-client-2" /> </figure> <p>Great to stay on top of the process. Especially liked to play with the financial section when viewing properties. Highly recommended!</p> Andree Shorter Invision </li> <li> 
 <figure> <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/testimonial-client-3-100x100.jpg" width="100" height="100" alt="testimonial-client-3" /> </figure> <p>Just started flat hunting. Your affordability calculator saved me some serious time to focus on what I can actually buy. Thanks so much.</p> Sofia Gerald AirBNB </li> <li> 
 <figure> <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/testimonial-client-4-100x100.jpg" width="100" height="100" alt="testimonial-client-4" /> </figure> <p>Thumbs Up, their service is magnificent, quick solution for top enterprise. Totally recommended App for your business</p> Chintya Abee Total Solution </li> </ul> 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/client-1-156x39.png" width="156" height="39" alt="client 1" srcset="https://themify.me/demo/themes/ultra-app/files/2018/05/client-1.png 156w, https://themify.me/demo/themes/ultra-app/files/2018/05/client-1-150x39.png 150w" sizes="(max-width: 156px) 100vw, 156px" /> 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/client-2-143x37.png" width="143" height="37" alt="client 2" /> 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/client-3-51x36.png" width="51" height="36" alt="client 3" /> 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/client-4-126x37.png" width="126" height="37" alt="client 4" /> 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/client-5-163x37.png" width="163" height="37" alt="client 5" /> 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/client-6-116x35.png" width="116" height="35" alt="client 6" /> 
 <h2>Features</h2>
 <p>Deserun mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod. Excepteur sint occaecat cupidatat.</p>
 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/camera-app-icon-1.png" alt="Camera" /> 
 
 <h3>Camera</h3> <p>Sed ut perspiciatis unde omnis iste natus error.</p> 
 
 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/share-app-icon-1.png" alt="Share" /> 
 
 <h3>Share</h3> <p>Excepteur sint occaecat cupidatat non proident.</p> 
 
 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/sync-app-icon-1.png" alt="Sync" /> 
 
 <h3>Sync</h3> <p>At vero eos et accusamus et iusto odio dignissimos.</p> 
 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/mobile-phone-345x710.png" width="345" height="710" alt="mobile phone" srcset="https://themify.me/demo/themes/ultra-app/files/2018/05/mobile-phone.png 345w, https://themify.me/demo/themes/ultra-app/files/2018/05/mobile-phone-146x300.png 146w" sizes="(max-width: 345px) 100vw, 345px" /> 
 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/filter-app-icon-1.png" alt="Filter" /> 
 
 <h3>Filter</h3> <p>Neque porro quisquam est, qui dolorem ipsum quiat.</p> 
 
 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/edit-app-icon-1.png" alt="Edit" /> 
 
 <h3>Edit</h3> <p>Nemo enim ipsam voluptatem quia voluptas.</p> 
 
 
 
 <img src="https://themify.me/demo/themes/ultra-app/files/2018/05/effect-app-icon-1.png" alt="Effects" /> 
 
 <h3>Effects</h3> <p>Nam libero tempore, cum soluta nobis est eligendi.</p> 
 
 Lite $5.99 <p>/ month</p> <p>1 User</p> <p>100 Exports</p> <p>100 Prototypes</p> <p></p> <a href="https://themify.me"> Buy Now </a> 
 Popular Pro $9.99 <p>/ month</p> <p>5 Users</p> <p>1000 Exports</p> <p>1000 Prototypes</p> <p></p> <a href="https://themify.me"> Buy Now </a> 
 Master $24.99 <p>/ month</p> <p>Unlimited Users</p> <p>Unlimited Exports</p> <p>Unlimited Prototypes</p> <p></p> <a href="https://themify.me"> Buy Now </a> 
 <h2>Got Questions?</h2> <p>Don’t be shy. We are here to answer your questions 24/7.</p>
 <form action="https://themify.me/demo/themes/ultra-app/wp-admin/admin-ajax.php" id="contact-0--form" method="post"> 
 <label for="contact-0--contact-name">Your Name *</label> <input type="text" name="contact-name" placeholder="" id="contact-0--contact-name" value="" required /> 
 <label for="contact-0--contact-email">Your Email *</label> <input type="text" name="contact-email" placeholder="" id="contact-0--contact-email" value="" required /> 
 <label for="contact-0--contact-subject">Subject *</label> <input type="text" name="contact-subject" placeholder="" id="contact-0--contact-subject" value="" required /> <label for="contact-0--contact-message">Message *</label> <textarea name="contact-message" placeholder="" id="contact-0--contact-message" rows="8" cols="45" required></textarea> 
 <button type="submit"> Send </button> </form><!--/themify_builder_static-->',
  'post_title' => 'Home',
  'post_excerpt' => '',
  'post_name' => 'home',
  'post_modified' => '2018-06-05 17:49:02',
  'post_modified_gmt' => '2018-06-05 17:49:02',
  'post_content_filtered' => '',
  'post_parent' => 0,
  'guid' => 'https://themify.me/demo/themes/ultra-app/?page_id=6',
  'menu_order' => 0,
  'post_type' => 'page',
  'meta_input' => 
  array (
    'page_layout' => 'sidebar-none',
    'content_width' => 'full_width',
    'hide_page_title' => 'yes',
    'header_wrap' => 'transparent',
    'post_filter' => 'no',
    '_themify_builder_settings_json' => '[{\\"row_order\\":\\"0\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col3-1\\",\\"modules\\":[{\\"mod_name\\":\\"text\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"font_color\\":\\"#ffffff\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"30\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"content_text\\":\\"<h1>Ultra App<\\\\/h1>\\\\n<p>Speed up your mobile app development now. Simple 5-min installation. Available both on iOS and Android.<\\\\/p>\\",\\"cid\\":\\"c18\\"}},{\\"mod_name\\":\\"buttons\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"checkbox_padding_link_apply_all\\":\\"1\\",\\"checkbox_link_margin_apply_all\\":\\"1\\",\\"checkbox_link_border_apply_all\\":\\"1\\",\\"buttons_size\\":\\"normal\\",\\"buttons_style\\":\\"circle\\",\\"display\\":\\"buttons-horizontal\\",\\"content_button\\":[{\\"label\\":\\"DOWNLOAD\\",\\"link\\":\\"https:\\\\/\\\\/themify.me\\\\/\\",\\"link_options\\":\\"regular\\",\\"button_color_bg\\":\\"blue\\",\\"icon_alignment\\":\\"left\\"}],\\"cid\\":\\"c22\\"}}],\\"styling\\":{\\"breakpoint_mobile\\":{\\"background_type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_bottom\\":\\"14\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\"}}},{\\"column_order\\":\\"1\\",\\"grid_class\\":\\"col3-2\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/ipad-app.png\\",\\"width_image\\":\\"689\\",\\"link_image\\":\\"https:\\\\/\\\\/www.youtube.com\\\\/watch?v=P8Lte26BBN8\\",\\"param_image\\":\\"lightbox\\",\\"animation_effect\\":\\"fadeInUp\\",\\"cid\\":\\"c30\\"}}]}],\\"column_alignment\\":\\"col_align_middle\\",\\"gutter\\":null,\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/bg-header.jpg\\",\\"background_repeat\\":\\"fullcover\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-top\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"12\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom\\":\\"25\\",\\"padding_bottom_unit\\":\\"%\\",\\"margin_bottom\\":\\"-22\\",\\"margin_bottom_unit\\":\\"%\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"row_width\\":\\"fullwidth\\",\\"breakpoint_mobile\\":{\\"background_type\\":\\"image\\",\\"background_repeat\\":\\"fullcover\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-top\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"22\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom\\":\\"5\\",\\"padding_bottom_unit\\":\\"%\\",\\"margin_bottom\\":\\"0\\",\\"margin_bottom_unit\\":\\"%\\",\\"checkbox_border_apply_all\\":\\"1\\"}}},{\\"row_order\\":\\"1\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col4-2\\",\\"modules\\":[{\\"mod_name\\":\\"text\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"30\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"font_color_h2\\":\\"#405cc2\\",\\"content_text\\":\\"<h2>Quick Prototypes<\\\\/h2>\\\\n<p>Perfect for presenting your apps, websites and prototypes. Mainstream your design and development process without hassle exports. Simply export it any time as you want. Available to view online or any mobile device.<\\\\/p>\\",\\"cid\\":\\"c41\\"}},{\\"mod_name\\":\\"buttons\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"checkbox_padding_link_apply_all\\":\\"1\\",\\"checkbox_link_margin_apply_all\\":\\"1\\",\\"checkbox_link_border_apply_all\\":\\"1\\",\\"buttons_size\\":\\"normal\\",\\"buttons_style\\":\\"circle\\",\\"display\\":\\"buttons-horizontal\\",\\"content_button\\":[{\\"label\\":\\"WATCH IT\\",\\"link\\":\\"https:\\\\/\\\\/themify.me\\\\/\\",\\"link_options\\":\\"regular\\",\\"button_color_bg\\":\\"purple\\",\\"icon_alignment\\":\\"left\\"}],\\"cid\\":\\"c45\\"}}],\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"10\\",\\"padding_top_unit\\":\\"%\\",\\"padding_right\\":\\"5\\",\\"padding_right_unit\\":\\"%\\",\\"padding_bottom\\":\\"5\\",\\"padding_bottom_unit\\":\\"%\\",\\"padding_left\\":\\"5\\",\\"padding_left_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"breakpoint_mobile\\":{\\"background_type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"5\\",\\"padding_top_unit\\":\\"%\\",\\"padding_right_unit\\":\\"%\\",\\"padding_bottom_unit\\":\\"%\\",\\"padding_left_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\"}}},{\\"column_order\\":\\"1\\",\\"grid_class\\":\\"col4-2\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/app-interface.png\\",\\"width_image\\":\\"670\\",\\"auto_fullwidth\\":\\"1\\",\\"param_image\\":\\"regular\\",\\"custom_parallax_scroll_speed\\":\\"2\\",\\"cid\\":\\"c53\\"}}]}],\\"column_alignment\\":\\"col_align_middle\\",\\"gutter\\":\\"gutter-none\\",\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_repeat\\":\\"fullcover\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"6\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom\\":\\"6\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"breakpoint_mobile\\":{\\"background_type\\":\\"image\\",\\"background_repeat\\":\\"fullcover\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"0\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\"},\\"row_width\\":\\"fullwidth-content\\",\\"row_anchor\\":\\"about\\"}},{\\"row_order\\":\\"2\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col-full\\",\\"modules\\":[{\\"mod_name\\":\\"text\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"text_align\\":\\"center\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"30\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"font_color_h2\\":\\"#405cc2\\",\\"content_text\\":\\"<h2>Testimonials<\\\\/h2>\\",\\"cid\\":\\"c64\\"}},{\\"mod_name\\":\\"testimonial-slider\\",\\"mod_settings\\":{\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"layout_testimonial\\":\\"image-top\\",\\"tab_content_testimonial\\":[{\\"content_testimonial\\":\\"<p>Thanks Plentific for helping us stay on top of a very stressful process! Finally exchanged and looking forward to complete.<\\\\/p>\\",\\"person_picture_testimonial\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/testimonial-client-1.jpg\\",\\"person_name_testimonial\\":\\"Hendry Bradshaw\\",\\"company_testimonial\\":\\"Evernote\\"},{\\"content_testimonial\\":\\"<p>Great to stay on top of the process. Especially liked to play with the financial section when viewing properties. Highly recommended!<\\\\/p>\\",\\"person_picture_testimonial\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/testimonial-client-2.jpg\\",\\"person_name_testimonial\\":\\"Andree Shorter\\",\\"company_testimonial\\":\\"Invision\\"},{\\"content_testimonial\\":\\"<p>Just started flat hunting. Your affordability calculator saved me some serious time to focus on what I can actually buy. Thanks so much.<\\\\/p>\\",\\"person_picture_testimonial\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/testimonial-client-3.jpg\\",\\"person_name_testimonial\\":\\"Sofia Gerald\\",\\"company_testimonial\\":\\"AirBNB\\"},{\\"content_testimonial\\":\\"<p>Thumbs Up, their service is magnificent, quick solution for top enterprise. Totally recommended App for your business<\\\\/p>\\",\\"person_picture_testimonial\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/testimonial-client-4.jpg\\",\\"person_name_testimonial\\":\\"Chintya Abee\\",\\"company_testimonial\\":\\"Total Solution\\"}],\\"img_w_slider\\":\\"100\\",\\"img_h_slider\\":\\"100\\",\\"visible_opt_slider\\":\\"3\\",\\"auto_scroll_opt_slider\\":\\"off\\",\\"scroll_opt_slider\\":\\"1\\",\\"speed_opt_slider\\":\\"normal\\",\\"effect_slider\\":\\"scroll\\",\\"pause_on_hover_slider\\":\\"resume\\",\\"wrap_slider\\":\\"yes\\",\\"show_nav_slider\\":\\"yes\\",\\"show_arrow_slider\\":\\"yes\\",\\"height_slider\\":\\"variable\\",\\"animation_effect\\":\\"fadeIn\\"}}]}],\\"column_alignment\\":null,\\"gutter\\":null,\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"row_anchor\\":\\"testimonials\\"}},{\\"row_order\\":\\"3\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col-full\\",\\"modules\\":[{\\"row_order\\":\\"0\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col6-1\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/client-1.png\\",\\"width_image\\":\\"156\\",\\"height_image\\":\\"39\\",\\"param_image\\":\\"regular\\",\\"cid\\":\\"c87\\"}}]},{\\"column_order\\":\\"1\\",\\"grid_class\\":\\"col6-1\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/client-2-143x37.png\\",\\"width_image\\":\\"143\\",\\"height_image\\":\\"37\\",\\"param_image\\":\\"regular\\",\\"cid\\":\\"c95\\"}}]},{\\"column_order\\":\\"2\\",\\"grid_class\\":\\"col6-1\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/client-3-51x36.png\\",\\"width_image\\":\\"51\\",\\"height_image\\":\\"36\\",\\"param_image\\":\\"regular\\",\\"cid\\":\\"c103\\"}}]},{\\"column_order\\":\\"3\\",\\"grid_class\\":\\"col6-1\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/client-4-126x37.png\\",\\"width_image\\":\\"126\\",\\"height_image\\":\\"37\\",\\"param_image\\":\\"regular\\",\\"cid\\":\\"c111\\"}}]},{\\"column_order\\":\\"4\\",\\"grid_class\\":\\"col6-1\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/client-5-163x37.png\\",\\"width_image\\":\\"163\\",\\"height_image\\":\\"37\\",\\"param_image\\":\\"regular\\",\\"cid\\":\\"c119\\"}}]},{\\"column_order\\":\\"5\\",\\"grid_class\\":\\"col6-1\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/client-6-116x35.png\\",\\"width_image\\":\\"116\\",\\"height_image\\":\\"35\\",\\"param_image\\":\\"regular\\",\\"cid\\":\\"c127\\"}}]}],\\"column_alignment\\":null,\\"gutter\\":null,\\"col_mobile\\":\\"column3-1 tb_3col\\",\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"60\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"breakpoint_mobile\\":{\\"background_type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"80\\",\\"padding_bottom\\":\\"40\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\"}}}]}],\\"column_alignment\\":\\"col_align_middle\\",\\"gutter\\":null,\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/bg-section-brand.jpg\\",\\"background_repeat\\":\\"fullcover\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"27\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom\\":\\"17\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"row_width\\":\\"fullwidth\\",\\"row_anchor\\":\\"clients\\"}},{\\"row_order\\":\\"4\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col-full\\",\\"modules\\":[{\\"mod_name\\":\\"text\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_position\\":\\"left-top\\",\\"text_align\\":\\"center\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"font_color_h2\\":\\"#405cc2\\",\\"content_text\\":\\"<h2>Features<\\\\/h2>\\",\\"cid\\":\\"c138\\"}},{\\"mod_name\\":\\"text\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_position\\":\\"left-top\\",\\"font_size\\":\\"1.2\\",\\"font_size_unit\\":\\"em\\",\\"line_height\\":\\"1.9\\",\\"line_height_unit\\":\\"em\\",\\"text_align\\":\\"center\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"30\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"font_color_h2\\":\\"#405cc2\\",\\"content_text\\":\\"<p>Deserun mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod. Excepteur sint occaecat cupidatat.<\\\\/p>\\",\\"cid\\":\\"c142\\"}},{\\"row_order\\":\\"2\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col3-1\\",\\"modules\\":[{\\"mod_name\\":\\"feature\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_position\\":\\"left-top\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"40\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"title_feature\\":\\"Camera\\",\\"content_feature\\":\\"<p>Sed ut perspiciatis unde omnis iste natus error.<\\\\/p>\\",\\"layout_feature\\":\\"icon-right\\",\\"circle_percentage_feature\\":\\"100\\",\\"circle_stroke_feature\\":\\"2\\",\\"circle_color_feature\\":\\"#405cc2\\",\\"circle_size_feature\\":\\"small\\",\\"icon_type_feature\\":\\"image_icon\\",\\"image_feature\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/camera-app-icon-1.png\\",\\"icon_color_feature\\":\\"#000\\",\\"link_options\\":\\"regular\\",\\"cid\\":\\"c154\\"}},{\\"mod_name\\":\\"feature\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_position\\":\\"left-top\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"40\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"title_feature\\":\\"Share\\",\\"content_feature\\":\\"<p>Excepteur sint occaecat cupidatat non proident.<\\\\/p>\\",\\"layout_feature\\":\\"icon-right\\",\\"circle_percentage_feature\\":\\"100\\",\\"circle_stroke_feature\\":\\"2\\",\\"circle_color_feature\\":\\"#405cc2\\",\\"circle_size_feature\\":\\"small\\",\\"icon_type_feature\\":\\"image_icon\\",\\"image_feature\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/share-app-icon-1.png\\",\\"icon_color_feature\\":\\"#000\\",\\"link_options\\":\\"regular\\",\\"cid\\":\\"c158\\"}},{\\"mod_name\\":\\"feature\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_position\\":\\"left-top\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"40\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"title_feature\\":\\"Sync\\",\\"content_feature\\":\\"<p>At vero eos et accusamus et iusto odio dignissimos.<\\\\/p>\\",\\"layout_feature\\":\\"icon-right\\",\\"circle_percentage_feature\\":\\"100\\",\\"circle_stroke_feature\\":\\"2\\",\\"circle_color_feature\\":\\"#405cc2\\",\\"circle_size_feature\\":\\"small\\",\\"icon_type_feature\\":\\"image_icon\\",\\"image_feature\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/sync-app-icon-1.png\\",\\"icon_feature\\":\\"fa-microchip\\",\\"icon_color_feature\\":\\"#405cc2\\",\\"link_options\\":\\"regular\\",\\"cid\\":\\"c162\\"}}]},{\\"column_order\\":\\"1\\",\\"grid_class\\":\\"col3-1\\",\\"modules\\":[{\\"mod_name\\":\\"image\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"style_image\\":\\"image-center\\",\\"url_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/mobile-phone.png\\",\\"width_image\\":\\"345\\",\\"height_image\\":\\"710\\",\\"param_image\\":\\"regular\\",\\"animation_effect\\":\\"fadeInUp\\"}}],\\"styling\\":{\\"breakpoint_mobile\\":{\\"background_type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_bottom\\":\\"12\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\"}}},{\\"column_order\\":\\"2\\",\\"grid_class\\":\\"col3-1\\",\\"modules\\":[{\\"mod_name\\":\\"feature\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_position\\":\\"left-top\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"40\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"title_feature\\":\\"Filter\\",\\"content_feature\\":\\"<p>Neque porro quisquam est, qui dolorem ipsum quiat.<\\\\/p>\\",\\"layout_feature\\":\\"icon-left\\",\\"circle_percentage_feature\\":\\"100\\",\\"circle_stroke_feature\\":\\"2\\",\\"circle_color_feature\\":\\"#405cc2\\",\\"circle_size_feature\\":\\"small\\",\\"icon_type_feature\\":\\"image_icon\\",\\"image_feature\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/filter-app-icon-1.png\\",\\"icon_feature\\":\\"fa-microchip\\",\\"icon_color_feature\\":\\"#000\\",\\"link_options\\":\\"regular\\",\\"cid\\":\\"c178\\"}},{\\"mod_name\\":\\"feature\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_position\\":\\"left-top\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"40\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"title_feature\\":\\"Edit\\",\\"content_feature\\":\\"<p>Nemo enim ipsam voluptatem quia voluptas.<\\\\/p>\\",\\"layout_feature\\":\\"icon-left\\",\\"circle_percentage_feature\\":\\"100\\",\\"circle_stroke_feature\\":\\"2\\",\\"circle_color_feature\\":\\"#405cc2\\",\\"circle_size_feature\\":\\"small\\",\\"icon_type_feature\\":\\"image_icon\\",\\"image_feature\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/edit-app-icon-1.png\\",\\"icon_feature\\":\\"fa-space-shuttle\\",\\"icon_color_feature\\":\\"#000\\",\\"link_options\\":\\"regular\\",\\"cid\\":\\"c182\\"}},{\\"mod_name\\":\\"feature\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"background_position\\":\\"left-top\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"40\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"title_feature\\":\\"Effects\\",\\"content_feature\\":\\"<p>Nam libero tempore, cum soluta nobis est eligendi.<\\\\/p>\\",\\"layout_feature\\":\\"icon-left\\",\\"circle_percentage_feature\\":\\"100\\",\\"circle_stroke_feature\\":\\"2\\",\\"circle_color_feature\\":\\"#405cc2\\",\\"circle_size_feature\\":\\"small\\",\\"icon_type_feature\\":\\"image_icon\\",\\"image_feature\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/effect-app-icon-1.png\\",\\"icon_feature\\":\\"fa-window-maximize\\",\\"icon_color_feature\\":\\"#823deb\\",\\"link_options\\":\\"regular\\",\\"cid\\":\\"c186\\"}}]}],\\"column_alignment\\":\\"col_align_middle\\",\\"gutter\\":null,\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"font_size\\":\\".9\\",\\"font_size_unit\\":\\"em\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\"}}],\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_left_unit\\":\\"em\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\"}}],\\"column_alignment\\":\\"col_align_middle\\",\\"gutter\\":null,\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"row_anchor\\":\\"features\\"}},{\\"row_order\\":\\"5\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col-full\\",\\"modules\\":[{\\"row_order\\":\\"0\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col3-1\\",\\"modules\\":[{\\"mod_name\\":\\"pricing-table\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"mod_button_bg_color\\":\\"#41b838\\",\\"mod_color_pricing_table\\":\\"green\\",\\"mod_title_pricing_table\\":\\"Lite\\",\\"mod_price_pricing_table\\":\\"$5.99\\",\\"mod_description_pricing_table\\":\\"\\\\/ month\\",\\"mod_feature_list_pricing_table\\":\\"1 User\\\\n100 Exports\\\\n100 Prototypes\\",\\"mod_button_text_pricing_table\\":\\"Buy Now\\",\\"mod_button_link_pricing_table\\":\\"https:\\\\/\\\\/themify.me\\"}}]},{\\"column_order\\":\\"1\\",\\"grid_class\\":\\"col3-1\\",\\"modules\\":[{\\"mod_name\\":\\"pricing-table\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"mod_button_bg_color\\":\\"#7b21e6\\",\\"mod_pop_font_color\\":\\"#ffffff\\",\\"mod_color_pricing_table\\":\\"purple\\",\\"mod_title_pricing_table\\":\\"Pro\\",\\"mod_price_pricing_table\\":\\"$9.99\\",\\"mod_description_pricing_table\\":\\"\\\\/ month\\",\\"mod_feature_list_pricing_table\\":\\"5 Users\\\\n1000 Exports\\\\n1000 Prototypes\\",\\"mod_button_text_pricing_table\\":\\"Buy Now\\",\\"mod_button_link_pricing_table\\":\\"https:\\\\/\\\\/themify.me\\",\\"mod_pop_text_pricing_table\\":\\"Popular\\",\\"mod_enlarge_pricing_table\\":\\"enlarge\\"}}]},{\\"column_order\\":\\"2\\",\\"grid_class\\":\\"col3-1\\",\\"modules\\":[{\\"mod_name\\":\\"pricing-table\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"mod_button_bg_color\\":\\"#41b838\\",\\"mod_color_pricing_table\\":\\"green\\",\\"mod_title_pricing_table\\":\\"Master\\",\\"mod_price_pricing_table\\":\\"$24.99\\",\\"mod_description_pricing_table\\":\\"\\\\/ month\\",\\"mod_feature_list_pricing_table\\":\\"Unlimited Users\\\\nUnlimited Exports\\\\nUnlimited Prototypes\\",\\"mod_button_text_pricing_table\\":\\"Buy Now\\",\\"mod_button_link_pricing_table\\":\\"https:\\\\/\\\\/themify.me\\"}}]}],\\"column_alignment\\":null,\\"gutter\\":null}]}],\\"column_alignment\\":null,\\"gutter\\":null,\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_image\\":\\"https://themify.me/demo/themes/ultra-app\\\\/files\\\\/2018\\\\/05\\\\/bg-pricing.jpg\\",\\"background_repeat\\":\\"fullcover\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-top\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"11\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom\\":\\"15\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"row_width\\":\\"fullwidth\\",\\"row_anchor\\":\\"pricing\\",\\"breakpoint_mobile\\":{\\"background_type\\":\\"image\\",\\"background_repeat\\":\\"fullcover\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-top\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"5\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom\\":\\"5\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\"}}},{\\"row_order\\":\\"6\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col-full\\",\\"modules\\":[{\\"mod_name\\":\\"text\\",\\"mod_settings\\":{\\"background_image-type\\":\\"image\\",\\"background_repeat\\":\\"repeat\\",\\"text_align\\":\\"center\\",\\"checkbox_padding_apply_all\\":\\"1\\",\\"margin_bottom\\":\\"30\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"font_color_h2\\":\\"#405cc2\\",\\"content_text\\":\\"<h2>Got Questions?<\\\\/h2>\\\\n<p>Don’t be shy. We are here to answer your questions 24\\\\/7.<\\\\/p>\\",\\"cid\\":\\"c232\\"}},{\\"row_order\\":\\"1\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col4-1\\"},{\\"column_order\\":\\"1\\",\\"grid_class\\":\\"col4-2\\",\\"modules\\":[{\\"mod_name\\":\\"contact\\",\\"mod_settings\\":{\\"checkbox_padding_apply_all\\":\\"1\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"checkbox_border_inputs_apply_all\\":\\"1\\",\\"checkbox_border_send_apply_all\\":\\"1\\",\\"checkbox_padding_success_message_apply_all\\":\\"1\\",\\"checkbox_margin_success_message_apply_all\\":\\"1\\",\\"checkbox_border_success_message_apply_all\\":\\"1\\",\\"checkbox_padding_error_message_apply_all\\":\\"1\\",\\"checkbox_margin_error_message_apply_all\\":\\"1\\",\\"checkbox_border_error_message_apply_all\\":\\"1\\",\\"layout_contact\\":\\"animated-label\\",\\"mail_contact\\":\\"info@themify.me\\",\\"field_name_label\\":\\"Your Name\\",\\"field_email_label\\":\\"Your Email\\",\\"field_subject_label\\":\\"Subject\\",\\"field_subject_require\\":\\"yes\\",\\"field_subject_active\\":\\"yes\\",\\"field_message_label\\":\\"Message\\",\\"field_extra\\":\\"{ \\\\\\\\\\\\\\"fields\\\\\\\\\\\\\\": [] }\\",\\"field_sendcopy_label\\":\\"Send a copy to myself\\",\\"field_order\\":\\"{}\\",\\"field_send_label\\":\\"Send\\",\\"field_send_align\\":\\"center\\"}}]},{\\"column_order\\":\\"2\\",\\"grid_class\\":\\"col4-1\\"}],\\"column_alignment\\":null,\\"gutter\\":\\"gutter-none\\"}]}],\\"column_alignment\\":null,\\"gutter\\":null,\\"styling\\":{\\"background_type\\":\\"image\\",\\"background_slider_size\\":\\"large\\",\\"background_slider_mode\\":\\"fullcover\\",\\"background_video_options\\":\\"mute\\",\\"background_repeat\\":\\"repeat\\",\\"background_attachment\\":\\"scroll\\",\\"background_position\\":\\"center-center\\",\\"cover_color-type\\":\\"color\\",\\"cover_color_hover-type\\":\\"hover_color\\",\\"padding_top\\":\\"5\\",\\"padding_top_unit\\":\\"%\\",\\"padding_bottom\\":\\"8\\",\\"padding_bottom_unit\\":\\"%\\",\\"checkbox_margin_apply_all\\":\\"1\\",\\"checkbox_border_apply_all\\":\\"1\\",\\"row_anchor\\":\\"contact\\"}},{\\"row_order\\":\\"7\\",\\"cols\\":[{\\"column_order\\":\\"0\\",\\"grid_class\\":\\"col-full\\"}],\\"column_alignment\\":null,\\"gutter\\":null}]',
  ),
  'tax_input' => 
  array (
  ),
);

if( ERASEDEMO ) {
	themify_undo_import_post( $post );
} else {
	themify_import_post( $post );
}

$post = array (
  'ID' => 135,
  'post_date' => '2018-05-27 16:19:29',
  'post_date_gmt' => '2018-05-27 16:19:29',
  'post_content' => '',
  'post_title' => 'About',
  'post_excerpt' => '',
  'post_name' => 'about',
  'post_modified' => '2018-05-27 23:20:57',
  'post_modified_gmt' => '2018-05-27 23:20:57',
  'post_content_filtered' => '',
  'post_parent' => 0,
  'guid' => 'https://themify.me/demo/themes/ultra-app/?p=135',
  'menu_order' => 1,
  'post_type' => 'nav_menu_item',
  'meta_input' => 
  array (
    '_menu_item_type' => 'custom',
    '_menu_item_menu_item_parent' => '0',
    '_menu_item_object_id' => '135',
    '_menu_item_object' => 'custom',
    '_menu_item_classes' => 
    array (
      0 => '',
    ),
    '_menu_item_url' => '#about',
  ),
  'tax_input' => 
  array (
    'nav_menu' => 'main-navigation',
  ),
);

if( ERASEDEMO ) {
	themify_undo_import_post( $post );
} else {
	themify_import_post( $post );
}

$post = array (
  'ID' => 136,
  'post_date' => '2018-05-27 16:19:29',
  'post_date_gmt' => '2018-05-27 16:19:29',
  'post_content' => '',
  'post_title' => 'Testimonials',
  'post_excerpt' => '',
  'post_name' => 'testimonials',
  'post_modified' => '2018-05-27 23:20:57',
  'post_modified_gmt' => '2018-05-27 23:20:57',
  'post_content_filtered' => '',
  'post_parent' => 0,
  'guid' => 'https://themify.me/demo/themes/ultra-app/?p=136',
  'menu_order' => 2,
  'post_type' => 'nav_menu_item',
  'meta_input' => 
  array (
    '_menu_item_type' => 'custom',
    '_menu_item_menu_item_parent' => '0',
    '_menu_item_object_id' => '136',
    '_menu_item_object' => 'custom',
    '_menu_item_classes' => 
    array (
      0 => '',
    ),
    '_menu_item_url' => '#testimonials',
  ),
  'tax_input' => 
  array (
    'nav_menu' => 'main-navigation',
  ),
);

if( ERASEDEMO ) {
	themify_undo_import_post( $post );
} else {
	themify_import_post( $post );
}

$post = array (
  'ID' => 137,
  'post_date' => '2018-05-27 16:19:29',
  'post_date_gmt' => '2018-05-27 16:19:29',
  'post_content' => '',
  'post_title' => 'Features',
  'post_excerpt' => '',
  'post_name' => 'features',
  'post_modified' => '2018-05-27 23:20:57',
  'post_modified_gmt' => '2018-05-27 23:20:57',
  'post_content_filtered' => '',
  'post_parent' => 0,
  'guid' => 'https://themify.me/demo/themes/ultra-app/?p=137',
  'menu_order' => 3,
  'post_type' => 'nav_menu_item',
  'meta_input' => 
  array (
    '_menu_item_type' => 'custom',
    '_menu_item_menu_item_parent' => '0',
    '_menu_item_object_id' => '137',
    '_menu_item_object' => 'custom',
    '_menu_item_classes' => 
    array (
      0 => '',
    ),
    '_menu_item_url' => '#features',
  ),
  'tax_input' => 
  array (
    'nav_menu' => 'main-navigation',
  ),
);

if( ERASEDEMO ) {
	themify_undo_import_post( $post );
} else {
	themify_import_post( $post );
}

$post = array (
  'ID' => 139,
  'post_date' => '2018-05-27 16:19:29',
  'post_date_gmt' => '2018-05-27 16:19:29',
  'post_content' => '',
  'post_title' => 'Contact',
  'post_excerpt' => '',
  'post_name' => 'contact',
  'post_modified' => '2018-05-27 23:20:57',
  'post_modified_gmt' => '2018-05-27 23:20:57',
  'post_content_filtered' => '',
  'post_parent' => 0,
  'guid' => 'https://themify.me/demo/themes/ultra-app/?p=139',
  'menu_order' => 4,
  'post_type' => 'nav_menu_item',
  'meta_input' => 
  array (
    '_menu_item_type' => 'custom',
    '_menu_item_menu_item_parent' => '0',
    '_menu_item_object_id' => '139',
    '_menu_item_object' => 'custom',
    '_menu_item_classes' => 
    array (
      0 => '',
    ),
    '_menu_item_url' => '#contact',
  ),
  'tax_input' => 
  array (
    'nav_menu' => 'main-navigation',
  ),
);

if( ERASEDEMO ) {
	themify_undo_import_post( $post );
} else {
	themify_import_post( $post );
}

$post = array (
  'ID' => 140,
  'post_date' => '2018-05-27 16:19:29',
  'post_date_gmt' => '2018-05-27 16:19:29',
  'post_content' => '',
  'post_title' => 'Download',
  'post_excerpt' => '',
  'post_name' => 'download',
  'post_modified' => '2018-05-27 23:20:57',
  'post_modified_gmt' => '2018-05-27 23:20:57',
  'post_content_filtered' => '',
  'post_parent' => 0,
  'guid' => 'https://themify.me/demo/themes/ultra-app/?p=140',
  'menu_order' => 5,
  'post_type' => 'nav_menu_item',
  'meta_input' => 
  array (
    '_menu_item_type' => 'custom',
    '_menu_item_menu_item_parent' => '0',
    '_menu_item_object_id' => '140',
    '_menu_item_object' => 'custom',
    '_menu_item_classes' => 
    array (
      0 => 'highlight-link',
    ),
    '_menu_item_url' => '#pricing',
  ),
  'tax_input' => 
  array (
    'nav_menu' => 'main-navigation',
  ),
);

if( ERASEDEMO ) {
	themify_undo_import_post( $post );
} else {
	themify_import_post( $post );
}


function themify_import_get_term_id_from_slug( $slug ) {
	$menu = get_term_by( "slug", $slug, "nav_menu" );
	return is_wp_error( $menu ) ? 0 : (int) $menu->term_id;
}

	$widgets = get_option( "widget_search" );
$widgets[1002] = array (
  'title' => '',
);
update_option( "widget_search", $widgets );

$widgets = get_option( "widget_recent-posts" );
$widgets[1003] = array (
  'title' => '',
  'number' => 5,
);
update_option( "widget_recent-posts", $widgets );

$widgets = get_option( "widget_recent-comments" );
$widgets[1004] = array (
  'title' => '',
  'number' => 5,
);
update_option( "widget_recent-comments", $widgets );

$widgets = get_option( "widget_archives" );
$widgets[1005] = array (
  'title' => '',
  'count' => 0,
  'dropdown' => 0,
);
update_option( "widget_archives", $widgets );

$widgets = get_option( "widget_categories" );
$widgets[1006] = array (
  'title' => '',
  'count' => 0,
  'hierarchical' => 0,
  'dropdown' => 0,
);
update_option( "widget_categories", $widgets );

$widgets = get_option( "widget_meta" );
$widgets[1007] = array (
  'title' => '',
);
update_option( "widget_meta", $widgets );

$widgets = get_option( "widget_text" );
$widgets[1008] = array (
  'title' => 'Office',
  'text' => 'Ultra Tower, 4th Fifth Street North York, M1E 5QF',
  'filter' => true,
  'visual' => true,
);
update_option( "widget_text", $widgets );

$widgets = get_option( "widget_themify-social-links" );
$widgets[1009] = array (
  'title' => '',
  'show_link_name' => NULL,
  'open_new_window' => NULL,
  'icon_size' => 'icon-large',
  'orientation' => 'horizontal',
);
update_option( "widget_themify-social-links", $widgets );



$sidebars_widgets = array (
  'sidebar-main' => 
  array (
    0 => 'search-1002',
    1 => 'recent-posts-1003',
    2 => 'recent-comments-1004',
    3 => 'archives-1005',
    4 => 'categories-1006',
    5 => 'meta-1007',
  ),
  'footer-widget-1' => 
  array (
    0 => 'text-1008',
  ),
  'footer-widget-2' => 
  array (
    0 => 'themify-social-links-1009',
  ),
); 
update_option( "sidebars_widgets", $sidebars_widgets );

$menu_locations = array();
$menu = get_terms( "nav_menu", array( "slug" => "main-navigation" ) );
if( is_array( $menu ) && ! empty( $menu ) ) $menu_locations["main-nav"] = $menu[0]->term_id;
set_theme_mod( "nav_menu_locations", $menu_locations );


$homepage = get_posts( array( 'name' => 'home', 'post_type' => 'page' ) );
			if( is_array( $homepage ) && ! empty( $homepage ) ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage[0]->ID );
			}
			
	ob_start(); ?>a:96:{s:16:"setting-page_404";s:1:"0";s:21:"setting-webfonts_list";s:11:"recommended";s:22:"setting-default_layout";s:8:"sidebar1";s:27:"setting-default_post_layout";s:9:"list-post";s:19:"setting-post_filter";s:2:"no";s:23:"setting-disable_masonry";s:3:"yes";s:19:"setting-post_gutter";s:6:"gutter";s:30:"setting-default_layout_display";s:7:"content";s:25:"setting-default_more_text";s:4:"More";s:21:"setting-index_orderby";s:4:"date";s:19:"setting-index_order";s:4:"DESC";s:30:"setting-default_media_position";s:5:"above";s:31:"setting-image_post_feature_size";s:5:"blank";s:32:"setting-default_page_post_layout";s:8:"sidebar1";s:37:"setting-default_page_post_layout_type";s:7:"classic";s:42:"setting-default_page_single_media_position";s:5:"above";s:38:"setting-image_post_single_feature_size";s:5:"blank";s:27:"setting-default_page_layout";s:8:"sidebar1";s:38:"setting-default_portfolio_index_layout";s:12:"sidebar-none";s:43:"setting-default_portfolio_index_post_layout";s:5:"grid3";s:29:"setting-portfolio_post_filter";s:3:"yes";s:33:"setting-portfolio_disable_masonry";s:3:"yes";s:24:"setting-portfolio_gutter";s:6:"gutter";s:39:"setting-default_portfolio_index_display";s:4:"none";s:50:"setting-default_portfolio_index_post_meta_category";s:3:"yes";s:49:"setting-default_portfolio_index_unlink_post_image";s:3:"yes";s:39:"setting-default_portfolio_single_layout";s:12:"sidebar-none";s:54:"setting-default_portfolio_single_portfolio_layout_type";s:9:"fullwidth";s:50:"setting-default_portfolio_single_unlink_post_image";s:3:"yes";s:22:"themify_portfolio_slug";s:7:"project";s:53:"setting-customizer_responsive_design_tablet_landscape";s:4:"1280";s:43:"setting-customizer_responsive_design_tablet";s:3:"768";s:43:"setting-customizer_responsive_design_mobile";s:3:"680";s:33:"setting-mobile_menu_trigger_point";s:3:"900";s:24:"setting-gallery_lightbox";s:8:"lightbox";s:27:"setting-script_minification";s:7:"disable";s:27:"setting-page_builder_expiry";s:1:"2";s:21:"setting-header_design";s:17:"header-horizontal";s:28:"setting-exclude_site_tagline";s:2:"on";s:27:"setting-exclude_search_form";s:2:"on";s:19:"setting-exclude_rss";s:2:"on";s:30:"setting-exclude_header_widgets";s:2:"on";s:22:"setting-header_widgets";s:17:"headerwidget-3col";s:21:"setting-footer_design";s:15:"footer-left-col";s:38:"setting-exclude_footer_menu_navigation";s:2:"on";s:22:"setting-use_float_back";s:2:"on";s:22:"setting-footer_widgets";s:17:"footerwidget-2col";s:27:"setting-imagefilter_applyto";s:12:"featuredonly";s:29:"setting-color_animation_speed";s:1:"5";s:29:"setting-relationship_taxonomy";s:8:"category";s:37:"setting-relationship_taxonomy_entries";s:1:"3";s:45:"setting-relationship_taxonomy_display_content";s:4:"none";s:30:"setting-single_slider_autoplay";s:3:"off";s:27:"setting-single_slider_speed";s:6:"normal";s:28:"setting-single_slider_effect";s:5:"slide";s:28:"setting-single_slider_height";s:4:"auto";s:18:"setting-more_posts";s:8:"infinite";s:19:"setting-entries_nav";s:8:"numbered";s:25:"setting-img_php_base_size";s:5:"large";s:27:"setting-global_feature_size";s:5:"blank";s:22:"setting-link_icon_type";s:9:"font-icon";s:32:"setting-link_type_themify-link-0";s:10:"image-icon";s:33:"setting-link_title_themify-link-0";s:7:"Twitter";s:31:"setting-link_img_themify-link-0";s:103:"https://themify.me/demo/themes/ultra-app/wp-content/themes/themify-ultra/themify/img/social/twitter.png";s:32:"setting-link_type_themify-link-1";s:10:"image-icon";s:33:"setting-link_title_themify-link-1";s:8:"Facebook";s:31:"setting-link_img_themify-link-1";s:104:"https://themify.me/demo/themes/ultra-app/wp-content/themes/themify-ultra/themify/img/social/facebook.png";s:32:"setting-link_type_themify-link-2";s:10:"image-icon";s:33:"setting-link_title_themify-link-2";s:7:"Google+";s:31:"setting-link_img_themify-link-2";s:107:"https://themify.me/demo/themes/ultra-app/wp-content/themes/themify-ultra/themify/img/social/google-plus.png";s:32:"setting-link_type_themify-link-3";s:10:"image-icon";s:33:"setting-link_title_themify-link-3";s:7:"YouTube";s:31:"setting-link_img_themify-link-3";s:103:"https://themify.me/demo/themes/ultra-app/wp-content/themes/themify-ultra/themify/img/social/youtube.png";s:32:"setting-link_type_themify-link-4";s:10:"image-icon";s:33:"setting-link_title_themify-link-4";s:9:"Pinterest";s:31:"setting-link_img_themify-link-4";s:105:"https://themify.me/demo/themes/ultra-app/wp-content/themes/themify-ultra/themify/img/social/pinterest.png";s:32:"setting-link_type_themify-link-6";s:9:"font-icon";s:33:"setting-link_title_themify-link-6";s:8:"Facebook";s:32:"setting-link_link_themify-link-6";s:27:"http://facebook.com/themify";s:33:"setting-link_ficon_themify-link-6";s:11:"fa-facebook";s:35:"setting-link_ficolor_themify-link-6";s:7:"#ffffff";s:32:"setting-link_type_themify-link-5";s:9:"font-icon";s:33:"setting-link_title_themify-link-5";s:7:"Twitter";s:32:"setting-link_link_themify-link-5";s:26:"http://twitter.com/themify";s:33:"setting-link_ficon_themify-link-5";s:10:"fa-twitter";s:35:"setting-link_ficolor_themify-link-5";s:7:"#ffffff";s:32:"setting-link_type_themify-link-8";s:9:"font-icon";s:33:"setting-link_title_themify-link-8";s:9:"Instagram";s:32:"setting-link_link_themify-link-8";s:29:"https://instagram.com/themify";s:33:"setting-link_ficon_themify-link-8";s:12:"fa-instagram";s:35:"setting-link_ficolor_themify-link-8";s:7:"#ffffff";s:22:"setting-link_field_ids";s:273:"{"themify-link-0":"themify-link-0","themify-link-1":"themify-link-1","themify-link-2":"themify-link-2","themify-link-3":"themify-link-3","themify-link-4":"themify-link-4","themify-link-6":"themify-link-6","themify-link-5":"themify-link-5","themify-link-8":"themify-link-8"}";s:23:"setting-link_field_hash";s:2:"10";s:30:"setting-page_builder_is_active";s:6:"enable";s:46:"setting-page_builder_animation_parallax_scroll";s:6:"mobile";s:4:"skin";s:92:"https://themify.me/demo/themes/ultra-app/wp-content/themes/themify-ultra/skins/app/style.css";}<?php $themify_data = unserialize( ob_get_clean() );

	// fix the weird way "skin" is saved
	if( isset( $themify_data['skin'] ) ) {
		$parsed_skin = parse_url( $themify_data['skin'], PHP_URL_PATH );
		$basedir_skin = basename( dirname( $parsed_skin ) );
		$themify_data['skin'] = trailingslashit( get_template_directory_uri() ) . 'skins/' . $basedir_skin . '/style.css';
	}

	themify_set_data( $themify_data );
	
}
themify_do_demo_import();