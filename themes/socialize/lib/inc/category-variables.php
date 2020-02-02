<?php

if ( ! function_exists( 'ghostpool_category_variables' ) ) {
	function ghostpool_category_variables() {
		

		/*--------------------------------------------------------------
		Pagination
		--------------------------------------------------------------*/

		if ( get_query_var( 'paged' ) ) {
			$GLOBALS['ghostpool_paged'] = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$GLOBALS['ghostpool_paged'] = get_query_var( 'page' );
		} else {
			$GLOBALS['ghostpool_paged'] = 1;
		}


		/*--------------------------------------------------------------
		Categories
		--------------------------------------------------------------*/

		$GLOBALS['ghostpool_tax'] = array();			
		if ( ! empty( $GLOBALS['ghostpool_cats'] ) && preg_match( '/^[1-9, ][0-9, ]*$/', $GLOBALS['ghostpool_cats'] ) ) {
			$taxonomies = apply_filters( 'gp_hierarchical_taxonomies', get_taxonomies( array( 'public' => true, 'hierarchical' => true ) ) );
			foreach( $taxonomies as $taxonomy ) {
				$GLOBALS['ghostpool_tax'][] = array( 'taxonomy' => $taxonomy, 'terms' => explode( ',', $GLOBALS['ghostpool_cats'] ), 'field' => 'id' );
			}
			$GLOBALS['ghostpool_tax'] = array( 'relation' => 'OR' ) + $GLOBALS['ghostpool_tax'];		
		} elseif ( ! empty( $GLOBALS['ghostpool_cats'] ) ) {
			$taxonomies = apply_filters( 'gp_hierarchical_taxonomies', get_taxonomies( array( 'public' => true, 'hierarchical' => true ) ) );
			foreach( $taxonomies as $taxonomy ) {
				$GLOBALS['ghostpool_tax'][] = array( 'taxonomy' => $taxonomy, 'terms' => explode( ',', $GLOBALS['ghostpool_cats'] ), 'field' => 'slug' );
			}
			$GLOBALS['ghostpool_tax'] = array( 'relation' => 'OR' ) + $GLOBALS['ghostpool_tax'];	
		} else {
			$GLOBALS['ghostpool_tax'] = null;
		}


		/*--------------------------------------------------------------
		Ordering
		--------------------------------------------------------------*/

		if ( isset( $GLOBALS['ghostpool_orderby'] ) ) {
		
			if ( $GLOBALS['ghostpool_orderby'] == 'newest' ) {
				$GLOBALS['ghostpool_orderby_value'] = 'date';
				$GLOBALS['ghostpool_order'] = 'desc';
				$GLOBALS['ghostpool_meta_key'] = '';
			} elseif ( $GLOBALS['ghostpool_orderby'] == 'oldest' ) {
				$GLOBALS['ghostpool_orderby_value'] = 'date';
				$GLOBALS['ghostpool_order'] = 'asc';
				$GLOBALS['ghostpool_meta_key'] = '';				
			} elseif ( $GLOBALS['ghostpool_orderby']  == 'title_az' ) {
				$GLOBALS['ghostpool_orderby_value'] = 'title';
				$GLOBALS['ghostpool_order'] = 'asc';
				$GLOBALS['ghostpool_meta_key'] = '';	
			} elseif ( $GLOBALS['ghostpool_orderby']  == 'title_za' ) {
				$GLOBALS['ghostpool_orderby_value'] = 'title';
				$GLOBALS['ghostpool_order'] = 'desc';
				$GLOBALS['ghostpool_meta_key'] = '';									
			} elseif ( $GLOBALS['ghostpool_orderby'] == 'comment_count' ) {
				$GLOBALS['ghostpool_orderby_value'] = 'comment_count';
				$GLOBALS['ghostpool_order'] = 'desc';
				$GLOBALS['ghostpool_meta_key'] = '';	
			} elseif ( $GLOBALS['ghostpool_orderby'] == 'views' ) {
				$GLOBALS['ghostpool_orderby_value'] = 'post_views';
				$GLOBALS['ghostpool_order'] = 'desc';
				$GLOBALS['ghostpool_meta_key'] = '';
			} elseif ( $GLOBALS['ghostpool_orderby'] == 'menu_order' ) {
				$GLOBALS['ghostpool_orderby_value'] = 'menu_order';
				$GLOBALS['ghostpool_order'] = 'asc';
				$GLOBALS['ghostpool_meta_key'] = '';	
			} elseif ( $GLOBALS['ghostpool_orderby'] == 'rand' ) {
				$GLOBALS['ghostpool_orderby_value'] = 'rand';
				$GLOBALS['ghostpool_order'] = 'asc';
				$GLOBALS['ghostpool_meta_key'] = '';	
			} else {
				$GLOBALS['ghostpool_orderby_value'] = '';
				$GLOBALS['ghostpool_order'] = '';
				$GLOBALS['ghostpool_meta_key'] = '';	
			}
		}	


		/*--------------------------------------------------------------
		Dates
		--------------------------------------------------------------*/

		// Date posted
		if ( isset( $GLOBALS['ghostpool_date_posted'] ) ) {			
			if ( $GLOBALS['ghostpool_date_posted'] == 'day' ) {
				$GLOBALS['ghostpool_date_posted_value'] = array(
					'column' => 'post_date_gmt',
					'after' => '1 day ago',
				);	
			} elseif ( $GLOBALS['ghostpool_date_posted'] == 'week' ) {	
				$GLOBALS['ghostpool_date_posted_value'] = array(	
					'column' => 'post_date_gmt',
					'after' => '1 week ago',
				);
			} elseif ( $GLOBALS['ghostpool_date_posted'] == 'month' ) {	
				$GLOBALS['ghostpool_date_posted_value'] = array(	
					'column' => 'post_date_gmt',
					'after' => '1 month ago',
				);
			} elseif ( $GLOBALS['ghostpool_date_posted'] == 'year' ) {	
				$GLOBALS['ghostpool_date_posted_value'] = array(	
					'column' => 'post_date_gmt',
					'after' => '1 year ago',
				);
			} elseif ( $GLOBALS['ghostpool_date_posted'] == 'all' ) {	
				$GLOBALS['ghostpool_date_posted_value'] = '';
			} else {
				$GLOBALS['ghostpool_date_posted_value'] = '';
			}
		}	

		// Date modified
		if ( isset( $GLOBALS['ghostpool_date_modified'] ) ) {			
			if ( $GLOBALS['ghostpool_date_modified'] == 'day' ) {
				$GLOBALS['ghostpool_date_modified_value'] = array(
					'column' => 'post_modified_gmt',
					'after' => '1 day ago',
				);	
			} elseif ( $GLOBALS['ghostpool_date_modified'] == 'week' ) {	
				$GLOBALS['ghostpool_date_modified_value'] = array(	
					'column' => 'post_modified_gmt',
					'after' => '1 week ago',
				);
			} elseif ( $GLOBALS['ghostpool_date_modified'] == 'month' ) {	
				$GLOBALS['ghostpool_date_modified_value'] = array(	
					'column' => 'post_modified_gmt',
					'after' => '1 month ago',
				);
			} elseif ( $GLOBALS['ghostpool_date_modified'] == 'year' ) {	
				$GLOBALS['ghostpool_date_modified_value'] = array(	
					'column' => 'post_modified_gmt',
					'after' => '1 year ago',
				);
			} elseif ( $GLOBALS['ghostpool_date_modified'] == 'all' ) {	
				$GLOBALS['ghostpool_date_modified_value'] = '';
			} else {
				$GLOBALS['ghostpool_date_modified_value'] = '';
			}
		}

				
		/*--------------------------------------------------------------
		Add category variables via your child theme using this function
		--------------------------------------------------------------*/

		if ( function_exists( 'ghostpool_custom_category_variables' ) ) {
			ghostpool_custom_category_variables();
		}

	}		
}	

?>