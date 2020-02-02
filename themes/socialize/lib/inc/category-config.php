<?php
/**
 * Category options
 *
 * @since Socialize 2.9
 */
if ( ! function_exists( 'ghostpool_category_options' ) ) {
	function ghostpool_category_options() {

		global $ghostpool_cat_options;

		if ( ! is_array( $ghostpool_cat_options ) ) {
			$ghostpool_cat_options = array();
		}
				
		// Category Options	
		$ghostpool_cat_options[] = array( 
			'id'      => 'page_header',
			'name'    => esc_html__( 'Page Header', 'socialize' ),
			'desc'    => esc_html__( 'The page header on the page.', 'socialize' ),
			'type'    => 'select',
			'tax'     => array( 'category', 'post_tag', 'product_cat', 'product_tag', 'gp_portfolios' ),
			'options' => array( 
				'default' => esc_html__( 'Default', 'socialize' ), 
				'gp-standard-page-header' => esc_html__( 'Standard', 'socialize' ), 
				'gp-large-page-header' => esc_html__( 'Large', 'socialize' ), 
				'gp-fullwidth-page-header' => esc_html__( 'Fullwidth', 'socialize' ), 
				'gp-full-page-page-header' => esc_html__( 'Full Page', 'socialize' ),
			),
			'default' => 'default',
		);

		$ghostpool_cat_options[] = array( 
			'id'      => 'bg_image',
			'name'    => esc_html__( 'Page Header Background', 'socialize' ),
			'desc'    => esc_html__( 'The background of the page header. Enter an image URL that must be uploaded to the Media Library.', 'socialize' ),
			'type'    => 'text',
			'tax'     => array( 'category', 'post_tag', 'product_cat', 'product_tag', 'gp_portfolios' ),
			'default' => '',
		);

		$ghostpool_cat_options[] = array( 
			'id'      => 'layout',
			'name'    => esc_html__( 'Page Layout', 'socialize' ),
			'desc'    => esc_html__( 'The page header on the page.', 'socialize' ),
			'type'    => 'select',
			'tax'     => array( 'category', 'post_tag', 'product_cat', 'product_tag', 'gp_portfolios' ),
			'options' => array( 
				'default' => esc_html__( 'Default', 'socialize' ), 
				'gp-left-sidebar' => esc_html__( 'Left Sidebar', 'socialize' ), 
				'gp-right-sidebar' => esc_html__( 'Right Sidebar', 'socialize' ), 
				'gp-both-sidebars' => esc_html__( 'Both Sidebars', 'socialize' ), 
				'gp-no-sidebar' => esc_html__( 'No Sidebars', 'socialize' ), 
				'gp-fullwidth' => esc_html__( 'Fullwidth', 'socialize' ),
			),
			'default' => 'default',
		);

		$ghostpool_cat_options[] = array( 
			'id'      => 'left_sidebar',
			'name'    => esc_html__( 'Left Sidebar', 'socialize' ),
			'desc'    => esc_html__( 'The left sidebar to display.', 'socialize' ),
			'type'    => 'sidebars',
			'tax'     => array( 'category', 'post_tag', 'product_cat', 'product_tag', 'gp_portfolios' ),
			'options' => array( 
				'default' => esc_html__( 'Default', 'socialize' ),
			),
			'default' => 'default',
		);

		$ghostpool_cat_options[] = array( 
			'id'      => 'right_sidebar',
			'name'    => esc_html__( 'Right Sidebar', 'socialize' ),
			'desc'    => esc_html__( 'The right sidebar to display.', 'socialize' ),
			'type'    => 'sidebars',
			'tax'     => array( 'category', 'post_tag', 'product_cat', 'product_tag', 'gp_portfolios' ),
			'options' => array( 
				'default' => esc_html__( 'Default', 'socialize' ),
			),
			'default' => 'default',
		);

		$ghostpool_cat_options[] = array( 
			'id'      => 'format',
			'name'    => esc_html__( 'Format', 'socialize' ),
			'desc'    => esc_html__( 'The format to display the items in.', 'socialize' ),
			'type'    => 'select',
			'tax'     => array( 'category', 'post_tag' ),
			'options' => array( 
				'default' => esc_html__( 'Default', 'socialize' ), 
				'gp-blog-large' => esc_html__( 'Large', 'socialize' ), 
				'gp-blog-standard' => esc_html__( 'Standard', 'socialize' ), 
				'gp-blog-columns-1' => esc_html__( '1 Column', 'socialize' ),
				'gp-blog-columns-2' => esc_html__( '2 Columns', 'socialize' ), 
				'gp-blog-columns-3' => esc_html__( '3 Columns', 'socialize' ), 
				'gp-blog-columns-4' => esc_html__( '4 Columns', 'socialize' ), 
				'gp-blog-columns-5' => esc_html__( '5 Columns', 'socialize' ), 
				'gp-blog-columns-6' => esc_html__( '6 Columns', 'socialize' ), 
				'gp-blog-masonry' => esc_html__( 'Masonry', 'socialize' ), 
			),
			'default' => 'default',
		);

		$ghostpool_cat_options[] = array( 
			'id'      => 'format',
			'name'    => esc_html__( 'Format', 'socialize' ),
			'desc'    => esc_html__( 'The format to display the items in.', 'socialize' ),
			'type'    => 'select',
			'tax'     => array( 'gp_portfolios' ),
			'options' => array( 
				'default' => esc_html__( 'Default', 'socialize' ),
				'gp-portfolio-columns-2' => esc_html__( '2 Columns', 'socialize' ), 
				'gp-portfolio-columns-3' => esc_html__( '3 Columns', 'socialize' ), 
				'gp-portfolio-columns-4' => esc_html__( '4 Columns', 'socialize' ), 
				'gp-portfolio-columns-5' => esc_html__( '5 Columns', 'socialize' ), 
				'gp-portfolio-columns-6' => esc_html__( '6 Columns', 'socialize' ), 
				'gp-portfolio-masonry' => esc_html__( 'Masonry', 'socialize' ), 
			),
			'default' => 'default',
		); 
		
 	}
}
add_action( 'after_setup_theme', 'ghostpool_category_options', 11 );

