<div class="gp-post-format-video-content <?php echo sanitize_html_class( $GLOBALS['ghostpool_image_alignment'] ); ?>">

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