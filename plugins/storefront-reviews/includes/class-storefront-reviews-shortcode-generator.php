<?php
if ( ! defined( 'ABSPATH' ) )
    exit;

/**
 * Class add a shortcode generator button to tinymce.
 */
class Storefront_Reviews_Shortcode_Generator {
	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		add_action( 'admin_head', array( $this, 'sr_tinymce_button' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'sr_tinymce_script' ) );
	}

	public function sr_tinymce_button() {
		global $typenow;

		// check user permissions
    	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
    		return;
    	}

		// check if WYSIWYG is enabled
		if ( get_user_option( 'rich_editing' ) == 'true') {
		    add_filter( 'mce_external_plugins', array( $this, 'sr_tinymce_plugin' ) );
		    add_filter( 'mce_buttons', array( $this, 'sr_tinymce_register_button' ) );
		}
	}

	public function sr_tinymce_plugin( $plugin_array ) {
	    $plugin_array['sr_tinymce_button'] = plugins_url( '../assets/js/reviews-button.min.js', __FILE__ );
	    return $plugin_array;
	}

	public function sr_tinymce_register_button( $buttons ) {
	   array_push( $buttons, 'sr_tinymce_button' );
	   return $buttons;
	}

	public function sr_tinymce_script() {
	    wp_enqueue_style( 'sr-tinymce-style', plugins_url( '../assets/css/admin.css', __FILE__ ) );
	}
}