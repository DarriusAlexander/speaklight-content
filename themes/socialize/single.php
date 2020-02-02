<?php get_header();

// Load page variables
ghostpool_loop_variables();

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-fullwidth-page-header' OR $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

	<div id="gp-content-wrapper" class="gp-container">

		<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-large-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>
		
		<div id="gp-inner-container">

			<div id="gp-left-column">
		
				<div id="gp-content">	

					<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
				
						<?php echo ghostpool_meta_data( get_the_ID() ); ?>
						
						<div id="gp-post-navigation">
		
							<?php ghostpool_breadcrumbs(); ?>
			
							<?php if ( ghostpool_option( 'post_meta', 'post_nav' ) == '1' OR ghostpool_option( 'post_meta', 'top_share_icons' ) == '1' ) { ?>
			
								<div id="gp-post-links">
									<?php if ( ghostpool_option( 'post_meta', 'post_nav' ) == '1' ) { ?>
										<?php previous_post_link( '%link', '', false ); ?>
										<?php next_post_link( '%link', '', false ); ?>
									<?php } ?>
									<?php if ( ghostpool_option( 'post_meta', 'top_share_icons' ) == '1' ) { ?>
									<a href="#" class="gp-share-button"></a><?php } ?>
								</div>
			
							<?php } ?>
					
							<?php if ( ghostpool_option( 'post_meta', 'top_share_icons' ) == '1' ) { ?>
								<?php get_template_part( 'lib/sections/share', 'icons' ); ?>
							<?php } ?>
			
							<div class="gp-clear"></div>
		
						</div>	
		
						<?php if ( $GLOBALS['ghostpool_title'] == 'enabled' && get_post_format() != 'video' ) { ?>	

							<header class="gp-entry-header">	
			
								<?php if ( $GLOBALS['ghostpool_meta_cats'] == '1' ) { ?>		
									<?php echo ghostpool_exclude_cats( get_the_ID(), false, false ); ?>
								<?php } ?>	

								<h1 class="gp-entry-title" itemprop="headline">
									<?php if ( ! empty( $GLOBALS['ghostpool_custom_title'] ) ) { echo esc_attr( $GLOBALS['ghostpool_custom_title'] ); } else { the_title(); } ?>
								</h1>

								<?php get_template_part( 'lib/sections/entry', 'meta' ); ?>
				
								<?php if ( ! empty( $GLOBALS['ghostpool_subtitle'] ) ) { ?>
									<h3 class="gp-subtitle"><?php echo esc_attr( $GLOBALS['ghostpool_subtitle'] ); ?></h3>
								<?php } ?>	
	
							</header>
		
						<?php } ?>

						<?php if ( has_post_thumbnail() && $GLOBALS['ghostpool_featured_image'] == 'enabled' && get_post_format() == '0' ) { ?>

							<div class="gp-post-thumbnail gp-entry-featured">
			
								<div class="<?php echo sanitize_html_class( $GLOBALS['ghostpool_image_alignment'] ); ?>">

									<?php $image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'], $GLOBALS['ghostpool_image_height'], $GLOBALS['ghostpool_hard_crop'], false, true ); ?>
									<?php if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
										$retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'] * 2, $GLOBALS['ghostpool_image_height'] * 2, $GLOBALS['ghostpool_hard_crop'], true, true );
									} else {
										$retina = '';
									} ?>

									<img src="<?php echo esc_url( $image[0] ); ?>" data-rel="<?php echo esc_url( $retina ); ?>" width="<?php echo absint( $image[1] ); ?>" height="<?php echo absint( $image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image" />
				
								</div>
				
							</div>

						<?php } elseif ( get_post_format() != '0' ) { ?>

							<div class="gp-entry-featured">
								<?php get_template_part( 'lib/sections/entry', get_post_format() ); ?>
							</div>

						<?php } ?>
																					
						<div class="gp-entry-content <?php if ( isset( $GLOBALS['ghostpool_image_alignment'] ) ) { echo sanitize_html_class( $GLOBALS['ghostpool_image_alignment'] ); } ?>">
								
							<?php if ( get_post_format() != 'quote' OR get_post_format() != 'link' ) { ?>

								<div class="gp-entry-text" itemprop="text"><?php the_content(); ?></div>

							<?php } ?>

							<?php wp_link_pages( 'before=<div class="gp-pagination gp-pagination-numbers gp-standard-pagination gp-entry-pagination"><ul class="page-numbers">&pagelink=<span class="page-numbers">%</span>&after=</ul></div>' ); ?>

						</div>

						<?php if ( isset( $GLOBALS['ghostpool_meta_tags'] ) && $GLOBALS['ghostpool_meta_tags'] == '1' ) { ?>
							<?php the_tags( '<div class="gp-entry-tags">', ' ', '</div>' ); ?>
						<?php } ?>

						<?php if ( ghostpool_option( 'post_meta', 'bottom_share_icons' ) == '1' ) { ?>
							<?php get_template_part( 'lib/sections/share', 'icons' ); ?>
						<?php } ?>
						
						<?php if ( ghostpool_option( 'post_author_info' ) == 'enabled' ) { ?>
							<?php get_template_part( 'lib/sections/author', 'info' ); ?>
						<?php } ?>

						<?php if ( ghostpool_option( 'post_related_items' ) == 'enabled' ) { ?>
							<?php get_template_part( 'lib/sections/related', 'items' ); ?>
						<?php } ?>

						<?php comments_template(); ?>

					</article>

				</div>
		
				<?php get_sidebar( 'left' ); ?>

			</div>

			<?php get_sidebar( 'right' ); ?>

		</div>

		<div class="gp-clear"></div>
	
	</div>

<?php endwhile; endif; ?>
	
<?php get_footer(); ?>