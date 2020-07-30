<?php

class Themify_Shortcodes {

	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
		return null == self::$instance ? self::$instance = new self : self::$instance;
	}

	private function __construct() {
		$is_themify_theme = $this->is_using_themify_theme();

		if( ! $is_themify_theme || ( $is_themify_theme && $this->deprecated_shortcodes() ) ) {
			add_action( 'init', array( $this, 'i18n' ) );
			add_action( 'init', array( $this, 'load_dependencies' ), 1 );
			add_action( 'init', array( $this, 'admin' ), 9 );
			add_action( 'init', array( $this, 'register' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 11 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
			add_filter( 'themify_twitter_credentials', array( $this, 'themify_twitter_credentials' ) );
			add_filter( 'themify_get_shortcode_template_file', array( $this, 'themify_get_shortcode_template_file' ), 10, 3 );
			add_filter( 'themify_twitter_missing_key_message', array( $this, 'themify_twitter_missing_key_message' ) );
		} else {
			return;
		}
	}

	public function i18n() {
		load_plugin_textdomain( 'themify-shortcodes', false, THEMIFY_SHORTCODES_DIR . '/languages' );
	}

	/**
	 * Returns true if the active theme is using Themify framework
	 *
	 * @return bool
	 */
	public function is_using_themify_theme() {
		return file_exists( get_template_directory() . '/themify/themify-utils.php' );
	}

	/**
	 * Check if shortcodes are deprecated in Themify framework (3.1.3+)
	 *
	 * @return bool
	 */
	function deprecated_shortcodes() {
		$flags = get_option( 'themify_flags', array() );
		if( isset( $flags['deprecate_shortcodes'] ) ) {
			return true;
		}

		return false;
	}

	public function load_dependencies() {
		// TinyMCE
		if( ! class_exists( 'Themify_TinyMCE' ) ) {
			define( 'THEMIFY_TINYMCE_URI', THEMIFY_SHORTCODES_URI . 'themify/tinymce/' );
			define( 'THEMIFY_TINYMCE_DIR', THEMIFY_SHORTCODES_DIR . 'themify/tinymce/' );

			include( THEMIFY_TINYMCE_DIR . 'class-themify-tinymce.php' );
		}

		// Icon Picker
		include( THEMIFY_SHORTCODES_DIR . 'themify/themify-icon-picker/themify-icon-picker.php' );
		Themify_Icon_Picker::get_instance( THEMIFY_SHORTCODES_URI . 'themify/themify-icon-picker' );
		Themify_Icon_Picker::get_instance()->register( 'Themify_Icon_Picker_FontAwesome' );
		Themify_Icon_Picker::get_instance()->register( 'Themify_Icon_Picker_Themify' );

		// Themify Framework
		if( ! defined( 'THEMIFY_DIR' ) ) {
			include( THEMIFY_SHORTCODES_DIR . 'themify/img.php' );
			include( THEMIFY_SHORTCODES_DIR . 'includes/theme-options.php' );
		}
		
		include( THEMIFY_SHORTCODES_DIR . 'includes/functions.php' );

		if( ! function_exists( 'themify_shortcode' ) ) {
			include( THEMIFY_SHORTCODES_DIR . 'themify/themify-shortcodes.php' );
		}
	}

	/**
	 * Load plugin assets for frontend
	 */
	public function enqueue() {
		/**
		 * Note: load themify-main-script only if it's not loaded by Themify framework, or Builder plugin.
		 */
		if( ! wp_script_is( 'themify-main-script' ) ) {
			$options = get_option( 'themify_shortcodes', array() );
			wp_enqueue_script( 'themify-main-script', THEMIFY_SHORTCODES_URI . 'themify/js/main.js', array( 'jquery' ), THEMIFY_SHORTCODES_VERSION, true );
			wp_localize_script( 'themify-main-script', 'themify_vars', apply_filters( 'themify_main_script_vars', array(
				'version' => THEMIFY_SHORTCODES_VERSION,
				'url' => THEMIFY_SHORTCODES_URI . 'themify',
				'includesURL' => includes_url(),
				'map_key' => isset( $options['gmap_api_key'] ) ? $options['gmap_api_key'] : '',
			) ) );
		}
	}

	/**
	 * Load assets required in the admin backend
	 *
	 * @since 1.0.3
	 */
	public function admin_enqueue( $hook ) {
		if( 'post-new.php' == $hook || 'post.php' == $hook ) {
			Themify_Icon_Picker::get_instance()->enqueue();
			wp_enqueue_style( 'themify-shortcodes-font-icons-css', THEMIFY_SHORTCODES_URI . 'themify/fontawesome/css/font-awesome.min.css', array(), THEMIFY_SHORTCODES_VERSION );
			wp_enqueue_style( 'themify-icons', THEMIFY_SHORTCODES_URI . 'themify/themify-icons/themify-icons.css', array(), THEMIFY_SHORTCODES_VERSION );
		}
	}

	/**
	 * Admin actions
	 * Setups the plugin's options page
	 *
	 * @since 1.0.0
	 */
	public function admin() {
		if( is_admin() ) {
			include( THEMIFY_SHORTCODES_DIR . 'includes/admin.php' );
			new Builder_Shortcodes_Admin();
		}
	}

	/**
	 * Get a list of shortcodes (along with their callback) included in the plugin
	 *
	 * @return array
	 * @since 1.0.3
	 */
	public function get_shortcodes() {
		$shortcodes = array(
			'themify_is_logged_in' => 'themify_shortcode',
            'themify_is_guest'     => 'themify_shortcode',
            'themify_button'       => 'themify_shortcode',
            'themify_quote'        => 'themify_shortcode',
            'themify_col'          => 'themify_shortcode',
            'themify_sub_col'      => 'themify_shortcode',
            'themify_img'          => 'themify_shortcode',
            'themify_hr'           => 'themify_shortcode',
            'themify_map'          => 'themify_shortcode',
            'themify_list_posts'   => 'themify_shortcode_list_posts',
            'themify_flickr'       => 'themify_shortcode_flickr',
            'themify_twitter'      => 'themify_shortcode_twitter',
            'themify_box'          => 'themify_shortcode_box',
            'themify_post_slider'  => 'themify_shortcode_post_slider',
            'themify_slider'       => 'themify_shortcode_slider',
            'themify_slide'        => 'themify_shortcode_slide',
            'themify_author_box'   => 'themify_shortcode_author_box',
            'themify_icon'         => 'themify_shortcode_icon',
            'themify_list'         => 'themify_shortcode_icon_list',
		);

		return $shortcodes;
	}

	/**
	 * Register shortcodes in the plugin
	 *
	 * @since 1.0.3
	 */
	public function register() {
		foreach( $this->get_shortcodes() as $code => $callback ) {
			add_shortcode( $code, $callback );
		}
	}

	/**
	 * Filter the credentials used by Twitter API, retrieve from plugin options
	 *
	 * @return array
	 */
	public function themify_twitter_credentials( $credentials ) {
		$options = get_option( 'themify_shortcodes' );
		if( isset( $options['twitter_consumer_key'] ) ) {
			$credentials['consumer_key'] = $options['twitter_consumer_key'];
		}
		if( isset( $options['twitter_consumer_secret'] ) ) {
			$credentials['consumer_secret'] = $options['twitter_consumer_secret'];
		}

		return $credentials;
	}

	/**
	 * When loop template file does not exist, use the one bundled in the plugin
	 *
	 * To override the loop.php template file copy it to /includes folder in the theme and rename
	 * the file to "themify-list-posts-loop.php".
	 *
	 * @return string
	 */
	public function themify_get_shortcode_template_file( $file, $slug, $name ) {
		global $themify;

		/* override the template file, only in shortcode context */
		if( isset( $themify->is_shortcode ) && $themify->is_shortcode == true
			&& $slug == 'includes/loop' // modify the template only in list_post shortcode
		) {
			if( $theme_file = locate_template( array( 'includes/themify-list-posts-loop.php', 'includes/loop.php' ) ) ) {
				$file = $theme_file;
			} else {
				$file = THEMIFY_SHORTCODES_DIR . 'includes/loop.php';
			}
		}

		return $file;
	}

	public function themify_twitter_missing_key_message( $message ) {
		return sprintf( __( 'Error: access keys missing in <a href="%s">Settings > Themify Shortcodes</a>', 'themify-shortcodes' ), admin_url( 'options-general.php?page=themify-shortcodes' ) );
	}
}