// New category options 
if ( ! function_exists( 'ghostpool_add_tax_fields' ) ) {
	function ghostpool_add_tax_fields( $tag ) {		

		global $ghostpool_cat_options;
		
		// Get current screen
		$screen = get_current_screen();

		// Get category option
		if ( isset( $tag->term_id ) ) {
			$term_id = $tag->term_id;
			$term_meta = get_option( "taxonomy_$term_id" );
		} else {
			$term_meta = null;
		}
		
		// Run category options through filter to add custom options
		$options = apply_filters( 'gp_custom_category_options', $ghostpool_cat_options );

		foreach ( $options as $option ) {
		
			switch( $option['type'] ) {
			
				case 'select' :
				
					// Checking what category pages to show this option on
					$add_field = false;
					foreach ( $option['tax'] as $type ) {
						if ( $screen->taxonomy == $type ) {
							$add_field = true;
						}
					}

					if ( $add_field == true ) { ?>
		
						<div class="form-field">
							<label for="category-<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_attr( $option['name'] ); ?></label>
							<select id="gp_term_meta_<?php echo esc_attr( $option['id'] ); ?>" name="gp_term_meta[<?php echo esc_attr( $option['id'] ); ?>]">
								<?php foreach ( $option['options'] as $key => $value ) { ?>
									<?php if ( $term_meta[$option['id']] != '' ) { ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $term_meta[$option['id']] == $key ) { echo ' selected="selected"'; } ?>><?php echo esc_attr( $value ); ?></option>
									<?php } else { ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $option['default'] == $key ) { echo ' selected="selected"'; } ?>><?php echo esc_attr( $value ); ?></option>
									<?php } ?>
								<?php } ?>
							</select>
							<p class="description"><?php echo esc_attr( $option['desc'] ); ?></p>
						</div>
			
					<?php }
					
				break;

				case 'sidebars' :
			
					// Checking what category pages to show this option on
					$add_field = false;
					foreach ( $option['tax'] as $type ) {
						if ( $screen->taxonomy == $type ) {
							$add_field = true;
						}
					}

					if ( $add_field == true ) { ?>
	
						<div class="form-field">
							<label for="category-<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_attr( $option['name'] ); ?></label>
							<select id="gp_term_meta_<?php echo esc_attr( $option['id'] ); ?>" name="gp_term_meta[<?php echo esc_attr( $option['id'] ); ?>]">
								
								<?php foreach ( $option['options'] as $key => $value ) { ?>
									<?php if ( $term_meta[$option['id']] != '' ) { ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $term_meta[$option['id']] == $key ) { echo ' selected="selected"'; } ?>><?php echo esc_attr( $value ); ?></option>
									<?php } else { ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $option['default'] == $key ) { echo ' selected="selected"'; } ?>><?php echo esc_attr( $value ); ?></option>
									<?php } ?>
								<?php } ?>
								
								<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
									<option value="<?php echo sanitize_title( $sidebar['id'] ); ?>"<?php if ( isset( $term_meta[$option['id']] ) && $term_meta[$option['id']] == $sidebar['id'] ) { ?>selected="selected"<?php } ?>>
										<?php echo ucwords( $sidebar['name'] ); ?>
									</option>
								<?php } ?>
							</select>
							<p class="description"><?php echo esc_attr( $option['desc'] ); ?></p>
						</div>
		
					<?php } 
					
				break;
				
				case 'text' :
			
					// Checking what category pages to show this option on
					$add_field = false;
					foreach ( $option['tax'] as $type ) {
						if ( $screen->taxonomy == $type ) {
							$add_field = true;
						}
					}

					if ( $add_field == true ) { ?>
	
						<div class="form-field">
							<label for="category-<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_attr( $option['name'] ); ?></label>
							<input name="gp_term_meta[<?php echo esc_attr( $option['id'] ); ?>]" id="gp_term_meta_<?php echo esc_attr( $option['id'] ); ?>" type="text" value="<?php echo esc_url( $term_meta[$option['id']] ? $term_meta[$option['id']] : '' ); ?>" />
							<p class="description"><?php echo esc_attr( $option['desc'] ); ?></p>
						</div>
		
					<?php }
					
				break;

			}
						
		}
		
	}
}
add_action( 'category_add_form_fields', 'ghostpool_add_tax_fields' );	
add_action( 'post_tag_add_form_fields', 'ghostpool_add_tax_fields' );
add_action( 'gp_portfolios_add_form_fields', 'ghostpool_add_tax_fields' );		

