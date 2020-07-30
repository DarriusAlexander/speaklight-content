<?php
if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
/**
 * Template Advanced Archive Products
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

$isActive=Themify_Builder::$frontedit_active===true;
if($isActive===true && isset($_POST['pageId'])){
    Tbp_Utils::get_wc_actual_query();
}
TB_Advanced_Products_Module::$builder_id=$args['element_id'];
$isLoop=Tbp_Utils::$isLoop===true;
Tbp_Utils::$isActive=$isActive;
Themify_Builder::$frontedit_active=false;
Tbp_Utils::$isLoop=true;
self::retrieve_template('template-archive-products.php', $args);
Themify_Builder::$frontedit_active=$isActive;
Tbp_Utils::$isLoop=$isLoop;
$args=TB_Advanced_Products_Module::$builder_id=$args['element_id']=null;