<?php
/**
 * Plugin Name: Storefront Reviews
 * Plugin URI: http://woocommerce.com/products/storefront-reviews/
 * Description: Display product reviews on the across your Storefront powered WooCommerce shop.
 * Version: 1.0.6
 * Author: WooCommerce
 * Author URI: http://woocommerce.com/
 * Requires at least: 4.2
 * Tested up to: 5.2
 * Woo: 1044976:0c8a1d86b8eff9f1edffa923aeb3fc1f
 *
 * Text Domain: storefront-reviews
 * Domain Path: /languages/
 *
 * @package Storefront_Reviews
 * @category Core
 * @author James Koster
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '0c8a1d86b8eff9f1edffa923aeb3fc1f', '1044976' );
// Sold On Woo - End

/**
 * Returns the main instance of Storefront_Reviews to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Storefront_Reviews
 */
function Storefront_Reviews() {
	return Storefront_Reviews::instance();
} // End Storefront_Reviews()

Storefront_Reviews();

/**
 * Main Storefront_Reviews Class
 *
 * @class Storefront_Reviews
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Reviews
 */
final class Storefront_Reviews {
	/**
	 * Storefront_Reviews The single instance of Storefront_Reviews.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The shortcode generator object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $shortcode_generator;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'storefront-reviews';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.6';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'storefront_reviews_load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'setup' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_links' ) );

		/**
		 * Custom Classes
		 */
		require_once 'includes/class-storefront-reviews-shortcode-generator.php';

