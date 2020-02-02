<?php

// Options
$related_cats = '';
$related_tags = wp_get_post_tags( get_the_ID() );
if ( is_singular( 'post' ) ) {
	$post_type = 'post';
	$per_page = ghostpool_option( 'post_related_items_per_page' );
	$items_in_view = ghostpool_option( 'post_related_items_in_view' );
	$GLOBALS['ghostpool_image_width'] = ghostpool_option( 'post_related_items_image', 'width' );
	$GLOBALS['ghostpool_image_height'] = ghostpool_option( 'post_related_items_image', 'height' );
	$related_cats = wp_get_post_terms( get_the_ID(), 'category' );
} elseif ( is_singular( 'gp_portfolio_item' ) ) {
	$post_type = 'gp_portfolio_item';
	$per_page = ghostpool_option( 'portfolio_item_related_items_per_page' );
	$items_in_view = ghostpool_option( 'portfolio_item_related_items_in_view' );
	$GLOBALS['ghostpool_image_width'] = ghostpool_option( 'portfolio_item_related_items_image', 'width' );
	$GLOBALS['ghostpool_image_height'] = ghostpool_option( 'portfolio_item_related_items_image', 'height' );	
	$related_cats = wp_get_post_terms( get_the_ID(), 'gp_portfolios' );
}

if ( $related_tags ) {
	$related_items = $related_tags;
} elseif ( $related_cats ) {
	$related_items = $related_cats;
} else {
	$related_items = '';
}

$temp_query = $wp_query;

if ( $related_items ) {

	$related_ids = array();

	foreach ( $related_items as $related_item ) $related_ids[] = $related_item->term_id;

	if ( $related_tags ) {	
		$related_type = 'tag__in';
		$related_query = $related_ids;
	} elseif ( is_singular( 'gp_portfolio_item' ) && $related_cats ) {
		$related_type = 'tax_query';
		$related_query = array( 'relation' => 'OR', array( 'taxonomy' => 'gp_portfolios', 'field' => 'term_id', 'terms' => $related_ids ) );
	} elseif ( $related_cats ) {
		$related_type = 'category__in';
		$related_query = $related_ids;
	} else {
		$related_type = '';
		$related_query = '';
	}
			
	$args = array(
		'post_type'           => $post_type,
		'orderby'             => 'rand',
		'order'               => 'asc',
		'paged'               => 1,
		'posts_per_page'      => $per_page,
		'offset'              => 0,
		$related_type 	  => $related_query,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
	); 

	$query = new wp_query( $args ); if ( $query->have_posts() ) : ?>
	
		<div class="gp-related-wrapper gp-carousel-wrapper gp-slider">

			<h3><?php esc_html_e( 'Related Articles', 'socialize' ); ?></h3>
			
			<ul class="slides">
			
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				
					<li>

						<section <?php post_class( 'gp-post-item' ); ?>>
						
							<?php if ( has_post_thumbnail() ) { ?>
						
								<div class="gp-post-thumbnail gp-loop-featured">
									
									 <div class="gp-image-above">

										<?php $image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'], $GLOBALS['ghostpool_image_height'], true, false, true ); ?>
										<?php if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
											$retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'] * 2, $GLOBALS['ghostpool_image_height'] * 2, true, true, true );
										} else {
											$retina = '';
										} ?>

										<a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo get_post_meta( get_the_ID(), 'link_target', true ); ?>"<?php } ?>>
				
											<img src="<?php echo esc_url( $image[0] ); ?>" data-rel="<?php echo esc_url( $retina ); ?>" width="<?php echo absint( $image[1] ); ?>" height="<?php echo absint( $image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image" />

										</a>
										
									</div>	
					
								</div>
						
							<?php } elseif ( get_post_format() != '0' && get_post_format() != 'gallery' ) { ?>
								
								<div class="gp-loop-featured">
									<?php get_template_part( 'lib/sections/loop', get_post_format() ); ?>
								</div>
								
							<?php } ?>
							
							<?php if ( get_post_format() != 'quote' OR has_post_thumbnail() ) { ?>

								<div class="gp-loop-content">

									<div class="gp-loop-title" itemprop="headline"><a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo get_post_meta( get_the_ID(), 'link_target', true ); ?>"<?php } ?>><?php the_title(); ?></a></div>
																									
									<div class="gp-loop-meta">
										<time class="gp-post-meta gp-meta-date" itemprop="datePublished" datetime="<?php echo get_the_date( 'c' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
									</div>	

								</div>
							
							<?php } ?>
						
						</section>
					
					</li>
				
				<?php endwhile; ?>	

			</ul>
				
		</div>

	<?php endif; wp_reset_postdata(); ?>

<?php } ?>