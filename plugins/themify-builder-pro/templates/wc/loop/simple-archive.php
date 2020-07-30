<?php
if(empty($args['archive_products'])){
    return;
}
$archive_default = array(
    'image' => array(
	'on' => '1',
	'val' => array(
	    'image_w' => '',
	    'image_h' => '',
	    'auto_fullwidth' => false,
	    'appearance_image' => '',
	    'sale_b' => 'on',
	    'badge_pos' => 'left',
	    'fallback_s' => 'no',
	    'fallback_i' => '',
	    'lightbox_w_unit' => '%',
	    'lightbox_h_unit' => '%',
	    'link' => 'permalink',
	    'open_link' => 'regular',
	    'hover_image' => 'no'
	)
    ),
    't' => array(
	'on' => '1',
	'val' => array(
	    'link' => 'permalink',
	    'open_link' => 'regular',
	    'lightbox_w_unit' => '%',
	    'lightbox_h_unit' => '%',
	    'html_tag' => 'h2',
	    'no_follow' => 'no'
	)
    ),
    'p_meta' => array(
	'on' => '0',
	'val' => array(
	    'enable_cat' => 'yes',
	    'cat' => '',
	    'enable_tag' => 'yes',
	    'tag' => '',
	    'enable_sku' => 'yes',
	    'sku' => ''
	)
    ),
    'p_desc' => array(
	'on' => '1',
	'val' => array(
	    'description' => 'short'
	)
    ),
    'p_price' => array(
	'on' => '1'
    ),
    'p_rating' => array(
	'on' => '0'
    ),
    'add_to_c' => array(
	'on' => '1',
	'val' => array(
	    'quantity' => 'no',
	    'label' => '',
	    'fullwidth' => 'no'
	)
    )
);
foreach ($args['archive_products'] as $key => $item) {
    if (!isset($item['val'])) {
	$item['val'] = array();
    }
    if (isset($archive_default[$key]['val'])) {
	$item['val'] = wp_parse_args($item['val'], $archive_default[$key]['val']);
    }
    switch ($key) {
	// Title
	case 't':
	    if ($item['on'] === '1') {
		themify_product_title_start();
		self::retrieve_template('partials/title.php', $item['val']);
		themify_product_title_end();
	    }
	    break;

	// Description
	case 'p_desc':
	    if ($item['on'] === '1') {
		self::retrieve_template('wc/description.php', $item['val']);
	    }
	    break;
	// Meta
	case 'p_meta':
	    if ($item['on'] === '1') {
		self::retrieve_template('wc/meta.php', $item['val']);
	    }
	    break;
	// Image
	case 'image':
	    if ($item['on'] === '1') {
		self::retrieve_template('wc/loop/image.php', $item['val']);
	    }
	    break;
	// Product Price
	case 'p_price':
	    if ($item['on'] === '1') {
		themify_product_price_start(); // Hook 
		?>
		<div class="post-meta entry-meta tbp_post_meta product-price">
		    <?php woocommerce_template_loop_price() ?>
		</div>
		<?php
		themify_product_price_end(); // Hook
	    }
	    break;
	// Rating
	case 'p_rating':
	    if ($item['on'] === '1') {
		self::retrieve_template('wc/rating.php');
	    }
	    break;

	// Add To Cart
	case 'add_to_c':
	    if ($item['on'] === '1') {
		self::retrieve_template('wc/loop/add-to-cart.php', $item['val']);
	    }
	    break;
    }
}
$args=null;
