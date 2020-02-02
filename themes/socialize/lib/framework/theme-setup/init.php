<?php

class GhostPool_Setup {

	/**
	 * @var GhostPool_Setup The reference to *GhostPool_Setup* instance of this class
	 */
	protected static $_instance = null;

	public $slug = 'socialize-setup';

	public function __construct() {
		$this->set_hooks();
		$this->load_dependencies();
	}

	/**
	 * Returns the GhostPool_Setup instance of this class.
	 *
	 * @return GhostPool_Setup - Main instance
	 */
	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function load_dependencies() {
		require_once( get_template_directory() . '/lib/framework/theme-setup/theme-addons.php' );
		require_once( get_template_directory() . '/lib/framework/importer/init.php' );
	}

	public function set_hooks() {

		add_action( 'admin_menu', array( $this, 'register_setup_page' ) );
		add_action( 'admin_init', array( $this, 'redirect_to_setup' ), 0 );
		add_action( 'wp_ajax_ghostpool_theme_updates_action', array( $this, 'theme_updates' ) );

		if ( isset( $_GET['page'] ) && $_GET['page'] == $this->slug OR ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'ghostpool_do_plugin_action' ) ) {
			add_action( 'admin_init', array( $this, 'config_addons' ), 12 );
		}
		
		if ( ( isset( $_GET['page'] ) && $_GET['page'] == $this->slug ) OR ( isset( $_GET['page'] ) && $_GET['page'] == 'ghostpool-importer' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'setup_scripts' ) );
		}
		
	}

	/**
	 * Register CSS & JS Files
	 */
	function setup_scripts() {
		wp_enqueue_style( 'theme-setup', get_template_directory_uri() . '/lib/framework/css/theme-setup.css', array() );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_script( 'theme-setup', get_template_directory_uri() . '/lib/framework/scripts/theme-setup.js', array( 'jquery' ) );
	}

	public function register_setup_page() {
		add_theme_page(
			esc_html__( 'Socialize Welcome', 'socialize' ),
			esc_html__( 'Socialize Welcome', 'socialize' ),
			'manage_options',
			$this->slug,
			array( $this, 'setup_page' )
		);
	}

	function setup_page() {
		require( get_template_directory() . '/lib/framework/theme-setup/welcome.php' );
	}

	public function redirect_to_setup() {
		// Theme activation redirect
		global $pagenow;
		if ( isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
			wp_redirect( admin_url( 'themes.php?page=socialize-setup' ) );
			exit;
		}
	}

	public function theme_updates() {
		if ( ! isset( $_POST['ghostpool_theme_updates_nonce'] ) OR ! wp_verify_nonce( $_POST['ghostpool_theme_updates_nonce'], 'ghostpool_theme_updates_action' ) ) {
			wp_send_json_error( array( 'error' => 'Sorry, your nonce did not verify.' ) );
		}

		$option_name = 'ghostpool_the_review';
		$tf_username = isset( $_POST['username'] ) ? $_POST['username'] : '';
		$tf_api      = isset( $_POST['api_key'] ) ? $_POST['api_key'] : '';

		if ( ! empty( $tf_username ) && ! empty( $tf_api ) ) {

			// Check to see if the user credentials are ok and if the user purchased the theme;
			if ( ! class_exists( 'Envato_Protected_API' ) ) {
				require_once( get_template_directory() . '/lib/framework/themes-updater/class-envato-protected-api.php' );
			}

			$theme_author  = 'GhostPool';
			$api           = new Envato_Protected_API( $tf_username, $tf_api );
			$purchased     = $api->wp_list_themes( true );
			$installed     = wp_get_themes();
			$filtered      = array();
			$has_purchased = false;

			foreach ( $installed as $theme ) {
				if ( $theme->{'Author Name'} !== $theme_author ) {
					continue;
				}
				$filtered[ $theme->Name ] = $theme;
			}

			foreach ( $purchased as $theme ) {
				if ( isset( $theme->theme_name ) && isset( $filtered[ $theme->theme_name ] ) ) {
					$has_purchased = true;

				}
			}

			if ( $has_purchased ) {
				// Save the updater values

				//Get entire array
				$my_options = get_option( $option_name );

				//Alter the options array appropriately
				$my_options['tf_username'] = $tf_username;
				$my_options['tf_apikey']   = $tf_api;

				//Update entire array
				update_option( $option_name, $my_options );

				wp_send_json_success( array( 'message' => esc_html__( 'Username and API key added successfully.', 'socialize' ) ) );
				
			} else {
				wp_send_json_error( array( 'error' => 'It seems you have not purchased the theme from this account. Please check the credentials you provided.' ) );
			}

		}

		wp_send_json_error( array( 'error' => 'Please enter your username and API key.' ) );
	}

	public function config_addons() {

		GhostPool_Addons_Manager()->plugins = array( 'bbpress' => GhostPool_Addons_Manager()->plugins['bbpress'] ) + GhostPool_Addons_Manager()->plugins;
		GhostPool_Addons_Manager()->plugins = array( 'buddypress' => GhostPool_Addons_Manager()->plugins['buddypress'] ) + GhostPool_Addons_Manager()->plugins;
		GhostPool_Addons_Manager()->plugins = array( 'theia-sticky-sidebar' => GhostPool_Addons_Manager()->plugins['theia-sticky-sidebar'] ) + GhostPool_Addons_Manager()->plugins;
		GhostPool_Addons_Manager()->plugins = array( 'visual-sidebars-editor' => GhostPool_Addons_Manager()->plugins['visual-sidebars-editor'] ) + GhostPool_Addons_Manager()->plugins;
		GhostPool_Addons_Manager()->plugins = array( 'js_composer' => GhostPool_Addons_Manager()->plugins['js_composer'] ) + GhostPool_Addons_Manager()->plugins;
		GhostPool_Addons_Manager()->plugins = array( 'socialize-plugin' => GhostPool_Addons_Manager()->plugins['socialize-plugin'] ) + GhostPool_Addons_Manager()->plugins;

		$prepend = array(
			'socialize-child' => array(
				'addon_type'  => 'child_theme',
				'name'        => 'Socialize Child Theme',
				'slug'        => 'socialize-child',
				'source'      => get_template_directory() . '/lib/plugins/socialize-child.zip',
				'source_type' => 'bundled',
				'version'     => '1.0',
				'required'    => true,
				'description' => esc_html__( 'Always activate the', 'socialize' ) . ' <a href="https://codex.wordpress.org/Child_Themes" target="_blank">' . esc_html__( 'child theme', 'socialize' ) . '</a> ' . esc_html__( 'to safely update Socialize.', 'socialize' ),
			)
		);

		GhostPool_Addons_Manager()->plugins = $prepend + GhostPool_Addons_Manager()->plugins;
	}

}

GhostPool_Setup::getInstance();