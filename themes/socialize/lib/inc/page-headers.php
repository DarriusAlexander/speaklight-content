<?php

/**
* Page title
*
*/
if ( ! function_exists( 'ghostpool_page_title' ) ) {

	function ghostpool_page_title( $post_id = '' ) { ?>

		<header class="gp-entry-header">
			<h1 class="gp-entry-title" itemprop="headline">
				<?php if ( is_category() OR is_tag() OR is_tax() ) {
					single_cat_title();
				} elseif ( is_archive() ) {
					if ( ! function_exists( '_wp_render_title_tag' ) && ! function_exists( 'ghostpool_render_title' ) ) { 
						echo apply_filters( 'ghostpool_archives_title', esc_html__( 'Archives', 'socialize' ) ); 
					} else { 
						echo apply_filters( 'ghostpool_archives_title', get_the_archive_title() );
					}
				} elseif ( is_front_page() ) {
					echo apply_filters( 'ghostpool_blog_title', esc_html__( 'Blog', 'socialize' ) ); 
				} else {
					wp_title( '' ); 
				} ?>
			</h1>
			<?php if ( category_description() != '' ) { ?>
				<h2 class="gp-subtitle"><?php echo str_replace( array( '<p>', '</p>' ), '', category_description() ); ?></h2>
			<?php } ?>	
		</header>

	<?php }
}

