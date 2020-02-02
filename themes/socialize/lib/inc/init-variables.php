<?php

if ( ! function_exists( 'ghostpool_init_variables' ) ) {
	function ghostpool_init_variables() {

		$global = get_option( 'socialize' ); 

		/*--------------------------------------------------------------
		BuddyPress
		--------------------------------------------------------------*/

		if ( function_exists( 'bp_is_active' ) && ! bp_is_blog_page() ) {

			if ( bp_is_user() ) {

				$GLOBALS['ghostpool_page_header'] = ghostpool_option( 'bp_members_page_header' ) != 'default' ? ghostpool_option( 'bp_members_page_header' ) : ghostpool_option( 'bp_page_header' );
				
				$GLOBALS['ghostpool_page_header_bg'] = ghostpool_option( 'bp_members_page_header_bg', 'url' ) != '' ? ghostpool_option( 'bp_members_page_header_bg' ) : ghostpool_option( 'bp_page_header_bg' );
			
				$GLOBALS['ghostpool_layout'] = ghostpool_option( 'bp_profile_layout' ) != 'default' ? ghostpool_option( 'bp_profile_layout' ) : ghostpool_option( 'bp_layout' );
			
				$GLOBALS['ghostpool_left_sidebar'] = ghostpool_option( 'bp_members_left_sidebar' ) != 'default' ? ghostpool_option( 'bp_members_left_sidebar' ) : ghostpool_option( 'bp_left_sidebar' );
			
				$GLOBALS['ghostpool_right_sidebar'] = ghostpool_option( 'bp_members_right_sidebar' ) != 'default' ? ghostpool_option( 'bp_members_right_sidebar' ) : ghostpool_option( 'bp_right_sidebar' );
							
										
			} elseif ( bp_is_activity_component() ) {
				
				$GLOBALS['ghostpool_page_header'] = ghostpool_option( 'bp_activity_page_header' ) != 'default' ? ghostpool_option( 'bp_activity_page_header' ) : ghostpool_option( 'bp_page_header' );
				
				$GLOBALS['ghostpool_page_header_bg'] = ghostpool_option( 'bp_activity_page_header_bg', 'url' ) != '' ? ghostpool_option( 'bp_activity_page_header_bg' ) : ghostpool_option( 'bp_page_header_bg' );
			
				$GLOBALS['ghostpool_layout'] = ghostpool_option( 'bp_activity_layout' ) != 'default' ? ghostpool_option( 'bp_activity_layout' ) : ghostpool_option( 'bp_layout' );
			
				$GLOBALS['ghostpool_left_sidebar'] = ghostpool_option( 'bp_activity_left_sidebar' ) != 'default' ? ghostpool_option( 'bp_activity_left_sidebar' ) : ghostpool_option( 'bp_left_sidebar' );
			
				$GLOBALS['ghostpool_right_sidebar'] = ghostpool_option( 'bp_activity_right_sidebar' ) != 'default' ? ghostpool_option( 'bp_activity_right_sidebar' ) : ghostpool_option( 'bp_right_sidebar' );
				
			} elseif ( bp_is_members_component() ) {

				$GLOBALS['ghostpool_page_header'] = ghostpool_option( 'bp_members_page_header' ) != 'default' ? ghostpool_option( 'bp_members_page_header' ) : ghostpool_option( 'bp_page_header' );
				
				$GLOBALS['ghostpool_page_header_bg'] = ghostpool_option( 'bp_members_page_header_bg', 'url' ) != '' ? ghostpool_option( 'bp_members_page_header_bg' ) : ghostpool_option( 'bp_page_header_bg' );
			
				$GLOBALS['ghostpool_layout'] = ghostpool_option( 'bp_members_layout' ) != 'default' ? ghostpool_option( 'bp_members_layout' ) : ghostpool_option( 'bp_layout' );
			
				$GLOBALS['ghostpool_left_sidebar'] = ghostpool_option( 'bp_members_left_sidebar' ) != 'default' ? ghostpool_option( 'bp_members_left_sidebar' ) : ghostpool_option( 'bp_left_sidebar' );
			
				$GLOBALS['ghostpool_right_sidebar'] = ghostpool_option( 'bp_members_right_sidebar' ) != 'default' ? ghostpool_option( 'bp_members_right_sidebar' ) : ghostpool_option( 'bp_right_sidebar' );

			} elseif ( bp_is_groups_component() ) {

				$GLOBALS['ghostpool_page_header'] = ghostpool_option( 'bp_groups_page_header' ) != 'default' ? ghostpool_option( 'bp_groups_page_header' ) : ghostpool_option( 'bp_page_header' );
				
				$GLOBALS['ghostpool_page_header_bg'] = ghostpool_option( 'bp_groups_page_header_bg', 'url' ) != '' ? ghostpool_option( 'bp_groups_page_header_bg' ) : ghostpool_option( 'bp_page_header_bg' );
			
				$GLOBALS['ghostpool_layout'] = ghostpool_option( 'bp_groups_layout' ) != 'default' ? ghostpool_option( 'bp_groups_layout' ) : ghostpool_option( 'bp_layout' );
			
				$GLOBALS['ghostpool_left_sidebar'] = ghostpool_option( 'bp_groups_left_sidebar' ) != 'default' ? ghostpool_option( 'bp_groups_left_sidebar' ) : ghostpool_option( 'bp_left_sidebar' );
			
				$GLOBALS['ghostpool_right_sidebar'] = ghostpool_option( 'bp_groups_right_sidebar' ) != 'default' ? ghostpool_option( 'bp_groups_right_sidebar' ) : ghostpool_option( 'bp_right_sidebar' );

			} elseif ( bp_is_register_page() ) {

				$GLOBALS['ghostpool_page_header'] = ghostpool_option( 'bp_register_page_header' ) != 'default' ? ghostpool_option( 'bp_register_page_header' ) : ghostpool_option( 'bp_page_header' );
				
				$GLOBALS['ghostpool_page_header_bg'] = ghostpool_option( 'bp_register_page_header_bg', 'url' ) != '' ? ghostpool_option( 'bp_register_page_header_bg' ) : ghostpool_option( 'bp_page_header_bg' );
			
				$GLOBALS['ghostpool_layout'] = ghostpool_option( 'bp_register_layout' ) != 'default' ? ghostpool_option( 'bp_register_layout' ) : ghostpool_option( 'bp_layout' );
			
				$GLOBALS['ghostpool_left_sidebar'] = ghostpool_option( 'bp_register_left_sidebar' ) != 'default' ? ghostpool_option( 'bp_register_left_sidebar' ) : ghostpool_option( 'bp_left_sidebar' );
			
				$GLOBALS['ghostpool_right_sidebar'] = ghostpool_option( 'bp_register_right_sidebar' ) != 'default' ? ghostpool_option( 'bp_register_right_sidebar' ) : ghostpool_option( 'bp_right_sidebar' );
								
			} else {
			
				$GLOBALS['ghostpool_page_header'] = ghostpool_option( 'bp_page_header' );
				
				$GLOBALS['ghostpool_page_header_bg'] = ghostpool_option( 'bp_page_header_bg' );
			
				$GLOBALS['ghostpool_layout'] = ghostpool_option( 'bp_layout' );
			
				$GLOBALS['ghostpool_left_sidebar'] = ghostpool_option( 'bp_left_sidebar' );
			
				$GLOBALS['ghostpool_right_sidebar'] = ghostpool_option( 'bp_right_sidebar' );
				
			}


		/*--------------------------------------------------------------
		bbPress
		--------------------------------------------------------------*/

		} elseif ( function_exists( 'is_bbpress' ) && is_bbpress() ) {

			if ( bbp_is_single_topic() OR bbp_is_single_reply() ) {
				$page_id = bbp_get_topic_id();
			} else {
				$page_id = get_the_ID();
			}
			
			if ( bbp_is_single_forum() OR bbp_is_single_topic() OR bbp_is_single_reply() ) {

				$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', $page_id, 'bbpress_page_header' ) == 'default' ? 
				$global['bbpress_page_header'] : redux_post_meta( 'socialize', $page_id, 'bbpress_page_header' );

				$page_header_bg = redux_post_meta( 'socialize', $page_id, 'bbpress_page_header_bg' );
				$GLOBALS['ghostpool_page_header_bg'] = ! empty( $page_header_bg['url'] ) ? $page_header_bg : $global['bbpress_page_header_bg'];
							
				$GLOBALS['ghostpool_page_header_text'] = redux_post_meta( 'socialize', $page_id, 'bbpress_page_header_text' ) == '' ? $global['bbpress_page_header_text'] : redux_post_meta( 'socialize', $page_id, 'bbpress_page_header_text' );
								
				$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', $page_id, 'bbpress_layout' ) == 'default' ? $global['bbpress_layout'] : redux_post_meta( 'socialize', $page_id, 'bbpress_layout' );
				
				$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', $page_id, 'bbpress_left_sidebar' ) == 'default' ? $global['bbpress_left_sidebar'] : redux_post_meta( 'socialize', $page_id, 'bbpress_left_sidebar' );	
				
				$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', $page_id, 'bbpress_right_sidebar' ) == 'default' ? $global['bbpress_right_sidebar'] : redux_post_meta( 'socialize', $page_id, 'bbpress_right_sidebar' );			
			
			} else {
							
				$GLOBALS['ghostpool_page_header'] = $global['bbpress_page_header'];
				
				$GLOBALS['ghostpool_page_header_bg'] = $global['bbpress_page_header_bg'];
				
				$GLOBALS['ghostpool_page_header_text'] = $global['bbpress_page_header_text'];
				
				$GLOBALS['ghostpool_layout'] = $global['bbpress_layout'];
				
				$GLOBALS['ghostpool_left_sidebar'] = $global['bbpress_left_sidebar'];
				
				$GLOBALS['ghostpool_right_sidebar'] = $global['bbpress_right_sidebar'];
			
			}
			

		/*--------------------------------------------------------------
		WooCommerce Shop Page
		--------------------------------------------------------------*/

		} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() && ( is_shop() OR is_product_category() OR is_product_tag() OR is_tax() ) ) {

			$page_id = get_option( 'woocommerce_shop_page_id' ); // Get WooCommerce shop page ID	

			$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', $page_id, 'page_page_header' ) == 'default' ? $global['shop_page_header'] : redux_post_meta( 'socialize', $page_id, 'page_page_header' );
			
			$GLOBALS['ghostpool_page_header_bg'] = get_post_meta( $page_id, 'page_page_header_bg', true );
			
			$GLOBALS['ghostpool_page_header_text'] = get_post_meta( $page_id, 'page_page_header', true ) ? get_post_meta( 
			$page_id, 'page_page_header', true ) : $global['shop_page_header_text'];
			
			$GLOBALS['ghostpool_teaser_video_bg'] = get_post_meta( $page_id, 'page_page_header_teaser_video_bg', true );
			
			$GLOBALS['ghostpool_full_video_bg'] = get_post_meta( $page_id, 'page_page_header_full_video_bg', true );		
			
			$GLOBALS['ghostpool_title'] = get_post_meta( $page_id, 'page_title', true );
			
			$GLOBALS['ghostpool_custom_title'] = get_post_meta( $page_id, 'page_custom_title', true );
			
			$GLOBALS['ghostpool_subtitle'] = get_post_meta( $page_id, 'page_subtitle', true );
			
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', $page_id, 'page_layout' ) == 'default' ? $global['shop_layout'] : redux_post_meta( 'socialize', $page_id, 'page_layout' );
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', $page_id, 'page_left_sidebar' ) == 'default' ? $global[ 'shop_left_sidebar' ] : redux_post_meta( 'socialize', $page_id, 'page_left_sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', $page_id, 'page_right_sidebar' ) == 'default' ? $global[ 'shop_right_sidebar' ] : redux_post_meta( 'socialize', $page_id, 'page_right_sidebar' );
	
		
		/*--------------------------------------------------------------
		WooCommerce Products
		--------------------------------------------------------------*/

		} elseif ( function_exists( 'is_woocommerce' ) && is_singular( 'product' ) ) {

			$GLOBALS['ghostpool_page_header'] = 'gp-standard-page-header';
			
			$GLOBALS['ghostpool_title'] = 'enabled';	
			
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', get_the_ID(), 'product_layout' ) == 'default' ? 
			$global['product_layout'] : redux_post_meta( 'socialize', get_the_ID(), 'product_layout' );
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'product_left_sidebar' ) == 'default' ? 
			$global['product_left_sidebar'] : redux_post_meta( 'socialize', get_the_ID(), 'product_left_sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'product_right_sidebar' ) == 'default' ? 
			$global['product_right_sidebar'] : redux_post_meta( 'socialize', get_the_ID(), 'product_right_sidebar' );
	
		/*--------------------------------------------------------------
		Events Calendar
		--------------------------------------------------------------*/

		} elseif ( class_exists( 'Tribe__Events__Main' ) && is_post_type_archive( 'tribe_events' ) ) {

			$GLOBALS['ghostpool_page_header'] = $global['events_page_header'];		
			
			$GLOBALS['ghostpool_page_header_bg'] = $global['events_page_header_bg'];		
			
			$GLOBALS['ghostpool_page_header_text'] = $global['events_page_header_text'];
			
			$GLOBALS['ghostpool_title'] = 'disabled';
			
			$GLOBALS['ghostpool_layout'] = $global['events_layout'];
			
			$GLOBALS['ghostpool_left_sidebar'] = $global['events_left_sidebar'];	
			
			$GLOBALS['ghostpool_right_sidebar'] = $global['events_right_sidebar'];			


		/*--------------------------------------------------------------
		Events Posts
		--------------------------------------------------------------*/

		} elseif ( class_exists( 'Tribe__Events__Main' ) && is_singular( 'tribe_events' ) ) {

			$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', get_the_ID(), 'events_post_page_header' ) == 'default' ? $global['events_post_page_header'] : redux_post_meta( 'socialize', get_the_ID(), 'events_post_page_header' );

			$page_header_bg = redux_post_meta( 'socialize', get_the_ID(), 'events_post_page_header_bg' );
			$GLOBALS['ghostpool_page_header_bg'] = ! empty( $page_header_bg['url'] ) ? $page_header_bg : $global['events_post_page_header_bg'];
									
			$GLOBALS['ghostpool_page_header_text'] = redux_post_meta( 'socialize', get_the_ID(), 'events_post_page_header_text' ) == '' ? $global['events_post_page_header_text'] : redux_post_meta( 'socialize', get_the_ID(), 'events_post_page_header_text' );
			
			$GLOBALS['ghostpool_title'] = 'disabled';
			
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', get_the_ID(), 'events_post_layout' ) == 'default' ? $global['events_post_layout'] : redux_post_meta( 'socialize', get_the_ID(), 'events_post_layout' );
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'events_post_left_sidebar' ) == 'default' ? $global['events_post_left_sidebar'] : redux_post_meta( 'socialize', get_the_ID(), 'events_post_left_sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'events_post_right_sidebar' ) == 'default' ? $global['events_post_right_sidebar'] : redux_post_meta( 'socialize', get_the_ID(), 'events_post_right_sidebar' );
			

		/*--------------------------------------------------------------
		Portfolio Categories
		--------------------------------------------------------------*/

		} elseif ( is_post_type_archive( 'gp_portfolio_item' ) OR is_tax( 'gp_portfolios' ) )  {

			// Get category option
			$term_data = null;
			if ( isset( get_queried_object()->term_id ) ) {
				$term_id = get_queried_object()->term_id;
				$term_data = get_option( "taxonomy_$term_id" );
			}	
			
			$GLOBALS['ghostpool_page_header'] = ! isset( $term_data['page_header'] ) || $term_data['page_header'] == 'default' ? $global['portfolio_cat_page_header'] : $term_data['page_header'];
			
			$GLOBALS['ghostpool_page_header_bg'] = $term_data['bg_image'];
			
			$GLOBALS['ghostpool_page_header_text'] =  $global['portfolio_cat_page_header_text'];
						
			$GLOBALS['ghostpool_title'] = 'enabled';	
			
			$GLOBALS['ghostpool_layout'] = ! isset( $term_data['layout'] ) || $term_data['layout'] == 'default' ? $global['portfolio_cat_layout'] : $term_data['layout'];
			
			$GLOBALS['ghostpool_left_sidebar'] = ! isset( $term_data['left_sidebar'] ) || $term_data['left_sidebar'] == 'default' ? $global['portfolio_cat_left_sidebar'] : $term_data['left_sidebar']; 
						
			$GLOBALS['ghostpool_right_sidebar'] = ! isset( $term_data['right_sidebar'] ) || $term_data['right_sidebar'] == 'default' ? $global['portfolio_cat_right_sidebar'] : $term_data['right_sidebar'];


		/*--------------------------------------------------------------
		Portfolio Page Template
		--------------------------------------------------------------*/

		} elseif ( is_page_template( 'portfolio-template.php' ) )  {

			$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_page_header' );

			$GLOBALS['ghostpool_page_header_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_page_header_bg' );

			$GLOBALS['ghostpool_page_header_text'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_page_header_text' );			
						
			$GLOBALS['ghostpool_teaser_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_page_header_teaser_video_bg' );
			
			$GLOBALS['ghostpool_full_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_page_header_full_video_bg' );
				
			$GLOBALS['ghostpool_title'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_title' );	
		
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_layout' );
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_left_sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_template_right_sidebar' );

		
		/*--------------------------------------------------------------
		Portfolio Items
		--------------------------------------------------------------*/

		} elseif ( is_singular( 'gp_portfolio_item' ) ) {

			$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_page_header' ) == 'default' ? $global['portfolio_item_page_header'] : redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_page_header' );

			$GLOBALS['ghostpool_page_header_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_page_header_bg' );	
		
			$GLOBALS['ghostpool_page_header_text'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_page_header_text' ) == '' ? $global['portfolio_item_page_header_text'] : redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_page_header_text' );
			
			$GLOBALS['ghostpool_teaser_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_page_header_teaser_video_bg' );
			
			$GLOBALS['ghostpool_full_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_page_header_full_video_bg' );
					
			$GLOBALS['ghostpool_title'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_title' );	
			
			$GLOBALS['ghostpool_custom_title'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_custom_title' );
			
			$GLOBALS['ghostpool_subtitle'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_subtitle' );
			
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_layout' ) == 'default' ? $global['portfolio_item_layout'] : redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_layout' );
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_left_sidebar' ) == 'default' ? $global['portfolio_item_left_sidebar'] : redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_left_sidebar' );	
			
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_right_sidebar' ) == 'default' ? $global['portfolio_item_right_sidebar'] : redux_post_meta( 'socialize', get_the_ID(), 'portfolio_item_right_sidebar' );	


		/*--------------------------------------------------------------
		Search/Author Results
		--------------------------------------------------------------*/

		} elseif ( is_search() or is_author() ) {
			
			$GLOBALS['ghostpool_page_header'] = $global['search_page_header'];
			
			$GLOBALS['ghostpool_page_header_bg'] = $global['search_page_header_bg'];

			$GLOBALS['ghostpool_page_header_text'] = $global['search_page_header_text'];	
									
			$GLOBALS['ghostpool_layout'] = $global['search_layout'];
			
			$GLOBALS['ghostpool_left_sidebar'] = $global['search_left_sidebar']; 
			
			$GLOBALS['ghostpool_right_sidebar'] = $global['search_right_sidebar'];
	
	
		/*--------------------------------------------------------------
		Blog Page Template
		--------------------------------------------------------------*/

		} elseif ( is_page_template( 'blog-template.php' ) )  {

			$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_page_header' );
			
			$GLOBALS['ghostpool_page_header_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_page_header_bg' );
			
			$GLOBALS['ghostpool_page_header_text'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_page_header_text' );
				
			$GLOBALS['ghostpool_teaser_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_page_header_teaser_video_bg' );				
			
			$GLOBALS['ghostpool_full_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_page_header_full_video_bg' );
				
			$GLOBALS['ghostpool_title'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_title' );
			
			$GLOBALS['ghostpool_custom_title'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_custom_title' );
			
			$GLOBALS['ghostpool_subtitle'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_subtitle' );
		
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_layout' );
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_left_sidebar' );	
						
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'blog_template_right_sidebar' );	


		/*--------------------------------------------------------------
		Homepage Template
		--------------------------------------------------------------*/

		} elseif ( is_page_template( 'homepage-template.php' ) )  {
				
			$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_page_header' );
			
			$GLOBALS['ghostpool_page_header_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_page_header_bg' ) ;
			
			$GLOBALS['ghostpool_page_header_text'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_page_header_text' );
				
			$GLOBALS['ghostpool_teaser_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_page_header_teaser_video_bg' );				
			
			$GLOBALS['ghostpool_full_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_page_header_full_video_bg' );
				
			$GLOBALS['ghostpool_title'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_title' );
			
			$GLOBALS['ghostpool_custom_title'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_custom_title' );
			
			$GLOBALS['ghostpool_subtitle'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_subtitle' );
		
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_layout' );
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_left_sidebar' );	
						
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'homepage_right_sidebar' );


		/*--------------------------------------------------------------
		Blank page
		--------------------------------------------------------------*/

		} elseif ( is_page_template( 'blank-page-template.php' ) ) {

			$GLOBALS['ghostpool_page_header'] = apply_filters( 'gp_blank_page_header', 'gp-standard-page-header' );
			
			$GLOBALS['ghostpool_layout'] = apply_filters( 'gp_blank_layout', 'gp-no-sidebar' );
	
			$GLOBALS['ghostpool_left_sidebar'] = apply_filters( 'gp_blank_left_sidebar', 'gp-left-sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = apply_filters( 'gp_blank_right_sidebar', 'gp-right-sidebar' );
			

		/*--------------------------------------------------------------
		Attachment page
		--------------------------------------------------------------*/

		} elseif ( is_attachment() ) {

			$GLOBALS['ghostpool_page_header'] = apply_filters( 'gp_attachment_page_header', 'gp-standard-page-header' );
			
			$GLOBALS['ghostpool_layout'] = apply_filters( 'gp_attachment_layout', 'gp-no-sidebar' );
	
			$GLOBALS['ghostpool_left_sidebar'] = apply_filters( 'gp_attachment_left_sidebar', 'gp-left-sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = apply_filters( 'gp_attachment_right_sidebar', 'gp-right-sidebar' );
			
												
		/*--------------------------------------------------------------
		Error 404 page
		--------------------------------------------------------------*/

		} elseif ( is_404() ) {

			$GLOBALS['ghostpool_page_header'] = apply_filters( 'gp_error_page_header', 'gp-standard-page-header' );
			
			$GLOBALS['ghostpool_layout'] = apply_filters( 'gp_error_layout', 'gp-no-sidebar' );
	
			$GLOBALS['ghostpool_left_sidebar'] = apply_filters( 'gp_error_left_sidebar', 'gp-left-sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = apply_filters( 'gp_error_right_sidebar', 'gp-right-sidebar' );
			
								
		/*--------------------------------------------------------------
		Post Categories, Archives & Tags
		--------------------------------------------------------------*/

		} elseif ( is_home() OR is_archive() ) {

			// Get category option
			$term_data = null;
			if ( isset( get_queried_object()->term_id ) ) {
				$term_id = get_queried_object()->term_id;
				$term_data = get_option( "taxonomy_$term_id" );
			}
			
			$GLOBALS['ghostpool_page_header'] = ! isset( $term_data['page_header'] ) || $term_data['page_header'] == 'default' ? $global['cat_page_header'] : $term_data['page_header'];

			$GLOBALS['ghostpool_page_header_bg'] = $term_data['bg_image'];
		
			$GLOBALS['ghostpool_page_header_text'] = $global['cat_page_header_text'];
			
			$GLOBALS['ghostpool_layout'] = ! isset( $term_data['layout'] ) || $term_data['layout'] == 'default' ? $global['cat_layout'] : $term_data['layout'];
			
			$GLOBALS['ghostpool_left_sidebar'] = ! isset( $term_data['left_sidebar'] ) || $term_data['left_sidebar'] == 'default' ? $global['cat_left_sidebar'] : $term_data['left_sidebar']; 
						
			$GLOBALS['ghostpool_right_sidebar'] = ! isset( $term_data['right_sidebar'] ) || $term_data['right_sidebar'] == 'default' ? $global['cat_right_sidebar'] : $term_data['right_sidebar'];
			

		/*--------------------------------------------------------------
		Posts
		--------------------------------------------------------------*/

		} elseif ( is_singular( 'post' ) ) {
	
			$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', get_the_ID(), 'post_page_header' ) == 'default' ? $global['post_page_header'] : redux_post_meta( 'socialize', get_the_ID(), 'post_page_header' );
						
			$GLOBALS['ghostpool_page_header_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'post_page_header_bg' );
			
			$GLOBALS['ghostpool_page_header_text'] = redux_post_meta( 'socialize', get_the_ID(), 'post_page_header_text' ) == '' ? $global['post_page_header_text'] : redux_post_meta( 'socialize', get_the_ID(), 'post_page_header_text' );
					
			$GLOBALS['ghostpool_teaser_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'post_page_header_teaser_video_bg' );
			
			$GLOBALS['ghostpool_full_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'post_page_header_full_video_bg' );
			
			$GLOBALS['ghostpool_title'] = redux_post_meta( 'socialize', get_the_ID(), 'post_title' );
			
			$GLOBALS['ghostpool_custom_title'] = redux_post_meta( 'socialize', get_the_ID(), 'post_custom_title' );
			
			$GLOBALS['ghostpool_subtitle'] = redux_post_meta( 'socialize', get_the_ID(), 'post_subtitle' );
			
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', get_the_ID(), 'post_layout' ) == 'default' ? $global['post_layout'] : redux_post_meta( 'socialize', get_the_ID(), 'post_layout' );
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'post_left_sidebar' ) == 'default' ? $global['post_left_sidebar'] : redux_post_meta( 'socialize', get_the_ID(), 'post_left_sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'post_right_sidebar' ) == 'default' ? $global['post_right_sidebar'] : redux_post_meta( 'socialize', get_the_ID(), 'post_right_sidebar' );


		/*--------------------------------------------------------------
		Slides
		--------------------------------------------------------------*/

		} elseif ( is_singular( 'gp_slide' ) ) {

			$GLOBALS['ghostpool_page_header'] = apply_filters( 'gp_slides_page_header', 'gp-standard-page-header' );
			
			$GLOBALS['ghostpool_title'] = apply_filters( 'gp_slides_title', '' );
			
			$GLOBALS['ghostpool_layout'] = apply_filters( 'gp_slides_layout', 'gp-no-sidebar' );
	
			$GLOBALS['ghostpool_left_sidebar'] = apply_filters( 'gp_slides_left_sidebar', 'gp-left-sidebar' );
			
			$GLOBALS['ghostpool_right_sidebar'] = apply_filters( 'gp_slides_right_sidebar', 'gp-right-sidebar' );
	
	
		/*--------------------------------------------------------------
		Pages
		--------------------------------------------------------------*/

		} else {

			$GLOBALS['ghostpool_page_header'] = redux_post_meta( 'socialize', get_the_ID(), 'page_page_header' ) && redux_post_meta( 'socialize', get_the_ID(), 'page_page_header' ) != 'default' ? redux_post_meta( 'socialize', get_the_ID(), 'page_page_header' ) : $global['page_page_header'];
			
			$GLOBALS['ghostpool_page_header_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'page_page_header_bg' );
						
			$GLOBALS['ghostpool_page_header_text'] = redux_post_meta( 'socialize', get_the_ID(), 'page_page_header_text' ) && redux_post_meta( 'socialize', get_the_ID(), 'page_page_header_text' ) != '' ? redux_post_meta( 'socialize', get_the_ID(), 'page_page_header_text' ) : $global['page_page_header_text'];

			$GLOBALS['ghostpool_teaser_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'page_page_header_teaser_video_bg' );
			
			$GLOBALS['ghostpool_full_video_bg'] = redux_post_meta( 'socialize', get_the_ID(), 'page_page_header_full_video_bg' );		
			
			$GLOBALS['ghostpool_title'] = redux_post_meta( 'socialize', get_the_ID(), 'page_title' );
			
			$GLOBALS['ghostpool_custom_title'] = redux_post_meta( 'socialize', get_the_ID(), 'page_custom_title' );
			
			$GLOBALS['ghostpool_subtitle'] = redux_post_meta( 'socialize', get_the_ID(), 'page_subtitle' );
			
			$GLOBALS['ghostpool_layout'] = redux_post_meta( 'socialize', get_the_ID(), 'page_layout' ) && redux_post_meta( 'socialize', get_the_ID(), 'page_layout' ) != 'default' ? redux_post_meta( 'socialize', get_the_ID(), 'page_layout' ) : $global['page_layout'];
			
			$GLOBALS['ghostpool_left_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'page_left_sidebar' ) && redux_post_meta( 'socialize', get_the_ID(), 'page_left_sidebar' ) != 'default' ? redux_post_meta( 'socialize', get_the_ID(), 'page_left_sidebar' ) : $global['page_left_sidebar'];	
					
			$GLOBALS['ghostpool_right_sidebar'] = redux_post_meta( 'socialize', get_the_ID(), 'page_right_sidebar' ) && redux_post_meta( 'socialize', get_the_ID(), 'page_right_sidebar' ) != 'default' ? redux_post_meta( 'socialize', get_the_ID(), 'page_right_sidebar' ) : $global['page_right_sidebar'];
						
			$GLOBALS['ghostpool_featured_image'] = redux_post_meta( 'socialize', get_the_ID(), 'page_featured_image' ) && redux_post_meta( 'socialize', get_the_ID(), 'page_featured_image' ) != 'default' ? redux_post_meta( 'socialize', get_the_ID(), 'page_featured_image' ) : $global['page_featured_image'];
			
			$image = redux_post_meta( 'socialize', get_the_ID(), 'page_image' );
			
			$GLOBALS['ghostpool_image_width'] = ! empty( $image['width'] ) ? $image['width'] : $global['page_image']['width'];
			
			$GLOBALS['ghostpool_image_height'] = ! empty( $image['height'] ) ? $image['height'] : 
			$global['page_image']['height'];
			
			$GLOBALS['ghostpool_hard_crop'] = redux_post_meta( 'socialize', get_the_ID(), 'page_hard_crop' ) && redux_post_meta( 'socialize', get_the_ID(), 'page_hard_crop' ) == 'default' ? redux_post_meta( 'socialize', get_the_ID(), 'page_hard_crop' ) : $global['page_hard_crop'];
			
			$GLOBALS['ghostpool_image_alignment'] = redux_post_meta( 'socialize', get_the_ID(), 'page_image_alignment' )  && redux_post_meta( 'socialize', get_the_ID(), 'page_image_alignment' ) != 'default' ? redux_post_meta( 'socialize', get_the_ID(), 'page_image_alignment' ) : $global['page_image_alignment'];

		}


		/*--------------------------------------------------------------
		Add init variables via your child theme using this function
		--------------------------------------------------------------*/
		
		if ( function_exists( 'ghostpool_custom_init_variables' ) ) {
			ghostpool_custom_init_variables();
		}
		
	}
}

?>