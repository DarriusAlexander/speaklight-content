<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRating extends Tbp_Dynamic_Item {

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
		return __( 'PTB Custom Fields (Rating)', 'themify' );
	}

	function get_value( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'icon' => 'fa-star',
			'size' => 'small',
			'vcolor' => 'rgba(250, 225, 80, 1)',
		) );

		if ( empty( $args['field'] ) ) {
			return;
		}

		list( $post_type, $field_name ) = explode( ':', $args['field'] );

		$ptb = PTB::get_option()->get_options();
		if ( isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ] ) ) {
			$field_config = $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ];
		} else {
			return;
		}

		$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
		if ( is_string( $cf_value ) ) {
			$value = $cf_value;
		} else if ( is_array( $cf_value ) && isset( $cf_value['total'] ) ) {
			$value = $cf_value['total'];
		} else {
			$value = 0;
		}
		$icon = themify_get_icon( $args['icon'] );
		$stars_count = $field_config['stars_count'];

		ob_start();
		?>

		<div itemprop="aggregateRating" itemscope="" itemtype="https://schema.org/AggregateRating"
			data-key="<?php echo esc_attr( $field_name ); ?>"
			data-post="<?php the_ID(); ?>"
			data-id="rating_<?php echo uniqid(); ?>"
			data-vcolor="<?php echo esc_attr( $args['vcolor'] ); ?>"
			class="ptb_extra_rating ptb_extra_readonly_rating ptb_extra_rating_<?php echo $args['size']; ?>"
		>
			<?php for ( $i = $stars_count; $i > 0; --$i ) : ?>
				<span class="<?php echo esc_attr( $icon ); ?><?php echo $value >= $i ? ' ptb_extra_voted' : '' ?>"></span>
			<?php endfor; ?>

			<meta itemprop="ratingValue" content="<?php echo $value > 0 ? ( $value > 5 ? 5 : $value ) : 1 ?>">
			<meta itemprop="ratingCount" content="<?php echo ! empty( $cf_value['count'] ) ? $cf_value['count'] : 1 ?>">
		</div>

		<?php
		return ob_get_clean();
	}

	function get_options() {
		$options = array();

		/* collect "text" field types in all post types */
		$ptb = PTB::$options->get_custom_post_types();
		foreach ( $ptb as $post_type_key => $post_type ) {
			if ( is_array( $post_type->meta_boxes ) ) {
				foreach ( $post_type->meta_boxes as $key => $field ) {
					if ( $field['type'] === 'rating' ) {
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
				'label' => __( 'Size', 'themify' ),
				'id' => 'size',
				'type' => 'select',
				'options' => array(
					'small' => __( 'Small', 'themify' ),
					'medium' => __( 'Medium', 'themify' ),
					'large' => __( 'Large', 'themify' ),
				),
			),
			array(
				'id' => 'icon',
				'type' => 'icon',
				'label' => __( 'Icon', 'themify' ),
			),
			array(
				'id' => 'vcolor',
				'type' => 'color',
				'label' => __( 'Color', 'themify' ),
			),
		);
	}
}