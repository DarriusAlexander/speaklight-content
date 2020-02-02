<?php get_header(); 

// Load page variables		
ghostpool_loop_variables();

?>

<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-fullwidth-page-header' OR $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

<div id="gp-content-wrapper" class="gp-container">

	<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-large-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

	<div id="gp-inner-container">

		<div id="gp-left-column">

			<div id="gp-content">

				<?php ghostpool_breadcrumbs(); ?>
				
				<header class="gp-entry-header">		
					<h1 class="gp-entry-title" itemprop="headline">
						<?php if ( isset( $_GET['s'] ) && ( $_GET['s'] != '' ) ) { ?>
							<?php echo absint( $wp_query->found_posts ); ?> <?php esc_html_e( 'search results for', 'socialize' ); ?> "<?php echo esc_attr( $s ); ?>"
						<?php } else { ?>
							<?php esc_html_e( 'Search', 'socialize' ); ?>
						<?php } ?>
					</h1>
				</header>
		
				<div id="gp-new-search">
		
					<?php if ( isset( $_GET['s'] ) && ( $_GET['s'] != '' ) ) { ?>
			
						<p><?php esc_html_e( 'If you didn\'t find what you were looking for try searching again.', 'socialize' ); ?></p>
			
					<?php } else { ?>
			
						<p><?php esc_html_e( 'You left the search box empty, please enter a valid term.', 'socialize' ); ?></p>
		
					<?php } ?>	
		
					<?php get_search_form(); ?>
		
				</div>

				<?php if ( isset( $_GET['s'] ) && ( $_GET['s'] != '' ) ) { ?>
	
					<div class="gp-blog-wrapper <?php echo sanitize_html_class( $GLOBALS['ghostpool_format'] ); ?>"<?php if ( function_exists( 'ghostpool_data_properties' ) ) { echo ghostpool_data_properties( 'taxonomy' ); } ?>>

						<?php if ( have_posts() ) : ?>

							<?php get_template_part( 'lib/sections/filter' ); ?>
								
							<div class="gp-inner-loop <?php echo sanitize_html_class( ghostpool_option( 'ajax' ) ); ?>">
							
								<?php if ( $GLOBALS['ghostpool_format'] == 'gp-blog-masonry' ) { ?><div class="gp-gutter-size"></div><?php } ?>

								<?php while ( have_posts() ) : the_post(); ?>

									<?php get_template_part( 'post', 'loop' ); ?>

								<?php endwhile; ?>
		
							</div>

							<?php echo ghostpool_pagination( $wp_query->max_num_pages ); ?>

						<?php else : ?>

							<strong class="gp-no-items-found"><?php esc_html_e( 'No items found.', 'socialize' ); ?></strong>

						<?php endif; ?>
		
					</div>
	
				<?php } ?>			
	
			</div>

			<?php get_sidebar( 'left' ); ?>

		</div>
	
		<?php get_sidebar( 'right' ); ?>
	
	</div>
	
	<div class="gp-clear"></div>

</div>

<?php get_footer(); ?>