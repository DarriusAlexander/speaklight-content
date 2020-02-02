<?php

/*--------------------------------------------------------------
Load Default Visual Composer Templates
--------------------------------------------------------------*/

// Remove cats and filter_cats_id options
// esc_html__ to all widget_title options

// Homepage 1
if ( ! function_exists( 'ghostpool_homepage_1_template' ) ) {
	function ghostpool_homepage_1_template( $data ) {
		$template = array();
		$template['name'] = esc_html__( 'Homepage', 'socialize' );
		$template['custom_class'] = 'ghostpool_vc_homepage_1_template';
		$template['content'] = '[vc_row css=".vc_custom_1444739740198{margin-bottom: 0px !important;}"][vc_column][activity widget_title="' . esc_html__( 'Latest Activity', 'socialize' ) . '" classes="gp-vc-element-1" title_color="#00bee9"][showcase widget_title="' . esc_html__( 'Featured News', 'socialize' ) . '" large_meta_cats="1" small_meta_cats="1" page_arrows="enabled" classes="gp-vc-element-2" title_color="#00bee9"][blog widget_title="' . esc_html__( 'Entertainment', 'socialize' ) . '" format="gp-blog-columns-3" per_page="3" image_width="230" image_height="150" image_alignment="gp-image-above" excerpt_length="0" meta_cats="1" classes="gp-secondary-vc-element gp-vc-element-3"][blog widget_title="' . esc_html__( 'Fashion', 'socialize' ) . '" per_page="2" image_width="230" image_height="150" meta_cats="1" classes="gp-secondary-vc-element gp-vc-element-4"][slider format="gp-slider-one-col" large_image_width="740" large_image_height="400" caption_text="disabled" per_page="3" classes="gp-secondary-vc-element gp-vc-element-5"][blog widget_title="' . esc_html__( 'Other News', 'socialize' ) . '" image_width="230" image_height="150" meta_cats="1" classes="gp-secondary-vc-element gp-vc-element-6"][/vc_column][/vc_row]';
		array_unshift( $data, $template );
		return $data;
	}
}	
add_filter( 'vc_load_default_templates', 'ghostpool_homepage_1_template' );

// Right Homepage 1
if ( ! function_exists( 'ghostpool_right_homepage_1_template' ) ) {
	function ghostpool_right_homepage_1_template( $data ) {
		$template = array();
		$template['name'] = esc_html__( 'Right Homepage', 'socialize' );
		$template['custom_class'] = 'ghostpool_vc_right_homepage_1_template';
		$template['content'] = '[vc_row][vc_column][login widget_title="' . esc_html__( 'Join The Community', 'socialize' ) . '"][bp_recently_active_members max_members="15"][bp_groups max_groups="5"][bp_members max_members="5"][events_list title="Events"][statistics widget_title="' . esc_html__( 'Statistics', 'socialize' ) . '" posts="1" comments="1" blogs="1" activity="1" members="1" groups="1" forums="1"][/vc_column][/vc_row]';
		array_unshift( $data, $template );
		return $data;
	}
}	
add_filter( 'vc_load_default_templates', 'ghostpool_right_homepage_1_template' );

// Left Homepage 1
if ( ! function_exists( 'ghostpool_left_homepage_1_template' ) ) {
	function ghostpool_left_homepage_1_template( $data ) {
		$template = array();
		$template['name'] = esc_html__( 'Left Homepage', 'socialize' );
		$template['custom_class'] = 'ghostpool_vc_left_homepage_1_template';
		$template['content'] = '[vc_row][vc_column][blog widget_title="' . esc_html__( 'Latest News', 'socialize' ) . '" per_page="20" image_width="80" image_height="80" excerpt_length="0" meta_cats="1"][/vc_column][/vc_row]';
		array_unshift( $data, $template );
		return $data;
	}
}	
add_filter( 'vc_load_default_templates', 'ghostpool_left_homepage_1_template' );


?>