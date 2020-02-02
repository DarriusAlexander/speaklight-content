<?php get_header(); ?>

<div id="gp-content-wrapper" class="gp-container">
		
	<div id="gp-inner-container">
	
		<div id="gp-left-column">
		
			<div id="gp-content">
							
				<?php ghostpool_breadcrumbs(); ?>

				<header class="gp-entry-header">
					<h1 class="gp-entry-title"><?php esc_html_e( 'Page Not Found', 'socialize' ); ?></h1>
				</header>

				<div class="gp-entry-content">
					<h2><?php esc_html_e( 'Oops, it looks like this page does not exist.', 'socialize' ); ?></h2>
				</div>
		
				<div class="gp-search">
		
					<p><?php esc_html_e( 'If you are lost use the search form below or visit our', 'socialize' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'homepage.', 'socialize' ); ?></a></p>
	
					<?php get_search_form(); ?>
	
				</div>

			</div>
				
			<?php get_sidebar( 'left' ); ?>

		</div>

		<?php get_sidebar( 'right' ); ?>

	</div>
		
	<div class="gp-clear"></div>
	
</div>
		
<?php get_footer(); ?>