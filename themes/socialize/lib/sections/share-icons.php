<div id="gp-share-icons">

	<h3><?php esc_html_e( 'Share This Post', 'socialize' ); ?></h3>

	<div class="gp-share-icons">
	
		<a href="https://twitter.com/share?text=<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>&url=<?php echo urlencode( get_permalink( get_the_ID() ) ); ?>" title="<?php esc_attr_e( 'Twitter', 'socialize' ); ?>" class="gp-share-twitter" onclick="window.open(this.href, 'gpwindow', 'left=50,top=50,width=600,height=350,toolbar=0'); return false;"></a>	
	
		<a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode( get_permalink( get_the_ID() ) ); ?>&t=<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" title="<?php esc_attr_e( 'Facebook', 'socialize' ); ?>" class="gp-share-facebook" onclick="window.open(this.href, 'gpwindow', 'left=50,top=50,width=600,height=350,toolbar=0'); return false;"></a>
	
		<a href="https://plusone.google.com/_/+1/confirm?hl=en-US&url=<?php echo urlencode( get_permalink( get_the_ID() ) ); ?>" title="<?php esc_attr_e( 'Google+', 'socialize' ); ?>" class="gp-share-google-plus" onclick="window.open(this.href, 'gpwindow', 'left=50,top=50,width=600,height=350,toolbar=0'); return false;"></a>

		<a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());" count-layout="vertical" title="<?php esc_attr_e( 'Pinterest', 'socialize' ); ?>" class="gp-share-pinterest" target="_blank"></a>

		<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink( get_the_ID() ) ); ?>&title=<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" title="<?php esc_attr_e( 'LinkedIn', 'socialize' ); ?>" class="gp-share-linkedin" onclick="window.open(this.href, 'gpwindow', 'left=50,top=50,width=600,height=350,toolbar=0'); return false;"></a>

		<a href="https://reddit.com/submit?url=<?php echo urlencode( get_permalink( get_the_ID() ) ); ?>&amp;title=<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" title="<?php esc_attr_e( 'Reddit', 'socialize' ); ?>" class="gp-share-reddit" onclick="window.open(this.href, 'gpwindow', 'left=50,top=50,width=600,height=350,toolbar=0'); return false;"></a>
		
		<a href="https://www.tumblr.com/share/link?url=<?php echo urlencode( get_permalink( get_the_ID() ) ); ?>&amp;title=<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" title="<?php esc_attr_e( 'Tumblr', 'socialize' ); ?>" class="gp-share-tumblr" onclick="window.open(this.href, 'gpwindow', 'left=50,top=50,width=600,height=350,toolbar=0'); return false;"></a>

		<a href="mailto:?subject=<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>&body=<?php echo urlencode( get_permalink( get_the_ID() ) ); ?>" title="<?php esc_attr_e( 'Email', 'socialize' ); ?>" class="gp-share-email" onclick="window.open(this.href, 'gpwindow', 'left=50,top=50,width=600,height=350,toolbar=0'); return false;"></a>
	
	</div>
	
</div>