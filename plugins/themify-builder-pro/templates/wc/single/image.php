<?php
themify_product_image_start(); // Hook 
$product = wc_get_product( get_the_ID() );
$attachment_id = $product->get_image_id();
$html='';
if ( !empty($args['thumb_image_w']) || !empty($args['thumb_image_h']) ) {
	$GLOBALS['themify']->gallery_thumb_size_w = $args['thumb_image_w'];
	$GLOBALS['themify']->gallery_thumb_size_h = $args['thumb_image_h'];
    function tb_pro_set_image_size_gallery_thumbnail($size){
		return array(
			empty($GLOBALS['themify']->gallery_thumb_size_w) ? $size['width'] : $GLOBALS['themify']->gallery_thumb_size_w,
			empty($GLOBALS['themify']->gallery_thumb_size_h) ? $size['height'] : $GLOBALS['themify']->gallery_thumb_size_h,
		);
	}
	add_filter( 'woocommerce_gallery_thumbnail_size', 'tb_pro_set_image_size_gallery_thumbnail',99 );
}
function tb_pro_get_product_image_thumbnail_html($args,$attachment_id,$thumbnail=false){
	$width_attr = true === $thumbnail ? 'thumb_image_w' : 'image_w';
	$height_attr = true === $thumbnail ? 'thumb_image_h' : 'image_h';
    $html = wc_get_gallery_image_html( $attachment_id, true );
	if ( $args[$width_attr] !== '' || $args[$height_attr] !== '' ) {
		if(!Themify_Builder_Model::is_img_php_disabled()){
			$src=wp_get_attachment_image_src( $attachment_id, true === $thumbnail && false ? array($args[$width_attr],$args[$height_attr]):'full' );
			if(!empty($src[0])){
				preg_match( '/src="([^"]+)"/', $html, $image_src );
				if(!empty($image_src[1])){
					$url = themify_get_image(array(
						'src'=>$src[0],
						'w'=>$args[$width_attr],
						'h'=>$args[$height_attr],
						'urlonly'=>true,
						'ignore'=>true
					));

					$html=str_replace($image_src[1],$url,$html);
					$image_src=$url=null;
				}
			}
		}
		if($args[$width_attr]!==''){
			$html=preg_replace('/width=\"([0-9]{1,})\"/','width="'.$args[$width_attr].'"',$html);
		}
		if($args[$height_attr]!==''){
			$html=preg_replace('/height=\"([0-9]{1,})\"/','height="'.$args[$height_attr].'"',$html);
		}
	}
	return $html;
}
if(!empty($attachment_id)){
	if ( !empty($args['thumb_image_w']) || !empty($args['thumb_image_h']) ) {
		// Generate the custom thumbnail size form main image
	    tb_pro_get_product_image_thumbnail_html($args,$attachment_id,true);
	}
    $html = tb_pro_get_product_image_thumbnail_html($args,$attachment_id);
}elseif($args['fallback_s'] === 'yes' && $args['fallback_i'] !== ''){
	//fallback
	$full_src = esc_url($args['fallback_i']);
	if(!Themify_Builder_Model::is_img_php_disabled()){
		$image = themify_get_image(array(
		'src'=>$full_src,
		'w'=>$args['image_w'],
		'h'=>$args['image_h'],
		'ignore'=>true
		));
	}
	else{
		$image = '<img class="wp-post-image" src="' . $full_src . '" width="' . $args['image_w'] . '" height="' . $args['image_h'] . '"/>';
	}
	$html='<div class="woocommerce-product-gallery__image">'.$image.'</div>';
}
$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$wrapper_classes = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'image-wrap',
	'woocommerce-product-gallery--' . ( $attachment_id ? 'with-images' : 'without-images' ),
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images'
) );
?>
<div class="product <?php echo $args['sale_b'] === 'yes' ? ' sale-badge-' . $args['badge_pos'] : ''; ?>">
	<?php if ($args['sale_b'] === 'yes'):?>
	    <?php woocommerce_show_product_sale_flash();?>
	<?php endif; ?>
	<div class="image-wrap <?php  esc_attr_e( implode( ' ', $wrapper_classes ) ); ?>" data-columns="<?php esc_attr_e( $columns ); ?>">
		<figure class="woocommerce-product-gallery__wrapper">
            <?php echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id ); ?>
            <?php if ( !empty($args['thumb_image_w']) || !empty($args['thumb_image_h']) ): ?>
                <?php
				$attachment_ids = $product->get_gallery_image_ids();
				if ( $attachment_ids && $product->get_image_id() ) {
					foreach ( $attachment_ids as $attachment_id ) {
					    // Generate custom image thumbnails
					    tb_pro_get_product_image_thumbnail_html($args,$attachment_id,true); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
					}
				}
                ?>
            <?php endif; ?>
            <?php do_action( 'woocommerce_product_thumbnails' );?>
		</figure>
	</div>
</div>
<?php
if ( !empty($args['thumb_image_w']) || !empty($args['thumb_image_h']) ) {
	unset($GLOBALS['themify']->gallery_thumb_size_w);
	unset($GLOBALS['themify']->gallery_thumb_size_h);
	remove_filter( 'woocommerce_gallery_thumbnail_size', 'tb_pro_set_image_size_gallery_thumbnail',99 );
}
themify_product_image_end(); // Hook
