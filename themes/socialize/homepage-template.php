<?php
/*
Template Name: Homepage
*/
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>		

	<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-fullwidth-page-header' OR $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

	<?php if ( redux_post_meta( 'socialize', get_the_ID(), 'homepage_content_header' ) ) { ?>
		<div id="gp-content-header"<?php if ( redux_post_meta( 'socialize', get_the_ID(), 'homepage_content_header_format' ) == 'fixed' ) { ?>  class="gp-container"<?php } ?>>
			<?php echo do_shortcode( redux_post_meta( 'socialize', get_the_ID(), 'homepage_content_header' ) ); ?>
		</div>
	<?php } ?>
		
	<div id="gp-content-wrapper" class="gp-container">

		<div id="gp-inner-container">

			<div id="gp-left-column">
		
				<div id="gp-content">
			
					<?php the_content(); ?>	
				
				</div>
			
				<?php get_sidebar( 'left' ); ?>
		
			</div>
				
			<?php get_sidebar( 'right' ); ?>
			
		</div>	
		
		<div class="gp-clear"></div>
			
	</div>
	
<?php endwhile; endif; ?>
	
<?php get_footer(); ?>