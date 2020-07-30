<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>

<?php global $post;
$id = $post->ID;

if(get_post_meta($id, 'buttonstyles_galaxy', true) == 'button_editable_textbox') {
    if(get_option('predefined_button_caption_show_hide') == '1'){
?>

<span><?php echo get_option('predefined_button_caption'); ?></span>

<?php }
}