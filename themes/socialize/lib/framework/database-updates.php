<?php

/*--------------------------------------------------------------
Updating to version 2.27
--------------------------------------------------------------*/

if ( get_option( 'socialize_db_version' ) == '1' ) {

	if ( ! function_exists( 'ghostpool_socialize_v227_update_database' ) ) {
	
		function ghostpool_socialize_v227_update_database() {
			
			// Get entire array
			$my_options = get_option( 'socialize' );

			// Alter the options array appropriately
			if ( $my_options['fixed_header'] == 'gp-fixed-header' ) {
				$my_options['fixed_header'] = 'gp-fixed-header-desktop';
			}
			if ( $my_options['back_to_top'] == 'gp-back-to-top' ) {
				$my_options['back_to_top'] = 'gp-back-to-top-desktop';
			}
			
			// Update entire array
			update_option( 'socialize', $my_options );
																							
		}
		
	}
	add_action( 'init', 'ghostpool_socialize_v227_update_database' );
	update_option( 'socialize_db_version', '1.1' );

}

/*--------------------------------------------------------------
Updating to version 2.26
--------------------------------------------------------------*/

if ( get_option( 'socialize_db_version' ) < '1' ) {

	if ( ! function_exists( 'ghostpool_socialize_v226_update_database' ) ) {
	
		function ghostpool_socialize_v226_update_database() {
			
			global $wpdb;
			
			// Change sidebar option names
			 if ( get_option( 'cs_sidebars' ) ) { 
				add_option( 'ghostpool_sidebars', get_option( 'cs_sidebars' ) );
				delete_option( 'cs_sidebars' );
			}
			if ( get_option( 'cs_modifiable' ) ) { 
				add_option( 'ghostpool_modifiable', get_option( 'cs_modifiable' ) );
				delete_option( 'cs_modifiable' );
			}	
			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_key = %s WHERE meta_key = %s", '_ghostpool_replacements', '_cs_replacements' ) );
																							
		}
		
	}
	add_action( 'init', 'ghostpool_socialize_v226_update_database' );
	update_option( 'socialize_db_version', '1' );

}

?>