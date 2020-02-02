<?php
/*
Template Name: Blog
*/
get_header(); ?>

<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-fullwidth-page-header' OR $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

<div id="gp-content-wrapper" class="gp-container">

	<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-large-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

	<div id="gp-inner-container">

		<div id="gp-left-column">
	
			<div id="gp-content">
							
				<?php ghostpool_breadcrumbs(); ?>
		
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>	

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
					
					<?php the_content(); ?>
		
				<?php endwhile; endif; rewind_posts(); ?>	

				<?php
				
				// Load page variables
				ghostpool_loop_variables();
				ghostpool_category_variables();
				
				$args = array(
					'post_status' 	      => 'publish',
					'post_type'           => explode( ',', $GLOBALS['ghostpool_post_types'] ),
					'tax_query'           => $GLOBALS['ghostpool_tax'],
					'orderby'             => $GLOBALS['ghostpool_orderby_value'],
					'order'               => $GLOBALS['ghostpool_order'],
					'meta_key'            => $GLOBALS['ghostpool_meta_key'],
					'posts_per_page'      => $GLOBALS['ghostpool_per_page'],
					'paged'               => $GLOBALS['ghostpool_paged'],
					'date_query'          => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),	
				);

				$query = new wp_query( $args ); ?>
			
				<div class="gp-blog-wrapper <?php echo sanitize_html_class( $GLOBALS['ghostpool_format'] ); ?>"<?php if ( function_exists( 'ghostpool_data_properties' ) ) { echo ghostpool_data_properties( 'blog-template' ); } ?>>
	
					<?php if ( $query->have_posts() ) : ?>
			
						<?php get_template_part( 'lib/sections/filter' ); ?>
														
						<div class="gp-inner-loop <?php echo sanitize_html_class( ghostpool_option( 'ajax' ) ); ?>">
		
							<?php if ( $GLOBALS['ghostpool_format'] == 'gp-blog-masonry' ) { ?><div class="gp-gutter-size"></div><?php } ?>
								
							<?php while ( $query->have_posts() ) : $query->the_post(); ?>

								<?php get_template_part( 'post', 'loop' ); ?>
			
							<?php endwhile; ?>
		
						</div>

						<?php echo ghostpool_pagination( $query->max_num_pages ); ?>

					<?php else : ?>

						<strong class="gp-no-items-found"><?php esc_html_e( 'No items found.', 'socialize' ); ?></strong>

					<?php endif; wp_reset_postdata(); ?>
			
				</div>

			</div>

			<?php get_sidebar( 'left' ); ?>

		</div>
	
		<?php get_sidebar( 'right' ); ?>

	</div>
	
	<div class="gp-clear"></div>

</div>

<?php get_footer(); ?>