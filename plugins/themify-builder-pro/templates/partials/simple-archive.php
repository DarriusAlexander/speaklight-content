<?php
if(empty($args['tab_content_archive_posts'])){
    return;
}
$archive_default = array(
    'image' => array(
	'on' => '1',
	'val' => array(
	    'image_w' => '',
	    'image_h' => '',
	    'auto_fullwidth' => false,
	    'fallback_s' => 'off',
	    'fallback_i' => '',
	    'lightbox_w_unit' => '%',
	    'lightbox_h_unit' => '%',
	    'link' => 'permalink',
	    'open_link' => 'regular'
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
	    'no_follow' => 'on'
	)
    ),
    'p_date' => array(
	'on' => '1',
	'val' => array(
	    'format' => 'def'
	)
    ),
    'p_meta' => array(
	'on' => '1',
	'val' => array(
	    'tab_content_post_meta' => array()
	)
    ),
    'cont' => array(
	'on' => '1',
	'val' => array(
	    'content_type' => 'excerpt',
	    'excerpt_length' => ''
	)
    ),
    'more_l' => array(
	'on' => '0',
	'val' => array(
	    'link_type' => 'permalink'
	)
    )
);

foreach ($args['tab_content_archive_posts'] as $key => $item) {
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
		themify_before_post_title(); // Hook
		self::retrieve_template('partials/title.php', $item['val']);
		themify_after_post_title(); // Hook
	    }
	    break;
	// Image
	case 'image':
	    if ($item['on'] === '1') {
		$param_image = 'w=' . $item['val']['image_w'] . '&h=' . $item['val']['image_h'] . '&ignore=true';
		$post_image = '';
		if ($item['val']['fallback_s'] === 'yes' && $item['val']['fallback_i'] !== '' && !has_post_thumbnail()) {
		    $param_image = 'w=' . $item['val']['image_w'] . '&h=' . $item['val']['image_h'] . '&src=' . esc_url($item['val']['fallback_i']) . '&alt=&ignore=true';
		} else {
		    $param_image = 'w=' . $item['val']['image_w'] . '&h=' . $item['val']['image_h'] . '&ignore=true';
		}
		$post_image = themify_get_image($param_image);
		if (Themify_Builder::$frontedit_active && $post_image === '') {
		    $post_image = '<img itemprop="url" src="' . THEMIFY_BUILDER_URI . '/img/image-placeholder.jpg' . '">';
		}

		if ($post_image !== '') {
		    themify_before_post_image(); // Hook
		    ?>

		    <figure itemscope itemtype="https://schema.org/ImageObject"
		    	class="post-image <?php echo ( $item['val']['auto_fullwidth'] == '1' ) ? 'auto_fullwidth' : ''; ?>">
				<?php
				if ($item['val']['link'] === 'none' || ( $item['val']['link'] === 'custom' && empty($item['val']['custom_link']) )) {
				    echo $post_image;
				} else {
				    $i_target = '';
				    $link_attr = array();
				    if ($item['val']['open_link'] === 'newtab') {
					$link_attr[] = ' target="_blank"';
				    };
				    $link_css_classes = '';
				    if ($item['val']['open_link'] === 'lightbox') {
					$link_css_classes = 'themify_lightbox';

					if (!empty($item['val']['lightbox_w']) || !empty($item['val']['lightbox_h'])) {
					    $lightbox_settings = array();
					    if ('' !== $item['val']['lightbox_w']) {
						$lightbox_settings[] = $item['val']['lightbox_w'] . $item['val']['lightbox_w_unit'];
					    }
					    if ('' !== $item['val']['lightbox_h']) {
						$lightbox_settings[] = $item['val']['lightbox_h'] . $item['val']['lightbox_h_unit'];
					    }
					    $link_attr[] = sprintf('data-zoom-config="%s"', implode('|', $lightbox_settings));
					}
				    }
				    if ($item['val']['link'] === 'permalink') {
					$i_link = themify_get_featured_image_link();
				    } elseif ($item['val']['link'] === 'media') {
					$i_link = $item['val']['fallback_s'] === 'yes' && $item['val']['fallback_i'] !== '' && !has_post_thumbnail() ? $item['val']['fallback_i'] : wp_get_attachment_url(get_post_thumbnail_id());
					$link_attr = array();
					$link_css_classes = 'themify_lightbox';
				    } elseif ($item['val']['link'] === 'custom' && $item['val']['custom_link'] !== '') {
					$i_link = esc_url($item['val']['custom_link']);
				    }
				    ?>
			    <a href="<?php echo $i_link; ?>"
			       class="<?php echo $link_css_classes; ?>" <?php echo implode(' ', $link_attr); ?>>
				<meta itemprop="url"><?php echo $post_image; ?></a>
			<?php } ?>
		    </figure>

		    <?php
		    themify_after_post_image(); // Hook
		}
	    }
	    break;

	// Date
	case 'p_date':
	    if ($item['on'] === '1') {
		?>
		<div class="tbp_post_date">
		    <?php if (isset($item['val']['before']) && '' !== $item['val']['before']): ?>
		        <span class="tbp_post_meta_before"><?php echo $item['val']['before'] ?></span>
		    <?php endif; ?>
		    <?php self::retrieve_template('partials/date.php', array_merge(array('type' => 'date'), $item)); ?>
		    <?php if (isset($item['val']['after']) && '' !== $item['val']['after']): ?>
		        <span class="tbp_post_meta_after"><?php echo $item['val']['after']; ?></span>
		    <?php endif; ?>
		</div>
		<?php
	    }
	    break;

	// Post Meta
	case 'p_meta':
	    if ($item['on'] === '1') {
		self::retrieve_template('partials/meta.php', array('meta' => $item['val']['tab_content_post_meta']));
	    }
	    break;

	// Content
	case 'cont':
	    if ($item['on'] === '1') {
		self::retrieve_template('partials/content.php', $item['val']);
	    }
	    break;

	// More Link
	case 'more_l':
	    if ($item['on'] === '1') {
		if ($item['val']['link_type'] === 'permalink') {
		    $more_text = empty($item['val']['link_text']) ? __('More &rarr;', 'themify') : $item['val']['link_text'];
		    ?>
		    <a href="<?php the_permalink(); ?>"
		       class="read-more"><?php echo $more_text; ?></a>
		    <?php
		}
	    }
	    break;
    }
}
$args=null;