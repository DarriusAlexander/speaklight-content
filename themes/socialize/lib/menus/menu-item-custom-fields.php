<?php

/**
Menu Item Custom Fields
http://kucrut.org/
*/

if ( ! class_exists( 'Ghostpool_Menu_Item_Custom_Fields' ) ) {
	/**
	* Menu Item Custom Fields Loader
	*/
	class GhostPool_Menu_Item_Custom_Fields {

		/**
		* Add filter
		*
		* @wp_hook action wp_loaded
		*/
		public static function load() {
			add_filter( 'wp_edit_nav_menu_walker', array( __CLASS__, '_filter_walker' ), 99 );
		}


		/**
		* Replace default menu editor walker with ours
		*
		* We don't actually replace the default walker. We're still using it and
		* only injecting some HTMLs.
		*
		* @since   0.1.0
		* @access  private
		* @wp_hook filter wp_edit_nav_menu_walker
		* @param   string $walker Walker class name
		* @return  string Walker class name
		*/
		public static function _filter_walker( $walker ) {
			$walker = 'Ghostpool_Menu_Item_Custom_Fields_Walker';
			if ( ! class_exists( $walker ) ) {
				require_once( get_template_directory() . '/lib/menus/walker-nav-menu-edit.php' );
			}

			return $walker;
		}
	}
	add_action( 'wp_loaded', array( 'Ghostpool_Menu_Item_Custom_Fields', 'load' ), 9 );
}

class Ghostpool_Menu_Item_Custom_Fields_Options {

	/**
	 * Holds our custom fields
	 *
	 * @var    array
	 * @access protected
	 * @since  Ghostpool_Menu_Item_Custom_Fields_Options 0.2.0
	 */
	protected static $fields = array();


