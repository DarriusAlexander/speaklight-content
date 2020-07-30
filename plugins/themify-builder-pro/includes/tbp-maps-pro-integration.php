<?php

/**
 * Enable displaying Posts in the Builder Maps Pro addon
 *
 * @return array
 */
function tbp_maps_pro_posts_data_provider( $providers ) {
	class Maps_Pro_Data_Provider_Posts extends Maps_Pro_Data_Provider {

		function get_id() {
			return 'posts';
		}

		function get_label() {
			return __( 'Posts', 'themify' );
		}

		function get_options() {
			return array(
				array(
					'type' => 'query_posts',
					'id' => 'post_type_post',
					'tax_id' => 'tax',
					'term_id' => 'tax_category',
					'slug_id' => 'post_slug',
				),
				array(
					'id' => 'per_page',
					'type' => 'number',
					'label' => __( 'Posts Per Page', 'themify' ),
					'help' => __( 'Enter the number of post to display.', 'themify' )
				),
				array(
					'id' => 'order',
					'type' => 'select',
					'label' => __( 'Order', 'themify' ),
					'help' => __( 'Descending = show newer posts first', 'themify' ),
					'order' =>true
				),
				array(
					'id' => 'orderby',
					'type' => 'select',
					'label' => __( 'Order By', 'themify' ),
					'options' => array(
						'date' => __( 'Date', 'themify' ),
						'ID' => __( 'Id', 'themify' ),
						'author' => __( 'Author', 'themify' ),
						'title' => __( 'Title', 'themify' ),
						'name' => __( 'Name', 'themify' ),
						'modified' => __( 'Modified', 'themify' ),
						'rand' => __( 'Random', 'themify' ),
						'comment_count' => __( 'Comment Count', 'themify' )
					)
				),
				array(
					'id' => 'offset',
					'type' => 'number',
					'label' => __( 'Offset', 'themify' ),
					'help' => __( 'Enter the number of post to displace or pass over.', 'themify' )
				),
				array(
					'label' => __( 'PTB Map field', 'themify' ),
					'id' => 'ptb_map_field',
					'type' => 'select',
					'dataset' => 'ptb_map_fields', // "tb_select_dataset_ptb_map_fields" filter will populate this field
					'wrap_class' => function_exists( 'run_ptb' ) ? '' : 'tb_hide',
					'help' => sprintf( __( 'Select the field that will be used as the location for marker. Requires <a href="%s">PTB Extra Fields</a> addon.', 'themify' ), 'https://themify.me/ptb-addons/extra-fields' ),
					'binding' => array(
						'empty' => array( 'show' => array( 'custom_field' ) ),
						'not_empty' => array( 'hide' => array( 'custom_field' ) ),
					),
				),
				array(
					'label' => __( 'Custom Field for Address', 'themify' ),
					'id' => 'custom_field',
					'type' => 'autocomplete',
					'dataset' => 'custom_fields',
					'help' => __( 'Name of the custom field that will be used as the location for marker. Accepts both human-readable address and Lat/Lng values.', 'themify' ),
				),
				array(
					'id' => 'marker_icon',
					'type' => 'image',
					'label' => __('Icon', 'tbp')
				),
			);
		}

		function get_items( $settings ) {
			global $post;

			$settings = wp_parse_args( $settings, array(
				'per_page' => 5,
				'custom_field' => '',
				'ptb_map_field' => '',
				'marker_icon' => '',
				'post_type_post' => 'post',
				'term_type' => 'category',
				'tax' => 'category',
				'post_slug' => '',
				'offset' => '',
				'order' => 'desc',
				'orderby' => 'date',
			) );
			$args = array(
				'post_status' => 'publish',
				'post_type' => $settings['post_type_post'],
				'posts_per_page' => $settings['per_page'],
				'order' => $settings['order'],
				'orderby' => $settings['orderby'],
				'no_found_rows'=>true,
				'ignore_sticky_posts'=>true,
				'suppress_filters' => false,
				'offset' => $settings['offset'],
			);
			if ( $settings['term_type'] === 'post_slug' ) {
				if ( $settings['post_slug'] !== '' ) {
					$args['post__in'] = Themify_Builder_Model::parse_slug_to_ids( $settings['post_slug'], $args['post_type'] );
				}
			} else {
				$terms = isset( $settings[ "tax_{$settings['tax']}" ] ) ? $settings[ "tax_{$settings['tax']}" ] : ( isset( $settings['tax_category'] ) ? $settings['tax_category'] : false );
				if ( $terms === false ) {
					return;
				}
				// deal with how category fields are saved
				$terms = preg_replace('/\|[multiple|single]*$/', '', $terms);

				$temp_terms = explode(',', $terms);
				$new_terms = array();
				$is_string = false;
				foreach ( $temp_terms as $t ) {
					if ( ! is_numeric( $t ) ) {
						$is_string = true;
					}
					if ( '' !== $t ) {
						array_push( $new_terms, trim( $t ) );
					}
				}
				if ( ! empty( $new_terms ) && ! in_array( '0', $new_terms ) ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => $settings['tax'],
							'field' => $is_string ? 'slug' : 'id',
							'terms' => $new_terms,
							'operator' => ( '-' === substr( $terms, 0, 1 ) ) ? 'NOT IN' : 'IN'
						)
					);
				}
			}

			$query = new WP_Query( apply_filters( 'tb_maps_pro_query', $args, $settings ) );
			if ( is_object( $post ) ){
				$saved_post = clone $post;
			}
			$items = array();
			while ( $query->have_posts() ) {
				$query->the_post();

				$item = $this->get_item( $settings );
				if ( ! empty( $item ) ) {
					$items[] = $item;
				}
			}
			if ( isset( $saved_post ) && is_object( $saved_post ) ) {
				$post = $saved_post;
				setup_postdata( $saved_post );
			}

			return $items;
		}

		function get_item( $settings ) {
			if ( ! empty ( $settings['ptb_map_field'] ) ) {
				$address = get_post_meta( get_the_id(), $settings['ptb_map_field'], true );
				if ( is_array( $address ) ) {
					$address = json_decode( $address['place'], true );
					$address = $address['location']['lat'] . ', ' . $address['location']['lng'];
				}
			} else {
				$address = get_post_meta( get_the_id(), $settings['custom_field'], true );
			}

			// skip posts that don't have the designated "address" meta field
			if ( ! $address ) {
				return false;
			}
			$text = sprintf(
				'
				<div style="float: left; margin-right: 10px;">
					<a href="%2$s">
						<img src="%1$s" alt="%3$s" />
					</a>
				</div>
				<div>
					<a href="%2$s"><strong>%3$s</strong></a>
				</div>
				<div>
					%4$s
				</div>',
				esc_attr( get_the_post_thumbnail_url( get_the_id(), 'thumbnail' ) ),
				esc_attr( get_permalink() ),
				esc_html( get_the_title() ),
				esc_html( get_the_excerpt() )
			);
			return array(
				'title' => $text,
				'image' => $settings['marker_icon'],
				'address' => $address,
			);
		}
	}

	$providers['posts'] = 'Maps_Pro_Data_Provider_Posts';
	return $providers;
}
add_filter( 'tb_maps_pro_data_providers', 'tbp_maps_pro_posts_data_provider' );


/**
 * Handles filling dynamic values for the "ptb_map_field" option in Maps Pro
 *
 * @return array
 */
function tbp_maps_pro_posts_map_fields( $values ) {
	return array(
		'options' => array_merge(
			array( '' => '' ),
			Tbp_Utils::get_ptb_fields_by_type( 'map' )
		),
	);
}
add_filter( 'tb_select_dataset_ptb_map_fields', 'tbp_maps_pro_posts_map_fields' );
