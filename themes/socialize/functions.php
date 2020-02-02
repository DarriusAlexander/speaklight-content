<?php

/**
* Load theme framework
*
*/
require_once( get_template_directory() . '/lib/framework/ghostpool-framework.php' );
 
 
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own ghostpool_theme_setup() function to override in a child theme.
 *
 */
if ( ! function_exists( 'ghostpool_theme_setup' ) ) {
	function ghostpool_theme_setup() {

		// Localisation
		load_theme_textdomain( 'socialize', trailingslashit( WP_LANG_DIR ) . 'themes/' );
		load_theme_textdomain( 'socialize', get_stylesheet_directory() . '/languages' );
		load_theme_textdomain( 'socialize', get_template_directory() . '/languages' );
				
		// Featured images
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 150, 150, true );

		// Background customizer
		add_theme_support( 'custom-background' );

		// This theme styles the visual editor with editor-style.css to match the theme style
		add_editor_style( 'lib/css/editor-style.css' );

		// Add default posts and comments RSS feed links to <head>
		add_theme_support( 'automatic-feed-links' );

		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		
		// BuddyPress legacy support
		add_theme_support( 'buddypress-use-legacy' );

		// Post formats
		add_theme_support( 'post-formats', array( 'quote', 'video', 'audio', 'gallery', 'link' ) );

		// Title support
		add_theme_support( 'title-tag' );
		
		// Register navigation menus
		register_nav_menus( array(
			'gp-primary-main-header-nav' => esc_html__( 'Primary Main Header Navigation', 'socialize' ),
			'gp-secondary-main-header-nav' => esc_html__( 'Secondary Main Header Navigation', 'socialize' ),
			'gp-left-small-header-nav'    => esc_html__( 'Left Small Header Navigation', 'socialize' ),
			'gp-right-small-header-nav' => esc_html__( 'Right Small Header Navigation', 'socialize' ),
			'gp-footer-nav' => esc_html__( 'Footer Navigation', 'socialize' ),
		) );
		
	}
}
add_action( 'after_setup_theme', 'ghostpool_theme_setup' );

/**
* Load theme functions
*
*/
if ( ! function_exists( 'ghostpool_load_theme_functions' ) ) {
	function ghostpool_load_theme_functions() {

		// Theme setup
		require_once( get_template_directory() . '/lib/framework/theme-setup/init.php' );

		// Load VC templates
		require_once( get_template_directory() . '/lib/inc/default-vc-templates.php' );
			
		// Sidebars
		require_once( get_template_directory() . '/lib/framework/custom-sidebars/custom-sidebars.php' );
				
		// Category options
		require_once( get_template_directory() . '/lib/inc/category-config.php' );

		// Init variables
		require_once( get_template_directory() . '/lib/inc/init-variables.php' );

		// Loop variables
		require_once( get_template_directory() . '/lib/inc/loop-variables.php' );

		// Category variables
		require_once( get_template_directory() . '/lib/inc/category-variables.php' );

		// Load plugin defaults
		require_once( get_template_directory() . '/lib/inc/plugin-defaults.php' );
		
		// Load ajax functions		
		require_once( get_template_directory() . '/lib/inc/ajax.php' );
		
		// Load custom menu walker
		require_once( get_template_directory() . '/lib/menus/custom-menu-walker.php' );

		// Load custom menu fields
		require_once( get_template_directory() . '/lib/menus/menu-item-custom-fields.php' );
		
		// Load page header functions
		require_once( get_template_directory() . '/lib/inc/page-headers.php' );

		// Load image resizer
		require_once( get_template_directory() . '/lib/inc/aq_resizer.php' );
		
		// Load login functions
		if ( ! is_user_logged_in() && ghostpool_option( 'popup_box' ) == 'enabled' ) {
			require_once( get_template_directory() . '/lib/inc/login-settings.php' );
		}
		
		// BuddyPress functions
		if ( function_exists( 'bp_is_active' ) ) {
			require_once( get_template_directory() . '/lib/inc/bp-functions.php' );
		}

		// bbPress functions
		if ( function_exists( 'is_bbpress' ) ) {
			require_once( get_template_directory() . '/lib/inc/bbpress-functions.php' );
		}

		// Woocommerce functions
		if ( function_exists( 'is_woocommerce' ) ) {
			require_once( get_template_directory() . '/lib/inc/wc-functions.php' );
		}

		// Disable activation redirect
		remove_action( 'admin_init', 'vc_page_welcome_redirect' );

		// Remove Visual Composer activation notice
		if ( function_exists( 'vc_set_as_theme' ) ) {
			setcookie( 'vchideactivationmsg', '1', strtotime( '+3 years' ), '/' );
			setcookie( 'vchideactivationmsg_vc11', ( defined( 'WPB_VC_VERSION' ) ? WPB_VC_VERSION : '1' ), strtotime( '+3 years' ), '/' );
		}
				
	}
}
add_action( 'after_setup_theme', 'ghostpool_load_theme_functions' );

/**
* Load Visual Composer element classes
*
*/
if ( function_exists( 'vc_set_as_theme' ) && ! function_exists( 'ghostpool_vc_shortcodes_container' ) ) {
	function ghostpool_vc_shortcodes_container() {
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
			class WPBakeryShortCode_Pricing_Table extends WPBakeryShortCodesContainer {}
			class WPBakeryShortCode_Testimonial_Slider extends WPBakeryShortCodesContainer {}
			class WPBakeryShortCode_Team extends WPBakeryShortCodesContainer {}
		}
		if ( class_exists( 'WPBakeryShortCode' ) ) {
			class WPBakeryShortCode_Pricing_Column extends WPBakeryShortCode {}	
			class WPBakeryShortCode_Testimonial extends WPBakeryShortCode {}
			class WPBakeryShortCode_Team_Member extends WPBakeryShortCode {}	
		}
	}
	add_action( 'init', 'ghostpool_vc_shortcodes_container' );
}

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 */
function ghostpool_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ghostpool_content_width', 730 );
}
add_action( 'after_setup_theme', 'ghostpool_content_width', 0 );

