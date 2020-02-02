<?php 

if ( ! function_exists( 'ghostpool_data_properties' ) ) {
	function ghostpool_data_properties( $type ) {
		
		if ( ghostpool_option( 'ajax', '', 'gp-ajax-loop' ) == 'gp-ajax-loop' ) {
		
			// Check to see if options exists
			$GLOBALS['ghostpool_ajax_cats'] = ! empty( $GLOBALS['ghostpool_cats'] ) ? $GLOBALS['ghostpool_cats'] : '';	
			$GLOBALS['ghostpool_ajax_page_ids'] = ! empty( $GLOBALS['ghostpool_page_ids'] ) ? $GLOBALS['ghostpool_page_ids'] : '';		
			$GLOBALS['ghostpool_ajax_post_types'] = ! empty( $GLOBALS['ghostpool_post_types'] ) ? $GLOBALS['ghostpool_post_types'] : '';			
			$GLOBALS['ghostpool_ajax_format'] = ! empty( $GLOBALS['ghostpool_format'] ) ? $GLOBALS['ghostpool_format'] : '';
			$GLOBALS['ghostpool_ajax_orderby'] = ! empty( $GLOBALS['ghostpool_orderby'] ) ? $GLOBALS['ghostpool_orderby'] : '';
			$GLOBALS['ghostpool_ajax_date_posted'] = ! empty( $GLOBALS['ghostpool_date_posted'] ) ? $GLOBALS['ghostpool_date_posted'] : '';
			$GLOBALS['ghostpool_ajax_date_modified'] = ! empty( $GLOBALS['ghostpool_date_modified'] ) ? $GLOBALS['ghostpool_date_modified'] : '';
			$GLOBALS['ghostpool_ajax_per_page'] = ! empty( $GLOBALS['ghostpool_per_page'] ) ? $GLOBALS['ghostpool_per_page'] : '';
			$GLOBALS['ghostpool_ajax_menu_per_page'] = ! empty( $GLOBALS['ghostpool_menu_per_page'] ) ? $GLOBALS['ghostpool_menu_per_page'] : '';
			$GLOBALS['ghostpool_ajax_offset'] = ! empty( $GLOBALS['ghostpool_offset'] ) ? $GLOBALS['ghostpool_offset'] : '';
			$GLOBALS['ghostpool_ajax_featured_image'] = ! empty( $GLOBALS['ghostpool_featured_image'] ) ? $GLOBALS['ghostpool_featured_image'] : '';
			$GLOBALS['ghostpool_ajax_image_width'] = ! empty( $GLOBALS['ghostpool_image_width'] ) ? $GLOBALS['ghostpool_image_width'] : '';
			$GLOBALS['ghostpool_ajax_image_height'] = ! empty( $GLOBALS['ghostpool_image_height'] ) ? $GLOBALS['ghostpool_image_height'] : '';
			$GLOBALS['ghostpool_ajax_hard_crop'] = ! empty( $GLOBALS['ghostpool_hard_crop'] ) ? $GLOBALS['ghostpool_hard_crop'] : '';
			$GLOBALS['ghostpool_ajax_image_alignment'] = ! empty( $GLOBALS['ghostpool_image_alignment'] ) ? $GLOBALS['ghostpool_image_alignment'] : '';
			$GLOBALS['ghostpool_ajax_content_display'] = ! empty( $GLOBALS['ghostpool_content_display'] ) ? $GLOBALS['ghostpool_content_display'] : '';
			$GLOBALS['ghostpool_ajax_excerpt_length'] = ! empty( $GLOBALS['ghostpool_excerpt_length'] ) ? $GLOBALS['ghostpool_excerpt_length'] : 0;
			$GLOBALS['ghostpool_ajax_meta_author'] = ! empty( $GLOBALS['ghostpool_meta_author'] ) ? $GLOBALS['ghostpool_meta_author'] : '';
			$GLOBALS['ghostpool_ajax_meta_date'] = ! empty( $GLOBALS['ghostpool_meta_date'] ) ? $GLOBALS['ghostpool_meta_date'] : '';
			$GLOBALS['ghostpool_ajax_meta_comment_count'] = ! empty( $GLOBALS['ghostpool_meta_comment_count'] ) ? $GLOBALS['ghostpool_meta_comment_count'] : '';
			$GLOBALS['ghostpool_ajax_meta_views'] = ! empty( $GLOBALS['ghostpool_meta_views'] ) ? $GLOBALS['ghostpool_meta_views'] : '';
			$GLOBALS['ghostpool_ajax_meta_cats'] = ! empty( $GLOBALS['ghostpool_meta_cats'] ) ? $GLOBALS['ghostpool_meta_cats'] : '';
			$GLOBALS['ghostpool_ajax_meta_tags'] = ! empty( $GLOBALS['ghostpool_meta_tags'] ) ? $GLOBALS['ghostpool_meta_tags'] : '';
			$GLOBALS['ghostpool_ajax_read_more_link'] = ! empty( $GLOBALS['ghostpool_read_more_link'] ) ? $GLOBALS['ghostpool_read_more_link'] : '';
			$GLOBALS['ghostpool_ajax_page_arrows'] = ! empty( $GLOBALS['ghostpool_page_arrows'] ) ? $GLOBALS['ghostpool_page_arrows'] : '';
			$GLOBALS['ghostpool_ajax_page_numbers'] = ! empty( $GLOBALS['ghostpool_page_numbers'] ) ? $GLOBALS['ghostpool_page_numbers'] : '';	
	 
			// Add to blog wrappers to pull query data 
			return ' data-type="' . $type . '" data-cats="' . $GLOBALS['ghostpool_ajax_cats'] . '" data-posttypes="' . $GLOBALS['ghostpool_ajax_post_types'] . '" data-pageids="' . $GLOBALS['ghostpool_ajax_page_ids'] . '" data-format="' . $GLOBALS['ghostpool_ajax_format'] . '" data-orderby="' . $GLOBALS['ghostpool_ajax_orderby'] . '" data-perpage="' . $GLOBALS['ghostpool_ajax_per_page'] . '" data-menuperpage="' . $GLOBALS['ghostpool_ajax_menu_per_page'] . '" data-offset="' . $GLOBALS['ghostpool_ajax_offset'] . '"  data-featuredimage="' . $GLOBALS['ghostpool_ajax_featured_image'] . '" data-imagewidth="' . $GLOBALS['ghostpool_ajax_image_width'] . '" data-imageheight="' . $GLOBALS['ghostpool_ajax_image_height'] . '" data-hardcrop="' . $GLOBALS['ghostpool_ajax_hard_crop'] . '" data-imagealignment="' . $GLOBALS['ghostpool_ajax_image_alignment'] . '" data-contentdisplay="' . $GLOBALS['ghostpool_ajax_content_display'] . '" data-excerptlength="' . $GLOBALS['ghostpool_ajax_excerpt_length'] . '" data-metaauthor="' . $GLOBALS['ghostpool_ajax_meta_author'] . '" data-metadate="' . $GLOBALS['ghostpool_ajax_meta_date'] . '" data-metacommentcount="' . $GLOBALS['ghostpool_ajax_meta_comment_count'] . '" data-metaviews="' . $GLOBALS['ghostpool_ajax_meta_views'] . '" data-metacats="' . $GLOBALS['ghostpool_ajax_meta_cats'] . '" data-metatags="' . $GLOBALS['ghostpool_ajax_meta_tags'] . '" data-readmorelink="' . $GLOBALS['ghostpool_ajax_read_more_link'] . '" data-pagearrows="' . $GLOBALS['ghostpool_ajax_page_arrows'] . '" data-pagenumbers="' . $GLOBALS['ghostpool_ajax_page_numbers'] . '"';
		
		}
	}
}
 
