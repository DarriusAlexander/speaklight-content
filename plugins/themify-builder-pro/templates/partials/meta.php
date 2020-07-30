<?php if(!empty($args['meta'])):?>
    <div class="entry-meta tbp_post_meta">
	<?php
	    $isExist=isset($args['mod_name']) && (Tbp_Utils::$isActive===true || Themify_Builder::$frontedit_active===true)?false:null;
	    foreach ($args['meta'] as $arg):
		$arg['val'] = empty($arg['val']) ? array() : $arg['val'];
	    ?>
	    <span class="tbp_post_meta_item tbp_post_meta_<?php echo $arg['type']; ?>">
		<?php if (!empty($arg['val']['before'])): ?>
		    <?php  $isExist=true;?>
		    <span class="tbp_post_meta_before"><?php echo $arg['val']['before']; ?></span>
		<?php endif; ?>
		<?php
		switch ($arg['type']):
		    case 'date':
		    case 'time':
			$isExist=true;
			self::retrieve_template('partials/date.php', $arg);
			break;
		    case 'author':
			$isExist=true;
			$arg['val'] = wp_parse_args($arg['val'], array(
			    'p_s' => 32,
			    'a_p' => 'no',
			    'l' => 'yes'
			));
			?>
			<span itemtype="https://schema.org/Organization" itemscope="itemscope" itemprop="publisher" class="author vcard tbp_post_meta_autor_inner">
				<?php if (!empty($arg['val']['icon'])): ?>
				    <span class="ti <?php echo $arg['val']['icon']; ?>"></span>
				<?php endif; ?>

				<?php if ('yes' === $arg['val']['l']): ?>
				    <a class="tbp_post_meta_link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author">
				    <?php endif; ?>

				    <?php if ('yes' === $arg['val']['a_p'] || 'on' === $arg['val']['a_p']): ?>
					<?php echo get_avatar(get_the_author_meta('ID'), $arg['val']['p_s']); ?>
				    <?php endif; ?>

				<span itemprop="name"><?php echo get_the_author(); ?></span>
				<?php if ('yes' === $arg['val']['l']): ?>
				    </a>
				<?php endif; ?>
			</span>
			<?php
			break;
		    case 'comments':
			$isExist=true;
			$arg['val'] = wp_parse_args($arg['val'], array(
			    'no' => '',
			    'one' => '',
			    'comments' => ''
			));
			$comments_num = get_comments_number();
			if ($comments_num === 0) {
			    $comments = $arg['val']['no'];
			} elseif ($comments_num === 1) {
			    $comments = $arg['val']['one'];
			} elseif (isset($arg['val']['comments'])) {
			    $comments = sprintf($arg['val']['comments'], $comments_num);
			}
			?>
			<?php if (!empty($arg['val']['icon'])): ?>
			    <span class="ti <?php echo $arg['val']['icon']; ?>"></span>
			<?php endif; ?>
			<?php if (isset($arg['val']['l']) && 'yes' === $arg['val']['l']): ?>
			    <a class="tbp_post_meta_link" href="<?php echo get_comments_link(); ?>">
			<?php endif; ?>
			    <?php echo $comments; ?>
			<?php if (isset($arg['val']['l']) && 'yes' === $arg['val']['l']): ?>
			    </a>
			<?php endif; ?>
			<?php
			break;
		    case 'terms':
			$arg['val'] = wp_parse_args($arg['val'], array(
			    'post_type' => 'post',
			    'taxonomy' => 'category',
			    'sep' => '',
			    'l' => 'yes'
			));
			$terms = get_the_terms(get_the_ID(), $arg['val']['taxonomy']);
			$hasLink = 'yes' === $arg['val']['l'];
			?>

			<?php if (!empty($arg['val']['icon'])): ?>
			    <span class="ti <?php echo $arg['val']['icon']; ?>"></span>
			    
			<?php $isExist=true;
			    endif; ?>

			<?php if (!empty($terms) && !is_wp_error($terms) && is_array($terms)): ?>
			    <?php
			    $isExist=true;
			    $num_of_terms = count($terms) - 1;
			    $template = $hasLink === true ? '<a href="%s">%s</a>%s' : '%s%s%s';
			    foreach ($terms as $i => $term):
				if ($hasLink === true) {
				    $term_link = get_term_link($term, array($arg['val']['taxonomy']));
				    if (is_wp_error($term_link)) {
					--$num_of_terms;
					continue;
				    }
				} else {
				    $term_link = '';
				}
				printf($template, $term_link, $term->name, ($i < $num_of_terms && $num_of_terms >= 1) ? $arg['val']['sep'] : '' );
				?>
			    <?php endforeach; ?>
			<?php endif; ?>
			<?php
			break;
			?>
		<?php endswitch; ?>
		<?php if (isset($arg['val']['after']) && '' !== $arg['val']['after']): ?>
		    <?php  $isExist=true;?>
		    <span class="tbp_post_meta_after"><?php echo $arg['val']['after'] ?></span>
		<?php endif; ?>
	    </span>
	</span>
    <?php endforeach;?>
    </div>
<?php endif;?>
<?php if($isExist===false):?>
    <div class="tbp_empty_module">
	<?php echo Themify_Builder_Model::get_module_name($args['mod_name']);?>
    </div>
<?php endif; $args=null;?>
