<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostImageAttachments extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'gallery' );
	}

	function get_label() {
		return __( 'Post Image Attachments', 'themify' );
	}

	function get_value( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'post_id' => Tbp_Utils::get_actual_viewing_post_id(),
		) );

		if ( $post = get_post( $args['post_id'] ) ) {
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'no_found_rows'=>true,
				'ignore_sticky_posts'=>true,
				'post_parent' => $post->ID,
				'exclude' => get_post_thumbnail_id( $post->ID )
			) );

			if ( $attachments ) {
				$ids = wp_list_pluck( $attachments, 'ID' );
				return '[gallery ids="' . implode( ',', $ids ) . '"]';
			}
		}
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Post ID', 'themify' ),
				'id' => 'post_id',
				'type' => 'number',
				'help' => __( 'Leave empty to get the data from current post in the loop.', 'themify' ),
			),
		);
	}
}
