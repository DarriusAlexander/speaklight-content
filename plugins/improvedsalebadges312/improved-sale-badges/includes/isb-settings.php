<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class WC_Isb_Settings {

		public static $presets;
		public static $isb_style;
		public static $isb_style_special;
		public static $isb_color;
		public static $isb_position;

		public static function init() {

			self::$isb_style = array(
				'isb_style_arrow' => 'Arrow Down CSS',
				'isb_style_arrow_alt' => 'Arrow Down Alternative CSS',
				'isb_style_basic' => 'Aliexpress Style CSS',
				'isb_style_basic_alt' => 'Aliexpress Style Alternative CSS',
				'isb_style_inline' => 'Inline CSS',
				'isb_style_plain' => 'Plain CSS',
				'isb_style_pop' => 'Pop SVG',
				'isb_style_pop_round' => 'Pop Round SVG',
				'isb_style_fresh' => 'Fresh SVG',
				'isb_style_round' => 'Round Triangle SVG',
				'isb_style_tag' => 'Tag SVG',
				'isb_style_xmas_1' => 'Bonus - Christmas 1 SVG',
				'isb_style_xmas_2' => 'Bonus - Christmas 2 SVG',
				'isb_style_ribbon' => 'Ribbon FULL SVG',
				'isb_style_vintage' => 'Vintage IMG',
				'isb_style_pure' => 'Pure CSS',
				'isb_style_modern' => 'Modern CSS',
				'isb_style_transparent' => 'Transparent CSS',
				'isb_style_transparent_2' => 'Transparent #2 CSS',
				'isb_style_random_squares' => 'Random Squares SVG',
				'isb_style_fresh_2' => 'Fresh #2 SVG',
				'isb_style_valentine' => 'Valentine SVG',
				'isb_style_cool' => 'Cool SVG',
				'isb_style_triangle' => 'Triangle SVG',
				'isb_style_eu' => 'EU Elegant CSS',
				'isb_style_eu_2' => 'EU Round CSS',
				'isb_style_eu_3' => 'EU On Side CSS',
				'isb_style_candy' => 'Candy SVG',
				'isb_style_candy_arrow' => 'Candy Arrow SVG',
				'isb_style_cloud' => 'Cloud SVG',
				'isb_style_shopkit' => 'ShopKit SVG',
				'isb_style_responsive_1' => 'Responsive - Square',
				'isb_style_responsive_2' => 'Responsive - Star',
				'isb_style_responsive_3' => 'Responsive - Badge',
				'isb_style_responsive_4' => 'Responsive - Upside Badge',
				'isb_style_responsive_5' => 'Responsive - Pop',
				'isb_style_responsive_6' => 'Responsive - Round Square',
				'isb_style_responsive_7' => 'Responsive - Buzz',
				'isb_style_responsive_8' => 'Responsive - Circle',
				'isb_style_responsive_9' => 'Responsive - Shake',
				'isb_style_responsive_10' => 'Responsive - Shake Line'
			);

			self::$isb_style_special = array(
				'isb_special_plain' => 'Plain CSS',
				'isb_special_arrow' => 'Arrow CSS',
				'isb_special_bigbadge' => 'Big Badge CSS',
				'isb_special_ribbon' => 'Ribbon SVG'
			);

			self::$isb_color = array(
				'isb_avada_green' => 'Avada Green',
				'isb_green' => 'Green',
				'isb_orange' => 'Orange',
				'isb_pink' => 'Pink',
				'isb_red' => 'Pale Red',
				'isb_yellow' => 'Golden Yellow',
				'isb_tirq' => 'Turquoise',
				'isb_brown' => 'Brown',
				'isb_plumb' => 'Plumb',
				'isb_marine' => 'Marine',
				'isb_dark_orange' => 'Dark Orange',
				'isb_fuschia' => 'Fuschia',
				'isb_sky' => 'Sky',
				'isb_ocean' => 'Ocean',
				'isb_regular_gray' => 'Regular Gray',
				'isb_summer_1' => 'Summer Pallete #1',
				'isb_summer_2' => 'Summer Pallete #2',
				'isb_summer_3' => 'Summer Pallete #3',
				'isb_summer_4' => 'Summer Pallete #4',
				'isb_summer_5' => 'Summer Pallete #5',
				'isb_trending_1' => 'Trending Pallete #1',
				'isb_trending_2' => 'Trending Pallete #2',
				'isb_trending_3' => 'Trending Pallete #3',
				'isb_trending_4' => 'Trending Pallete #4',
				'isb_trending_5' => 'Trending Pallete #5',
				'isb_trending_6' => 'Trending Pallete #6',
				'isb_trending_7' => 'Trending Pallete #7',
				'isb_trending_8' => 'Trending Pallete #8',
				'isb_trending_9' => 'Trending Pallete #9',
				'isb_sk_material' => 'ShopKit Material',
				'isb_sk_flat' => 'ShopKit Flat',
				'isb_sk_creative' => 'ShopKit Creative',
			);

			self::$isb_position = array(
				'isb_left' => esc_html__( 'Left', 'isbwoo' ),
				'isb_right'=> esc_html__( 'Right', 'isbwoo' )
			);

			//add_filter( 'svx_plugins_settings', __CLASS__ . '::get_settings', 50 );
			add_action( 'admin_enqueue_scripts', __CLASS__ . '::isb_scripts', 9 );
			add_action( 'wp_ajax_isb_respond', __CLASS__ . '::isb_respond' );


			global $isb_set;

			add_action( 'woocommerce_product_write_panel_tabs', __CLASS__ . '::isb_add_product_tab' );
			add_action( 'woocommerce_product_data_panels', __CLASS__ . '::isb_product_tab' );
			add_action( 'save_post', __CLASS__ . '::isb_product_save' );

		}

		public static function isb_scripts( $hook ) {

			$init = false;

			if ( isset($_GET['page'], $_GET['tab']) && ($_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'improved_badges' ) {
				$init = true;
			}

			if ( $hook == 'post-new.php' || $hook == 'post.php' && get_option( 'wc_settings_isb_overrides', 'no' ) == 'yes' ) {
				$init = true;
			}

			if ( $init === true ) {

				//wp_enqueue_style( 'isb-style', Wcmnisb()->plugin_url() . '/assets/css/admin' . ( is_rtl() ? '-rtl' : '' ) . '.css', false, WC_Improved_Sale_Badges_Init::$version );
				wp_enqueue_style( 'isb-style', Wcmnisb()->plugin_url() . '/assets/css/admin' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', false, WC_Improved_Sale_Badges_Init::$version );

				wp_enqueue_script( 'isb-admin', Wcmnisb()->plugin_url() . '/assets/js/admin.js', array( 'jquery' ), WC_Improved_Sale_Badges_Init::$version, true );

				$curr_args = array(
					'ajax' => admin_url( 'admin-ajax.php' ),
				);

				wp_localize_script( 'isb-admin', 'isb', $curr_args );

			}

		}

		public static function isb_preview( $field ) {

			$presets = get_option( 'wcmn_isb_presets', array() );
			$badge_overrides = get_option( 'wcmn_isb_overrides', array() );

			$ready_tax = array(
				'product_tag' => esc_html__( 'Product Tag', 'isbwoo' ),
				'product_cat' => esc_html__( 'Product Category', 'isbwoo' )
			);

			ob_start();
			?>
			<select class="isb_presets">
				<option value=""><?php esc_html_e( 'Default', 'isbwoo' ); ?></option>
				<?php
					if ( !empty( $presets ) ) {
						foreach ( $presets as $k2 => $v1 ) {
					?>
							<option value="<?php echo $k2; ?>"><?php echo $v1; ?></option>
					<?php
						}
					}
				?>
			</select>
		<?php
			$presetsHTML = ob_get_clean();
		?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
				</th>
				<td class="forminp forminp-<?php echo sanitize_title( $field['type'] ) ?>">
					<div id="isb_preview">
				<?php
					global $isb_set;

					$isb_set['style'] = ( isset( $_POST['isb_style'] ) ? $_POST['isb_style'] : get_option( 'wc_settings_isb_style', 'isb_style_shopkit' ) );
					$isb_set['color'] = ( isset( $_POST['isb_color'] ) ? $_POST['isb_color'] : get_option( 'wc_settings_isb_color', 'isb_sk_material' ) );
					$isb_set['position'] = ( isset( $_POST['isb_position'] ) ? $_POST['isb_position'] : get_option( 'wc_settings_isb_position', 'isb_left' ) );
					$isb_set['special'] = ( isset( $_POST['isb_special'] ) ? $_POST['isb_special'] : get_option( 'wc_settings_isb_special', '' ) );
					$isb_set['special_text'] = ( isset( $_POST['isb_special_text'] ) ? $_POST['isb_special_text'] : get_option( 'wc_settings_isb_special_text', '' ) );

					$isb_price['type'] = 'simple';
					$isb_price['id'] = get_the_ID();
					$isb_price['regular'] = 32;
					$isb_price['sale'] = 27;
					$isb_price['difference'] = $isb_price['regular'] - $isb_price['sale'];
					$isb_price['percentage'] = round( ( $isb_price['regular'] - $isb_price['sale'] ) * 100 / $isb_price['regular'] );

					$isb_curr_set = $isb_set;


					if ( isset( $isb_set['special'] ) && $isb_set['special'] !== '' ) {
						$isb_class = $isb_set['special'] . ' ' . $isb_set['color'] . ' ' . $isb_set['position'];
						$include = Wcmnisb()->plugin_path() . '/includes/specials/' . $isb_curr_set['special'] . '.php';
					}
					else {
						$isb_class = $isb_set['style'] . ' ' . $isb_set['color'] . ' ' . $isb_set['position'];
						$include = Wcmnisb()->plugin_path() . '/includes/styles/' . $isb_curr_set['style'] . '.php';
					}

					if ( file_exists ( $include ) ) {
						include( $include );
					}
				?>
					</div>
					<div id="isb_controls">
						<select id="isb-presets" class="isb_filter_presets">
							<option value=""><?php esc_html_e( 'Default', 'isbwoo' ); ?></option>
							<?php
								foreach ( $presets as $k => $v ) {
								?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php
								}
							?>
						</select>
						<span id="isb-save-preset" class="button-primary"><?php esc_html_e( 'Save Preset', 'isbwoo' ); ?></span>
						<span id="isb-save-default" class="button-primary"><?php esc_html_e( 'Save as Default', 'isbwoo' ); ?></span>
						<span id="isb-load-preset" class="button"><?php esc_html_e( 'Load Preset', 'isbwoo' ); ?></span>
						<span id="isb-delete-preset" class="button"><?php esc_html_e( 'Delete Preset', 'isbwoo' ); ?></span>
					</div>
					<div id="isb_overrides">
						<h4><?php esc_html_e( 'Featured Badge', 'isbwoo' ); ?></h4>
						<p class="featured">
							<select class="isb_presets">
								<option value=""><?php esc_html_e( 'Default', 'isbwoo' ); ?></option>
								<?php
									if ( !empty( $presets ) ) {
										foreach ( $presets as $k2 => $v1 ) {
									?>
											<option value="<?php echo $k2; ?>"<?php echo isset( $badge_overrides['featured'] ) && $badge_overrides['featured'] == $k2 ? ' selected="selected"' : ''; ?>><?php echo $v1; ?></option>
									<?php
										}
									}
								?>
							</select>
						</p>
						<hr />
						<h4><?php esc_html_e( 'New Product Badge', 'isbwoo' ); ?></h4>
						<p class="new">
							<?php esc_html_e( 'Expire in', 'isbwoo' ); ?> <input type="number" min="1" class="isb_days" value="<?php echo isset( $badge_overrides['new'] ) ? $badge_overrides['new']['days'] : ''; ?>" /> <?php esc_html_e( 'days', 'isbwoo' ); ?> 
							<select class="isb_presets">
								<option value=""><?php esc_html_e( 'Default', 'isbwoo' ); ?></option>
								<?php
									if ( !empty( $presets ) ) {
										foreach ( $presets as $k2 => $v1 ) {
									?>
											<option value="<?php echo $k2; ?>"<?php echo isset( $badge_overrides['new']['preset'] ) && $badge_overrides['new']['preset'] == $k2 ? ' selected="selected"' : ''; ?>><?php echo $v1; ?></option>
									<?php
										}
									}
								?>
							</select>
						</p>
						<hr />
					<?php
						foreach ( $ready_tax as $k => $v ) {
							$dropdown = wp_dropdown_categories( array( 'hide_empty' => 0, 'echo' => 0, 'hierarchical' => ( is_taxonomy_hierarchical( $k ) ? 1 : 0 ), 'class' => 'isb_tax_select', 'depth' => 0, 'taxonomy' => $k, 'hide_if_empty' => true, 'value_field' => 'id', ) );
							if ( empty( $dropdown ) ) {
								continue;
							}
					?>
							<h4>
							<?php
								echo $v . ' ' . esc_html__( 'Badges', 'isbwoo' );
							?>
							</h4>
							<p class="<?php echo $k; ?>">
								<?php echo $dropdown; ?>
								<?php echo $presetsHTML; ?>
								<span class="button isb_add"><?php esc_html_e( 'Add Override', 'isbwoo' ); ?></span>
								<span class="isb_overrides">
							<?php
								if ( isset( $badge_overrides[$k] ) ) {
									foreach ( $badge_overrides[$k] as $k1 => $v2 ) {
										if ( !array_key_exists( $k, $ready_tax ) || !isset( $presets[$v2] ) ) {
											continue;
										}
									?>
										<span class="isb_override">
										<?php
											$term = get_term_by( 'id', $k1, $k );
											echo esc_html__( 'Term', 'isbwoo' ) . ' : <span class="isb_id" data-id="' . $k1 . '">' . $term->name . '</span>'; ?> <?php echo esc_html__( 'Preset', 'isbwoo' ) . ' : <span class="isb_preset" data-preset="' . $v2 . '">' . $v2 . '</span>';
										?>
											<span class="isb_remove"><?php esc_html_e( 'Remove', 'isbwoo' ); ?></span>
										</span>
									<?php
									}
								}
							?>
								</span>
							</p>
							<hr />
					<?php
						}
					?>
						<p class="isb_save_overrides">
							<span id="isb_save_overrides" class="button-primary"><?php esc_html_e( 'Save Overrides', 'isbwoo' ); ?></span>
						</p>
					</div>
				</td>
			</tr>
		<?php
		}

		public static function get_settings() {

			$plugins['improved_badges'] = array(
				'slug' => 'improved_badges',
				'name' => esc_html__( 'Improved Badges for WooCommerce', 'isbwoo' ),
				'desc' => esc_html__( 'Settings page for Improved Badges for WooCommerce!', 'isbwoo' ),
				'link' => 'https://mihajlovicnenad.com/product/improved-badges-woocommerce/',
				'ref' => array(
					'name' => esc_html__( 'More plugins and themes?', 'isbwoo' ),
					'url' => 'https://mihajlovicnenad.com/'
				),
				'doc' => array(
					'name' => esc_html__( 'Documentation and Plugin Guide', 'isbwoo' ),
					'url' => 'https://mihajlovicnenad.com/improved-sale-badges/documentation-and-guide/'
				),
				'sections' => array(
					'badges' => array(
						'name' => esc_html__( 'Default Badge', 'isbwoo' ),
						'desc' => esc_html__( 'Default Badges Options', 'isbwoo' ),
					),
					'presets' => array(
						'name' => esc_html__( 'Badge Presets', 'isbwoo' ),
						'desc' => esc_html__( 'Badge Presets Options', 'isbwoo' ),
					),
					'manager' => array(
						'name' => esc_html__( 'Badge Manager', 'isbwoo' ),
						'desc' => esc_html__( 'Manager Options', 'isbwoo' ),
					),
					'timers' => array(
						'name' => esc_html__( 'Timer/Countdowns', 'isbwoo' ),
						'desc' => esc_html__( 'Timer/Countdowns Options', 'isbwoo' ),
					),
					'installation' => array(
						'name' => esc_html__( 'Installation', 'isbwoo' ),
						'desc' => esc_html__( 'Installation Options', 'isbwoo' ),
					),
					'license' => array(
						'name' => esc_html__( 'Plugin License', 'isbwoo' ),
						'desc' => esc_html__( 'License Options', 'isbwoo' ),
					),
				),
				'settings' => array(

					'wc_settings_isb_preview' => array(
						'name'    => esc_html__( 'Preview', 'isbwoo' ),
						'type'    => 'hidden',
						'desc'    => esc_html__( 'Current badge preview', 'isbwoo' ),
						'id'      => 'wc_settings_isb_preview',
						'autoload' => false,
						'section' => 'badges',
						'class'   => 'isb_preview'
					),

					'wc_settings_isb_style' => array(
						'name'    => esc_html__( 'Style', 'isbwoo' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Select sale badge style', 'isbwoo' ),
						'id'      => 'wc_settings_isb_style',
						'default' => 'isb_style_shopkit',
						'options' => self::$isb_style,
						'autoload' => false,
						'section' => 'badges'
					),
					'wc_settings_isb_color' => array(
						'name'    => esc_html__( 'Color', 'isbwoo' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Select sale badge color', 'isbwoo' ),
						'id'      => 'wc_settings_isb_color',
						'default'     => 'isb_sk_material',
						'options' => self::$isb_color,
						'autoload' => false,
						'section' => 'badges'
					),
					'wc_settings_isb_position' => array(
						'name'    => esc_html__( 'Position', 'isbwoo' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Select sale badge position', 'isbwoo' ),
						'id'      => 'wc_settings_isb_position',
						'default'     => 'isb_left',
						'options' => self::$isb_position,
						'autoload' => false,
						'section' => 'badges'
					),
					'wc_settings_isb_special' => array(
						'name'    => esc_html__( 'Special', 'isbwoo' ),
						'type'    => 'select',
						'desc'    => esc_html__( 'Select special badge', 'isbwoo' ),
						'id'      => 'wc_settings_isb_special',
						'default'     => '',
						'options' => array_merge( array( '' => esc_html__( 'None', 'isbwoo' ) ), self::$isb_style_special ),
						'autoload' => false,
						'section' => 'badges'
					),
					'wc_settings_isb_special_text' => array(
						'name'    => esc_html__( 'Special Text', 'isbwoo' ),
						'type'    => 'textarea',
						'desc'    => esc_html__( 'Enter special badge text', 'isbwoo' ),
						'id'      => 'wc_settings_isb_special_text',
						'default'     => 'Text',
						'autoload' => false,
						'section' => 'badges'
					),

					'wcmn_isb_presets' => array(
						'name' => esc_html__( 'Presets Manager', 'isbwoo' ),
						'type' => 'list',
						'id'   => 'wcmn_isb_presets',
						'desc' => esc_html__( 'Add badge presets using the Presets Manager', 'isbwoo' ),
						'autoload' => false,
						'section' => 'presets',
						'title' => esc_html__( 'Preset Name', 'isbwoo' ),
						'options' => 'list',
						'ajax_options' => 'ajax:wp_options:_wcmn_isb_preset_%NAME%',
						'settings' => array(
							'preview' => array(
								'name'    => esc_html__( 'Preview', 'isbwoo' ),
								'type'    => 'hidden',
								'desc'    => esc_html__( 'Current badge preview', 'isbwoo' ),
								'id'      => 'preview',
								'class'   => 'isb_preview'
							),
							'name' => array(
								'name' => esc_html__( 'Preset Name', 'isbwoo' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter preset name', 'isbwoo' ),
								'default' => '',
							),
							'style' => array(
								'name'    => esc_html__( 'Style', 'isbwoo' ),
								'type'    => 'select',
								'desc'    => esc_html__( 'Select sale badge style', 'isbwoo' ),
								'id'      => 'style',
								'default' => 'isb_style_shopkit',
								'options' => self::$isb_style,
							),
							'color' => array(
								'name'    => esc_html__( 'Color', 'isbwoo' ),
								'type'    => 'select',
								'desc'    => esc_html__( 'Select sale badge color', 'isbwoo' ),
								'id'      => 'color',
								'default'     => 'isb_sk_material',
								'options' => self::$isb_color,
							),
							'position' => array(
								'name'    => esc_html__( 'Position', 'isbwoo' ),
								'type'    => 'select',
								'desc'    => esc_html__( 'Select sale badge position', 'isbwoo' ),
								'id'      => 'position',
								'default'     => 'isb_left',
								'options' => self::$isb_position,
							),
							'special' => array(
								'name'    => esc_html__( 'Special', 'isbwoo' ),
								'type'    => 'select',
								'desc'    => esc_html__( 'Select special badge', 'isbwoo' ),
								'id'      => 'special',
								'default'     => '',
								'options' => array_merge( array( '' => esc_html__( 'None', 'isbwoo' ) ), self::$isb_style_special ),
							),
							'special_text' => array(
								'name'    => esc_html__( 'Special Text', 'isbwoo' ),
								'type'    => 'textarea',
								'desc'    => esc_html__( 'Enter special badge text', 'isbwoo' ),
								'id'      => 'special_text',
								'default'     => 'Text',
							),
						),
					),

					'wcmn_isb_overrides' => array(
						'name' => esc_html__( 'Badge Overrides', 'isbwoo' ),
						'type' => 'hidden',
						'id'   => 'wcmn_isb_overrides',
						'desc' => esc_html__( 'Set badge overrides', 'isbwoo' ),
						'autoload' => false,
						'section' => 'hidden',
					),

					'_wcmn_expire_in' => array(
						'name' => esc_html__( 'New Badge Period', 'isbwoo' ),
						'type' => 'number',
						'id'   => '_wcmn_expire_in',
						'desc' => esc_html__( 'Set new product expire in period (days)', 'isbwoo' ),
						'autoload' => false,
						'section' => 'manager',
					),
					'_wcmn_expire_in_preset' => array(
						'name' => esc_html__( 'New Badge Preset', 'isbwoo' ),
						'type' => 'select',
						'id'   => '_wcmn_expire_in_preset',
						'desc' => esc_html__( 'Set new product badge preset', 'isbwoo' ),
						'autoload' => false,
						'section' => 'manager',
						'options' => 'read:wcmn_isb_presets'
					),

					'_wcmn_featured_badge' => array(
						'name' => esc_html__( 'Featured Badge', 'isbwoo' ),
						'type' => 'select',
						'id'   => '_wcmn_featured_badge',
						'desc' => esc_html__( 'Set featured badge', 'isbwoo' ),
						'autoload' => false,
						'section' => 'manager',
						'options' => 'read:wcmn_isb_presets'
					),

					'_wcmn_tags' => array(
						'name' => esc_html__( 'Tag Badges', 'isbwoo' ),
						'type' => 'list',
						'id'   => '_wcmn_tags',
						'desc' => esc_html__( 'Add tag badge presets', 'isbwoo' ),
						'autoload' => false,
						'section' => 'manager',
						'title' => esc_html__( 'Name', 'isbwoo' ),
						'options' => 'list',
						'default' => array(),
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Name', 'isbwoo' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter override name', 'isbwoo' ),
								'default' => '',
							),
							'term' => array(
								'name' => esc_html__( 'Term', 'isbwoo' ),
								'type' => 'select',
								'id'   => 'term',
								'desc' => esc_html__( 'Set override term', 'isbwoo' ),
								'options' => 'ajax:taxonomy:product_tag:has_none',
								'default' => ''
							),
							'preset' => array(
								'name' => esc_html__( 'Preset', 'isbwoo' ),
								'type' => 'select',
								'id'   => 'preset',
								'desc' => esc_html__( 'Set override preset', 'isbwoo' ),
								'options' => 'read:wcmn_isb_presets',
								'default' => ''
							),
						),
					),

					'_wcmn_categories' => array(
						'name' => esc_html__( 'Category Badges', 'isbwoo' ),
						'type' => 'list',
						'id'   => '_wcmn_categories',
						'desc' => esc_html__( 'Add category badge presets', 'isbwoo' ),
						'autoload' => false,
						'section' => 'manager',
						'title' => esc_html__( 'Name', 'isbwoo' ),
						'options' => 'list',
						'default' => array(),
						'settings' => array(
							'name' => array(
								'name' => esc_html__( 'Name', 'isbwoo' ),
								'type' => 'text',
								'id' => 'name',
								'desc' => esc_html__( 'Enter override name', 'isbwoo' ),
								'default' => '',
							),
							'term' => array(
								'name' => esc_html__( 'Term', 'isbwoo' ),
								'type' => 'select',
								'id'   => 'term',
								'desc' => esc_html__( 'Set override term', 'isbwoo' ),
								'autoload' => false,
								'section' => 'manager',
								'options' => 'ajax:taxonomy:product_cat:has_none',
								'default' => ''
							),
							'preset' => array(
								'name' => esc_html__( 'Preset', 'isbwoo' ),
								'type' => 'select',
								'id'   => 'preset',
								'desc' => esc_html__( 'Set override preset', 'isbwoo' ),
								'autoload' => false,
								'section' => 'manager',
								'options' => 'read:wcmn_isb_presets',
								'default' => ''
							),
						),
					),

					'wc_settings_isb_overrides' => array(
						'name'    => esc_html__( 'Single Product Badges', 'isbwoo' ),
						'type'    => 'checkbox',
						'desc'    => esc_html__( 'Enable custom badge override for each product.', 'isbwoo' ),
						'id'      => 'wc_settings_isb_overrides',
						'default'     => 'no',
						'autoload' => false,
						'section' => 'installation'
					),
					'wc_settings_isb_template_overrides' => array(
						'name' => esc_html__( 'Use Tempalte Overrides', 'isbwoo' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'This is the default installation when checked, sale-flash.php template will be replaced with the plugin badge. If you enter a custom action below, the entered action will be used to output the plugin badge in the appropriate place in your theme.', 'isbwoo' ),
						'id'   => 'wc_settings_isb_template_overrides',
						'default' => 'yes',
						'autoload' => true,
						'section' => 'installation'
					),
					'wc_settings_isb_archive_action' => array(
						'name' => esc_html__( 'Shop Init Action', 'isbwoo' ),
						'type' => 'text',
						'desc' => esc_html__( 'Use custom initialization action for Shop/Product Archive Pages. Use actions initiated in your content-single-product.php template. Please enter action name in following format action_name:priority', 'isbwoo' ) . ' ( default: woocommerce_before_shop_loop_item:10 )',
						'id'   => 'wc_settings_isb_archive_action',
						'default' => '',
						'autoload' => true,
						'section' => 'installation'
					),
					'wc_settings_isb_single_action' => array(
						'name' => esc_html__( 'Single Product Init Action', 'isbwoo' ),
						'type' => 'text',
						'desc' => esc_html__( 'Use custom initialization action for Single Product Pages. Use actions initiated in your content-single-product.php template. Please enter action name in following format action_name:priority', 'isbwoo' ) . ' ( default: woocommerce_before_single_product_summary:15 )',
						'id'   => 'wc_settings_isb_single_action',
						'default' => '',
						'autoload' => true,
						'section' => 'installation'
					),
					'wc_settings_isb_force_scripts' => array(
						'name' => esc_html__( 'Plugin Scripts', 'isbwoo' ),
						'type' => 'checkbox',
						'desc' => esc_html__( 'Check this option to enable plugin scripts in all pages. This option fixes issues in Quick Views.', 'isbwoo' ),
						'id'   => 'wc_settings_isb_force_scripts',
						'default' => 'no',
						'autoload' => true,
						'section' => 'installation'
					),

					'wc_settings_isb_timer' => array(
						'name' => esc_html__( 'Disable Timers', 'isbwoo' ),
						'type' => 'multiselect',
						'desc' => esc_html__( 'Select sale timers to disable.', 'isbwoo' ),
						'id'   => 'wc_settings_isb_timer',
						'options' => array(
							'start' => esc_html__( 'Starting Sale', 'isbwoo' ),
							'end' => esc_html__( 'Ending Sale', 'isbwoo' )
						),
						'default' => array(),
						'autoload' => false,
						'section' => 'timers',
						'class' => 'svx-selectize'
					),

					'wc_settings_isb_timer_adjust' => array(
						'name' => esc_html__( 'Adjust Timer', 'isbwoo' ),
						'type' => 'number',
						'desc' => esc_html__( 'Adjust sale timer countdown clock. Option is set in minutes.', 'isbwoo' ),
						'id'   => 'wc_settings_isb_timer_adjust',
						'default' => '',
						'autoload' => false,
						'section' => 'timers'
					),

					'wc_settings_isb_update_code' => array(
						'name'    => esc_html__( 'Purchase Code', 'isbwoo' ),
						'type'    => 'text',
						'desc'    => esc_html__( 'Enter your purchase code to get automatic updates directly in the WP Dashboard!', 'isbwoo' ),
						'id'      => 'wc_settings_isb_update_code',
						'default'     => '',
						'autoload' => true,
						'section' => 'license'
					),

				)
			);

			foreach ( $plugins['improved_badges']['settings'] as $k => $v ) {
				$get = isset( $v['translate'] ) ? $v['id'] . SevenVX()->language() : $v['id'];
				$std = isset( $v['default'] ) ?  $v['default'] : '';
				$set = ( $set = get_option( $get, false ) ) === false ? $std : $set;
				$plugins['improved_badges']['settings'][$k]['val'] = SevenVX()->stripslashes_deep( $set );
			}

			if ( substr_count( $plugins['improved_badges']['settings']['wc_settings_isb_update_code']['val'], '-' ) == 4 ) {
				$plugins['improved_badges']['license'] = esc_url( home_url() );
			}

			return apply_filters( 'wc_isb_settings', $plugins );

		}

		public static function call_badge() {

			if ( isset( $_POST['data']['isb_preset'] ) ) {
				$preset = self::get_preset( $_POST['data']['isb_preset'] );
				if ( !empty( $preset ) ) {
					$isb_set = array_merge(
						array( 'type' => 'simple'),
						$preset[0]
					);
				}
			}

			if ( !isset( $isb_set ) ) {
				$isb_set = array(
					'style' => isset( $_POST['data']['isb_style'] ) && $_POST['data']['isb_style'] !== '' ? $_POST['data']['isb_style'] : get_option( 'wc_settings_isb_style', 'isb_style_shopkit' ),
					'color' => isset( $_POST['data']['isb_color'] ) && $_POST['data']['isb_color'] !== '' ? $_POST['data']['isb_color'] : get_option( 'wc_settings_isb_color', 'isb_sk_material' ),
					'position' => isset( $_POST['data']['isb_position'] ) && $_POST['data']['isb_position'] !== '' ? $_POST['data']['isb_position'] : get_option( 'wc_settings_isb_position', 'isb_left' ),
					'special' => isset( $_POST['data']['isb_special'] ) ? $_POST['data']['isb_special'] : get_option( 'wc_settings_isb_special', '' ),
					'special_text' => isset( $_POST['data']['isb_special_text'] ) ? $_POST['data']['isb_special_text'] : get_option( 'wc_settings_isb_special_text', '' ),
					'type' => 'simple'
				);
			}

			$isb_price['id'] = 1;
			$isb_price['type'] = 'simple';
			$isb_price['regular'] = 32;
			$isb_price['sale'] = 27;
			$isb_price['difference'] = $isb_price['regular'] - $isb_price['sale'];
			$isb_price['percentage'] = round( ( $isb_price['regular'] - $isb_price['sale'] ) * 100 / $isb_price['regular'] );
			$isb_price['time'] = '2:04:50';
			$isb_price['time_mode'] = 'end';

			if ( is_array( $isb_set ) ) {
				$isb_class = ( isset( $isb_set['special'] ) && $isb_set['special'] !== '' ? $isb_set['special'] : $isb_set['style'] ) . ' ' . $isb_set['color'] . ' ' . $isb_set['position'];
			}
			else {
				$isb_class = 'isb_style_shopkit isb_sk_material isb_left';
			}

			$isb_curr_set = $isb_set;

			if ( isset( $isb_set['special'] ) && $isb_set['special'] !== '' ) {
				$include = Wcmnisb()->plugin_path() . '/includes/specials/' . $isb_set['special'] . '.php';
			}
			else {
				$include = Wcmnisb()->plugin_path() . '/includes/styles/' . $isb_set['style'] . '.php';
			}
			

			ob_start();

			if ( file_exists( $include ) ) {
				include( $include );
			}

			$html = ob_get_clean();

			die($html);
			exit;

		}

		public static function isb_respond() {
			if ( !isset( $_POST['data'] ) ) {
				die();
				exit;
			}

			self::call_badge();

		}

		public static function isb_add_product_tab() {
			if ( get_option( 'wc_settings_isb_overrides', 'no' ) == 'yes' ) {
				echo ' <li class="isb_tab"><a href="#isb_tab"><span>'. esc_html__('Sale Badges', 'isbwoo' ) .'</span></a></li>';
			}
		}

		public static function isb_product_tab() {
			if ( get_option( 'wc_settings_isb_overrides', 'no' ) == 'yes' ) {
				global $post, $isb_set;

				$curr_badge = get_post_meta( $post->ID, '_isb_settings' );

				$isb_set['preset'] = ( isset( $_POST['isb_preset'] ) ? $_POST['isb_preset'] : '' );
				$isb_set['style'] = ( isset( $_POST['isb_style'] ) ? $_POST['isb_style'] : get_option( 'wc_settings_isb_style', 'isb_style_shopkit' ) );
				$isb_set['color'] = ( isset( $_POST['isb_color'] ) ? $_POST['isb_color'] : get_option( 'wc_settings_isb_color', 'isb_sk_material' ) );
				$isb_set['position'] = ( isset( $_POST['isb_position'] ) ? $_POST['isb_position'] : get_option( 'wc_settings_isb_position', 'isb_left' ) );
				$isb_set['special'] = ( isset( $_POST['isb_special'] ) ? $_POST['isb_special'] : get_option( 'wc_settings_isb_special', '' ) );
				$isb_set['special_text'] = ( isset( $_POST['isb_special_text'] ) ? $_POST['isb_special_text'] : get_option( 'wc_settings_isb_special_text', '' ) );

				$check_settings = array(
					'preset' => $isb_set['preset'],
					'style' => $isb_set['style'],
					'color' => $isb_set['color'],
					'position' => $isb_set['position'],
					'special' => $isb_set['special'],
					'special_text' => $isb_set['special_text']
				);

				if ( is_array( $curr_badge ) && isset( $curr_badge[0] ) ) {
					$curr_badge = $curr_badge[0];
					$isb_set = $curr_badge;
					foreach ( $check_settings as $k => $v ) {
						$curr_badge[$k] = ( isset( $curr_badge[$k] ) && $curr_badge[$k] !== '' ? $curr_badge[$k] : $v );
					}
				}
				else {
					$curr_badge = $check_settings;
				}

				$isb_curr_set = $curr_badge;

				if ( isset( $curr_badge['preset'] ) && $curr_badge['preset'] !== '' ) {
					$preset = self::get_preset( $curr_badge['preset'] );
					if ( !empty( $preset ) ) {
						$isb_curr_set = $preset[0];
					}
				}

			?>
				<div id="isb_tab" class="panel woocommerce_options_panel">

					<div class="options_group grouping basic">
						<span class="wc_settings_isb_title"><?php esc_html_e('Badge Settings', 'isbwoo' ); ?></span>
						<div id="isb_preview">
						<?php

							$isb_price['id'] = 1;
							$isb_price['type'] = 'simple';
							$isb_price['regular'] = 32;
							$isb_price['sale'] = 27;
							$isb_price['difference'] = $isb_price['regular'] - $isb_price['sale'];
							$isb_price['percentage'] = round( ( $isb_price['regular'] - $isb_price['sale'] ) * 100 / $isb_price['regular'] );
							$isb_price['time'] = '2:04:50';
							$isb_price['time_mode'] = 'end';

							if ( is_array($isb_curr_set) ) {
								$isb_class = ( $isb_curr_set['special'] !== '' ? $isb_curr_set['special'] : $isb_curr_set['style'] ) . ' ' . $isb_curr_set['color'] . ' ' . $isb_curr_set['position'];
							}
							else {
								$isb_class = 'isb_style_shopkit isb_sk_material isb_left';
							}

							if ( $isb_curr_set['special'] !== '' ) {
								$include = Wcmnisb()->plugin_path() . '/includes/specials/' . $isb_curr_set['special'] . '.php';
							}
							else {
								$include = Wcmnisb()->plugin_path() . '/includes/styles/' . $isb_curr_set['style'] . '.php';
							}

							if ( file_exists ( $include ) ) {
								include( $include );
							}

						?>
						</div>
						<p class="form-field isb_preset">
							<label for="wc_settings_isb_preset"><?php esc_html_e('Badge Preset', 'isbwoo' ); ?></label>
							<?php $presets = get_option( 'wcmn_isb_presets', array() ); ?>
							<select id="wc_settings_isb_preset" name="isb_preset_single" class="option select short">
								<option value=""<?php echo ( isset( $isb_set['preset'] ) && $isb_set['preset'] == '' ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'None', 'isbwoo' ); ?></option>
								<?php
									if ( !empty( $presets ) ) {
										foreach ( $presets as $k2 => $v1 ) {
									?>
											<option value="<?php echo $k2; ?>"<?php echo ( isset( $isb_set['preset'] ) && $isb_set['preset'] == $k2 ? ' selected="selected"' : '' ); ?>><?php echo $v1; ?></option>
									<?php
										}
									}
								?>
							</select>
						</p>
						<p class="form-field isb_style isb_no_preset">
							<label for="wc_settings_isb_style"><?php esc_html_e('Badge Style', 'isbwoo' ); ?></label>
							<select id="wc_settings_isb_style" name="isb_style_single" class="option select short">
								<option value=""<?php echo ( isset($isb_set['style']) ? ' selected="selected"' : '' ); ?>><?php esc_html_e('None', 'isb_woo' ); ?></option>
						<?php
							foreach ( self::$isb_style as $k => $v ) {
								printf('<option value="%1$s"%3$s>%2$s</option>', $k, $v, ( $isb_set['style'] == $k ? ' selected="selected"' : '' ) );
							}
						?>
							</select>
						</p>
						<p class="form-field isb_color isb_no_preset">
							<label for="wc_settings_isb_color"><?php esc_html_e('Badge Color', 'isbwoo' ); ?></label>
							<select id="wc_settings_isb_color" name="isb_color_single" class="option select short">
								<option value=""<?php echo ( isset($isb_set['color']) ? ' selected="selected"' : '' ); ?>><?php esc_html_e('None', 'isb_woo' ); ?></option>
						<?php
							foreach ( self::$isb_color as $k => $v ) {
								printf('<option value="%1$s"%3$s>%2$s</option>', $k, $v, ( $isb_set['color'] == $k ? ' selected="selected"' : '' ) );
							}
						?>
							</select>
						</p>
						<p class="form-field isb_position isb_no_preset">
							<label for="wc_settings_isb_position"><?php esc_html_e('Badge Position', 'isbwoo' ); ?></label>
							<select id="wc_settings_isb_position" name="isb_position_single" class="option select short">
								<option value=""<?php echo ( isset($isb_set['position']) ? ' selected="selected"' : '' ); ?>><?php esc_html_e('None', 'isb_woo' ); ?></option>
						<?php
							foreach ( self::$isb_position as $k => $v ) {
								printf('<option value="%1$s"%3$s>%2$s</option>', $k, $v, ( $isb_set['position'] == $k ? ' selected="selected"' : '' ) );
							}
						?>
							</select>
						</p>
						<p class="form-field isb_special_badge isb_no_preset">
							<label for="wc_settings_isb_special"><?php esc_html_e('Special Badge', 'isbwoo' ); ?></label>
							<select id="wc_settings_isb_special" name="isb_style_special" class="option select short">
								<option value=""<?php echo ( isset($isb_set['special']) ? ' selected="selected"' : '' ); ?>><?php esc_html_e('None', 'isb_woo' ); ?></option>
						<?php
							foreach ( self::$isb_style_special as $k => $v ) {
								printf('<option value="%1$s"%3$s>%2$s</option>', $k, $v, ( isset($isb_set['special']) && $isb_set['special'] == $k ? ' selected="selected"' : '' ) );
							}
						?>
							</select>
						</p>
						<p class="form-field isb_special_text isb_no_preset">
							<label for="wc_settings_isb_special_text"><?php esc_html_e('Special Badge Text', 'isbwoo' ); ?></label>
							<textarea id="wc_settings_isb_special_text" name="isb_style_special_text" class="option short"><?php echo ( isset( $isb_set['special_text'] ) ? $isb_set['special_text'] : '' ); ?></textarea>
						</p>
					</div>

				</div>
			<?php
			}
		}

		public static function isb_product_save( $curr_id ) {
			if ( get_option( 'wc_settings_isb_overrides', 'no' ) == 'yes' ) {
				$curr = array();

				if ( isset( $_POST['product-type'] ) ) {
					$curr = array(
						'preset' => ( isset($_POST['isb_preset_single']) ? $_POST['isb_preset_single'] : '' ),
						'style' => ( isset($_POST['isb_style_single']) ? $_POST['isb_style_single'] : '' ),
						'color' => ( isset($_POST['isb_color_single']) ? $_POST['isb_color_single'] : '' ),
						'position' => ( isset($_POST['isb_position_single']) ? $_POST['isb_position_single'] : '' ),
						'special' => ( isset($_POST['isb_style_special']) ? $_POST['isb_style_special'] : '' ),
						'special_text' => ( isset($_POST['isb_style_special_text']) ? $_POST['isb_style_special_text'] : '' )
					);
					update_post_meta( $curr_id, '_isb_settings', $curr );
				}
			}
		}

		public static function get_preset( $preset ) {

			if ( $preset == '' ) {
				return array();
			}

			$process = get_option( '_wcmn_isb_preset_' . $preset, array() );
			if ( isset( $process['name'] ) ) {
				return array( 0 => $process );
			}
			else {
				return array();
			}

		}

	}

	add_action( 'init', array( 'WC_Isb_Settings', 'init' ), 100 );
	if ( isset($_GET['page'], $_GET['tab']) && ($_GET['page'] == 'wc-settings' ) && $_GET['tab'] == 'improved_badges' ) {
		add_action( 'svx_plugins_settings', array( 'WC_Isb_Settings', 'get_settings' ), 50 );
	}

?>