<?php get_header(); ?>

<div id="gp-content-wrapper" class="gp-container">
		
	<div id="gp-inner-container">
	
		<div id="gp-left-column">
		
			<div id="gp-content">
							
				<?php ghostpool_breadcrumbs(); ?>

				<header class="gp-entry-header">
					<h1 class="gp-entry-title"><?php the_title(); ?></h1>
				</header>

				<?php the_attachment_link( get_the_ID(), true ) ?>

				<div class="gp-entry-content">
					<?php the_content(); ?>
				</div>

			</div>

			<?php get_sidebar( 'left' ); ?>

		</div>

		<?php get_sidebar( 'right' ); ?>

	</div>
	
	<div class="gp-clear"></div>
	
</div>

<?php get_footer(); ?>