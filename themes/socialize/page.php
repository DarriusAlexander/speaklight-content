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
			
					<?php ghostpool_breadcrumbs(); ?>
				
					<?php if ( $GLOBALS['ghostpool_title'] == 'enabled' ) { ?>	
						<header class="gp-entry-header">	
						
							<h1 class="gp-entry-title" itemprop="headline">
								<?php if ( ! empty( $GLOBALS['ghostpool_custom_title'] ) ) { echo esc_attr( $GLOBALS['ghostpool_custom_title'] ); } else { the_title(); } ?>
							</h1>
									
							<?php if ( ! empty( $GLOBALS['ghostpool_subtitle'] ) ) { ?>
								<h3 class="gp-subtitle"><?php echo esc_attr( $GLOBALS['ghostpool_subtitle'] ); ?></h3>
							<?php } ?>
			
						</header>
					<?php } ?>

					<?php if ( has_post_thumbnail() && $GLOBALS['ghostpool_featured_image'] == 'enabled' ) { ?>

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

					<?php } ?>
				
					<div class="gp-entry-content <?php if ( isset( $GLOBALS['ghostpool_image_alignment'] ) ) { echo sanitize_html_class( $GLOBALS['ghostpool_image_alignment'] ); } ?>">

						<div class="gp-entry-text" itemprop="text"><?php the_content(); ?></div>

						<?php wp_link_pages( 'before=<div class="gp-pagination gp-pagination-numbers gp-standard-pagination gp-entry-pagination"><ul class="page-numbers">&pagelink=<span class="page-numbers">%</span>&after=</ul></div>' ); ?>	
	
					</div>
	
					<?php if ( ghostpool_option( 'page_author_info' ) == 'enabled' ) { ?>
						<?php get_template_part( 'lib/sections/author', 'info' ); ?>
					<?php } ?>

					<?php comments_template(); ?>

				</div>
				
				<?php get_sidebar( 'left' ); ?>

			</div>

			<?php get_sidebar( 'right' ); ?>

		</div>
		
		<div class="gp-clear"></div>
	
	</div>
	
<?php endwhile; endif; ?>
	
<?php get_footer(); ?>