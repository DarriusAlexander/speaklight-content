<?php
/**
 * Version 0.0.3
 *
 * This file is just an example you can copy it to your theme and modify it to fit your own needs.
 * Watch the paths though.
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'GhostPool_Importer' ) && class_exists( 'GhostPool_Socialize' ) ) {

	if ( file_exists( WP_PLUGIN_DIR . '/socialize-plugin/importer/radium-importer.php' ) ) {			
		require_once( WP_PLUGIN_DIR . '/socialize-plugin/importer/radium-importer.php' );
	}
	
	class GhostPool_Importer extends Radium_Theme_Importer {

		private static $instance;
		public $theme_options_framework = 'redux';
		public $theme_option_name       = 'socialize';
		public $theme_options_file_name = 'theme_options.txt';
		public $widgets_file_name       = 'widgets.json';
		public $content_demo_file_name  = 'content.xml';
		public $widget_import_results;

		public function __construct() {
			$this->demo_files_path = get_template_directory() . '/lib/framework/importer/demo-files/';
			self::$instance = $this;
			parent::__construct();
		}

		public static function getInstance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	
		public function set_demo_menus() {
			$locations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();
			if ( $menus ) {
				foreach( $menus as $menu ) { // assign menus to theme locations
					if ( $menu->name == 'Socialize Primary Main Header Menu' ) {
						$locations['gp-primary-main-header-nav'] = $menu->term_id;	
					} elseif ( $menu->name == 'Socialize Secondary Main Header Menu' ) {
						$locations['gp-secondary-main-header-nav'] = $menu->term_id;					
					} elseif ( $menu->name == 'Socialize Left Small Header Menu' ) {
						$locations['gp-left-small-header-nav'] = $menu->term_id;
						$locations['gp-footer-nav'] = $menu->term_id;	
					} elseif ( $menu->name == 'Socialize Social Icons Menu' ) {
						$locations['gp-right-small-header-nav'] = $menu->term_id;				
					}
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );	
		}

		public function after_wp_importer() {
		
			if ( get_page_by_path( 'home-1' ) ) {				
				update_option( 'page_on_front', get_page_by_path( 'home-1' )->ID );
			}	
			update_option( 'show_on_front', 'page' );
	
			$vse_hls = get_page_by_path( 'gp-homepage-left-sidebar', OBJECT, 'epx_vcsb' );
			if ( $vse_hls && $vse_hls->post_content == '' ) {	
				wp_update_post( array(
					'ID'           => $vse_hls->ID,
					'post_status'  => 'publish',
					'post_content' => '[vc_row][vc_column][blog widget_title="' . esc_html__( 'Latest News', 'socialize' ) . '" per_page="20" image_width="80" image_height="80" excerpt_length="0" meta_cats="1"][/vc_column][/vc_row]',
				) );
			}
			
			$vse_hrs = get_page_by_path( 'gp-homepage-right-sidebar', OBJECT, 'epx_vcsb' );
			if ( $vse_hrs && $vse_hrs->post_content == '' ) {	
				wp_update_post( array(
					'ID'           => $vse_hrs->ID,
					'post_status'  => 'publish',
					'post_content' => '[vc_row][vc_column][login widget_title="Join The Community"][bp_recently_active_members max_members="16"][bp_groups max_groups="5"][bp_members max_members="5"][events_list title="Events"][statistics widget_title="Statistics" posts="1" comments="1" blogs="1" activity="1" members="1" groups="1" forums="1"][/vc_column][/vc_row]',
				) );
			}
			
			// Delete "Hello World" post
			$default_post = get_posts( array( 'title' => 'Hello World!' ) );
			if ( $default_post ) {				
				wp_delete_post( $default_post[0]->ID );
			}
		
		}					
		
	}

	GhostPool_Importer::getInstance();

}