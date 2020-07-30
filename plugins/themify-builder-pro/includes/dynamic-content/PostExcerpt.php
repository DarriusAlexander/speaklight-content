<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostExcerpt extends Tbp_Dynamic_Item {

	private $length = null;

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'textarea', 'wp_editor', 'wp_editor' );
	}

	function get_label() {
		return __( 'Post Excerpt', 'themify' );
	}

	function get_value( $args = array() ) {
		if ( ! empty( $args['length'] ) ) {
		    $this->length = $args['length'];
		    add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 1000 );
		}
		if(empty($args['post_id'])){
		    $the_query = Tbp_Utils::get_actual_query();
		    if($the_query===null || $the_query->have_posts()){
			if($the_query!==null){
			    $the_query->the_post();
			}
			$value = get_the_excerpt();
		    }
		    if($the_query!==null){
			wp_reset_postdata();
		    }
		}
		else{
		    $value = get_the_excerpt( $args['post_id'] );
		}
		if ( ! empty( $args['length'] ) ) {
		    remove_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 1000 );
		}
		$this->length = null;
		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Excerpt Length', 'themify' ),
				'id' => 'length',
				'type' => 'number',
				'help' => __( 'Limit the excerpt by words.', 'themify' ),
			),
			array(
				'label' => __( 'Post ID', 'themify' ),
				'id' => 'post_id',
				'type' => 'number',
				'help' => __( 'Leave empty to get the data from current post in the loop.', 'themify' ),
			),
		);
	}

	function excerpt_length( $length ) {
		return $this->length;
	}
}