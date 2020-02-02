<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>		

	<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-fullwidth-page-header' OR $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

	<div id="gp-content-wrapper" class="gp-container">

		<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-large-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

		<div id="gp-inner-container">

			<div id="gp-left-column">
		
				<div id="gp-content">

					<div <?php post_class(); ?>>
							
						<?php ghostpool_breadcrumbs(); ?>
				
						<header class="gp-entry-header">
						
							<h1 class="gp-entry-title" itemprop="headline"><?php if ( ! empty( $GLOBALS['ghostpool_custom_title'] ) ) { echo esc_attr( $GLOBALS['ghostpool_custom_title'] ); } else { the_title(); } ?></h1>	
															
							<?php if ( ! empty( $GLOBALS['ghostpool_subtitle'] ) ) { ?>
								<h3 class="gp-subtitle"><?php echo esc_attr( $GLOBALS['ghostpool_subtitle'] ); ?></h3>
							<?php } ?>
							
						</header>
									
						<div class="gp-entry-content">
							<?php the_content(); ?>
						</div>

					</div>

				</div>

				<?php get_sidebar( 'left' ); ?>

			</div>
		
			<?php get_sidebar( 'right' ); ?>
		
		</div>
		
		<div class="gp-clear"></div>
	
	</div>
	
<?php endwhile; endif; ?>
	
<?php get_footer(); ?>