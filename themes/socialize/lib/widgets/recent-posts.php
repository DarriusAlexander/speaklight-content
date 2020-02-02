<?php

if ( ! function_exists( 'ghostpool_recent_posts' ) ) {
	function ghostpool_recent_posts() {
		register_widget( 'GhostPool_Recent_Posts' );
	}
}
add_action( 'widgets_init', 'ghostpool_recent_posts' );

if ( ! class_exists( 'GhostPool_Recent_Posts' ) ) {
	class GhostPool_Recent_Posts extends WP_Widget {
	
		function __construct() {
			$widget_ops = array( 'classname' => 'gp-recent-posts', 'description' => esc_html__( 'Your site\'s most recent posts with thumbnails.', 'socialize' ) );
			parent::__construct( 'gp-recent-posts-widget', esc_html__( 'GP Recent Posts', 'socialize' ), $widget_ops );
		}

		function widget( $args, $instance ) {
		
			global $date_range;
			extract( $args );
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Recent Posts', 'socialize' ) : $instance['title'] );
			$posts = empty( $instance['posts'] ) ? '5' : $instance['posts'];
			$show_date = $instance['show_date'] ? '1' : '0';
			$show_views = $instance['show_views'] ? '1' : '0';
	
			echo html_entity_decode( $before_widget );	
			
			?>

			<?php if ( $title ) { echo html_entity_decode( $before_title . $title . $after_title ); } ?>
	
			<?php 

			$args = array( 
				'post_status'         => 'publish',
				'post_type'           => 'post',
				'posts_per_page'      => $posts,
				'ignore_sticky_posts' => true,
				'no_found_rows' 	  => true,
			);

			$query = new wp_query( $args ); ?>
					
			<div class="gp-blog-wrapper gp-blog-standard">
		
				<?php if ( $query->have_posts() ) : ?>
			
					<div class="gp-inner-loop">
			
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
	
							<section <?php post_class( 'gp-post-item' ); ?>>

								<?php if ( has_post_thumbnail() ) { ?>
										
									<div class="gp-post-thumbnail">
								
										<div class="gp-image-align-left">
								
											<?php $image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), 100, 80, true, false, true ); ?>
											<?php if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
												$retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), 100 * 2, 80 * 2, true, true, true );
											} else {
												$retina = '';
											} ?>
									
											<a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo get_post_meta( get_the_ID(), 'link_target', true ); ?>"<?php } ?>>

												<img src="<?php echo esc_url( $image[0] ); ?>" data-rel="<?php echo esc_url( $retina );; ?>" width="<?php echo absint( $image[1] ); ?>" height="<?php echo absint( $image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image" />
										
											</a>
									
										</div>
																
									</div>		

								<?php } ?>

								<div class="gp-loop-content">
														
									<h3 class="gp-loop-title" itemprop="headline"><a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo get_post_meta( get_the_ID(), 'link_target', true ); ?>"<?php } ?>><?php the_title(); ?></a></h3>

									<?php if ( $show_date OR $show_views ) { ?>
										<div class="gp-loop-meta">
											<?php if ( $show_date ) { ?><time class="gp-post-meta gp-meta-date" itemprop="datePublished" datetime="<?php echo get_the_date( 'c' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time><?php } ?>
											<?php if ( function_exists( 'pvc_get_post_views' ) && $show_views ) { ?><span class="gp-post-meta gp-meta-views"><?php echo pvc_get_post_views(); ?> <?php esc_html_e( 'views', 'socialize' ); ?></span><?php } ?>	
										</div>
									<?php } ?>
							
								</div>
													
							</section>

						<?php endwhile; ?>
					
					</div>
		
				<?php else : ?>

					<strong class="gp-no-items-found"><?php esc_html_e( 'No items found.', 'socialize' ); ?></strong>

				<?php endif; wp_reset_postdata(); ?>
							
			</div>

			<?php echo html_entity_decode( $after_widget );

		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['posts'] = $new_instance['posts'];
			$instance['show_date'] = $new_instance['show_date'];	
			$instance['show_views'] = $new_instance['show_views'];						
			return $instance;
		}

		function form( $instance ) {
		
			$defaults = array( 
				'title'     => 'Recent Posts',
				'posts'     => 5,
				'show_date' => 1,
				'show_views' => 0,
			 ); $instance = wp_parse_args( ( array ) $instance, $defaults ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'socialize' ); ?></label>
				<br/><input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'socialize' ); ?></label> <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts' ) ) ?>" value="<?php echo esc_attr( $instance['posts'] ); ?>" size="3" />
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" value="1" <?php checked( $instance['show_date'], 1 ); ?> /><label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'socialize' ); ?></label>
			</p>

			<p>
				<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_views' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_views' ) ); ?>" value="1" <?php checked( $instance['show_views'], 1 ); ?> /><label for="<?php echo esc_attr( $this->get_field_id( 'show_views' ) ); ?>"><?php esc_html_e( 'Display post views?', 'socialize' ); ?></label>
			</p>
							
			<input type="hidden" name="widget-options" id="widget-options" value="1" />

			<?php

		}
	}

} ?>