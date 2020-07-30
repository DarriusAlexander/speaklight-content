<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_SiteDescription extends Tbp_Dynamic_Item {
	function get_category() {
		return 'general';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'Site Description', 'themify' );
	}

	function get_value( $args = array() ) {
		return get_bloginfo( 'description' );
	}
}