/**
* Page header
*
*/						
if ( ! function_exists( 'ghostpool_page_header' ) ) {

	function ghostpool_page_header( $post_id = '' ) {
		
		global $post;
		
		$GLOBALS['ghostpool_page_header_bg_css'] = '';

		// Detect WooCommerce
		if ( function_exists( 'is_woocommerce' ) && ( is_shop() OR is_product_category() OR is_product_tag() ) ) {
			$woocommerce = true;
			$post_id = get_option( 'woocommerce_shop_page_id' ); // Get WooCommerce shop page ID	
		} else {
			$woocommerce = '';
		}
	
		// Detect bbPress
		if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			$bbpress = true;
		} else {
			$bbpress = '';
		}
	
		// Detect Events Calendar
		if ( class_exists( 'Tribe__Events__Main' ) && ( is_post_type_archive( 'tribe_events' ) OR is_singular( 'tribe_events' ) ) ) {
			$events = true;
		} else {
			$events = '';
		}
		
		// Background image
		if ( function_exists( 'bp_is_active' ) && bp_is_user() ) {
			$cover_image_url = bp_attachments_get_attachment( 'url', array( 'members' => 'groups', 'item_id' => bp_displayed_user_id() ) );
			$GLOBALS['ghostpool_page_header_bg_css'] = ' style="background-image: url(' . $cover_image_url . ');"';
	
		} elseif ( function_exists( 'bp_is_active' ) && bp_is_group_single() ) { 
			global $bp;
			$cover_image_url = bp_attachments_get_attachment( 'url', array( 'object_dir' => 'groups', 'item_id' => $bp->groups->current_group->id ) );
			$GLOBALS['ghostpool_page_header_bg_css'] = ' style="background-image: url(' . $cover_image_url . ');"';			
						
		} elseif ( ( is_singular() OR $woocommerce == true OR $bbpress == true OR $events == true OR is_search() OR is_author() ) && ! empty( $GLOBALS['ghostpool_page_header_bg']['url'] ) ) {
			//echo 'Ind: Post, page, search, author and shop background from title header option';
			//echo ' Global: BuddyPress background from title header option';
			$GLOBALS['ghostpool_page_header_bg_css'] = ' style="background-image: url(' . $GLOBALS['ghostpool_page_header_bg']['url'] . ');"';
		} elseif ( ( is_singular() OR $woocommerce == true OR $bbpress == true OR $events == true ) && has_post_thumbnail( $post_id ) ) {
			//echo 'Ind: Post, page, search, author and shop background from featured image';
			//echo ' Global: BuddyPress background from featured image';
			$GLOBALS['ghostpool_page_header_bg_css'] = ' style="background-image: url(' . wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ) . ');"';
		} elseif ( is_archive() && ! is_search() && ! is_author() && ! empty( $GLOBALS['ghostpool_page_header_bg'][0] ) ) {	
			//echo 'bbPress archive';
			//echo 'Ind: Category background image';
			$GLOBALS['ghostpool_page_header_bg_css'] = ' style="background-image: url(' . $GLOBALS['ghostpool_page_header_bg'] . ');"';
		} elseif ( ( ( $woocommerce == true OR $bbpress == true OR $events == true ) && is_archive() ) && ! empty( $GLOBALS['ghostpool_page_header_bg']['url'] ) ) {
			//echo 'Global: WooCommerce product categories or bbPress forums/topics global image';
			$GLOBALS['ghostpool_page_header_bg_css'] = ' style="background-image: url(' . ghostpool_option( 'title_bg', 'background-image' ) . ');"';
		} elseif ( ghostpool_option( 'title_bg', 'background-image' ) ) {
			//echo 'Global background image';
			$GLOBALS['ghostpool_page_header_bg_css'] = ' style="background-image: url(' . ghostpool_option( 'title_bg', 'background-image' ) . ');"';
		} else {
			//echo 'Empty';		
			$GLOBALS['ghostpool_page_header_bg_css'] = '';		
		}
						
		// Parallax effect
		if ( ghostpool_option( 'page_header_parallax' ) == 'enabled' ) {
			$GLOBALS['ghostpool_parse_parallax_scrolling'] = ' data-stellar-background-ratio="0.6"';
			$GLOBALS['ghostpool_parallax_class'] = 'gp-parallax';
		} else {
			$GLOBALS['ghostpool_parse_parallax_scrolling'] = '';
			$GLOBALS['ghostpool_parallax_class'] = '';
		}
	
		// Video header classes
		if ( ! empty( $GLOBALS['ghostpool_teaser_video_bg'] ) OR ! empty( $GLOBALS['ghostpool_full_video_bg'] ) ) { 		
			$GLOBALS['ghostpool_video_header_class'] = 'gp-has-video';
		} else {
			$GLOBALS['ghostpool_video_header_class'] = '';
		}
		if ( ! empty( $GLOBALS['ghostpool_teaser_video_bg'] ) ) {
			$GLOBALS['ghostpool_teaser_video_header_class'] = 'gp-has-teaser-video';
		} else {
			$GLOBALS['ghostpool_teaser_video_header_class'] = '';
		}
	
		?>
		
		<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ?>
			<div id="gp-full-page-bg"<?php echo wp_kses_post( $GLOBALS['ghostpool_page_header_bg_css'] ); ?>></div>
		<?php } ?>

		<header class="gp-page-header <?php echo sanitize_html_class( $GLOBALS['ghostpool_parallax_class'] ); ?> <?php echo sanitize_html_class( $GLOBALS['ghostpool_video_header_class'] ); ?> <?php echo sanitize_html_class( $GLOBALS['ghostpool_teaser_video_header_class'] ); ?><?php if ( isset( $GLOBALS['ghostpool_page_header_text'] ) ) { ?> gp-has-text<?php } ?>"<?php echo wp_kses_post( $GLOBALS['ghostpool_parse_parallax_scrolling'] ); ?><?php if ( $GLOBALS['ghostpool_page_header'] != 'gp-full-page-page-header' ) { echo wp_kses_post( $GLOBALS['ghostpool_page_header_bg_css'] ); } ?>>										

			<?php if ( ! empty( $GLOBALS['ghostpool_teaser_video_bg'] ) OR ! empty( $GLOBALS['ghostpool_full_video_bg'] ) ) {

				// YouTube or Vimeo ID
				$GLOBALS['ghostpool_full_video_bg'] = str_replace( 'www.', '', $GLOBALS['ghostpool_full_video_bg'] );
				if ( preg_match( '/http:\/\/vimeo/', $GLOBALS['ghostpool_full_video_bg'] ) ) {
					$video_id = str_replace('http://vimeo.com/', '', $GLOBALS['ghostpool_full_video_bg'] );
					$video_provider = 'vimeo';
				} elseif ( preg_match( '/https:\/\/vimeo/', $GLOBALS['ghostpool_full_video_bg'] ) ) {
					$video_id = str_replace('https://vimeo.com/', '', $GLOBALS['ghostpool_full_video_bg'] );
					$video_provider = 'vimeo';
				} elseif ( preg_match( '/http:\/\/youtube.com/', $GLOBALS['ghostpool_full_video_bg'] ) ) {
					$video_id = str_replace('http://youtube.com/watch?v=', '', $GLOBALS['ghostpool_full_video_bg'] );
					$video_provider = 'youtube';
				} elseif ( preg_match( '/https:\/\/youtube.com/', $GLOBALS['ghostpool_full_video_bg'] ) ) {
					$video_id = str_replace('https://youtube.com/watch?v=', '', $GLOBALS['ghostpool_full_video_bg'] );
					$video_provider = 'youtube';
				} elseif ( preg_match( '/http:\/\/youtu.be/', $GLOBALS['ghostpool_full_video_bg'] ) ) {
					$video_id = str_replace( 'http://youtu.be/', '', $GLOBALS['ghostpool_full_video_bg'] );	
					$video_provider = 'youtube';		
				} elseif ( preg_match( '/https:\/\/youtu.be/', $GLOBALS['ghostpool_full_video_bg'] ) ) {
					$video_id = str_replace( 'https://youtu.be/', '', $GLOBALS['ghostpool_full_video_bg'] );	
					$video_provider = 'youtube';													
				} else {
					$video_id = $GLOBALS['ghostpool_full_video_bg'];
					$video_provider = 'html5';
				}
						
				?>

				<div class="gp-video-header">
					<span class="gp-video-media" data-video-src="<?php echo esc_attr( $video_id ); ?>" data-teaser-source="<?php echo esc_url( $GLOBALS['ghostpool_teaser_video_bg'] ); ?>" data-provider="<?php echo esc_attr( $video_provider ); ?>"></span>
					<div class="gp-close-video-button"></div>
				</div>

			<?php } ?>	
			
			<div class="gp-container">
			
				<?php if ( isset( $GLOBALS['ghostpool_page_header_text'] ) && ! empty( $GLOBALS['ghostpool_page_header_text'] ) ) { ?>
					<h2><?php echo esc_attr( $GLOBALS['ghostpool_page_header_text'] ); ?></h2>
				<?php } else { ?>
					<h1 itemprop="headline">
						<?php if ( is_category() OR is_tag() OR is_tax() ) {
							single_cat_title();
						} elseif ( is_archive() ) {
							if ( ! function_exists( '_wp_render_title_tag' ) && ! function_exists( 'ghostpool_render_title' ) ) { 
								echo apply_filters( 'ghostpool_archives_title', esc_html__( 'Archives', 'socialize' ) );
							} else { 
								echo apply_filters( 'ghostpool_archives_title', get_the_archive_title() );
							}
						} elseif ( is_front_page() ) {
							echo apply_filters( 'ghostpool_blog_title', esc_html__( 'Blog', 'socialize' ) ); 
						} else {
							wp_title( '' ); 
						} ?>
					</h1>
				<?php } ?>
				
				<?php if ( ! empty( $GLOBALS['ghostpool_full_video_bg'] ) ) { ?>	
					<div class="gp-play-video-button-wrapper">
						<a href="<?php echo esc_url( $GLOBALS['ghostpool_full_video_bg'] ); ?>" class="gp-play-video-button"></a>
					</div>
				<?php } ?>	
		
			</div>			

		</header>

	<?php
							
	}

}

?>