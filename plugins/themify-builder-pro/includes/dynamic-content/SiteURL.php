<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_SiteURL extends Tbp_Dynamic_Item {

	function get_category() {
		return 'general';
	}

	function get_type() {
		return array( 'url' );
	}

	function get_label() {
		return __( 'Site URL', 'themify' );
	}

	function get_value( $args = array() ) {
		return home_url();
	}
}
