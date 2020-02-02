<?php

if ( ! function_exists( 'ghostpool_categories' ) ) {
	function ghostpool_categories() {
		register_widget( 'GhostPool_Categories' );
	}
}
add_action( 'widgets_init', 'ghostpool_categories' );

if ( ! class_exists( 'GhostPool_Categories' ) ) {
	class GhostPool_Categories extends WP_Widget {

		function __construct() {
			$widget_ops = array( 'classname' => 'gp-categories', 'description' => esc_html__( 'A list or dropdown of categories.', 'socialize' ) );
			parent::__construct( 'gp-categories-widget', esc_html__( 'GP Categories', 'socialize' ), $widget_ops );
		}

		function widget( $args, $instance ) {
	
			extract( $args );

			static  $first_dropdown = true;
		
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Categories', 'socialize' ) : $instance['title'], $instance, $this->id_base );
		
			$c = ! empty( $instance['count'] ) ? '1' : '0';
			$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
			$d = ! empty( $instance['dropdown'] ) ? '1' : '0';
			$exclude = $instance['exclude'];
		
			echo html_entity_decode( $before_widget );
		
		
			?>
		
			<?php if ( $title ) { echo html_entity_decode( $before_title . $title . $after_title ); } ?>

			<?php
		
			$cat_args = array(
				'orderby'      => 'name',
				'show_count'   => $c,
				'hierarchical' => $h,
				'exclude' => $exclude
			);


			if ( $d ) {

				$dropdown_id = ( $first_dropdown ) ? 'cat' : "{$this->id_base}-dropdown-{$this->number}";		
				$first_dropdown = false;
			
				echo '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>';
			
				$cat_args['show_option_none'] = esc_html__( 'Select Category', 'socialize' );
				$cat_args['id'] = $dropdown_id;

				wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $cat_args ) ); ?>

				<script type='text/javascript'>
				/* <![CDATA[ */
				(function() {
					var dropdown = document.getElementById( "<?php echo esc_js( $dropdown_id ); ?>" );
					function onCatChange() {
						if ( dropdown.options[ dropdown.selectedIndex ].value != '' ) {
							location.href = "<?php echo esc_url( home_url( '/' ) ); ?>?cat=" + dropdown.options[ dropdown.selectedIndex ].value;
						}
					}
					dropdown.onchange = onCatChange;
				})();
				/* ]]> */
				</script>

			<?php } else { ?>
		
				<ul>
					<?php
					$cat_args['title_li'] = '';
					wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) );
					?>
				</ul>
			
			<?php
		
			}

			echo html_entity_decode( $after_widget );
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			$instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;
			$instance['hierarchical'] = ! empty( $new_instance['hierarchical'] ) ? 1 : 0;
			$instance['dropdown'] = ! empty( $new_instance['dropdown'] ) ? 1 : 0;
			$instance['exclude'] = ! empty( $new_instance['exclude'] ) ? $new_instance['exclude'] : '';

			return $instance;
		}

		function form( $instance ) {
	
			// Defaults
			$instance = wp_parse_args( ( array ) $instance, array( 'title' => '' ) );
			$title = esc_attr( $instance['title'] );
			$count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
			$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
			$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
			$exclude = isset( $instance['exclude'] ) ? $instance['exclude'] : '';

		?>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'socialize' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
				
			<p><input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'dropdown' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dropdown' ) ); ?>"<?php checked( $dropdown ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'dropdown' ) ); ?>"><?php esc_html_e( 'Show as dropdown', 'socialize' ); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>"<?php checked( $count ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Show post counts', 'socialize' ); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>"<?php checked( $hierarchical ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php esc_html_e( 'Show hierarchy', 'socialize' ); ?></label></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>"><?php esc_html_e( 'Exclude:', 'socialize' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'exclude' ) ); ?>" type="text" value="<?php echo esc_attr( $exclude ); ?>" />
			<br/><small><?php esc_html_e( 'Enter the ID numbers of the categories you want to exclude, separating each with a comma (e.g. 48,142).', 'socialize' ); ?></small></p>
				
	<?php

		}

	}

} ?>