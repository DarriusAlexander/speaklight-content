<div class="gp-post-format-quote-content">
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<blockquote>
			<?php the_content(); ?>
			<cite><?php echo esc_attr( get_post_meta( get_the_ID(), 'quote_source', true ) ); ?></cite>
		</blockquote>
	</a>
</div>