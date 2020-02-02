<div class="gp-post-format-audio-content gp-image-above">

	<?php 
	
	$mp3 = '';
	$ogg = '';

	if ( get_post_meta( get_the_ID(), 'audio_mp3_url', true ) ) {
		$mp3 = get_post_meta( get_the_ID(), 'audio_mp3_url', true );
		$mp3 = $mp3['url'];
	}

	if ( get_post_meta( get_the_ID(), 'audio_ogg_url', true ) ) {
		$ogg = get_post_meta( get_the_ID(), 'audio_ogg_url', true );
		$ogg = $ogg['url'];
	}
			
	echo do_shortcode( '[audio mp3="' . esc_url( $mp3 ) . '" ogg="' . esc_url( $ogg ) . '"][/audio]' ); ?>
	
</div>