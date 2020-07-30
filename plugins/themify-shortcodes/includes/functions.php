<?php

if ( ! function_exists( 'themify_get_shortcode_template' ) ) :
/**
 * Returns markup
 * @param $posts
 * @param string $slug
 * @param string $name
 * @return mixed|void
 */
function themify_get_shortcode_template( $posts, $slug = 'includes/loop', $name = 'index', $args = array() ) {
	global $post, $themify, $ThemifyBuilder;

	$args = wp_parse_args( $args, array(
		'before_post' => '',
		'after_post' => '',
	) );

	if ( is_object( $post ) )
		$saved_post = clone $post;

	$themify->is_shortcode_template = true;

	// Add flag that template loop is in builder loop
	if ( is_object( $ThemifyBuilder ) ) {
		$ThemifyBuilder->in_the_loop = true;
	}
	ob_start();

	// get_template_part, defined in wp-includes/general-template.php
	$templates = array();
	$name = (string) $name;
	if ( '' !== $name )
		$templates[] = "{$slug}-{$name}.php";
	$templates[] = "{$slug}.php";
	$template_file = apply_filters( 'themify_get_shortcode_template_file', locate_template( $templates, false, false ), $slug, $name );

	foreach ( $posts as $post ) {
		setup_postdata( $post );

		echo $args['before_post'];

		// get_template_part, defined in wp-includes/general-template.php
		do_action( "get_template_part_{$slug}", $slug, $name );
		if( ! empty( $template_file ) ) {
			include $template_file;
		}

		echo $args['after_post'];
	}
	$html = ob_get_contents();
	ob_end_clean();

	if ( isset( $saved_post ) && is_object( $saved_post ) ) {
		$post = $saved_post;
		/**
		 * WooCommerce plugin resets the global $product on the_post hook,
		 * call setup_postdata on the original $post object to prevent fatal error from WC
		 */
		setup_postdata( $saved_post );
	}

	// Add flag that template loop is in builder loop
	if ( is_object( $ThemifyBuilder ) ) {
		$ThemifyBuilder->in_the_loop = false;
	}

	return apply_filters('themify_get_shortcode_template', $html);
}
endif;

if ( ! function_exists( 'themify_get_author_link' ) ) :
/**
 * Builds the markup for the entry author with microdata information.
 * @return string
 */
function themify_get_author_link() {
	$output = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . get_the_author() . '</a></span>';
	return $output;
}
endif;

if( ! function_exists( 'themify_get_categories_as_classes' ) ) :
/**
 * Returns a CSS-formatted string of categories assigned to current post
 *
 * @return string
 */
function themify_get_categories_as_classes( $post_id ) {
	$categories = wp_get_post_categories( $post_id );
	$class = '';
	foreach( $categories as $cat )
		$class .= ' cat-' . $cat;

	return $class;
}
endif;