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
					
						<header class="gp-entry-header gp-bbp-entry-header">
							<h1 class="gp-entry-title" itemprop="headline"><?php the_title(); ?></h1>
						</header>
					
						<div class="gp-entry-content">
							<?php the_content(); ?>
						</div>

					</div>

				</div>
			
				<?php bbp_restore_all_filters( 'the_content', 0 ); ?>

				<?php get_sidebar( 'left' ); ?>
			
			</div>

			<?php get_sidebar( 'right' ); ?>
		
		</div>
				
		<div class="gp-clear"></div>
	
	</div>
	
<?php endwhile; endif; ?>
	
<?php get_footer(); ?>