		$this->shortcode_generator = new Storefront_Reviews_Shortcode_Generator();
	}

	/**
	 * Main Storefront_Reviews Instance
	 *
	 * Ensures only one instance of Storefront_Reviews is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Storefront_Reviews()
	 * @return Main Storefront_Reviews instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function storefront_reviews_load_plugin_textdomain() {
		load_plugin_textdomain( 'storefront-reviews', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Plugin page links
	 *
	 * @since  1.0.0
	 */
	public function plugin_links( $links ) {
		$plugin_links = array(
			'<a href="https://woocommerce.com/contact-us/">' . __( 'Support', 'storefront-reviews' ) . '</a>',
			'<a href="http://docs.woocommerce.com/document/storefront-reviews/">' . __( 'Docs', 'storefront-reviews' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();

		// get theme customizer url
		$url = admin_url() . 'customize.php?';
		$url .= 'url=' . urlencode( site_url() . '?storefront-customizer=true' ) ;
		$url .= '&return=' . urlencode( admin_url() . 'plugins.php' );
		$url .= '&storefront-customizer=true';

		$notices 		= get_option( 'storefront_reviews_activation_notice', array() );
		$notices[]		= sprintf( __( '%sThanks for installing the Storefront Reviews extension. To get started, visit the %sCustomizer%s.%s %sOpen the Customizer%s', 'storefront-reviews' ), '<p>', '<a href="' . esc_url( $url ) . '">', '</a>', '</p>', '<p><a href="' . esc_url( $url ) . '" class="button button-primary">', '</a></p>' );

		update_option( 'storefront_reviews_activation_notice', $notices );
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Setup all the things.
	 * Only executes if Storefront or a child theme using Storefront as a parent is active and the extension specific filter returns true.
	 * Child themes can disable this extension using the storefront_reviews_supported filter
	 * @return void
	 */
	public function setup() {
		$theme = wp_get_theme();

		if ( 'Storefront' == $theme->name || 'storefront' == $theme->template && apply_filters( 'storefront_reviews_supported', true ) ) {
			add_filter( 'body_class', array( $this, 'body_classes' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'styles' ), 999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'customize_register', array( $this, 'storefront_reviews_customize_register' ) );
			add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
			add_action( 'admin_notices', array( $this, 'customizer_notice' ) );
			add_action( 'homepage', array( $this, 'storefront_homepage_reviews' ), 90 );

			// Hide the 'More' section in the customizer
			add_filter( 'storefront_customizer_more', '__return_false' );
		} else {
			add_action( 'admin_notices', array( $this, 'install_storefront_notice' ) );
		}
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since  1.0.4
	 * @param  array $classes Classes for the body element.
	 * @return array
	 */
	public function body_classes( $classes ) {
		global $storefront_version;

		if ( version_compare( $storefront_version, '2.3.0', '<' ) ) {
			$classes[] = 'storefront-reviews-compatibility';
		}

		return $classes;
	}

	/**
	 * Admin notice
	 * Checks the notice setup in install(). If it exists display it then delete the option so it's not displayed again.
	 * @since   1.0.0
	 * @return  void
	 */
	public function customizer_notice() {
		$notices = get_option( 'storefront_reviews_activation_notice' );

		if ( $notices = get_option( 'storefront_reviews_activation_notice' ) ) {

			foreach ( $notices as $notice ) {
				echo '<div class="notice is-dismissible updated">' . $notice . '</div>';
			}

			delete_option( 'storefront_reviews_activation_notice' );
		}
	}

	/**
	 * Storefront install
	 * If the user activates the plugin while having a different parent theme active, prompt them to install Storefront.
	 * @since   1.0.0
	 * @return  void
	 */
	public function install_storefront_notice() {
		echo '<div class="notice is-dismissible updated">
				<p>' . __( 'Storefront Reviews requires that you use Storefront as your parent theme.', 'storefront-reviews' ) . ' <a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=storefront' ), 'install-theme_storefront' ) ) .'">' . __( 'Install Storefront now', 'storefront-reviews' ) . '</a></p>
			</div>';
	}

	/**
	 * Customizer Controls and settings
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function storefront_reviews_customize_register( $wp_customize ) {

		/**
	     * Add a new section
	     */
        $wp_customize->add_section( 'storefront_reviews_section' , array(
		    'title'      		=> __( 'Storefront Reviews', 'storefront-reviews' ),
		    'priority'   		=> 55,
		    'active_callback'	=> array( $this, 'storefront_homepage_template_callback' ),
		) );

        /**
         * Title
         */
		$wp_customize->add_setting( 'storefront_reviews_heading_text', array(
	        'default'           => __( 'Recent Reviews', 'storefront-reviews' ),
	        'sanitize_callback' => 'sanitize_text_field',
	        'transport'			=> 'postMessage',
	    ) );

	    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'storefront_reviews_heading_text', array(
            'label'         	=> __( 'Product reviews title', 'storefront-reviews' ),
            'section'       	=> 'storefront_reviews_section',
            'settings'      	=> 'storefront_reviews_heading_text',
            'type'     			=> 'text',
            'priority'			=> 10,
        ) ) );

        $wp_customize->add_setting( 'storefront_reviews_reviews_type', array(
			'default' 			=> 'recent',
			'sanitize_callback'	=> 'storefront_sanitize_choices',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'storefront_reviews_reviews_type', array(
			'label'			=> __( 'Reviews to display', 'storefront-reviews' ),
			'section'		=> 'storefront_reviews_section',
			'settings'		=> 'storefront_reviews_reviews_type',
			'type'			=> 'select',
			'priority'		=> 11,
			'choices'		=> array(
				'recent'			=> 'Recent Reviews',
				'specific-product'	=> 'Reviews of a specific product',
				'specific-reviews'	=> 'Specific reviews',
			),
		) ) );

        /**
         * Specific Product
         */
		$wp_customize->add_setting( 'storefront_reviews_product', array(
	        'default'           => 0,
	        'sanitize_callback' => 'absint',
	    ) );

	    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'storefront_reviews_product', array(
            'label'         	=> __( 'Product ID', 'storefront-reviews' ),
            'description'		=> __( 'Display reviews from a specific product by adding the ID here', 'storefront-reviews' ),
            'section'       	=> 'storefront_reviews_section',
            'settings'      	=> 'storefront_reviews_product',
            'type'     			=> 'text',
            'priority'			=> 12,
            'active_callback' 	=> array( $this, 'specific_product_callback' ),
        ) ) );


		/**
         * Specific Reviews
         */
		$wp_customize->add_setting( 'storefront_reviews_specific_reviews', array(
	        'default'           => '',
	        'sanitize_callback' => 'sanitize_text_field',
	    ) );

	    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'storefront_reviews_specific_reviews', array(
            'label'         	=> __( 'Review IDs', 'storefront-reviews' ),
            'description'		=> __( 'Comma separate specific review IDs to display them', 'storefront-reviews' ),
            'section'       	=> 'storefront_reviews_section',
            'settings'      	=> 'storefront_reviews_specific_reviews',
            'type'     			=> 'text',
            'priority'			=> 13,
            'active_callback' 	=> array( $this, 'specific_reviews_callback' ),
        ) ) );

        if ( class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'layout_divider', array(
				'section'  	=> 'storefront_reviews_section',
				'type'		=> 'divider',
				'priority' 	=> 14,
			) ) );
		}

        /**
		 * Style
		 */
		$wp_customize->add_setting( 'storefront_reviews_layout', array(
			'default'    		=> 'style-1',
			'sanitize_callback'	=> 'sanitize_key'
		) );

		$wp_customize->add_control( new Storefront_Custom_Radio_Image_Control( $wp_customize, 'storefront_reviews_layout', array(
					'settings'		=> 'storefront_reviews_layout',
					'section'		=> 'storefront_reviews_section',
					'label'    		=> __( 'Review display', 'storefront-reviews' ),
					'description'   => __( 'Choose a design/layout for the reviews', 'storefront-reviews' ),
					'priority'		=> 14,
					'choices'		=> array(
						'style-1' 		=> plugins_url( '/assets/img/admin/style-1.png', __FILE__ ),
						'style-2' 		=> plugins_url( '/assets/img/admin/style-2.png', __FILE__ ),
						'style-3' 		=> plugins_url( '/assets/img/admin/style-3.png', __FILE__ ),
					)
		) ) );

		/**
		 * Gravatar
		 */
		$wp_customize->add_setting( 'storefront_reviews_gravatar', array(
			'default'			=> apply_filters( 'storefront_reviews_gravatar_default', true ),
			'sanitize_callback'	=> 'absint',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'storefront_reviews_gravatar', array(
			'label'			=> __( 'Display Gravatar', 'storefront-reviews' ),
			'description'	=> __( 'Display the reviwers Gravatar?', 'storefront-reviews' ),
			'section'		=> 'storefront_reviews_section',
			'settings'		=> 'storefront_reviews_gravatar',
			'type'			=> 'checkbox',
			'priority'		=> 15,
		) ) );

        if ( class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'id_divider', array(
				'section'  	=> 'storefront_reviews_section',
				'type'		=> 'divider',
				'priority' 	=> 17,
			) ) );
		}

		if ( class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'id_divider', array(
				'section'  	=> 'storefront_reviews_section',
				'type'		=> 'divider',
				'priority' 	=> 18,
			) ) );
		}

		/**
         * Number
         */
        $wp_customize->add_setting( 'storefront_reviews_number', array(
	        'default'           => '2',
	        'sanitize_callback'	=> 'storefront_sanitize_choices',
	    ) );

	    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'storefront_reviews_number', array(
			'label'       => __( 'Maximum reviews', 'storefront-reviews' ),
			'description' => __( 'The maximum number of reviews to display', 'storefront-reviews' ),
			'section'     => 'storefront_reviews_section',
			'settings'    => 'storefront_reviews_number',
			'type'        => 'select',
			'priority'    => 19,
			'choices'     => array(
				'1'           => '1',
				'2'           => '2',
				'3'           => '3',
				'4'           => '4',
				'5'           => '5',
				'6'           => '6',
				'7'           => '7',
				'8'           => '8',
				'9'           => '9',
				'10'          => '10',
			),
        ) ) );

        /**
         * Columns
         */
        $wp_customize->add_setting( 'storefront_reviews_columns', array(
	        'default'           => '2',
	        'sanitize_callback'	=> 'storefront_sanitize_choices',
	    ) );

	    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'storefront_reviews_columns', array(
			'label'       => __( 'Review columns', 'storefront-reviews' ),
			'description' => __( 'The number of columns reviews are arranged in to', 'storefront-reviews' ),
			'section'     => 'storefront_reviews_section',
			'settings'    => 'storefront_reviews_columns',
			'type'        => 'select',
			'priority'    => 20,
			'choices'     => array(
				'1'           => '1',
				'2'           => '2',
				'3'           => '3',
			),
        ) ) );

         if ( class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'carousel_divider_before', array(
				'section'  	=> 'storefront_reviews_section',
				'type'		=> 'divider',
				'priority' 	=> 20,
			) ) );
		}

        /**
		 * Carousel
		 */
		$wp_customize->add_setting( 'storefront_reviews_carousel', array(
			'default'			=> apply_filters( 'storefront_reviews_checkbox_default', false ),
			'sanitize_callback'	=> 'absint',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'storefront_reviews_carousel', array(
			'label'			=> __( 'Carousel', 'storefront-reviews' ),
			'description'	=> __( 'Display the reviews in a carousel', 'storefront-reviews' ),
			'section'		=> 'storefront_reviews_section',
			'settings'		=> 'storefront_reviews_carousel',
			'type'			=> 'checkbox',
			'priority'		=> 20,
		) ) );

		if ( class_exists( 'Arbitrary_Storefront_Control' ) ) {
			$wp_customize->add_control( new Arbitrary_Storefront_Control( $wp_customize, 'carousel_divider_after', array(
				'section'  	=> 'storefront_reviews_section',
				'type'		=> 'divider',
				'priority' 	=> 21,
			) ) );
		}

        /**
		 * Color picker
		 */
		$wp_customize->add_setting( 'storefront_reviews_star_color', array(
			'default'			=> apply_filters( 'storefront_reviews_star_color_default', apply_filters( 'storefront_default_accent_color', '#96588a' ) ),
			'sanitize_callback'	=> 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'storefront_reviews_star_color', array(
			'label'			=> __( 'Star color', 'storefront-reviews' ),
			'description'	=> __( 'The color of the star ratings throughout the store', 'storefront-reviews' ),
			'section'		=> 'storefront_reviews_section',
			'settings'		=> 'storefront_reviews_star_color',
			'priority'		=> 22,
		) ) );

	}

	/**
	 * Specific product callback
	 * @return bool
	 */
	public function specific_product_callback( $control ) {
	    return $control->manager->get_setting( 'storefront_reviews_reviews_type' )->value() == 'specific-product' ? true : false;
	}

	/**
	 * Specific reviews callback
	 * @return bool
	 */
	public function specific_reviews_callback( $control ) {
	    return $control->manager->get_setting( 'storefront_reviews_reviews_type' )->value() == 'specific-reviews' ? true : false;
	}

	/**
	 * Homepage callback
	 * @return bool
	 */
	public function storefront_homepage_template_callback() {
		return is_page_template( 'template-homepage.php' ) ? true : false;
	}

	/**
	 * Enqueue scripts
	 * @since   1.0.0
	 * @return  void
	 */
	public function scripts() {
		wp_register_script( 'owl-carousel', plugins_url( '/assets/js/owl.carousel.min.js', __FILE__ ), array( 'jquery' ), '1.3.3' );
		wp_register_script( 'owl-carousel-init', plugins_url( '/assets/js/owl.carousel.init.min.js', __FILE__ ), array( 'owl-carousel' ), '1.0.0' );

		$translation_array = array(
			'columns'	=> get_theme_mod( 'storefront_reviews_columns', '2' ),
			'previous' 	=> __( 'Previous', 'storefront-reviews' ),
			'next'		=> __( 'Next', 'storefront-reviews' ),
		);

		wp_localize_script( 'owl-carousel-init', 'carousel_parameters', $translation_array );
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since   1.0.0
	 * @return  void
	 */
	public function styles() {
		wp_enqueue_style( 'sr-styles', plugins_url( '/assets/css/style.css', __FILE__ ) );

		$content_bg_color				= get_theme_mod( 'sd_content_background_color' );
		$content_frame 					= get_theme_mod( 'sd_fixed_width' );
		$star_color 					= get_theme_mod( 'storefront_reviews_star_color', apply_filters( 'storefront_default_accent_color', '#96588a' ) );
		$accent_color 					= get_theme_mod( 'storefront_accent_color' );

		if ( $content_bg_color && 'true' == $content_frame && class_exists( 'Storefront_Designer' ) ) {
			$bg_color 	= str_replace( '#', '', $content_bg_color );
		} else {
			$bg_color	= get_theme_mod( 'background_color' );
		}


		$storefront_reviews_style = '
		.style-2 .sr-review-content {
			background-color: ' . storefront_adjust_color_brightness( $bg_color, 10 ) . ';
		}

		.style-2 .sr-review-content:after {
			border-top-color: ' . storefront_adjust_color_brightness( $bg_color, 10 ) . ' !important;
		}

		.star-rating span:before,
		.star-rating:before {
			color: ' . $star_color . ';
		}

		.star-rating:before {
			opacity: 0.25;
		}

		.sr-carousel .owl-prev:before, .sr-carousel .owl-next:before {
			color: ' . $accent_color . ';
		}

		ul.product-reviews li.product-review.style-3 .inner {
			background-color: ' . Storefront_Reviews::hex_to_rgba( $bg_color, 0.8 ) . ';
		}';

		wp_add_inline_style( 'sr-styles', $storefront_reviews_style );
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @since  1.0.0
	 */
	public function customize_preview_js() {
		wp_enqueue_script( 'sr-customizer', plugins_url( '/assets/js/customizer.min.js', __FILE__ ), array( 'customize-preview' ), '1.3', true );
	}

	/**
	 * Display the reviews
	 * @param  [type] $atts [description]
	 * @return [type]       [description]
	 */
	public static function reviews( $atts ) {
		$reviews_type 	= get_theme_mod( 'storefront_reviews_reviews_type', 'recent' );

		$product 		= 0;

		if ( 'specific-product' == $reviews_type ) {
			$product = get_theme_mod( 'storefront_reviews_product', 0 );
		}

		$specific 		= '';

		if ( 'specific-reviews' == $reviews_type ) {
			$specific = get_theme_mod( 'storefront_reviews_specific_reviews', 0 );
		}

		$atts = extract( shortcode_atts( array(
							'title'			=> sanitize_text_field( get_theme_mod( 'storefront_reviews_heading_text', __( 'Product Reviews', 'storefront-reviews' ) ) ),
							'columns'		=> get_theme_mod( 'storefront_reviews_columns', '2' ),
							'number'		=> get_theme_mod( 'storefront_reviews_number', '2' ),
							'scope'			=> get_theme_mod( 'storefront_reviews_reviews_type', 'recent' ),
							'product_id'	=> $product,
							'review_ids'	=> $specific,
							'layout'		=> get_theme_mod( 'storefront_reviews_layout', 'style-1' ),
							'gravatar'		=> get_theme_mod( 'storefront_reviews_gravatar', true ),
							'carousel'		=> get_theme_mod( 'storefront_reviews_carousel', false ),

		), $atts, 'storefront_reviews' ) );

		// Check for reviews
		$reviews = get_comments( array(
							'number' 		=> $number,
							'post_id'		=> $product_id,
							'status' 		=> 'approve',
							'post_status' 	=> 'publish',
							'comment__in'	=> $review_ids,
							'post_type' 	=> 'product',
							'parent'		=> 0, )
						);

		// If reviews are found, do the stuff
		if ( $reviews ) {

			$carousel_class = '';

			if ( true == $carousel ) {
				$carousel_class = 'owl-carousel';
			}

			echo '<div class="storefront-product-section storefront-reviews">';

				echo '<div class="woocommerce columns-' . esc_attr( $columns ) . '">';

					if ( $title ) {
						echo '<h2 class="section-title"><span>' . wp_kses_post( $title ) . '</span></h2>';
					}

					echo '<ul class="product-reviews ' . esc_attr( $carousel_class ) . '" data-columns="' . esc_attr( $columns ) . '">';

					$count = 0;

					foreach ( (array) $reviews as $review ) {
						$gravatar_output 	= '';
						$gravatar_url		= '';

						if ( true == esc_attr( $gravatar ) ) {
							$gravatar_output 	= get_avatar( $review->comment_author_email );
							$gravatar_url		= get_avatar_url( $review->comment_author_email, array( 'size' => 500 ) );
						}

						$_product 		= wc_get_product( $review->comment_post_ID );

						$rating 		= intval( get_comment_meta( $review->comment_ID, 'rating', true ) );

						$rating_html 	= wc_get_rating_html( $rating );

						$count++;

						$class = '';

						if ( 0 == $count % $columns ) {
							$class = 'last';
						}

						if ( 1 == $count % $columns ) {
							$class = 'first';
						}

						echo '<li class="product-review ' . $class . ' ' . esc_attr( $layout ) . '">';

							if ( 'style-1' == $layout ) {
								echo '<a href="' . esc_url( get_comment_link( $review->comment_ID ) ) . '" class="sr-images">';
									echo wp_kses_post( $_product->get_image( 'shop_catalog' ) );
									echo wp_kses_post( $gravatar_output );
								echo '</a>';

								echo '<div class="sr-review-content">';

									echo wp_kses_post( $rating_html );

									echo '<p><strong>' . esc_attr( $_product->get_title() ) . '</strong> (' .wp_kses_post( $_product->get_price_html() ) . ') <br />' . __( 'reviewed by', 'storefront-reviews' ) . ' ' . get_comment_author_link( intval( $review->comment_ID ) ) . '</p>';

									echo '<hr />';

									echo wp_kses_post( wpautop( $review->comment_content ) );

									echo '<a href="' . esc_url( get_permalink( $review->comment_post_ID ) ) . '" class="sr-view-product">' . __( 'View this product', 'storefront-reviews' ) . ' &rarr;</a>';

								echo '</div>';
							} elseif ( 'style-2' == $layout ) {
								echo '<div class="sr-review-content">';

									echo wp_kses_post( $_product->get_image( 'woocommerce_gallery_thumbnail' ) );

									echo wp_kses_post( $rating_html );

									echo '<p><strong>' . esc_attr( $_product->get_title() ) . '</strong> (' . wp_kses_post( $_product->get_price_html() ) . ')</p>';

									echo wp_kses_post( wpautop( $review->comment_content ) );

									echo '<a href="' . esc_url( get_permalink( $review->comment_post_ID ) ) . '" class="sr-view-product">' . __( 'View this product', 'storefront-reviews' ) . ' &rarr;</a>';

								echo '</div>';

								echo '<div class="sr-review-meta">';
									echo $gravatar_output;
									echo '<strong>' . get_comment_author_link( intval( $review->comment_ID ) ) . '</strong>' . '<br /><date>' . $review->comment_date . '</date>';
								echo '</div>';
							} elseif ( 'style-3' == $layout ) {
								echo '<div class="sr-review-content" style="background-image: url(' . esc_url( $gravatar_url ) . '); background-size: cover;">';

									echo '<div class="inner">';

										echo $_product->get_image( 'woocommerce_gallery_thumbnail' );

										echo $rating_html;

										echo '<p><strong>' . esc_attr( $_product->get_title() ) . '</strong> (' .wp_kses_post( $_product->get_price_html() ) . ') <br />' . __( 'reviewed by', 'storefront-reviews' ) . ' ' . get_comment_author_link( $review->comment_ID ) . '</p>';

										echo '<hr />';

										echo wp_kses_post( wpautop( $review->comment_content ) );

										echo '<a href="' . esc_url( get_permalink( $review->comment_post_ID ) ) . '" class="sr-view-product">' . __( 'View this product', 'storefront-reviews' ) . ' &rarr;</a>';

									echo '</div>';

								echo '</div>';
							}

						echo '</li>';
					}

					echo '</ul>';

				echo '</div>';

			echo '</div>';

			if ( true == $carousel ) {
				wp_enqueue_script( 'owl-carousel' );
				wp_enqueue_script( 'owl-carousel-init' );
			}
		}
	}

	/**
	 * Display the reviews section via shortcode
	 * @see reviews()
	 */
	public static function reviews_shortcode( $atts ) {
		ob_start();
		Storefront_Reviews()->reviews( $atts );
		return ob_get_clean();
	}

	/**
	 * Display the reviews on the homepage
	 */
	public static function storefront_homepage_reviews() {
		$atts = array( 'heading_text' => sanitize_text_field( get_theme_mod( 'storefront_reviews_heading_text', __( 'Heading Text', 'storefront-reviews' ) ) ) );

		Storefront_Reviews()->reviews( $atts );
	}

	public function hex_to_rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if ( empty( $color ) ) {
			return $default;
		}

		//Sanitize $color if "#" is provided
        if ( $color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

		//Check if color has 6 or 3 characters and get values
        if ( strlen( $color ) == 6 ) {
        	$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
        	$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
        	return $default;
        }

		//Convert hexadec to rgb
        $rgb =  array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}

			$output = 'rgba( ' . implode( ", ", $rgb ) . ',' . $opacity . ' )';
		} else {
			$output = 'rgb( ' . implode( ", ", $rgb ) . ' )';
		}

		//Return rgb(a) color string
        return $output;
	}
} // End Class

// Create a shortcode to display the reviews
add_shortcode( 'storefront_reviews', array( 'Storefront_Reviews', 'reviews_shortcode' ) );
