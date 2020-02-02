<?php
/*
Template Name: Portfolio
*/
get_header();

// Load page variables		
ghostpool_loop_variables();
ghostpool_category_variables();

?>

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
					
				$args = array(
					'post_status'         => 'publish',
					'post_type'           => 'gp_portfolio_item',
					'tax_query'           => $GLOBALS['ghostpool_tax'],
					'posts_per_page'      => $GLOBALS['ghostpool_per_page'],
					'orderby'             => $GLOBALS['ghostpool_orderby_value'],
					'order'               => $GLOBALS['ghostpool_order'],
					'paged'               => $GLOBALS['ghostpool_paged'],
					'date_query'          => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),	
				);

				$query = new wp_query( $args ); ?>

				<div id="gp-portfolio" class="gp-portfolio-wrapper <?php echo sanitize_html_class( $GLOBALS['ghostpool_format'] ); ?>">		

					<?php if ( $query->have_posts() ) : ?>

						<?php if ( $GLOBALS['ghostpool_filter'] == 'enabled' ) { ?>
							<div id="gp-portfolio-filters" class="gp-portfolio-filters">
								<ul>
								   <li><a href="#" data-filter="*" class="gp-active"><?php echo esc_html__( 'All', 'socialize' ); ?></a></li>
									<?php 
									$terms = get_terms( 'gp_portfolios' );
									$cat_array = explode( ',', $GLOBALS['ghostpool_cats'] );
									if ( !empty( $terms ) ) {
										foreach ( $terms as $term ) {
											if ( ! empty( $cat_array[0] ) ) {
												foreach( $cat_array as $cat ) {							
													if ( $term->term_id == $cat ) {
														echo '<li><a href="#" data-filter=".' . sanitize_title( $term->slug ) . '">' . esc_attr( $term->name ). '</a></li>';
													}	
												}
											} else {
												echo '<li><a href="#" data-filter=".' . sanitize_title( $term->slug ) . '">' . esc_attr( $term->name ). '</a></li>';
											}	
										}
									}
									?>
								</ul>
							</div>
						<?php } ?>
	
						<div class="gp-inner-loop">
							
							<div class="gp-gutter-size"></div>
								
							<?php while ( $query->have_posts() ) : $query->the_post(); ?>

								<?php get_template_part( 'portfolio', 'loop' ); ?>

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