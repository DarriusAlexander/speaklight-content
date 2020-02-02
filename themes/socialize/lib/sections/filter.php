<?php

if ( $GLOBALS['ghostpool_filter'] == 'enabled' && ghostpool_option( 'ajax' ) == 'gp-ajax-loop' ) { ?>

	<div class="gp-filter-wrapper">

		<?php if ( isset( $GLOBALS['ghostpool_filter_cats'] ) && $GLOBALS['ghostpool_filter_cats'] == '1' ) { 

			$args = array(
				'parent' => (int) $GLOBALS['ghostpool_filter_cats_id'],
			);
			$term = term_exists( (int) $GLOBALS['ghostpool_filter_cats_id'], 'category' );
			if ( $term !== 0 && $term !== null ) {
				$terms = get_terms( 'category', $args );
			} else {
				$terms = get_terms( 'category' );
			}
			
			if ( $terms ) { ?>
				<div class="gp-filter-menu gp-filter-cats">
					<select name="gp-filter-cats">	
						<option value="<?php echo esc_attr( $GLOBALS['ghostpool_filter_cats_id'] ); ?>"><?php esc_html_e( 'All', 'socialize' ); ?></option>		
						<?php foreach( $terms as $term ) {
							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) { ?>
								<option value="<?php echo esc_attr( $term->term_id ); ?>"><?php echo esc_attr( $term->name ); ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</div>
			<?php } ?>
		<?php } ?>

		<?php if ( ( isset( $GLOBALS['ghostpool_filter_date'] ) && $GLOBALS['ghostpool_filter_date'] == '1' )
		OR ( isset( $GLOBALS['ghostpool_filter_title'] ) && $GLOBALS['ghostpool_filter_title'] == '1' )
		OR ( isset( $GLOBALS['ghostpool_filter_comment_count'] ) && $GLOBALS['ghostpool_filter_comment_count'] == '1' )
		OR ( isset( $GLOBALS['ghostpool_filter_views'] ) && $GLOBALS['ghostpool_filter_views'] == '1' ) ) { ?>

			<div class="gp-filter-menu gp-filter-orderby">
				<select name="gp-filter-orderby">
	
					<?php if ( isset( $GLOBALS['ghostpool_filter_date'] ) && $GLOBALS['ghostpool_filter_date'] == '1' ) { ?>
						<option value="newest"<?php if ( $GLOBALS['ghostpool_orderby'] == 'newest' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Newest', 'socialize' ); ?></option>
						<option value="oldest"<?php if ( $GLOBALS['ghostpool_orderby'] == 'oldest' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Oldest', 'socialize' ); ?></option>
					<?php } ?>
		
					<?php if ( isset( $GLOBALS['ghostpool_filter_title'] ) && $GLOBALS['ghostpool_filter_title'] == '1' ) { ?>
						<option value="title_az"<?php if ( $GLOBALS['ghostpool_orderby'] == 'title_az' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Title (A-Z)', 'socialize' ); ?></option>
						<option value="title_za"<?php if ( $GLOBALS['ghostpool_orderby'] == 'title_za' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Title (Z-A)', 'socialize' ); ?></option>
					<?php } ?>		
											
					<?php if ( isset( $GLOBALS['ghostpool_filter_comment_count'] ) && $GLOBALS['ghostpool_filter_comment_count'] == '1' ) { ?>
						<option value="comment_count"<?php if ( $GLOBALS['ghostpool_orderby'] == 'comment_count' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Most Comments', 'socialize' ); ?></option>
					<?php } ?>		
						
					<?php if ( isset( $GLOBALS['ghostpool_filter_views'] ) && $GLOBALS['ghostpool_filter_views'] == '1' ) { ?>
						<option value="views"<?php if ( $GLOBALS['ghostpool_orderby'] == 'views' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Most Views', 'socialize' ); ?></option>
					<?php } ?>
					
				</select>
			</div>
		
		<?php } ?>


		<?php if ( isset( $GLOBALS['ghostpool_filter_date_posted'] ) && $GLOBALS['ghostpool_filter_date_posted'] == '1' ) { ?>		

			<div class="gp-filter-menu gp-filter-date-posted">
				<select name="gp-filter-date-posted">
					<option value="all"<?php if ( $GLOBALS['ghostpool_date_posted'] == 'all' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Posted any date', 'socialize' ); ?></option>
					<option value="year"<?php if ( $GLOBALS['ghostpool_date_posted'] == 'year' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Posted in the last year', 'socialize' ); ?></option>
					<option value="month"<?php if ( $GLOBALS['ghostpool_date_posted'] == 'month' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Posted in the last month', 'socialize' ); ?></option>
					<option value="week"<?php if ( $GLOBALS['ghostpool_date_posted'] == 'week' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Posted in the last week', 'socialize' ); ?></option>
					<option value="day"<?php if ( $GLOBALS['ghostpool_date_posted'] == 'day' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Posted in the last day', 'socialize' ); ?></option>	
				</select>
			</div>	
	
		<?php } ?>

		<?php if ( isset( $GLOBALS['ghostpool_filter_date_modified'] ) && $GLOBALS['ghostpool_filter_date_modified'] == '1' ) { ?>		
		
			<div class="gp-filter-menu gp-filter-date-modified">
				<select name="gp-filter-date-modified">
					<option value="all"<?php if ( $GLOBALS['ghostpool_date_modified'] == 'all' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Updated any date', 'socialize' ); ?></option>
					<option value="year"<?php if ( $GLOBALS['ghostpool_date_modified'] == 'year' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Updated in the last year', 'socialize' ); ?></option>
					<option value="month"<?php if ( $GLOBALS['ghostpool_date_modified'] == 'month' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Updated in the last month', 'socialize' ); ?></option>
					<option value="week"<?php if ( $GLOBALS['ghostpool_date_modified'] == 'week' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Updated in the last week', 'socialize' ); ?></option>
					<option value="day"<?php if ( $GLOBALS['ghostpool_date_modified'] == 'day' ) { ?> selected="selected"<?php } ?>><?php esc_html_e( 'Updated in the last day', 'socialize' ); ?></option>	
				</select>
			</div>
				
		<?php } ?>			

	</div>

<?php } ?>