<section <?php post_class( 'gp-post-item' ); ?> itemscope itemtype="http://schema.org/Blog">

	<?php if ( is_main_query() && in_the_loop() && is_archive() ) { echo ghostpool_meta_data( get_the_ID() ); } ?>

	<?php if ( has_post_thumbnail() && $GLOBALS['ghostpool_featured_image'] == 'enabled' ) { ?>

		<div class="gp-post-thumbnail gp-loop-featured">
		
			 <div class="<?php echo sanitize_html_class( $GLOBALS['ghostpool_image_alignment'] ); ?>">

				<?php $image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'], $GLOBALS['ghostpool_image_height'], $GLOBALS['ghostpool_hard_crop'], false, true ); ?>
				<?php if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
					$retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'] * 2, $GLOBALS['ghostpool_image_height'] * 2, $GLOBALS['ghostpool_hard_crop'], true, true );
				} else {
					$retina = '';
				} ?>

				<?php $mobile_image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), 80, 80, $GLOBALS['ghostpool_hard_crop'], false, true ); ?>
				<?php if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
					$mobile_retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), 80 * 2, 80 * 2, $GLOBALS['ghostpool_hard_crop'], true, true );
				} else {
					$mobile_retina = '';
				} ?>
					
				<a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo redux_post_meta( 'socialize', get_the_ID(), 'link_target' ); ?>"<?php } ?>>
					
					<img src="<?php echo esc_url( $image[0] ); ?>" data-rel="<?php echo esc_url( $retina ); ?>" width="<?php echo absint( $image[1] ); ?>" height="<?php echo absint( $image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image gp-large-image" />
					
					<img src="<?php echo esc_url( $mobile_image[0] ); ?>" data-rel="<?php echo esc_url( $mobile_retina ); ?>" width="<?php echo absint( $mobile_image[1] ); ?>" height="<?php echo absint( $mobile_image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image gp-mobile-image" />
	
				</a>
			
			</div>
									
		</div>

	<?php } elseif ( get_post_format() != '0' && $GLOBALS['ghostpool_featured_image'] == 'enabled' ) { ?>

		<div class="gp-loop-featured">
			<?php get_template_part( 'lib/sections/loop', get_post_format() ); ?>
		</div>

	<?php } ?>

	<?php if ( get_post_format() != 'quote' OR has_post_thumbnail() && $GLOBALS['ghostpool_featured_image'] == 'enabled' ) { ?>
	
		<div class="gp-loop-content <?php echo sanitize_html_class( $GLOBALS['ghostpool_image_alignment'] ); ?>">
		
			<?php if ( $GLOBALS['ghostpool_meta_cats'] == '1' ) { ?>		
				<?php echo ghostpool_exclude_cats( get_the_ID(), false, true ); ?>
			<?php } ?>	
			
			<h2 class="gp-loop-title"><a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo redux_post_meta( 'socialize', get_the_ID(), 'link_target' ); ?>"<?php } ?>><?php the_title(); ?></a></h2>	

			<?php get_template_part( 'lib/sections/loop', 'meta' ); ?>

			<?php if ( $GLOBALS['ghostpool_content_display'] == 'full_content' ) { ?>

				<div class="gp-loop-text">
					<?php global $more; $more = 0; the_content( esc_html__( '[Read More]', 'socialize' ) ); ?>
				</div>

			<?php } else { ?>

				<?php if ( $GLOBALS['ghostpool_excerpt_length'] != '0' ) { ?>
					<div class="gp-loop-text">
						<p><?php echo ghostpool_excerpt( $GLOBALS['ghostpool_excerpt_length'] ); ?></p>
					</div>
				<?php } ?>
	
			<?php } ?>
		
			<?php if ( isset( $GLOBALS['ghostpool_meta_tags'] ) && $GLOBALS['ghostpool_meta_tags'] == '1' ) { the_tags( '<div class="gp-loop-tags">', ' ', '</div>' ); } ?>

		</div>
	
	<?php } ?>

	<?php if ( $GLOBALS['ghostpool_format'] == 'gp-blog-large' ) { ?>
		<div class="gp-loop-divider"></div>
	<?php } ?>	
		
</section>