if ( ! function_exists( 'ghostpool_register_ajax' ) ) {
	function ghostpool_register_ajax() {
		
		if ( ghostpool_option( 'ajax', '', 'gp-ajax-loop' ) == 'gp-ajax-loop' ) {			
	
			global $query_string;
		
			// Determine http or https for admin-ajax.php URL
			if ( is_ssl() ) { $scheme = 'https'; } else { $scheme = 'http'; }

			// Load scripts
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );			
			wp_enqueue_script( 'gp-flexslider' );
			wp_enqueue_script( 'ghostpool-ajax-loop', get_template_directory_uri() . '/lib/scripts/ajax-loop.js', array( 'jquery' ), '', true );
			wp_localize_script( 'ghostpool-ajax-loop', 'ghostpoolAjax', array(
				'ajaxurl' => admin_url( 'admin-ajax.php', $scheme ),
				'ajaxnonce' => wp_create_nonce( 'gp-ajax-nonce' ),
				'querystring' => $query_string,
			) ); 
		
		}		
		
	}
}
add_action( 'wp_enqueue_scripts', 'ghostpool_register_ajax' );

if ( ! function_exists( 'ghostpool_ajax' ) ) {
	function ghostpool_ajax() {
	
		if ( ghostpool_option( 'ajax', '', 'gp-ajax-loop' ) == 'gp-ajax-loop' ) {
			
			if ( ! wp_verify_nonce( $_GET['ajaxnonce'], 'gp-ajax-nonce' ) )
				die();
	
			// Pagination
			$ghostpool_pagination = ( isset( $_GET['pagenumber'] ) ) ? $_GET['pagenumber'] : 0;
				
			// Get theme options from ajax values
			$GLOBALS['ghostpool_cats'] = isset( $_GET['cats'] ) ? $_GET['cats'] : '';		
			$GLOBALS['ghostpool_post_types'] = isset( $_GET['posttypes'] ) ? explode( ',', $_GET['posttypes'] ) : '';
			$GLOBALS['ghostpool_page_ids'] = isset( $_GET['pageids'] ) ? $_GET['pageids'] : '';
			$GLOBALS['ghostpool_format'] = isset( $_GET['format'] ) ? $_GET['format'] : '';
			$GLOBALS['ghostpool_orderby'] = isset( $_GET['orderby'] ) ? $_GET['orderby'] : '';
			$GLOBALS['ghostpool_date_posted'] = isset( $_GET['dateposted'] ) ? $_GET['dateposted'] : '';
			$GLOBALS['ghostpool_date_modified'] = isset( $_GET['datemodified'] ) ? $_GET['datemodified'] : '';
			$GLOBALS['ghostpool_per_page'] = isset( $_GET['perpage'] ) ? $_GET['perpage'] : '';
			$GLOBALS['ghostpool_menu_per_page'] = isset( $_GET['menuperpage'] ) ? $_GET['menuperpage'] : '';
			$GLOBALS['ghostpool_offset'] = isset( $_GET['offset'] ) ? $_GET['offset'] : '';
			$GLOBALS['ghostpool_featured_image'] = isset( $_GET['featuredimage'] ) ? $_GET['featuredimage'] : '';
			$GLOBALS['ghostpool_image_width'] = isset( $_GET['imagewidth'] ) ? $_GET['imagewidth'] : '';
			$GLOBALS['ghostpool_image_height'] = isset( $_GET['imageheight'] ) ? $_GET['imageheight'] : '';
			$GLOBALS['ghostpool_hard_crop'] = isset( $_GET['hardcrop'] ) ? $_GET['hardcrop'] : '';
			$GLOBALS['ghostpool_image_alignment'] = isset( $_GET['imagealignment'] ) ? $_GET['imagealignment'] : '';
			$GLOBALS['ghostpool_content_display'] = isset( $_GET['contentdisplay'] ) ? $_GET['contentdisplay'] : '';
			$GLOBALS['ghostpool_excerpt_length'] = isset( $_GET['excerptlength'] ) ? $_GET['excerptlength'] : '0';
			$GLOBALS['ghostpool_meta_author'] = isset( $_GET['metaauthor'] ) ? $_GET['metaauthor'] : '';
			$GLOBALS['ghostpool_meta_date'] = isset( $_GET['metadate'] ) ? $_GET['metadate'] : '';
			$GLOBALS['ghostpool_meta_comment_count'] = isset( $_GET['metacommentcount'] ) ? $_GET['metacommentcount'] : '';
			$GLOBALS['ghostpool_meta_views'] = isset( $_GET['metaviews'] ) ? $_GET['metaviews'] : '';
			$GLOBALS['ghostpool_meta_cats'] = isset( $_GET['metacats'] ) ? $_GET['metacats'] : '';
			$GLOBALS['ghostpool_meta_tags'] = isset( $_GET['metatags'] ) ? $_GET['metatags'] : '';
			$GLOBALS['ghostpool_read_more_link'] = isset( $_GET['readmorelink'] ) ? $_GET['readmorelink'] : '';
			$GLOBALS['ghostpool_page_arrows'] = isset( $_GET['pagearrows'] ) ? $_GET['pagearrows'] : '';
			$GLOBALS['ghostpool_page_numbers'] = isset( $_GET['pagenumbers'] ) ? $_GET['pagenumbers'] : '';
						
			// Use filtered category is selected
			if ( isset( $_GET['cats_new'] ) && $_GET['cats_new'] != '0' ) {
				$GLOBALS['ghostpool_cats'] = $_GET['cats_new'];
			}

			// Use filtered menu category is selected
			if ( isset( $_GET['menu_cats_new'] ) && $_GET['menu_cats_new'] != '0' ) {
				$GLOBALS['ghostpool_cats'] = $_GET['menu_cats_new'];
			}
		
			// Use filtered orderby if selected
			if ( isset( $_GET['orderby_new'] ) && $_GET['orderby_new'] != '0' ) {
				$GLOBALS['ghostpool_orderby'] = $_GET['orderby_new'];
			}		

			// Use filtered date posted if selected
			if ( isset( $_GET['date_posted_new'] ) && $_GET['date_posted_new'] != '0' ) {
				$GLOBALS['ghostpool_date_posted'] = $_GET['date_posted_new'];
			}	
			
			// Use filtered date modified if selected
			if ( isset( $_GET['date_modified_new'] ) && $_GET['date_modified_new'] != '0' ) {
				$GLOBALS['ghostpool_date_modified'] = $_GET['date_modified_new'];
			}	
							
			// Load page variables
			ghostpool_loop_variables();
			ghostpool_category_variables();
		
			$GLOBALS['ghostpool_meta_query'] = '';
			
			// Tax query
			if ( $_GET['type'] == 'blog' OR $_GET['type'] == 'showcase' OR $_GET['type'] == 'blog-template' OR $_GET['type'] == 'menu' ) {
				$tax_query = $GLOBALS['ghostpool_tax'];
			} else {
				$tax_query = '';
			}

			// Page IDs
			if ( $GLOBALS['ghostpool_page_ids'] ) {
				$GLOBALS['ghostpool_page_ids'] = explode( ',', $GLOBALS['ghostpool_page_ids'] );
			} else {
				$GLOBALS['ghostpool_page_ids'] = '';
			}

			// Query														
			if ( $_GET['type'] == 'taxonomy' ) {
				$defaults = array(
					'date_query' => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),
				);
				$args = $_GET['querystring'] . "&post_status=publish&orderby=" . $GLOBALS['ghostpool_orderby_value'] . "&order=" . $GLOBALS['ghostpool_order'] . "&meta_key=" . $GLOBALS['ghostpool_meta_key'] . "&posts_per_page=" . $GLOBALS['ghostpool_per_page'] . "&paged=$ghostpool_pagination";		
				$args = wp_parse_args( $args, $defaults );
			} elseif ( $_GET['type'] == 'menu' ) {	
				$args = array(
					'post_status' 	  => 'publish',
					'post_type'       => array( 'post', 'page' ),
					'tax_query'       => $tax_query,
					'orderby'         => 'date',
					'order'           => 'desc',
					'posts_per_page'  => $GLOBALS['ghostpool_menu_per_page'],
					'paged'           => $ghostpool_pagination,		
				);				
			} else {
				$args = array(
					'post_status' 	 => 'publish',
					'post_type' 	 => $GLOBALS['ghostpool_post_types'],
					'post__in'       => $GLOBALS['ghostpool_page_ids'],
					'tax_query' 	 => $tax_query,
					'orderby' 		 => $GLOBALS['ghostpool_orderby_value'],
					'order' 		 => $GLOBALS['ghostpool_order'],
					'meta_query' 	 => $GLOBALS['ghostpool_meta_query'],
					'meta_key' 		 => $GLOBALS['ghostpool_meta_key'],
					'posts_per_page' => $GLOBALS['ghostpool_per_page'],
					'offset' 		 => $GLOBALS['ghostpool_offset'],
					'paged'          => $ghostpool_pagination,
					'date_query' => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),
				);
			}
		
			//print_r($args);
		
			$query = new wp_query( $args );
		
			$GLOBALS['ghostpool_counter'] = 1;
						
			if ( $query->have_posts() ) :
		
				$total_pages = $query->max_num_pages;

				// Pagination (Arrows)
				if ( $GLOBALS['ghostpool_page_arrows'] == 'enabled' OR $_GET['type'] == 'menu' ) { 
					echo '<div class="gp-pagination-arrows gp-ajax-pagination">';
						if ( $ghostpool_pagination > 1 ) {
							echo '<a href="#" data-pagelink="' . ( $ghostpool_pagination - 1 ) . '" class="prev"></a>';
						} else {
							echo '<span class="prev gp-disabled"></span>';
						}
						if ( $ghostpool_pagination < $total_pages ) {
							echo '<a href="#" data-pagelink="' . ( $ghostpool_pagination + 1 ) . '" class="next"></a>';
						} else {
							echo '<span class="next gp-disabled"></span>';
						}
					echo '</div>'; 
				}
								
				if ( $GLOBALS['ghostpool_format'] == 'gp-blog-masonry' ) { echo '<div class="gp-gutter-size"></div>'; }
			
				while ( $query->have_posts() ) : $query->the_post(); 	
			
					// Load Visual Composer shortcodes
					if ( function_exists( 'vc_set_as_theme' ) ) {
						WPBMap::addAllMappedShortcodes();
					}
						
					// Large and small options for showcase element
					if ( $_GET['type'] == 'showcase' ) {
						if ( $GLOBALS['ghostpool_counter'] % $GLOBALS['ghostpool_per_page'] == 1 ) {
							$GLOBALS['ghostpool_featured_image'] = isset( $_GET['largefeaturedimage'] ) ? $_GET['largefeaturedimage'] : '';
							$GLOBALS['ghostpool_image_width'] = isset( $_GET['largeimagewidth'] ) ? $_GET['largeimagewidth'] : '';
							$GLOBALS['ghostpool_image_height'] = isset( $_GET['largeimageheight'] ) ? $_GET['largeimageheight'] : '';
							$GLOBALS['ghostpool_image_alignment'] = isset( $_GET['largeimagealignment'] ) ? $_GET['largeimagealignment'] : '';
							$GLOBALS['ghostpool_excerpt_length'] = isset( $_GET['largeexcerptlength'] ) ? $_GET['largeexcerptlength'] : '0';
							$GLOBALS['ghostpool_meta_author'] = isset( $_GET['largemetaauthor'] ) ? $_GET['largemetaauthor'] : '';
							$GLOBALS['ghostpool_meta_date'] = isset( $_GET['largemetadate'] ) ? $_GET['largemetadate'] : '';
							$GLOBALS['ghostpool_meta_comment_count'] = isset( $_GET['largemetacommentcount'] ) ? $_GET['largemetacommentcount'] : '';
							$GLOBALS['ghostpool_meta_views'] = isset( $_GET['largemetaviews'] ) ? $_GET['largemetaviews'] : '';
							$GLOBALS['ghostpool_meta_cats'] = isset( $_GET['largemetacats'] ) ? $_GET['largemetacats'] : '';
							$GLOBALS['ghostpool_meta_tags'] = isset( $_GET['largemetatags'] ) ? $_GET['largemetatags'] : '';
							$GLOBALS['ghostpool_read_more_link'] = isset( $_GET['largereadmorelink'] ) ? $_GET['largereadmorelink'] : '';
						} else {
							$GLOBALS['ghostpool_featured_image'] = isset( $_GET['smallfeaturedimage'] ) ? $_GET['smallfeaturedimage'] : '';
							$GLOBALS['ghostpool_image_width'] = isset( $_GET['smallimagewidth'] ) ? $_GET['smallimagewidth'] : '';
							$GLOBALS['ghostpool_image_height'] = isset( $_GET['smallimageheight'] ) ? $_GET['smallimageheight'] : '';
							$GLOBALS['ghostpool_image_alignment'] = isset( $_GET['smallimagealignment'] ) ? $_GET['smallimagealignment'] : '';
							$GLOBALS['ghostpool_excerpt_length'] = isset( $_GET['smallexcerptlength'] ) ? $_GET['smallexcerptlength'] : '0';
							$GLOBALS['ghostpool_meta_author'] = isset( $_GET['smallmetaauthor'] ) ? $_GET['smallmetaauthor'] : '';
							$GLOBALS['ghostpool_meta_date'] = isset( $_GET['smallmetadate'] ) ? $_GET['smallmetadate'] : '';
							$GLOBALS['ghostpool_meta_comment_count'] = isset( $_GET['smallmetacommentcount'] ) ? $_GET['smallmetacommentcount'] : '';
							$GLOBALS['ghostpool_meta_views'] = isset( $_GET['smallmetaviews'] ) ? $_GET['smallmetaviews'] : '';
							$GLOBALS['ghostpool_meta_cats'] = isset( $_GET['smallmetacats'] ) ? $_GET['smallmetacats'] : '';
							$GLOBALS['ghostpool_meta_tags'] = isset( $_GET['smallmetatags'] ) ? $_GET['smallmetatags'] : '';
							$GLOBALS['ghostpool_read_more_link'] = isset( $_GET['smallreadmorelink'] ) ? $_GET['smallreadmorelink'] : '';
						}
					}
											
				?>
	
					<?php if ( $_GET['type'] == 'showcase' && ( ( isset( $GLOBALS['ghostpool_counter'] ) && $GLOBALS['ghostpool_counter'] % $GLOBALS['ghostpool_per_page'] == 2 OR $GLOBALS['ghostpool_counter'] == 2 ) && $query->current_post != 0 ) ) { ?>
						<div class="gp-small-posts">
					<?php } ?>

						<?php if ( $_GET['type'] == 'menu' ) {
															
							// Post link
							if ( get_post_format() == 'link' ) { 
								$link = esc_url( get_post_meta( get_the_ID(), 'link', true ) );
							} else {
								$link = get_permalink();
							}
						
							echo '<section class="' . implode( ' ' , get_post_class( 'gp-post-item' ) ) . '">';
						
								if ( has_post_thumbnail() ) {
						
									$image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), 270, 140, true, false, true );
									if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
										$retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), 270 * 2, 140 * 2, true, true, true );
									} else {
										$retina = '';
									}
									
									echo '<div class="gp-post-thumbnail"><div class="gp-image-above">
										<a href="' . $link . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '" target="' . get_post_meta( get_the_ID(), 'link_target', true ) . '">
											<img src="' . $image[0] . '" data-rel="' . $retina . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . the_title_attribute( array( 'echo' => false ) ) . '" class="gp-post-image" />
										</a>
									</div></div>';
				
								}
									
								echo '<h2 class="gp-loop-title"><a href="' . $link . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '" target="' . get_post_meta( get_the_ID(), 'link_target', true ) . '">' . get_the_title() . '</a></h2>		
							
								<div class="gp-loop-meta"><time class="gp-post-meta gp-meta-date" itemprop="datePublished" datetime="' . get_the_date( 'c' ) . '">' . get_the_time( get_option( 'date_format' ) ) . '</time></div>
										
							</section>';						
						
						} else {
				
							get_template_part( 'post', 'loop' );
					
						} ?>

					<?php if ( $_GET['type'] == 'showcase' && ( isset( $GLOBALS['ghostpool_counter'] ) && $GLOBALS['ghostpool_counter'] % $GLOBALS['ghostpool_per_page'] == 0 ) OR ( ( $query->current_post + 1 ) == $query->post_count && $query->current_post != 0 ) ) { ?>
						</div>
					<?php } ?>	

				<?php $GLOBALS['ghostpool_counter']++; endwhile; ?>

				<?php 
			
				// Pagination (Numbers)		
				if ( $total_pages > 1 && $_GET['type'] != 'menu' && $GLOBALS['ghostpool_page_numbers'] == 'enabled' ) { 
					  echo '<div class="gp-pagination gp-pagination-numbers gp-ajax-pagination">';
					  echo paginate_links( array(  
						'base'     => '%_%',  
						'format'   => '/page/%#%',
						'current'  => $ghostpool_pagination,  
						'total'    => $total_pages,  
						'type'      => 'list',
						'prev_text' => '',
						'next_text' => '',  
						'end_size'  => 1,
						'mid_size'  => 1,      
					  ));
					  echo '</div>'; 
				}
				?>
		
			<?php else : ?>

				<strong class="gp-no-items-found"><?php esc_html_e( 'No items found.', 'socialize' ); ?></strong>
	
			<?php endif; wp_reset_postdata();

			die();
			
		}	
	}	
}
add_action( 'wp_ajax_ghostpool_ajax', 'ghostpool_ajax' );
add_action( 'wp_ajax_nopriv_ghostpool_ajax', 'ghostpool_ajax' );

?>