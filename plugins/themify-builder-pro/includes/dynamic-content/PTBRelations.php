<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRelations extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'wp_editor' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Relations)', 'themify' );
	}

	function get_value( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'show' => 'grid',
			'columns' => 1,
			'minSlides' => 1,
			'autoHover' => 1,
			'pause' => 1,
			'pager' => 1,
			'controls' => 1,
			'orderby' => 'post__in',
			'order' => 'asc',
		) );

		if ( empty( $args['field'] ) ) {
			return '';
		}

		list( $post_type, $field_name ) = explode( ':', $args['field'] );
		$ptb = PTB::get_option()->get_options();

		if ( ! isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ] ) ) {
			return '';
		}

		$ptb_options = PTB::get_option();
		$def = $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ];
		$rel_options = PTB_Relation::get_option();
		$template = $rel_options->get_relation_template( $def['post_type'], get_post_type() );
		if ( ! $template ) {
			return '';
		}
		$themplate_layout = $ptb_options->get_post_type_template( $template['id'] );
		if ( ! isset( $themplate_layout['relation']['layout'] ) ) {
			return;
		}

		$content = '';
		$is_shortcode = PTB_Public::$shortcode;
		PTB_Public::$shortcode = true;
		$ver = PTB::get_plugin_version( WP_PLUGIN_DIR . '/themify-ptb-relation/themify-ptb-relation.php' );
		$themplate = new PTB_Form_PTT_Them( 'ptb', $ver );
		$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
		$relType = ! empty( $cf_value['relType'] ) ? (int) $cf_value['relType'] : 1;
		$ids = array_filter( explode( ', ', $cf_value['ids'] ) );
		$query_args = array(
			'post_type' => $def['post_type'],
			'post_status' => 'publish',
			'order' => $args['order'],
			'orderby' => $args['orderby'],
			'no_found_rows' => 1,
		);
		if ( $relType === 1 ) {
			$query_args['post__in'] = $ids;
			$query_args['posts_per_page'] = count( $ids );
		} else {
			$tmp = array();
			$terms = get_terms( array(
				'include' => $ids
			) );
			foreach ( $terms as $term ) {
				$tmp[ $term->taxonomy ][] = $term->term_id;
			}
			$value = array();
			$temp = array();
			foreach ( $tmp as $k => $v ) {
				$value[] = array(
					'taxonomy' => $k,
					'field' => 'term_id',
					'terms' => $v
				);
			}
			if ( ! empty( $value ) ) {
				$value['relation'] = 'AND';
				$query_args['tax_query'] = $value;
				$query_args['nopaging'] = 1;
			}
		}
		global $post;
		$old_post = clone $post;
		$query = new WP_Query;
		$rel_posts = $query->query( $query_args );
		foreach ( $rel_posts as $p ) {
			$post = $p;
			setup_postdata( $post );

			$cmb_options = $post_support = $post_meta = $post_taxonomies = array();
			$ptb_options->get_post_type_data( $def['post_type'], $cmb_options, $post_support, $post_taxonomies );
			$post_meta['post_url'] = get_permalink();
			$post_meta['taxonomies'] = ! empty( $post_taxonomies ) ? wp_get_post_terms( get_the_ID(), array_values($post_taxonomies ) ) : array();
			$post_meta = array_merge( $post_meta, get_post_custom(), get_post( '', ARRAY_A ) );
			$content .= '<li class="ptb_relation_item">' . $themplate->display_public_themplate( $themplate_layout['relation'], $post_support, $cmb_options, $post_meta, $def['post_type'], false ) . '</li>';
		}
		PTB_Public::$shortcode = $is_shortcode;
		$post = $old_post;
		setup_postdata( $post );

		if ( $args['show'] === 'slider' ) {
			$slider_args = array(
				'minSlides' => $args['minSlides'],
				'autoHover' => $args['autoHover'],
				'pause' => $args['pause'],
				'pager' => $args['pager'],
				'controls' => $args['controls'],
			);
			if ( ! wp_script_is( 'ptb-relation' ) ) {
				wp_enqueue_style( 'ptb-bxslider' );
				wp_enqueue_script( 'ptb-relation' );
			}
		}

		if ( ! empty( $content ) ) {
		$content = 
			'<div class="ptb_loops_shortcode clearfix ptb_relation_' . $args['show'] . '">'
				. ( $args['show'] === 'slider' ? '<ul class="ptb_relation_post_slider" data-slider="' . esc_attr( json_encode( $slider_args ) ) . '">' : '<ul class="ptb_relation_posts ptb_relation_columns_' . $args['columns'] . '">' )
					. $content
				. '</ul>'
			. '</div>';
		}

		return $content;
	}

	function get_options() {
		$options = array();

		/* collect "progress_bar" field types in all post types */
		$ptb = PTB::$options->get_custom_post_types();
		foreach ( $ptb as $post_type_key => $post_type ) {
			if ( is_array( $post_type->meta_boxes ) ) {
				foreach ( $post_type->meta_boxes as $key => $field ) {
					if ( $field['type'] === 'relation' ) {
					    $label = PTB_Utils::get_label( $post_type->plural_label );
					    $name = PTB_Utils::get_label( $field['name'] );
						$options[ "{$post_type_key}:{$key}" ] = sprintf( '%s: %s', $label, $name );
					}
				}
			}
		}

		return array(
			array(
				'label' => __( 'Field', 'themify' ),
				'id' => 'field',
				'type' => 'select',
				'options' => $options,
			),
			array(
				'label' => __( 'Show', 'themify' ),
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'grid' => __( 'Grid', 'themify' ),
					'slider' => __( 'Slider', 'themify' ),
				),
				'binding' => array(
					'grid' => array( 'show' => array( 'columns' ), 'hide' => array( 'minSlides', 'autoHover', 'pause', 'pager', 'controls' ) ),
					'slider' => array( 'hide' => array( 'columns' ), 'show' => array( 'minSlides', 'autoHover', 'pause', 'pager', 'controls' ) ),
				),
			),
			array(
				'label' => __( 'Grid Columns', 'themify' ),
				'id' => 'columns',
				'type' => 'select',
				'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9 ),
			),
			array(
				'label' => __( 'Visible Slides', 'themify' ),
				'id' => 'minSlides',
				'type' => 'select',
				'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7 ),
			),
			array(
				'label' => __( 'Pause On Hover', 'themify' ),
				'id' => 'autoHover',
				'type' => 'select',
				'options' => array( 1 => __( 'Yes', 'themify' ), 0 => __( 'No', 'themify' ) ),
			),
			array(
				'label' => __( 'Auto Scroll', 'themify' ),
				'id' => 'pause',
				'type' => 'select',
				'options' => array( 1 => __( '1 Second', 'themify' ), 2 => __( '2 Seconds', 'themify' ), 3 => __( '3 Seconds', 'themify' ), 4 => __( '4 Seconds', 'themify' ), 5 => __( '5 Seconds', 'themify' ), 6 => __( '6 Seconds', 'themify' ), 7 => __( '7 Seconds', 'themify' ), 8 => __( '8 Seconds', 'themify' ), 9 => __( '9 Seconds', 'themify' ), ),
			),
			array(
				'label' => __( 'Show Slider Pagination', 'themify' ),
				'id' => 'pager',
				'type' => 'select',
				'options' => array( 1 => __( 'Yes', 'themify' ), 0 => __( 'No', 'themify' ) ),
			),
			array(
				'label' => __( 'Show Slider arrow buttons', 'themify' ),
				'id' => 'controls',
				'type' => 'select',
				'options' => array( 1 => __( 'Yes', 'themify' ), 0 => __( 'No', 'themify' ) ),
			),
			array(
				'label' => __( 'Order', 'themify' ),
				'id' => 'order',
				'type' => 'select',
				'options' => array(
					'asc' => __( 'Ascending', 'themify' ),
					'desc' => __( 'Descending', 'themify' ),
				),
			),
			array(
				'label' => __( 'Order By', 'themify' ),
				'id' => 'order',
				'type' => 'select',
				'options' => array(
					'date' => __( 'Date', 'themify' ),
					'id' => __( 'ID', 'themify' ),
					'author' => __( 'Author', 'themify' ),
					'title' => __( 'Title', 'themify' ),
					'name' => __( 'Name', 'themify' ),
					'modified' => __( 'Modified', 'themify' ),
					'rand' => __( 'Random', 'themify' ),
					'comment_count' => __( 'Comment Count', 'themify' ),
					'menu_order' => __( 'Menu Order', 'themify' ),
				),
			),
		);
	}
}