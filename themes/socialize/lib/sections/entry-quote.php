<div class="gp-post-format-quote-content">
	<blockquote>
		<?php the_content(); ?>
		<cite><?php echo esc_attr( get_post_meta( get_the_ID(), 'quote_source', true ) ); ?></cite>
	</blockquote>
</div>