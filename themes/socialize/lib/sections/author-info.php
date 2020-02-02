<?php if ( get_the_author_meta( 'description' ) ) { ?>
	<div class="gp-author-info">

		<?php echo get_avatar( get_the_author_meta( 'ID' ), 110 ); ?>

		<div class="gp-author-meta">

			<div class="gp-author-name">
				<?php echo apply_filters( 'gp_author_url', '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) ) . '</a>' ); ?>
			</div>

			<div class="gp-author-desc">
				<?php the_author_meta( 'description' ); ?>
			</div>
		
			<div class="gp-author-social-icons">
				<?php if ( get_the_author_meta( 'twitter' ) ) { ?><a href="<?php echo get_the_author_meta( 'twitter' ); ?>" class="gp-twitter-icon"></a><?php } ?>
				<?php if ( get_the_author_meta( 'facebook' ) ) { ?><a href="<?php echo get_the_author_meta( 'facebook' ); ?>" class="gp-facebook-icon"></a><?php } ?>
				<?php if ( get_the_author_meta( 'googleplus' ) ) { ?><a href="<?php echo get_the_author_meta( 'googleplus' ); ?>" class="gp-google-plus-icon"></a><?php } ?>
				<?php if ( get_the_author_meta( 'pinterest' ) ) { ?><a href="<?php echo get_the_author_meta( 'pinterest' ); ?>" class="gp-pinterest-icon"></a><?php } ?>
				<?php if ( get_the_author_meta( 'youtube' ) ) { ?><a href="<?php echo get_the_author_meta( 'youtube' ); ?>" class="gp-youtube-icon"></a><?php } ?>
				<?php if ( get_the_author_meta( 'vimeo' ) ) { ?><a href="<?php echo get_the_author_meta( 'vimeo' ); ?>" class="gp-vimeo-icon"></a><?php } ?>
				<?php if ( get_the_author_meta( 'flickr' ) ) { ?><a href="<?php echo get_the_author_meta( 'flickr' ); ?>" class="gp-flickr-icon"></a><?php } ?>
				<?php if ( get_the_author_meta( 'linkedin' ) ) { ?><a href="<?php echo get_the_author_meta( 'linkedin' ); ?>" class="gp-linkedin-icon"></a><?php } ?>
				<?php if ( get_the_author_meta( 'instagram' ) ) { ?><a href="<?php echo get_the_author_meta( 'instagram' ); ?>" class="gp-instagram-icon"></a><?php } ?>
			</div>

		</div>

	</div>
<?php } ?>