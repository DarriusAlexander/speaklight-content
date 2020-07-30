<?php

class TBP_Pointers {
	public function __construct() {
	    self::register_pointers();
	}

	private static function register_pointers() {
		
		// Get pointers for this screen
		$pointers = apply_filters( 'tbp_pointers_register', self::registerThemePointers() );

		if ( ! $pointers || ! is_array( $pointers ) ) 
			return;

		// Get dismissed pointers
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		$valid_pointers = array();

		// Check pointers and remove dismissed ones.
		foreach ( $pointers as $pointer_id => $pointer ) {

			$remember_dismiss = isset( $pointer['remember_dismiss'] ) && $pointer['remember_dismiss'] === true;

			// Sanity check
			if ( ( $remember_dismiss===true && in_array( $pointer_id, $dismissed )) || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) ) 
				continue;

			$pointer['pointer_id'] = $pointer_id;

			// Add the pointer to $valid_pointers array
			$valid_pointers['pointers'][] =  $pointer;
		}

		if ( empty( $valid_pointers ) ) 
			return;

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );

		wp_localize_script( 'tbp-admin', '_tbp_pointers', $valid_pointers );
	}

	private static function registerThemePointers() {
		$arr = array();
		if ( isset( $_GET['page'],$_GET['status'] ) && 'activate' === $_GET['status'] && Tbp_Themes::$post_type=== $_GET['page']) {
		    $arr['theme_activated'] = array(
			    'target' => '#placeholder_pointer_theme_activated',
			    'options' => array(
				    'content' => sprintf( '<h3>%s</h3><p>%s</p>', esc_html__( 'Your Pro Theme has been activated', 'themify' ), esc_html__( 'Click Go to Templates to add/edit a Pro Template.', 'themify' ) ),
				    'position' => array( 'edge' => 'top' )
			    )
		    );
		}
		return $arr;
	}
	
	public static function setNoThemePointer( $p ) {
		$p['theme_add_new'] = array(
			'target' => '#placeholder_pointer_add_new_theme',
			'options' => array(
				'content' => sprintf( '<h3>%s</h3><p>%s</p>', esc_html__( "You don't have a Pro Theme Yet.", 'themify' ), esc_html__( 'Click Add New to add Pro Theme.', 'themify' ) ),
				'position' => array( 'edge' => 'top' )
			)
		);
		return $p;
	}
}