/**
 * Enqueues scripts and styles.
 *
 */	
if ( ! function_exists( 'ghostpool_scripts' ) ) {

	function ghostpool_scripts() {
		
		wp_enqueue_style( 'ghostpool-style', get_stylesheet_uri() );
		
		wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/lib/fonts/font-awesome/css/font-awesome.min.css' );

		if ( ghostpool_option( 'lightbox' ) != 'disabled' ) {
			wp_enqueue_style( 'prettyphoto', get_template_directory_uri() . '/lib/scripts/prettyPhoto/css/prettyPhoto.css' );
		}
					
		if ( ghostpool_option( 'custom_stylesheet' ) ) {
			wp_enqueue_style( 'ghostpool-custom-style', get_template_directory_uri() . '/' . ghostpool_option( 'custom_stylesheet' ) );
		}
		
		$custom_css = '';

		$custom_css .= '#gp-main-header {min-height:' . ghostpool_option( 'desktop_header_height', 'height' ) . ';}' . 

		'.gp-desktop-scrolling #gp-main-header {min-height:' . ghostpool_option( 'desktop_scrolling_header_height', 'height' ) . ';}' .

		'.gp-header-standard #gp-logo {padding:' . ( ghostpool_option( 'desktop_header_height', 'height' ) - ghostpool_option( 'desktop_logo_dimensions', 'height' ) ) / 2 . 'px 0;}' . 

		'.gp-desktop-scrolling.gp-header-standard #gp-logo {padding:' . ( ghostpool_option( 'desktop_scrolling_header_height', 'height' ) - ghostpool_option( 'desktop_scrolling_logo_dimensions', 'height' ) ) / 2 . 'px 0;}' . 

		'.gp-header-standard #gp-primary-main-nav .menu > li > a{padding:' . ( ghostpool_option( 'desktop_header_height', 'height' ) - ( preg_replace( '/[^0-9]/', '', ghostpool_option( 'primary_nav_typography', 'line-height' ) ) + preg_replace( '/[^0-9]/', '', ghostpool_option( 'primary_nav_link_border_hover', 'border-top' ) ) ) ) / 2 . 'px 0;}
		.gp-header-standard #gp-cart-button,.gp-header-standard #gp-search-button,.gp-header-standard #gp-profile-button{padding:' . ( ghostpool_option( 'desktop_header_height', 'height' ) - 18 ) / 2 . 'px 0;}' .

		'.gp-desktop-scrolling.gp-header-standard #gp-primary-main-nav .menu > li > a{padding:' . ( ghostpool_option( 'desktop_scrolling_header_height', 'height' ) - ( preg_replace( '/[^0-9]/', '', ghostpool_option( 'primary_nav_typography', 'line-height' ) ) + preg_replace( '/[^0-9]/', '', ghostpool_option( 'primary_nav_link_border_hover', 'border-top' ) ) ) ) / 2 . 'px 0;}
		.gp-desktop-scrolling.gp-header-standard #gp-cart-button,.gp-desktop-scrolling.gp-header-standard #gp-search-button,.gp-desktop-scrolling.gp-header-standard #gp-profile-button{padding:' . ( ghostpool_option( 'desktop_scrolling_header_height', 'height' ) - 18 ) / 2 . 'px 0;}' .

		'.gp-nav .menu > .gp-standard-menu > .sub-menu > li:hover > a{color:' . ghostpool_option( 'dropdown_link', 'hover' ) . '}' .

		'.gp-theme li:hover .gp-primary-dropdown-icon{color:' . ghostpool_option( 'primary_dropdown_icon', 'hover' ) . '}' .

		'.gp-theme .sub-menu li:hover .gp-secondary-dropdown-icon{color:' . ghostpool_option( 'secondary_dropdown_icon', 'hover' ) . '}' .

		'.gp-header-centered #gp-cart-button,.gp-header-centered #gp-search-button,.gp-header-centered #gp-profile-button{line-height:' . ( preg_replace( '/[^0-9]/', '', ghostpool_option( 'primary_nav_typography', 'line-height' ) ) + 2 ) . 'px;}' .

		'.gp-header-standard #gp-secondary-main-nav .menu > li > a{padding:' . ( ghostpool_option( 'desktop_header_height', 'height' ) - ( preg_replace( '/[^0-9]/', '', ghostpool_option( 'secondary_nav_typography', 'line-height' ) ) + preg_replace( '/[^0-9]/', '', ghostpool_option( 'secondary_nav_link_border_hover', 'border-top' ) ) ) ) / 2 . 'px 0;}' .

		'.gp-desktop-scrolling.gp-header-standard #gp-secondary-main-nav .menu > li > a{padding:' . ( ghostpool_option( 'desktop_scrolling_header_height', 'height' ) - ( preg_replace( '/[^0-9]/', '', ghostpool_option( 'secondary_nav_typography', 'line-height' ) ) + preg_replace( '/[^0-9]/', '', ghostpool_option( 'secondary_nav_link_border_hover', 'border-top' )  ) ) ) / 2 . 'px 0;}' .

		'.gp-header-centered #gp-secondary-main-nav .menu > li > a {line-height:' . ghostpool_option( 'primary_nav_typography', 'line-height' ) . ';}' .

		'.gp-active{color: ' . ghostpool_option( 'general_link', 'hover' ) . ';}' .

		'.gp-theme .widget.buddypress div.item-options a.selected:hover{color: ' . ghostpool_option( 'widget_title_link', 'regular' ) . '!important;}' .

		'.gp-theme #buddypress .activity-list .activity-content blockquote a{color: ' . ghostpool_option( 'general_link', 'regular' ) . '}' . 

		'.gp-theme #buddypress .activity-list .activity-content blockquote a:hover{color: ' . ghostpool_option( 'general_link', 'hover' ) . '}' . 

		'@media only screen and (max-width: 1082px) {' .

			'.gp-header-standard #gp-primary-main-nav .menu > li > a {padding:' . ( ghostpool_option( 'desktop_header_height', 'height' ) - ( 16 + preg_replace( '/[^0-9]/', '', ghostpool_option( 'primary_nav_link_border_hover', 'border-top' ) ) ) ) / 2 . 'px 0;}' . 

			'.gp-desktop-scrolling.gp-header-standard #gp-primary-main-nav .menu > li > a {padding:' . ( ghostpool_option( 'desktop_scrolling_header_height', 'height' ) - ( 16 + preg_replace( '/[^0-9]/', '', ghostpool_option( 'primary_nav_link_border_hover', 'border-top' ) ) ) ) / 2 . 'px 0;}' . 
		
			'.gp-header-standard #gp-cart-button,.gp-header-standard #gp-search-button,.gp-header-standard #gp-profile-button{padding:' . ( ghostpool_option( 'desktop_header_height', 'height' ) - 18 ) / 2 . 'px 0;}' .

			'.gp-desktop-scrolling.gp-header-standard #gp-cart-button,.gp-desktop-scrolling.gp-header-standard #gp-search-button,.gp-desktop-scrolling.gp-header-standard #gp-profile-button{padding:' . ( ghostpool_option( 'desktop_scrolling_header_height', 'height' ) - 18 ) / 2 . 'px 0;}' .
		
			'.gp-header-standard #gp-secondary-main-nav .menu > li > a{padding:' . ( ghostpool_option( 'desktop_header_height', 'height' ) - ( 14 + preg_replace( '/[^0-9]/', '', ghostpool_option( 'secondary_nav_link_border_hover', 'border-top' ) ) ) ) / 2 . 'px 0;}' .

			'.gp-desktop-scrolling.gp-header-standard #gp-secondary-main-nav .menu > li > a{padding:' . ( ghostpool_option( 'desktop_scrolling_header_height', 'height' ) - ( 14 + preg_replace( '/[^0-9]/', '', ghostpool_option( 'secondary_nav_link_border_hover', 'border-top' ) ) ) ) / 2 . 'px 0;}' .
		
		'}' .

		'@media only screen and (max-width: 1023px) {' .
	
			'.gp-responsive #gp-main-header {min-height:' . ghostpool_option( 'mobile_header_height', 'height' ) . ';}' .
	
			'.gp-responsive #gp-logo {padding:' . ( ghostpool_option( 'mobile_header_height', 'height' ) - ghostpool_option( 'mobile_logo_dimensions', 'height' ) ) / 2 . 'px 0;}' .
	
			'.gp-responsive #gp-mobile-nav-button,.gp-responsive #gp-cart-button,.gp-responsive #gp-search-button,.gp-responsive #gp-profile-button{padding:' . ( ghostpool_option( 'mobile_header_height', 'height' ) - 18 ) / 2 . 'px 0;}' .
	
		'}';
	
		if ( ghostpool_option( 'custom_css' ) ) {
			$custom_css .= ghostpool_option( 'custom_css' );
		}

		wp_add_inline_style( 'ghostpool-style', $custom_css );

		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/lib/scripts/modernizr.js', false, '', true );
				
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
			wp_enqueue_script( 'comment-reply' );
		}
		
		wp_enqueue_script( 'imagesloaded' );
		
		if ( ghostpool_option( 'smooth_scrolling' ) == 'gp-smooth-scrolling' ) { 
			wp_enqueue_script( 'nicescroll', get_template_directory_uri() . '/lib/scripts/nicescroll.min.js', false, '', true );
		}
		
		wp_enqueue_script( 'selectivizr', get_template_directory_uri() . '/lib/scripts/selectivizr.min.js', false, '', true );

		wp_enqueue_script( 'placeholder', get_template_directory_uri() . '/lib/scripts/placeholders.min.js', false, '', true );
		
		if ( ghostpool_option( 'lightbox' ) != 'disabled' ) {				
			wp_enqueue_script( 'prettyphoto', get_template_directory_uri() . '/lib/scripts/prettyPhoto/js/jquery.prettyPhoto.js', array( 'jquery' ), '', true );
		}
		
		if ( ghostpool_option( 'back_to_top' ) != 'gp-no-back-to-top' ) { 
			wp_enqueue_script( 'jquery-totop', get_template_directory_uri() . '/lib/scripts/jquery.ui.totop.min.js', array( 'jquery' ), '', true );
		}		

		wp_enqueue_script( 'jquery-flexslider', get_template_directory_uri() . '/lib/scripts/jquery.flexslider-min.js', array( 'jquery' ), '', true );
		
		wp_enqueue_script( 'isotope', get_template_directory_uri() . '/lib/scripts/isotope.pkgd.min.js', false, '', true );

		wp_enqueue_script( 'jquery-stellar', get_template_directory_uri() . '/lib/scripts/jquery.stellar.min.js', array( 'jquery' ), '', true );

		wp_enqueue_script( 'ghostpool-video-header', get_template_directory_uri() . '/lib/scripts/jquery.video-header.js', array( 'jquery' ), '', true );

		if ( is_singular( 'post' ) ) {
			$items_in_view = ghostpool_option( 'post_related_items_in_view' );
		} elseif ( is_singular( 'gp_portfolio_item' ) ) {
			$items_in_view = ghostpool_option( 'portfolio_item_related_items_in_view' );
		} else {
			$items_in_view = '';
		}
																											
		wp_enqueue_script( 'ghostpool-custom-js', get_template_directory_uri() . '/lib/scripts/custom.js', array( 'jquery' ), '', true );

		if ( is_ssl() ) {
			$url = esc_url( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		} else { 
			$url = esc_url( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		}
		
		wp_localize_script( 'ghostpool-custom-js', 'ghostpool_script', array(
			'lightbox' => ghostpool_option( 'lightbox' ),
			'url' => $url,
			'related_items_in_view' => $items_in_view
		) );
								
	}
}
add_action( 'wp_enqueue_scripts', 'ghostpool_scripts' );

/**
 * Enqueues admin scripts and styles.
 *
 */	
if ( ! function_exists( 'ghostpool_admin_scripts' ) ) {
	function ghostpool_admin_scripts() {
		wp_enqueue_style( 'ghostpool-admin', get_template_directory_uri() . '/lib/framework/css/general-admin.css' );
		wp_enqueue_script( 'ghostpool-admin', get_template_directory_uri() . '/lib/framework/scripts/admin.js', '', '', true );
	}
}
add_action( 'admin_enqueue_scripts', 'ghostpool_admin_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 */	
if ( !function_exists( 'ghostpool_body_classes' ) ) {
	function ghostpool_body_classes( $classes ) {
		global $post;	
		$classes[] = 'gp-theme';
		$classes[] = 'gp-responsive';
		$classes[] = ghostpool_option( 'theme_layout' );
		$classes[] = ghostpool_option( 'retina', '', 'gp-retina' );
		$classes[] = ghostpool_option( 'smooth_scrolling' );
		$classes[] = ghostpool_option( 'back_to_top' );
		$classes[] = ghostpool_option( 'fixed_header' );
		$classes[] = ghostpool_option( 'header_layout' );
		$classes[] = ghostpool_option( 'cart_button' );
		$classes[] = ghostpool_option( 'search_button' );
		$classes[] = ghostpool_option( 'profile_button' );
		$classes[] = ghostpool_option( 'small_header' );
		$classes[] = $GLOBALS['ghostpool_page_header'];
		$classes[] = $GLOBALS['ghostpool_layout'];			
		if ( is_page_template( 'homepage-template.php' ) ) {
			$classes[] = 'gp-homepage';
		}
		if ( defined( 'TSS_VERSION' ) ) {	
			$classes[] = 'gp-sticky-sidebars';	
		}			
		return $classes;
	}
}
add_filter( 'body_class', 'ghostpool_body_classes' );

/**
 * Content added to header
 *
 */	
if ( ! function_exists( 'ghostpool_wp_header' ) ) {

	function ghostpool_wp_header() {
	
		echo '<!--[if gte IE 9]><style>.gp-slider-wrapper .gp-slide-caption + .gp-post-thumbnail:before,body:not(.gp-full-page-page-header) .gp-page-header.gp-has-text:before,body:not(.gp-full-page-page-header) .gp-page-header.gp-has-teaser-video.gp-has-text .gp-video-header:before{filter: none;}</style><![endif]-->';
		
		// Title fallback for versions earlier than WordPress 4.1
		if ( ! function_exists( '_wp_render_title_tag' ) && ! function_exists( 'ghostpool_render_title' ) ) {
			function ghostpool_render_title() { ?>
				<title><?php wp_title( '|', true, 'right' ); ?></title>
			<?php }
		}

		// Initial variables - variables loaded only once at the top of the page
		ghostpool_init_variables();	

		// Add custom JavaScript code
		if ( ghostpool_option( 'js_code' ) ) {
			if ( strpos( ghostpool_option( 'js_code' ), '<script ' ) !== false ) { 
				echo ghostpool_option( 'js_code' ); 
			} else {
				$js_code = str_replace( array( '<script>', '</script>' ), '', ghostpool_option( 'js_code' ) );
				echo '<script>' . $js_code . '</script>';
			}    
		}
				
	}
}
add_action( 'wp_head', 'ghostpool_wp_header' );

/**
 * Navigation user meta
 *
 */	
if ( ! function_exists( 'ghostpool_nav_user_meta' ) ) {
	function ghostpool_nav_user_meta( $user_id = NULL ) {

		// These are the metakeys we will need to update
		$GLOBALS['ghostpool_meta_key']['menus'] = 'metaboxhidden_nav-menus';
		$GLOBALS['ghostpool_meta_key']['properties'] = 'managenav-menuscolumnshidden';

		// So this can be used without hooking into user_register
		if ( ! $user_id ) {
			$user_id = get_current_user_id(); 
		}
	
		// Set the default hiddens if it has not been set yet
		if ( ! get_user_meta( $user_id, $GLOBALS['ghostpool_meta_key']['menus'], true ) ) {
			$meta_value = array( 'add-gp_slides', 'add-gp_slide' );
			update_user_meta( $user_id, $GLOBALS['ghostpool_meta_key']['menus'], $meta_value );
		}

		// Set the default properties if it has not been set yet
		if ( ! get_user_meta( $user_id, $GLOBALS['ghostpool_meta_key']['properties'], true) ) {
			$meta_value = array( 'link-target', 'xfn', 'description' );
			update_user_meta( $user_id, $GLOBALS['ghostpool_meta_key']['properties'], $meta_value );
		}
	
	}	
}
add_action( 'admin_init', 'ghostpool_nav_user_meta' );

/**
 * Insert schema meta data
 *
 */
if ( ! function_exists( 'ghostpool_meta_data' ) ) {
	function ghostpool_meta_data( $post_id ) {
	
		global $post;
		
		$global = get_option( 'socialize' );

		// Get title
		if ( ! empty( $GLOBALS['ghostpool_custom_title'] ) ) { 
			$title = esc_attr( $GLOBALS['ghostpool_custom_title'] );
		} else {
			$title = get_the_title( $post_id );
		}

		// Meta data
		return '<meta itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" content="' . esc_url( get_permalink( $post_id ) ) . '">
		<meta itemprop="headline" content="' . esc_attr( $title ) . '">			
		<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
			<meta itemprop="url" content="' . esc_url( wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ) ) . '">
			<meta itemprop="width" content="' . absint( $GLOBALS['ghostpool_image_width'] ) . '">	
			<meta itemprop="height" content="' . absint( $GLOBALS['ghostpool_image_height'] ) . '">		
		</div>
		<meta itemprop="author" content="' . get_the_author_meta( 'display_name', $post->post_author ) . '">			
		<meta itemprop="datePublished" content="' . get_the_time( 'Y-m-d' ) . '">
		<meta itemprop="dateModified" content="' . get_the_modified_date( 'Y-m-d' ) . '">
		<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="' . esc_url( ghostpool_option( 'desktop_logo', 'url' ) ) . '">
				<meta itemprop="width" content="' . absint( ghostpool_option( 'desktop_logo_dimensions', 'width' ) ) . '">
				<meta itemprop="height" content="' . absint( ghostpool_option( 'desktop_logo_dimensions', 'height' ) ) . '">
			</div>
			<meta itemprop="name" content="' . get_bloginfo( 'name' ) . '">
		</div>';

	}
}

/**
 * Insert breadcrumbs
 *
 */
if ( ! function_exists( 'ghostpool_breadcrumbs' ) ) {
	function ghostpool_breadcrumbs() {

		if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ) { 
			$breadcrumbs = yoast_breadcrumb( '<div id="gp-breadcrumbs">', '</div>' );
		} else {
			$breadcrumbs = '';
		}

	}
}

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 */
 
// Categories Widget
require_once( get_template_directory() . '/lib/widgets/categories.php' );
	
// Recent Comments Widget
require_once( get_template_directory() . '/lib/widgets/recent-comments.php' );

// Recent Posts Widget
require_once( get_template_directory() . '/lib/widgets/recent-posts.php' );

if ( ! function_exists( 'ghostpool_widgets_init' ) ) {
	function ghostpool_widgets_init() {

		// Sidebars
		register_sidebar( array( 
			'name'          => esc_html__( 'Right Sidebar', 'socialize' ),
			'id'            => 'gp-right-sidebar',
			'description'   => esc_html__( 'Displayed on posts, pages and post categories.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );
		
		register_sidebar( array( 
			'name'          => esc_html__( 'Left Sidebar', 'socialize' ),
			'id'            => 'gp-left-sidebar',
			'description'   => esc_html__( 'Displayed on posts, pages and post categories.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array( 
			'name'          => esc_html__( 'Homepage Left Sidebar', 'socialize' ),
			'id'            => 'gp-homepage-left-sidebar',
			'description'   => esc_html__( 'Displayed on the homepage.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array( 
			'name'          => esc_html__( 'Homepage Right Sidebar', 'socialize' ),
			'id'            => 'gp-homepage-right-sidebar',
			'description'   => esc_html__( 'Displayed on the homepage.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );
				
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 1', 'socialize' ),
			'id'            => 'gp-footer-1',
			'description'   => esc_html__( 'Displayed as the first column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );        

		register_sidebar( array(
			'name'          => esc_html__( 'Footer 2', 'socialize' ),
			'id'            => 'gp-footer-2',
			'description'   => esc_html__( 'Displayed as the second column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );        
	
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 3', 'socialize' ),
			'id'            => 'gp-footer-3',
			'description'   => esc_html__( 'Displayed as the third column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );        
	
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 4', 'socialize' ),
			'id'            => 'gp-footer-4',
			'description'   => esc_html__( 'Displayed as the fourth column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );      

		register_sidebar( array(
			'name'          => esc_html__( 'Footer 5', 'socialize' ),
			'id'            => 'gp-footer-5',
			'description'   => esc_html__( 'Displayed as the fifth column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );

		// Deprecated since v1.1
		/*register_sidebar( array( 
			'name'          => esc_html__( 'Standard Sidebar (Deprecated)', 'socialize' ),
			'id'            => 'gp-standard-sidebar',
			'description'   => esc_html__( 'Displayed on posts, pages and post categories.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );*/
			
	}
}
add_action( 'widgets_init', 'ghostpool_widgets_init' );

/**
 * Change excerpt character length
 *
 */
if ( ! function_exists( 'ghostpool_excerpt_length' ) ) {
	function ghostpool_excerpt_length() {
		if ( function_exists( 'buddyboss_global_search_init' ) && is_search() ) {
			return 50;
		} else {
			return 10000;
		}	
	}
}
add_filter( 'excerpt_length', 'ghostpool_excerpt_length' );

/**
 * Custom excerpt format
 *
 */	
if ( ! function_exists( 'ghostpool_excerpt' ) ) {
	function ghostpool_excerpt( $length ) {
		if ( isset( $GLOBALS['ghostpool_read_more_link'] ) && $GLOBALS['ghostpool_read_more_link'] == 'enabled' ) {
			$more_text = '...<a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" class="gp-read-more" title="' . the_title_attribute( 'echo=0' ) . '">' . esc_html__( '[Read More]', 'socialize' ) . '</a>';
		} else {
			$more_text = '...';
		}	
		
		if ( get_post_meta( get_the_ID(), 'video_description', true ) ) {
			$excerpt = get_post_meta( get_the_ID(), 'video_description', true );
		} else {
			$excerpt = get_the_excerpt();
		}
					
		$excerpt = strip_tags( $excerpt );
		if ( function_exists( 'mb_strlen' ) && function_exists( 'mb_substr' ) ) { 
			if ( mb_strlen( $excerpt ) > $length ) {
				$excerpt = mb_substr( $excerpt, 0, $length ) . $more_text;
			}
		} else {
			if ( strlen( $excerpt ) > $length ) {
				$excerpt = substr( $excerpt, 0, $length ) . $more_text;
			}	
		}
		return $excerpt;
	}
}

/**
 * Prefix portfolio categories
 *
 */
if ( ! function_exists( 'ghostpool_add_prefix_to_terms' ) ) {
	function ghostpool_add_prefix_to_terms( $term_id, $tt_id, $taxonomy ) {
		if ( $taxonomy == 'gp_portfolios' && ghostpool_option( 'portfolio_cat_prefix_slug' ) ) {
			$term = get_term( $term_id, $taxonomy );
			$args = array( 'slug' => ghostpool_option( 'portfolio_cat_prefix_slug' ) . '-' . $term->slug );
			wp_update_term( $term_id, $taxonomy, $args );
		} 
	}
}
add_action( 'created_term', 'ghostpool_add_prefix_to_terms', 10, 3 );

/**
 * Change password protect text
 *
 */	
if ( ! function_exists( 'ghostpool_password_form' ) ) {
	function ghostpool_password_form() {
		global $post;
		$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
		$o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
		<p>' . esc_html__( 'To view this protected post, enter the password below:', 'socialize' ) . '</p>
		<label for="' . $label . '"><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" /></label> <input type="submit" class="pwsubmit" name="Submit" value="' .  esc_attr__( 'Submit', 'socialize' ) . '" />
		</form>
		';
		return $o;
	}
}
add_filter( 'the_password_form', 'ghostpool_password_form' );

/**
 * Redirect empty search to search page
 *
 */
if ( ! function_exists( 'ghostpool_empty_search' ) ) {
	function ghostpool_empty_search( $query ) {
		global $wp_query;
		if ( isset( $_GET['s'] ) && ( $_GET['s'] == '' ) ) {
			$wp_query->set( 's', ' ' );
			$wp_query->is_search = true;
		}
		return $query;
	}
}
add_action( 'pre_get_posts', 'ghostpool_empty_search' );

/**
 * Alter category queries
 *
 */	
if ( ! function_exists( 'ghostpool_category_queries' ) ) {
	function ghostpool_category_queries( $query ) {	
		if ( is_admin() OR ! $query->is_main_query() ) { 
			return;
		} else {
			if ( is_post_type_archive( 'gp_portfolio_item' ) OR is_tax( 'gp_portfolios' ) )  {
				$GLOBALS['ghostpool_orderby'] = ghostpool_option( 'portfolio_cat_orderby' );
				$GLOBALS['ghostpool_per_page'] = ghostpool_option( 'portfolio_cat_per_page' );
				$GLOBALS['ghostpool_date_posted'] = ghostpool_option( 'portfolio_cat_date_posted' );
				$GLOBALS['ghostpool_date_modified'] = ghostpool_option( 'portfolio_cat_date_modified' );
			} elseif ( is_author() ) {
				$GLOBALS['ghostpool_orderby'] = ghostpool_option( 'search_orderby' );
				$GLOBALS['ghostpool_per_page'] = ghostpool_option( 'search_per_page' );
				$GLOBALS['ghostpool_date_posted'] = ghostpool_option( 'search_date_posted' );
				$GLOBALS['ghostpool_date_modified'] = ghostpool_option( 'search_date_modified' );
			} elseif ( is_search() ) {
				$GLOBALS['ghostpool_per_page'] = ghostpool_option( 'search_per_page' );
				$GLOBALS['ghostpool_date_posted'] = ghostpool_option( 'search_date_posted' );
				$GLOBALS['ghostpool_date_modified'] = ghostpool_option( 'search_date_modified' );				
			} elseif ( is_home() OR is_archive() ) {
				$GLOBALS['ghostpool_orderby'] = ghostpool_option( 'cat_orderby' );
				$GLOBALS['ghostpool_per_page'] = ghostpool_option( 'cat_per_page' );
				$GLOBALS['ghostpool_date_posted'] = ghostpool_option( 'cat_date_posted' );
				$GLOBALS['ghostpool_date_modified'] = ghostpool_option( 'cat_date_modified' );
			}
			if ( isset( $GLOBALS['ghostpool_per_page'] ) ) {
				ghostpool_loop_variables();
				ghostpool_category_variables();
				if ( isset( $GLOBALS['ghostpool_per_page'] ) ) {
					$query->set( 'posts_per_page', $GLOBALS['ghostpool_per_page'] );
				}
				if ( isset( $GLOBALS['ghostpool_orderby_value'] ) ) {
					$query->set( 'orderby', $GLOBALS['ghostpool_orderby_value'] );	
				}	
				if ( isset( $GLOBALS['ghostpool_order'] ) ) {
					$query->set( 'order', $GLOBALS['ghostpool_order'] );
				}	
				if ( isset( $GLOBALS['ghostpool_meta_key'] ) ) {
					$query->set( 'meta_key', $GLOBALS['ghostpool_meta_key'] );
				}
				if ( isset( $GLOBALS['ghostpool_date_posted_value'] ) && isset( $GLOBALS['ghostpool_date_modified_value'] ) ) {
					$query->set( 'date_query', array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ) );
				}
				return;
			}	
		}
	}
}	
add_action( 'pre_get_posts', 'ghostpool_category_queries', 1 );

/**
 * Pagination
 *
 */
if ( ! function_exists( 'ghostpool_pagination' ) ) {
	function ghostpool_pagination( $query ) {
		$big = 999999999;
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		if ( $query >  1 ) {
			return '<div class="gp-pagination gp-pagination-numbers gp-standard-pagination">' . paginate_links( array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, $paged ),
				'total'     => $query,
				'type'      => 'list',
				'prev_text' => '',
				'next_text' => '',
				'end_size'  => 1,
				'mid_size'  => 1, 
			) ) . '</div>';
		}
	}
}

if ( ! function_exists( 'ghostpool_get_previous_posts_page_link' ) ) {
	function ghostpool_get_previous_posts_page_link() {
		global $paged;
		$nextpage = intval( $paged ) - 1;
		if ( $nextpage < 1 ) {
			$nextpage = 1;
		}	
		if ( $paged > 1 ) {
			return '<a href="#" data-pagelink="' . esc_attr( $nextpage ) . '" class="prev"></a>';
		} else {
			return '<span class="prev gp-disabled"></span>';
		}
	}
}		

if ( ! function_exists( 'ghostpool_get_next_posts_page_link' ) ) {
	function ghostpool_get_next_posts_page_link( $max_page = 0 ) {
		global $paged;
		if ( ! $paged ) {
			$paged = 1;
		}	
		$nextpage = intval( $paged ) + 1;
		if ( ! $max_page || $max_page >= $nextpage ) {
			return '<a href="#" data-pagelink="' . esc_attr( $nextpage ) . '" class="next"></a>';
		} else {
			return '<span class="next gp-disabled"></span>';
		}
	}
}

/**
 * Custom next and prev rel links
 *
 */
if ( function_exists( 'wpseo_auto_load' ) ) {
	if ( ! function_exists( 'ghostpool_rel_prev_next' ) ) {
		function ghostpool_rel_prev_next() {
			if ( is_page_template( 'blog-template.php' ) OR is_page_template( 'portfolio-template.php' ) ) {
		
				global $paged;
		
				// Load page variables
				ghostpool_loop_variables();
				ghostpool_category_variables();
			
				if ( is_page_template( 'blog-template.php' ) ) {

					$args = array(
						'post_status' 	      => 'publish',
						'post_type'           => explode( ',', $GLOBALS['ghostpool_post_types'] ),
						'tax_query'           => $GLOBALS['ghostpool_tax'],
						'orderby'             => $GLOBALS['ghostpool_orderby_value'],
						'order'               => $GLOBALS['ghostpool_order'],
						'meta_key'            => $GLOBALS['ghostpool_meta_key'],
						'posts_per_page'      => $GLOBALS['ghostpool_per_page'],
						'paged'               => $GLOBALS['ghostpool_paged'],
						'date_query'          => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),	
					);

				} else {

					$args = array(
						'post_status'         => 'publish',
						'post_type'           => 'gp_portfolio_item',
						'tax_query'           => $GLOBALS['ghostpool_tax'],
						'posts_per_page'      => $GLOBALS['ghostpool_per_page'],
						'orderby'             => $GLOBALS['ghostpool_orderby_value'],
						'order'               => $GLOBALS['ghostpool_order'],
						'paged'               => $GLOBALS['ghostpool_paged'],
						'date_query'          => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),	
					);
					
				}	

				// Contains query data
				$query = new wp_query( $args );
			
				// Get maximum pages from query
				$max_page = $query->max_num_pages;
			
				if ( ! $paged ) {
					$paged = 1;
				}
		
				// Prev rel link
				$prevpage = intval( $paged ) - 1;
				if ( $prevpage < 1 ) {
					$prevpage = 1;
				}	
				if ( $paged > 1 ) {
					echo '<link rel="prev" href="' . get_pagenum_link( $prevpage ) . '">';
				}
		
				// Next rel link
				$nextpage = intval( $paged ) + 1;	
				if ( ! $max_page OR $max_page >= $nextpage ) {
					echo '<link rel="next" href="' . get_pagenum_link( $nextpage ) . '">';
				}

				// Meta noindex,follow on paginated page templates
				if ( ( is_page_template( 'blog-template.php' ) OR is_page_template( 'portfolio-template.php' ) ) && $paged > 1 ) {
					echo '<meta name="robots" content="noindex,follow">';
				}
					
			}
		}
	}	
	add_action( 'wp_head', 'ghostpool_rel_prev_next' );
}
/**
 * Custom canonical link
 *
 */
if ( function_exists( 'wpseo_auto_load' ) ) {	
	if ( ! function_exists( 'ghostpool_canonical_link' ) ) {	
		function ghostpool_canonical_link( $canonical ) {
			if ( is_page_template( 'blog-template.php' ) OR is_page_template( 'portfolio-template.php' ) ) {
				global $paged;		
				if ( ! $paged ) {
					$paged = 1;
				}
				return get_pagenum_link( $paged );
			} else {
				return $canonical;
			}
		}
	}
	add_filter( 'wpseo_canonical', 'ghostpool_canonical_link' );
}

/**
 * Exclude categories
 *
 */
if ( ! function_exists( 'ghostpool_exclude_cats' ) ) {
	function ghostpool_exclude_cats( $post_id, $no_link = false, $loop ) {
					
		// Get categories for post
		$cats = wp_get_object_terms( $post_id, 'category', array( 'fields' => 'ids' ) );
		
		// Remove categories that are excluded
		if ( ghostpool_option( 'cat_exclude_cats' ) ) { 
			$excluded_cats = array_diff( $cats, ghostpool_option( 'cat_exclude_cats' ) );
		} else {
			$excluded_cats = $cats;
		}
		
		// Construct new categories loop
		if ( ! empty( $excluded_cats ) && ! is_wp_error( $excluded_cats ) ) { 		
			$cat_link = '';
			foreach( $excluded_cats as $excluded_cat ) {
				if ( has_term( $excluded_cat, 'category', $post_id ) ) {
					$term = get_term( $excluded_cat, 'category' );
					$term_link = get_term_link( $term, 'category' );
					if ( ! $term_link OR is_wp_error( $term_link ) ) {
						continue;
					}
					if ( $no_link == true ) {
						$cat_link .= esc_attr( $term->name ) . ' / ';
					} else {
						$cat_link .= '<a href="' . esc_url( $term_link ) . '">' . esc_attr( $term->name ) . '</a> / ';
					}
				}
			}
			if ( $loop == true ) {
				return '<div class="gp-loop-cats">' . rtrim( $cat_link, ' / ' ) . '</div>';
			} else {			
				return '<div class="gp-entry-cats">' . rtrim( $cat_link, ' / ' ) . '</div>';
			}
		}

	}
}

/**
 * Remove hentry tag from post loop
 *
 */
if ( ! function_exists( 'ghostpool_remove_hentry' ) ) {
	function ghostpool_remove_hentry( $classes ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
		return $classes;
	}
}
add_filter( 'post_class', 'ghostpool_remove_hentry' );

/**
 * Add lightbox class to image links
 *
 */
if ( ! function_exists( 'ghostpool_lightbox_image_link' ) ) {
	function ghostpool_lightbox_image_link( $content ) {	
		global $post;
		if ( ghostpool_option( 'lightbox' ) != 'disabled' ) {
			if ( ghostpool_option( 'lightbox' ) == 'group_images' ) {
				$group = '[image-' . $post->ID . ']';
			} else {
				$group = '';
			}
			$pattern = "/<a(.*?)href=('|\")(.*?).(jpg|jpeg|png|gif|bmp|ico)('|\")(.*?)>/i";
			preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER );
			foreach ( $matches as $val ) {
				$pattern = '<a' . $val[1] . 'href=' . $val[2] . $val[3] . '.' . $val[4] . $val[5] . $val[6] . '>';
				$replacement = '<a' . $val[1] . 'href=' . $val[2] . $val[3] . '.' . $val[4] . $val[5] . ' data-rel="prettyPhoto' . $group . '"' . $val[6] . '>';
				$content = str_replace( $pattern, $replacement, $content );			
			}
			return $content;
		} else {
			return $content;
		}
	}	
}
add_filter( 'the_content', 'ghostpool_lightbox_image_link' );	
add_filter( 'wp_get_attachment_link', 'ghostpool_lightbox_image_link' );
add_filter( 'bbp_get_reply_content', 'ghostpool_lightbox_image_link' );

/**
 * TGM Plugin Activation class
 *
 */
if ( version_compare( phpversion(), '5.2.4', '>=' ) ) {
	require_once( get_template_directory() . '/lib/inc/class-tgm-plugin-activation.php' );
}

if ( ! function_exists( 'ghostpool_register_required_plugins' ) ) {
	
	function ghostpool_register_required_plugins() {

		$plugins = array(

			array(
				'name'               => esc_html__( 'Socialize Plugin', 'socialize' ),
				'slug'               => 'socialize-plugin',
				'source'             => get_template_directory() . '/lib/plugins/socialize-plugin.zip',
				'required'           => true,
				'version'            => '3.9.6.2',
				'force_activation'   => false,
				'force_deactivation' => false,
			),

			array(
				'name'               => esc_html__( 'WPBakery Page Builder', 'socialize' ),
				'slug'               => 'js_composer',
				'source'             => get_template_directory() . '/lib/plugins/js_composer.zip',
				'required'           => true,
				'version'            => '5.5',
				'force_activation'	 => false,
				'force_deactivation' => false,
			),

			array(
				'name'               => esc_html__( 'Visual Sidebars Editor', 'socialize' ),
				'slug'               => 'visual-sidebars-editor',
				'source'             => get_template_directory() . '/lib/plugins/visual-sidebars-editor.zip',
				'required'           => true,
				'version'            => '1.2.5',
				'force_activation'	 => false,
				'force_deactivation' => false,
			),
			
			array(
				'name'   		     => esc_html__( 'Theia Sticky Sidebar', 'socialize' ),
				'slug'   		     => 'theia-sticky-sidebar',
				'source'   		     => get_template_directory() . '/lib/plugins/theia-sticky-sidebar.zip',
				'required'   		 => false,
				'version'   		 => '1.8.0',
				'force_activation'	 => false,
				'force_deactivation' => false,
			),

			array(
				'name'      => esc_html__( 'BuddyPress', 'socialize' ),
				'slug'      => 'buddypress',
				'required' 	=> false,
			),
			
			array(
				'name'      => esc_html__( 'bbPress', 'socialize' ),
				'slug'      => 'bbpress',
				'required' 	=> false,
			),
						
			array(
				'name'      => esc_html__( 'The Events Calendar', 'socialize' ),
				'slug'      => 'the-events-calendar	',
				'required' 	=> false,
			),
												
			array(
				'name'      => esc_html__( 'Contact Form 7', 'socialize' ),
				'slug'      => 'contact-form-7',
				'required' 	=> false,
			),

			array(
				'name'      => esc_html__( 'WordPress Social Login', 'socialize' ),
				'slug'      => 'wordpress-social-login',
				'required' 	=> false,
			),
			
			array(
				'name'   		     => esc_html__( 'Google Captcha', 'socialize' ),
				'slug'   		     => 'google-captcha',
				'required'   		 => false,
			),
			
			array(
				'name'      => esc_html__( 'Post Views Counters', 'socialize' ),
				'slug'      => 'post-views-counter',
				'required' 	=> false,
			),
			
			array(
				'name'      => esc_html__( 'Yoast SEO', 'socialize' ),
				'slug'      => 'wordpress-seo',
				'required' 	=> false,
				'is_callable' => 'wpseo_init',
			),
																							
		);

		$config = array(
			'id'           => 'socialize',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'has_notices'  => true,                 
			'dismissable'  => true,                  
			'dismiss_msg'  => '',
			'is_automatic' => true,
			'message'      => '',
		);
 
		tgmpa( $plugins, $config );

	}
}
add_action( 'tgmpa_register', 'ghostpool_register_required_plugins' );

?>