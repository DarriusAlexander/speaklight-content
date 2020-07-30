<?php
if ($args['pagination_option'] === 'numbers') {
    echo self::get_pagenav('', '', $args['query'], 0);
} else {
?>
    <div class="pagenav pagenav-prev-next clearfix">
	<?php echo get_next_posts_link($args['next_link'], $args['query']->max_num_pages), get_previous_posts_link($args['prev_link']); ?>
    </div>
<?php
}
$args = null;