// Edit category options
if ( ! function_exists( 'ghostpool_edit_tax_fields' ) ) {
	function ghostpool_edit_tax_fields( $tag ) {

		global $ghostpool_cat_options;

		// Get current screen
		$screen = get_current_screen();

		// Get category option
		if ( isset( $tag->term_id ) ) {
			$term_id = $tag->term_id;
			$term_meta = get_option( "taxonomy_$term_id" );
		} else {
			$term_meta = null;
		}
		
		// Run category options through filter to add custom options
		$options = apply_filters( 'gp_custom_category_options', $ghostpool_cat_options );
		
		foreach ( $options as $option ) {
		
			switch( $option['type'] ) {
			
				case 'select' :
				
					// Checking what category pages to show this option on
					$add_field = false;
					foreach ( $option['tax'] as $type ) {
						if ( $screen->taxonomy == $type ) {
							$add_field = true;
						}
					}

					if ( $add_field == true ) { ?>
		
						<tr class="form-field">
							<th scope="row" valign="top">
								<label for="category-<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_attr( $option['name'] ); ?></label>
							</th>
							<td>	
								<select id="gp_term_meta_<?php echo esc_attr( $option['id'] ); ?>" name="gp_term_meta[<?php echo esc_attr( $option['id'] ); ?>]">
									<?php foreach ( $option['options'] as $key => $value ) { ?>
										<?php if ( $term_meta[$option['id']] != '' ) { ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $term_meta[$option['id']] == $key ) { echo ' selected="selected"'; } ?>><?php echo esc_attr( $value ); ?></option>
										<?php } else { ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $option['default'] == $key ) { echo ' selected="selected"'; } ?>><?php echo esc_attr( $value ); ?></option>
										<?php } ?>
									<?php } ?>
								</select>
								<p class="description"><?php echo esc_attr( $option['desc'] ); ?></p>
							</td>
						</tr>
			
					<?php }
					
				break;

				case 'sidebars' :
			
					// Checking what category pages to show this option on
					$add_field = false;
					foreach ( $option['tax'] as $type ) {
						if ( $screen->taxonomy == $type ) {
							$add_field = true;
						}
					}

					if ( $add_field == true ) { ?>
	
						<tr class="form-field">
							<th scope="row" valign="top">
								<label for="category-<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_attr( $option['name'] ); ?></label>
							</th>
							<td>	
								<select id="gp_term_meta_<?php echo esc_attr( $option['id'] ); ?>" name="gp_term_meta[<?php echo esc_attr( $option['id'] ); ?>]">
								
									<?php foreach ( $option['options'] as $key => $value ) { ?>
										<?php if ( $term_meta[$option['id']] != '' ) { ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $term_meta[$option['id']] == $key ) { echo ' selected="selected"'; } ?>><?php echo esc_attr( $value ); ?></option>
										<?php } else { ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $option['default'] == $key ) { echo ' selected="selected"'; } ?>><?php echo esc_attr( $value ); ?></option>
										<?php } ?>
									<?php } ?>
								
									<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
										<option value="<?php echo sanitize_title( $sidebar['id'] ); ?>"<?php if ( isset( $term_meta[$option['id']] ) && $term_meta[$option['id']] == $sidebar['id'] ) { ?>selected="selected"<?php } ?>>
											<?php echo ucwords( $sidebar['name'] ); ?>
										</option>
									<?php } ?>
								</select>
								<p class="description"><?php echo esc_attr( $option['desc'] ); ?></p>
							</td>
						</tr>
		
					<?php } 
					
				break;
				
				case 'text' :
			
					// Checking what category pages to show this option on
					$add_field = false;
					foreach ( $option['tax'] as $type ) {
						if ( $screen->taxonomy == $type ) {
							$add_field = true;
						}
					}

					if ( $add_field == true ) { ?>
	
						<tr class="form-field">
							<th scope="row" valign="top">
								<label for="category-<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_attr( $option['name'] ); ?></label>
							</th>
							<td>
								<input name="gp_term_meta[<?php echo esc_attr( $option['id'] ); ?>]" id="gp_term_meta_<?php echo esc_attr( $option['id'] ); ?>" type="text" value="<?php echo esc_url( $term_meta[$option['id']] ? $term_meta[$option['id']] : '' ); ?>" />
								<p class="description"><?php echo esc_attr( $option['desc'] ); ?></p>
							</td>
						</tr>
		
					<?php }
					
				break;
								
			}
			
		}	
	
	}
}
add_action( 'edit_category_form_fields', 'ghostpool_edit_tax_fields' );	
add_action( 'post_tag_edit_form_fields', 'ghostpool_edit_tax_fields' );
add_action( 'gp_portfolios_edit_form_fields', 'ghostpool_edit_tax_fields' );

// Save category options
if ( ! function_exists( 'ghostpool_save_tax_fields' ) ) {	
	function ghostpool_save_tax_fields( $term_id ) {
		if ( isset( $_POST['gp_term_meta'] ) ) {
			$term_id = $term_id;
			$term_meta = get_option( "taxonomy_$term_id" );
			$cat_keys = array_keys( $_POST['gp_term_meta'] );
				foreach ( $cat_keys as $key ) {
				if ( isset( $_POST['gp_term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['gp_term_meta'][$key];
				}
			}
			update_option( "taxonomy_$term_id", $term_meta );
		}
	}			
}
add_action( 'created_category', 'ghostpool_save_tax_fields' );		
add_action( 'edit_category', 'ghostpool_save_tax_fields' );
add_action( 'created_post_tag', 'ghostpool_save_tax_fields' ); 
add_action( 'edited_post_tag', 'ghostpool_save_tax_fields' );
add_action( 'created_gp_portfolios', 'ghostpool_save_tax_fields' );	
add_action( 'edited_gp_portfolios', 'ghostpool_save_tax_fields' );			

?>