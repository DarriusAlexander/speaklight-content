<?php
/*
Template Name: Link
*/
get_header(); ?>

<?php if ( have_posts() ) : the_post();
	
	$link = ghostpool_option( 'link_template_link' );
	
	if ( ! preg_match( '/^http:\/\//', $link ) ) {
		$link = 'http://' . $link;
	}

	esc_url_raw( wp_redirect( $link ) );
	exit();

endif; ?>

<?php get_footer(); ?>