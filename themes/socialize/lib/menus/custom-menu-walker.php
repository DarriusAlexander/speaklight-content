<?php

if ( ! class_exists( 'Ghostpool_Custom_Menu' ) ) {

	class Ghostpool_Custom_Menu extends Walker_Nav_Menu {

		// Start level (add classes to ul sub-menus)
		function start_lvl( &$output, $depth = 0, $args = array() ) {
		
			// Depth dependent classes
			$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
			$display_depth = ( $depth + 1 ); // because it counts the first submenu as 0
			$classes = array(
				'sub-menu',
				( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
				( $display_depth >=2 ? 'sub-sub-menu' : '' ),
				'menu-depth-' . $display_depth
				);
			$class_names = implode( ' ', $classes );

			// Build html
			$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
			
		}
  
		// Start element (add main/sub classes to li's and links)
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			global $wp_query;
	
			$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

			// Depth dependent classes
			$depth_classes = array(
				( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
				( $depth >=2 ? 'sub-sub-menu-item' : '' ),
				( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
				'menu-item-depth-' . $depth
			);
			$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

			// Depth dependent classes
			$display_depth = ( $depth + 1); // because it counts the first submenu as 0
			$sub_menu_classes = array(
				'sub-menu',
				( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
				( $display_depth >=2 ? 'sub-sub-menu' : '' ),
				'menu-depth-' . $display_depth
				);
			$submenu_depth_class_names = implode( ' ', $sub_menu_classes );
			
			// Parsed classes
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

			// Build html
			
			$menu_type = get_post_meta( $item->ID, 'menu-item-gp-menu-type', true ) ? get_post_meta( $item->ID, 'menu-item-gp-menu-type', true ) : 'gp-standard-menu';
			
			// Profile class
			if ( $menu_type == 'gp-profile-link' ) {
				$profile_class = 'gp-standard-menu';
			} else {
				$profile_class = '';
			}	
			
			if ( ( is_user_logged_in() && get_post_meta( $item->ID, 'menu-item-gp-user-display', true ) != 'gp-show-logged-out' ) OR ( ! is_user_logged_in() && get_post_meta( $item->ID, 'menu-item-gp-user-display', true ) != 'gp-show-logged-in' ) ) {
			
				if ( ( is_user_logged_in() && ( $menu_type == 'gp-login-link' OR $menu_type == 'gp-register-link' ) ) ) {
				
					$output .= '';
				
				} elseif ( ( ! is_user_logged_in() && ( $menu_type == 'gp-logout-link' OR $menu_type == 'gp-profile-link' ) ) ) {
				
					$output .= '';

				} else {
						
					$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $menu_type . ' ' . $profile_class . ' ' . get_post_meta( $item->ID, 'menu-item-gp-columns', true ) . ' ' . get_post_meta( $item->ID, 'menu-item-gp-content', true ) . ' ' . get_post_meta( $item->ID, 'menu-item-gp-display', true ) . ' ' . $depth_class_names . ' ' . get_post_meta( $item->ID, 'menu-item-gp-hide-nav-label', true ) . ' ' . $class_names . '">';

					// Link attributes
					$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
					$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
					$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				
					// Menu type
					if ( $menu_type == 'gp-login-link' ) {			
						$item_link = '#login';
					} elseif ( $menu_type == 'gp-register-link' ) {
						if ( function_exists( 'bp_is_active' ) ) {
							$item_link = bp_get_signup_page( false );
						} else {
							$item_link = '#register';
						}	
					} elseif ( $menu_type == 'gp-logout-link' ) {	
						$item_link = wp_logout_url( apply_filters( 'gp_logout_redirect', get_home_url() ) );
					} elseif ( $menu_type == 'gp-profile-link' ) {	
						if ( function_exists( 'bp_is_active' ) ) {
							global $bp;
							$item_link = $bp->loggedin_user->domain; 
						} else {
							$current_user = wp_get_current_user();
							$item_link = get_author_posts_url( $current_user->ID );
						}								
					} else {
						$item_link = $item->url;
					}
				
					$attributes .= ! empty( $item_link ) ? ' href="' . esc_attr( $item_link ) .'"' : '';
				
					$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
			
					// Tab content menu
					if ( $menu_type == 'gp-tab-content-menu' OR $menu_type == 'gp-content-menu' ) {

						// Default variables
						$GLOBALS['ghostpool_menu'] = true;
						$GLOBALS['ghostpool_cats'] = $item->object_id;
					
						// Load page variables
						ghostpool_category_variables();
						
						// Posts per page depending on menu type
						if ( $menu_type == 'gp-content-menu' ) {
							$GLOBALS['ghostpool_menu_per_page'] = 5;
						} else {
							$GLOBALS['ghostpool_menu_per_page'] = 4;
						}
								
						$query_args = array(
							'post_status' 	      => 'publish',
							'post_type'           => array( 'post', 'page' ),
							'tax_query'           => $GLOBALS['ghostpool_tax'],
							'orderby'             => 'date',
							'order'           	  => 'desc',
							'posts_per_page'      => $GLOBALS['ghostpool_menu_per_page'],
							'paged'               => 1,
						);

						$query = new wp_query( $query_args ); 

						if ( function_exists( 'ghostpool_data_properties' ) ) {
							$data_properties = ghostpool_data_properties( 'menu' ); 
						} else {
							$data_properties = '';
						}
				
						$dropdown = '<ul class="sub-menu ' . $submenu_depth_class_names . '">
						<li id="nav-menu-item-'. $item->ID . '" class="' . $class_names . '"' . $data_properties . '>';
					
							if ( $query->have_posts() ) :
				
								if ( $menu_type == 'gp-tab-content-menu' ) {

									$terms = get_terms( array( 
										'taxonomy' => $item->object, 
										'parent'  => $item->object_id,
									) );
									if ( ! empty( $terms ) ) {
										$dropdown .= '<ul class="gp-menu-tabs">
											<li id="' . $item->object_id . '" class="gp-selected">' . esc_html__( 'All', 'socialize' ) . '</li>';		
											foreach( $terms as $term ) {
												if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
													$dropdown .= '<li id="' . $term->term_id . '">' . $term->name . '</li>';
												}
											}
										$dropdown .= '</ul>';
										
									}
													
								}

								$dropdown .= '<div class="gp-inner-loop ' . ghostpool_option( 'ajax' ) . '" >';
						
								while ( $query->have_posts() ) : $query->the_post();
															
									// Post link
									if ( get_post_format() == 'link' ) { 
										$link = esc_url( get_post_meta( get_the_ID(), 'link', true ) );
										$target = 'target="' . get_post_meta( get_the_ID(), 'link_target', true ) . '"';
									} else {
										$link = get_permalink();
										$target = '';
									}
														
									$dropdown .= '<section class="' . implode( ' ' , get_post_class( 'gp-post-item' ) ) . '">';

										if ( has_post_thumbnail() ) {
																				
											$image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), apply_filters( 'gp_menu_image_width', '270' ), apply_filters( 'gp_menu_image_width', '140' ), true, false, true );
											if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
												$retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), apply_filters( 'gp_menu_image_width', '270' ) * 2, apply_filters( 'gp_menu_image_width', '140' ) * 2, true, true, true );
											} else {
												$retina = '';
											}
									
											$dropdown .= '<div class="gp-post-thumbnail"><div class="gp-image-above">
												<a href="' . $link . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '"' . $target . '>
													<img src="' . $image[0] . '" data-rel="' . $retina . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . the_title_attribute( array( 'echo' => false ) ) . '" class="gp-post-image" />
												</a>
											</div></div>';
							
										}
								
										$dropdown .= '<h3 class="gp-loop-title"><a href="' . $link . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '"'. $target. '>' . get_the_title() . '</a></h3>
										
										<div class="gp-loop-meta"><time class="gp-post-meta gp-meta-date" datetime="' . get_the_date( 'c' ) . '">' . get_the_time( get_option( 'date_format' ) ) . '</time></div>
						
									</section>';
						
								endwhile; 
					
								$dropdown .= '</div>';
								
								if ( ghostpool_option( 'ajax' ) == 'gp-ajax-loop' ) {
									$dropdown .= '<div class="gp-pagination gp-standard-pagination gp-pagination-arrows">' . ghostpool_get_previous_posts_page_link( $query->max_num_pages ) . ghostpool_get_next_posts_page_link( $query->max_num_pages ) . '</div>';
								}	
											
							endif; wp_reset_postdata();
							$GLOBALS['ghostpool_menu'] = null;
							$GLOBALS['ghostpool_cats'] = null;

						$dropdown .= '</li></ul>';

					} else {
					
						$dropdown = '';
					
					}	
					
					// Navigation label
					if ( $menu_type == 'gp-profile-link' ) {
						if ( function_exists( 'bp_notifications_get_notifications_for_user' ) ) { 
							$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
							$count = ! empty( $notifications ) ? count( $notifications ) : 0;
							$count = '<a href="' . $bp->loggedin_user->domain . '/notifications" class="gp-notification-counter">' . $count . '</a>';
						} else {
							$count = '';
						} 
						$current_user = wp_get_current_user();
						$username = $current_user->display_name;
						$limit = apply_filters( 'gp_truncate_bp_username', 15 );
						if ( strlen( $username ) > $limit ) { 
							$username = substr( $username, 0, $limit ) . '...';
						}
						$nav_label = $username;
						$after = $args->after . $count;
					} elseif ( get_post_meta( $item->ID, 'menu-item-gp-hide-nav-label', true ) == 'gp-hide-nav-label' && get_post_meta( $item->ID, 'menu-item-gp-content', true ) != 'gp-menu-header' ) {
						$nav_label = '';
						$after = $args->after;	
					} else {
						$nav_label = $item->title;
						$after = $args->after;
					}

					$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s%7$s',
						$args->before,
						$attributes,
						$args->link_before,
						apply_filters( 'the_title', $nav_label, $item->ID ),
						$args->link_after,
						$after,
						$dropdown
					);
			
					// Build html
					$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
				}
				
			}
							
		}
		
		// End element (add closing li's)
		function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
			$menu_type = get_post_meta( $item->ID, 'menu-item-gp-menu-type', true ) ? get_post_meta( $item->ID, 'menu-item-gp-menu-type', true ) : 'gp-standard-menu';

			if ( ( is_user_logged_in() && get_post_meta( $item->ID, 'menu-item-gp-user-display', true ) != 'gp-show-logged-out' ) OR ( ! is_user_logged_in() && get_post_meta( $item->ID, 'menu-item-gp-user-display', true ) != 'gp-show-logged-in' ) ) {
			
				if ( ( is_user_logged_in() && ( $menu_type == 'gp-login-link' OR $menu_type == 'gp-register-link' ) ) ) {
				
					$output .= '';
				
				} elseif ( ( ! is_user_logged_in() && ( $menu_type == 'gp-logout-link' OR $menu_type == 'gp-profile-link' ) ) ) {
				
					$output .= '';
				
				} else {
				
					$output .= '</li>';

				}
			
			}
								
		}

	}
} 

?>