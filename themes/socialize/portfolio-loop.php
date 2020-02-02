<?php

// Portfolio Categories
$terms = get_the_terms( get_the_ID(), 'gp_portfolios' );
if ( isset( $GLOBALS['ghostpool_cats'] ) ) {
	$cat_array = explode( ',', $GLOBALS['ghostpool_cats'] );
}
$portfolio_cats = null;
if ( ! empty( $terms ) ) {
	foreach ( $terms as $term ) {
		if ( ! empty( $cat_array[0] ) ) {
			foreach( $cat_array as $cat ) {
				if ( $term->term_id == $cat ) {		
					$portfolio_cats .= sanitize_title( $term->slug ) . ' ';
				}
			}
		} else {
			$portfolio_cats .= sanitize_title( $term->slug ) . ' ';
		}	
	}
} ?>

<section <?php post_class( 'gp-portfolio-item ' . $portfolio_cats . ghostpool_option( 'portfolio_item_image_size' ) ); ?> data-portfolio-cat="<?php echo esc_attr( $portfolio_cats ); ?>" itemscope itemtype="http://schema.org/Blog">

	<?php if ( is_main_query() && in_the_loop() && is_archive() ) { echo ghostpool_meta_data( get_the_ID() ); } ?>

	<?php if ( has_post_thumbnail() ) { ?>

		<div class="gp-post-thumbnail gp-loop-featured">
			
			<?php if ( $GLOBALS['ghostpool_format'] != 'gp-portfolio-masonry' ) {
			
				$image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), apply_filters( 'gp_portfolio_standard_image_width', '700' ), apply_filters( 'gp_portfolio_standard_image_height', '500' ), true, false, true );
			
			} elseif ( ghostpool_option( 'portfolio_item_image_size' ) == 'gp-narrow' ) {
			
				$image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), apply_filters( 'gp_portfolio_narrow_image_width', '1000' ), apply_filters( 'gp_portfolio_narrow_image_height', '500' ), true, false, true );
				
			} elseif ( ghostpool_option( 'portfolio_item_image_size' ) == 'gp-tall' ) {
			
				$image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), apply_filters( 'gp_portfolio_tall_image_width', '500' ), apply_filters( 'gp_portfolio_tall_image_height', '1000' ), true, false, true );
				
			} else {
			
				$image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), apply_filters( 'gp_portfolio_square_image_width', '500' ), apply_filters( 'gp_portfolio_square_image_height', '500' ), true, false, true );						
			
			} ?>
			
			<?php if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
				$retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), ( $image[1] * 2 ), ( $image[2] * 2 ), true, true, true );
			} else {
				$retina = '';
			} ?>
				
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<img src="<?php echo esc_url( $image[0] ); ?>" data-rel="<?php echo esc_url( $retina ); ?>" width="<?php echo absint( $image[1] ); ?>" height="<?php echo absint( $image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image" />			
			</a>
			
		</div>

	<?php } ?>

	<?php if ( $GLOBALS['ghostpool_format'] != 'gp-portfolio-masonry' ) { ?>

		<h2 class="gp-loop-title" itemprop="headline"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	
	<?php } ?>
			
</section>