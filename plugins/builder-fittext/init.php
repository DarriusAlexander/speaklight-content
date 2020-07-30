<?php

/*
  Plugin Name:  Builder FitText
  Plugin URI:   https://themify.me/addons/fittext
  Version:      1.1.9
  Author:       Themify
  Author URI:   https://themify.me
  Description:  Auto fit text in the container. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
  Text Domain:  builder-fittext
  Domain Path:  /languages
 */

defined('ABSPATH') or die('-1');

class Builder_Fittext {

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
        if ($instance === null) {
            $instance = new self;
        }
        return $instance;
    }

    private function __construct() {
        $this->constants();
        add_action('plugins_loaded', array($this, 'i18n'), 5);
        add_action('themify_builder_setup_modules', array($this, 'register_module'));
        if(is_admin()){
	    add_filter( 'plugin_row_meta', array( $this, 'themify_plugin_meta'), 10, 2 );
	    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'action_links') );
        }
        
    }

    public function constants() {
        $data = get_file_data(__FILE__, array('Version'));
        $this->version = $data[0];
        $this->url = trailingslashit(plugin_dir_url(__FILE__));
        $this->dir = trailingslashit(plugin_dir_path(__FILE__));
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
    public function i18n() {
        load_plugin_textdomain('builder-fittext', false, '/languages');
    }

    public function register_module() {
	Themify_Builder_Model::register_directory('templates', $this->dir . 'templates');
	Themify_Builder_Model::register_directory('modules', $this->dir . 'modules');
    }
}

Builder_Fittext::get_instance();
