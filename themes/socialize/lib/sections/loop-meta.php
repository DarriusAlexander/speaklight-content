<?php if ( ( isset( $GLOBALS['ghostpool_meta_author'] ) && $GLOBALS['ghostpool_meta_author'] == '1' ) OR ( isset( $GLOBALS['ghostpool_meta_date'] ) && $GLOBALS['ghostpool_meta_date'] == '1' ) OR ( isset( $GLOBALS['ghostpool_meta_comment_count'] ) && $GLOBALS['ghostpool_meta_comment_count'] == '1' ) OR ( isset( $GLOBALS['ghostpool_meta_views'] ) && $GLOBALS['ghostpool_meta_views'] == '1' ) ) { ?>

	<div class="gp-loop-meta">
	
		<?php if ( isset( $GLOBALS['ghostpool_meta_author'] ) && $GLOBALS['ghostpool_meta_author'] == '1' ) { ?><span class="gp-post-meta gp-meta-author"><?php echo apply_filters( 'gp_author_url', '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) ) . '</a>' ); ?></span><?php } ?>

		<?php if ( isset( $GLOBALS['ghostpool_meta_date'] ) && $GLOBALS['ghostpool_meta_date'] == '1' ) { ?><time class="gp-post-meta gp-meta-date" itemprop="datePublished" datetime="<?php echo get_the_date( 'c' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time><?php } ?>

		<?php if ( isset( $GLOBALS['ghostpool_meta_comment_count'] ) && $GLOBALS['ghostpool_meta_comment_count'] == '1' ) { ?><span class="gp-post-meta gp-meta-comments"><?php comments_popup_link( esc_html__( 'No Comments', 'socialize' ), esc_html__( '1 Comment', 'socialize' ), esc_html__( '% Comments', 'socialize' ), 'comments-link', esc_html__( 'Comments Closed', 'socialize' ) ); ?></span><?php } ?>
	
		<?php if ( function_exists( 'pvc_get_post_views' ) && ( isset( $GLOBALS['ghostpool_meta_views'] ) && $GLOBALS['ghostpool_meta_views'] == '1' ) ) { ?><span class="gp-post-meta gp-meta-views"><?php echo pvc_get_post_views(); ?> <?php esc_html_e( 'views', 'socialize' ); ?></span><?php } ?>
		
	</div>

<?php } ?>