<?php
if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
/**
 * Template Related Posts
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
$fields_default = array(
	'heading' => '',
	'term_type' => 'category',
	'per_page' => 3,
	'term_id' => array()
);
$args['mod_settings'] = wp_parse_args($args['mod_settings'], $fields_default);
$args['mod_settings']['pagination'] = 'no';
$isActive=Themify_Builder::$frontedit_active===true;
$post_id = true === $isActive && isset($_POST['pageId']) ? $_POST['pageId'] : get_the_ID();
// Backward compatibility
$args['mod_settings']['term_type'] = 'tag' === $args['mod_settings']['term_type'] ? 'post_tag' : $args['mod_settings']['term_type'];
$terms = get_the_terms($post_id,$args['mod_settings']['term_type']);
if(is_array($terms)){
	foreach ($terms as $term){
		$args['mod_settings']['term_id'][] = $term->term_id;
	}
}
$args['mod_settings']['exclude'] = array($post_id);
if($isActive===true && isset($_POST['pageId'])){
	Tbp_Utils::get_actual_query();
}
$isLoop=Tbp_Utils::$isLoop===true;
Tbp_Utils::$isActive=$isActive;
Themify_Builder::$frontedit_active=false;
Tbp_Utils::$isLoop=true;
self::retrieve_template('template-archive-posts.php', $args);
Themify_Builder::$frontedit_active=$isActive;
Tbp_Utils::$isLoop=$isLoop;
