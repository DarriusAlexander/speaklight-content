<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_SiteIcon extends Tbp_Dynamic_Item {

	function get_category() {
		return 'general';
	}

	function get_type() {
		return array( 'image' );
	}

	function get_label() {
		return __( 'Site Icon', 'themify' );
	}

	function get_value( $args = array() ) {
	    $size = isset($args['size'])?(int)$args['size']:512;
	    $value = get_site_icon_url($size );
	    return $value?$value:'';
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Size', 'themify' ),
				'id' => 'size',
				'class'=>'large',
				'type' => 'number',
				'help' => sprintf( __( 'Configured in <a href="%s" target="_blank">Customizer</a> > Site Identity tab.', 'themify' ), admin_url( 'customize.php' ) ),
			),
		);
	}
}