	/**
	 * Initialize plugin
	 */
	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );

		self::$fields = array(
			'gp-menu-type' => esc_html__( 'Menu Type', 'socialize' ),
			'gp-columns' => esc_html__( 'Columns', 'socialize' ),
			'gp-display' => esc_html__( 'Device Display', 'socialize' ),
			'gp-user-display' => esc_html__( 'Logged In/Out Display', 'socialize' ),
			'gp-hide-nav-label' => esc_html__( 'Hide Navigation Label', 'socialize' ),
			'gp-content' => esc_html__( 'Content', 'socialize' ),
		);
	}


	/**
	 * Save custom field value
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         Nav menu ID
	 * @param int   $menu_item_db_id Menu item ID
	 * @param array $menu_item_args  Menu item data
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		//check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		foreach ( self::$fields as $_key => $label ) {
			$key = sprintf( 'menu-item-%s', $_key );

			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				// Do some checks here...
				$value = $_POST[ $key ][ $menu_item_db_id ];
			}
			else {
				$value = null;
			}

			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			}
			else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		}
	}


	/**
	 * Print field
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function _fields( $id, $item, $depth, $args ) {
		foreach ( self::$fields as $_key => $label ) :
			$key   = sprintf( 'menu-item-%s', $_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name  = sprintf( '%s[%s]', $key, $item->ID );
			$value = get_post_meta( $item->ID, $key, true );
			$class = sprintf( 'field-%s', $_key );
			?>
				<?php if ( ( $_key == 'gp-menu-type' OR $_key == 'gp-columns' ) && $depth < 1 ) { ?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $label ); ?></label>
						<br/><select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
							<?php if ( $_key == 'gp-menu-type' ) { ?>
								<option value="gp-standard-menu"<?php if ( $value == 'gp-standard-menu' ) { echo 'selected'; } ?>><?php esc_html_e( 'Standard Menu', 'socialize' ); ?></option>
								<option value="gp-megamenu"<?php if ( $value == 'gp-megamenu' ) { echo 'selected'; } ?>><?php esc_html_e( 'Mega Menu', 'socialize' ); ?></option>
								<?php if( $item->type == 'taxonomy' ) { ?>
									<option value="gp-content-menu"<?php if ( $value == 'gp-content-menu' ) { echo 'selected'; } ?>><?php esc_html_e( 'Content Menu', 'socialize' ); ?></option>
									<option value="gp-tab-content-menu"<?php if ( $value == 'gp-tab-content-menu' ) { echo 'selected'; } ?>><?php esc_html_e( 'Tab Content Menu', 'socialize' ); ?></option>
								<?php } ?>
								<option value="gp-login-link"<?php if ( $value == 'gp-login-link' ) { echo 'selected'; } ?>><?php esc_html_e( 'Login Link', 'socialize' ); ?></option>
								<option value="gp-register-link"<?php if ( $value == 'gp-register-link' ) { echo 'selected'; } ?>><?php esc_html_e( 'Register Link', 'socialize' ); ?></option>
								<option value="gp-logout-link"<?php if ( $value == 'gp-logout-link' ) { echo 'selected'; } ?>><?php esc_html_e( 'Logout Link', 'socialize' ); ?></option>
								<option value="gp-profile-link"<?php if ( $value == 'gp-profile-link' ) { echo 'selected'; } ?>><?php esc_html_e( 'Profile Link', 'socialize' ); ?></option>
							<?php } else { ?>
								<option value="gp-columns-1"<?php if ( $value == 'gp-columns-1' ) { echo 'selected'; } ?>><?php esc_html_e( '1 Column', 'socialize' ); ?></option>
								<option value="gp-columns-2"<?php if ( $value == 'gp-columns-2' ) { echo 'selected'; } ?>><?php esc_html_e( '2 Columns', 'socialize' ); ?></option>
								<option value="gp-columns-3"<?php if ( $value == 'gp-columns-3' ) { echo 'selected'; } ?>><?php esc_html_e( '3 Columns', 'socialize' ); ?></option>
								<option value="gp-columns-4"<?php if ( $value == 'gp-columns-4' ) { echo 'selected'; } ?>><?php esc_html_e( '4 Columns', 'socialize' ); ?></option>
								<option value="gp-columns-5"<?php if ( $value == 'gp-columns-5' ) { echo 'selected'; } ?>><?php esc_html_e( '5 Columns', 'socialize' ); ?></option>
							<?php } ?>	
						</select>		
						<?php if ( $_key == 'gp-columns' ) { ?>		
							<br/><span class="description"><?php esc_html_e( 'Set the number of columns when using the mega menu option only.', 'socialize' ); ?></span>
						<?php } ?>	
					</p>
				<?php } ?>

				<?php if ( $_key == 'gp-display' ) { ?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $label ); ?></label>
						<br/><select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
							<option value="gp-show-all"<?php if ( $value == 'gp-show-all' ) { echo 'selected'; } ?>><?php esc_html_e( 'Show on all devices', 'socialize' ); ?></option>
							<option value="gp-hide-on-mobile"<?php if ( $value == 'gp-hide-on-mobile' ) { echo 'selected'; } ?>><?php esc_html_e( 'Only show on larger devices', 'socialize' ); ?></option>
							<option value="gp-show-on-mobile"<?php if ( $value == 'gp-show-on-mobile' ) { echo 'selected'; } ?>><?php esc_html_e( 'Only show on smaller devices', 'socialize' ); ?></option>
						</select>		
						<br/><span class="description"><?php esc_html_e( 'Choose what devices to show this link on.', 'socialize' ); ?></span>	
					</p>
				<?php } ?>

				<?php if ( $_key == 'gp-user-display' ) { ?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $label ); ?></label> 
						<br/><select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
							<option value="gp-show-all"<?php if ( $value == 'gp-show-all' ) { echo 'selected'; } ?>><?php esc_html_e( 'Show for all users', 'socialize' ); ?></option>
							<option value="gp-show-logged-in"<?php if ( $value == 'gp-show-logged-in' ) { echo 'selected'; } ?>><?php esc_html_e( 'Show for logged in users only', 'socialize' ); ?></option>
							<option value="gp-show-logged-out"<?php if ( $value == 'gp-show-logged-out' ) { echo 'selected'; } ?>><?php esc_html_e( 'Show for logged out users only', 'socialize' ); ?></option>
						</select>	
						<br/><span class="description"><?php esc_html_e( 'Choose what users see this link.', 'socialize' ); ?></span>
					</p>		
				<?php } ?>

				<?php if ( $_key == 'gp-hide-nav-label' ) { ?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $label ); ?></label> <input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="gp-hide-nav-label"<?php if ( $value == 'gp-hide-nav-label' ) { echo 'checked'; } ?>>
						<br/><span class="description"><?php esc_html_e( 'Hide the navigation label (for example if you only want to show the icon).', 'socialize' ); ?></span>
					</p>		
				<?php } ?>
								
				<?php if ( $_key == 'gp-content' && $depth >= 1 ) { ?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $label ); ?></label>
						<br/><select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
							<option value="gp-menu-link"<?php if ( $value == 'gp-menu-link' ) { echo 'selected'; } ?>><?php esc_html_e( 'Menu Link', 'socialize' ); ?></option>
							<option value="gp-menu-text"<?php if ( $value == 'gp-menu-text' ) { echo 'selected'; } ?>><?php esc_html_e( 'Menu Text', 'socialize' ); ?></option>
							<option value="gp-menu-image"<?php if ( $value == 'gp-menu-image' ) { echo 'selected'; } ?>><?php esc_html_e( 'Menu Image', 'socialize' ); ?></option>
							<option value="gp-menu-header"<?php if ( $value == 'gp-menu-header' ) { echo 'selected'; } ?>><?php esc_html_e( 'Menu Header', 'socialize' ); ?></option>
						</select>		
						<br/><span class="description"><?php esc_html_e( 'Choose to replace the menu link with text or an image. Add your text to "Navigation Label" text field or image URL to the "URL" text field.', 'socialize' ); ?></span>	
					</p>
				<?php } ?>			
								
			<?php
		endforeach;
	}


	/**
	 * Add our fields to the screen options toggle
	 *
	 * @param array $columns Menu item columns
	 * @return array
	 */
	public static function _columns( $columns ) {
		$columns = array_merge( $columns, self::$fields );

		return $columns;
	}
}
Ghostpool_Menu_Item_Custom_Fields_Options::init();