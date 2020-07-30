<?php
/*
Plugin Name:  Builder Masked Image
Plugin URI:   https://themify.me/addons/masked-image
Version:      1.0.7
Author:       Themify
Author URI:   https://themify.me
Description:  A Builder addon to mask images in any shape with a SVG or PNG file. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Text Domain:  builder-masked-image
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( '-1' );

class Builder_Masked_Image {

	public $url;
	private $dir;
	public $version;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
		static $instance = null;
		if ( $instance===null ) {
			$instance = new self;
		}
		return $instance;
	}

	private function __construct() {
		$this->constants();
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );
		add_action( 'themify_builder_setup_modules', array( $this, 'register_module' ) );
		
		if ( is_admin() ) {
                    add_action( 'themify_builder_admin_enqueue', array( $this, 'admin_enqueue' ) );
		    add_filter( 'plugin_row_meta', array( $this, 'themify_plugin_meta'), 10, 2 );
		    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'action_links') );
		} else {
                    add_action( 'themify_builder_frontend_enqueue', array( $this, 'admin_enqueue' ));
		}
	}

	public function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	public function i18n() {
		load_plugin_textdomain( 'builder-masked-image', false, '/languages' );
	}

	public function register_module() {
		Themify_Builder_Model::register_directory( 'templates', $this->dir . 'templates' );
		Themify_Builder_Model::register_directory( 'modules', $this->dir . 'modules' );
		
	}

	public function admin_enqueue() {
		wp_enqueue_script( 'builder-masked-image', themify_enque($this->url . 'assets/admin.js'), array( 'jquery' ),  $this->version,true );
		wp_localize_script('builder-masked-image', 'builderMask',array(
		    'admin_css'=>$this->url . 'assets/admin.min.css',
		    'v'=>$this->version,
		    'path'=>'https://themify.me/public-api/svg-icons/'
		));

	}

	
	public function themify_plugin_meta( $links, $file ) {
		if ( plugin_basename( __FILE__ ) === $file ) {
			$row_meta = array(
			  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'themify' ) . '">' . esc_html__( 'View Changelogs', 'themify' ) . '</a>'
			);
	 
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	
	public function action_links( $links ) {
		if ( is_plugin_active( 'themify-updater/themify-updater.php' ) ) {
			$tlinks = array(
			 '<a href="' . admin_url( 'index.php?page=themify-license' ) . '">'.__('Themify License', 'themify') .'</a>',
			 );
		} else {
			$tlinks = array(
			 '<a href="' . esc_url('https://themify.me/docs/themify-updater-documentation') . '">'. __('Themify Updater', 'themify') .'</a>',
			 );
		}
		return array_merge( $links, $tlinks );
	}
}
Builder_Masked_Image::get_instance();
