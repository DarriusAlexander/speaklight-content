<div class="gp-post-format-video-content gp-entry-video-wrapper">

	<div class="gp-entry-video">

		<?php if ( get_post_meta( get_the_ID(), 'video_embed_url', true ) ) { ?>

			<?php global $wp_embed; ?>
			<?php echo $wp_embed->run_shortcode( '[embed width="' . absint( $GLOBALS['ghostpool_image_width'] ) . '" height="' . absint( $GLOBALS['ghostpool_image_height'] ) . '"]' . esc_url( get_post_meta( get_the_ID(), 'video_embed_url', true ) ) . '[/embed]' ); ?>

		<?php } else { 
		
			$mp4 = '';
			$m4v = '';
			$webm = '';
			$ogv = '';
		
			if ( get_post_meta( get_the_ID(), 'video_mp4_url', true ) ) {	
				$mp4 = get_post_meta( get_the_ID(), 'video_mp4_url', true );
				$mp4 = $mp4['url'];
			}
	
			if ( get_post_meta( get_the_ID(), 'video_m4v_url', true ) ) {		
				$m4v = get_post_meta( get_the_ID(), 'video_m4v_url', true );
				$m4v = $m4v['url'];
			}
	
			if ( get_post_meta( get_the_ID(), 'video_webm_url', true ) ) {	
				$webm = get_post_meta( get_the_ID(), 'video_webm_url', true );
				$webm = $webm['url'];
			}
	
			if ( get_post_meta( get_the_ID(), 'video_ogv_url', true ) ) {	
				$ogv = get_post_meta( get_the_ID(), 'video_ogv_url', true );
				$ogv = $ogv['url'];
			}
	
			?>

			<?php echo do_shortcode( '[video mp4="' . esc_url( $mp4 ) . '" m4v="' . esc_url( $m4v ) . '" webm="' . esc_url( $webm ). '" ogv="' . esc_url( $ogv ) . '"][/video]' ); ?>

		<?php } ?>
	
	</div>
		
	<?php if ( $GLOBALS['ghostpool_title'] == 'enabled' ) { ?>	

		<header class="gp-entry-header">	

			<?php if ( $GLOBALS['ghostpool_title'] == 'enabled' ) { ?>	
				<h1 class="gp-entry-title<?php if ( ! empty( $GLOBALS['ghostpool_subtitle'] ) ) { ?> has-subtitle<?php } ?>" itemprop="headline">
					<?php if ( ! empty( $GLOBALS['ghostpool_custom_title'] ) ) { echo esc_attr( $GLOBALS['ghostpool_custom_title'] ); } else { the_title(); } ?>
				</h1>
			<?php } ?>	

			<?php if ( ! empty( $GLOBALS['ghostpool_subtitle'] ) ) { ?>
				<h3 class="gp-subtitle"><?php echo esc_attr( $GLOBALS['ghostpool_subtitle'] ); ?></h3>
			<?php } ?>
		
			<?php if ( $GLOBALS['ghostpool_meta_cats'] == '1' ) { ?>
				<?php echo ghostpool_exclude_cats( get_the_ID(), false, false ); ?>
			<?php } ?>				
					
			<?php get_template_part( 'lib/sections/entry', 'meta' ); ?>
			
			<?php if ( get_post_meta( get_the_ID(), 'video_description', true ) ) { ?>
				<div class="gp-video-description">
					<?php echo get_post_meta( get_the_ID(), 'video_description', true ); ?>	
				</div>			
			<?php } ?>		

		</header>
	
	<?php } ?>	

</div>