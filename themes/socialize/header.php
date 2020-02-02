<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php if ( is_singular() && pings_open( get_queried_object() ) ) { ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php } ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( ! is_page_template( 'blank-page-template.php' ) ) { ?>
	
	<div id="gp-site-wrapper">
			
		<?php if ( has_nav_menu( 'gp-primary-main-header-nav' ) OR has_nav_menu( 'gp-secondary-main-header-nav' ) ) { ?>		
			<nav id="gp-mobile-nav">
				<div id="gp-mobile-nav-close-button"></div>
				<?php wp_nav_menu( array( 'theme_location' => 'gp-primary-main-header-nav', 'sort_column' => 'menu_order', 'container' => '', 'items_wrap' => '<ul class="menu">%3$s</ul>', 'fallback_cb' => 'null', 'walker' => new Ghostpool_Custom_Menu ) ); ?>
				<?php wp_nav_menu( array( 'theme_location' => 'gp-secondary-main-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new Ghostpool_Custom_Menu ) ); ?>
			</nav>
			<div id="gp-mobile-nav-bg"></div>
		<?php } ?>
			
		<div id="gp-page-wrapper">

			<header id="gp-main-header">

				<div class="gp-container">
				
					<div id="gp-logo">
						<?php if ( ghostpool_option( 'desktop_logo', 'url' ) OR ghostpool_option( 'mobile_logo', 'url' ) ) { ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo( 'name' ); ?>">
								<img src="<?php echo esc_url( ghostpool_option( 'desktop_logo', 'url' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo absint( ghostpool_option( 'desktop_logo_dimensions', 'width' ) ); ?>" height="<?php echo absint( ghostpool_option( 'desktop_logo_dimensions', 'height' ) ); ?>" class="gp-desktop-logo" />
								<img src="<?php echo esc_url( ghostpool_option( 'desktop_scrolling_logo', 'url' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo absint( ghostpool_option( 'desktop_scrolling_logo_dimensions', 'width' ) ); ?>" height="<?php echo absint( ghostpool_option( 'desktop_scrolling_logo_dimensions', 'height' ) ); ?>" class="gp-scrolling-logo" />
								<img src="<?php echo esc_url( ghostpool_option( 'mobile_logo', 'url' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo absint( ghostpool_option( 'mobile_logo_dimensions', 'width' ) ); ?>" height="<?php echo absint( ghostpool_option( 'mobile_logo_dimensions', 'height' ) ); ?>" class="gp-mobile-logo" />
							</a>
						<?php } ?>
					</div>
					
					<a id="gp-mobile-nav-button"></a>
				
					<?php if ( has_nav_menu( 'gp-primary-main-header-nav' ) OR has_nav_menu( 'gp-secondary-main-header-nav' ) OR ghostpool_option( 'search_button' ) != 'gp-search-disabled' ) { ?>

						<nav id="gp-main-nav" class="gp-nav">
						
							<nav id="gp-primary-main-nav">
								<?php wp_nav_menu( array( 'theme_location' => 'gp-primary-main-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new Ghostpool_Custom_Menu ) ); ?>
							</nav>
						
							<?php if ( function_exists( 'is_woocommerce' ) && ghostpool_option( 'cart_button' ) != 'gp-cart-disabled' ) { echo ghostpool_dropdown_cart(); } ?>
						
							<?php if ( ghostpool_option( 'search_button' ) != 'gp-search-disabled' ) { ?>
								<div id="gp-search">
									<div id="gp-search-button"></div>
									<div id="gp-search-box"><?php get_search_form(); ?></div>
								</div>
							<?php } ?>
							
							<?php if ( ghostpool_option( 'profile_button' ) != 'gp-profile-disabled' && is_user_logged_in() ) { ?>
								<a href="<?php if ( function_exists( 'bp_is_active' ) ) { global $bp; echo $bp->loggedin_user->domain; } else { $current_user = wp_get_current_user(); echo get_author_posts_url( $current_user->ID ); } ?>" id="gp-profile-button"></a>
								<?php if ( function_exists( 'bp_notifications_get_notifications_for_user' ) ) { 
									$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
									$count = ! empty( $notifications ) ? count( $notifications ) : 0;
									echo '<a href="' . $bp->loggedin_user->domain . '/notifications" class="gp-notification-counter">' . $count . '</a>';
								} ?>
							<?php } ?>	
																		
							<nav id="gp-secondary-main-nav">
								<?php wp_nav_menu( array( 'theme_location' => 'gp-secondary-main-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new Ghostpool_Custom_Menu ) ); ?>
							</nav>
												
						</nav>
													
					<?php } ?>
						
				</div>
			
			</header>

			<?php if ( ghostpool_option( 'small_header' ) != 'gp-no-small-header' ) { ?>
	
				<header id="gp-small-header">
	
					<div class="gp-container">

						<div class="gp-left-triangle"></div>
						<div class="gp-right-triangle"></div>
					
						<nav id="gp-top-nav" class="gp-nav">		
							
							<div id="gp-left-top-nav">	
								<?php wp_nav_menu( array( 'theme_location' => 'gp-left-small-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new Ghostpool_Custom_Menu ) ); ?>
							</div>	
						
							<div id="gp-right-top-nav">	
								<?php wp_nav_menu( array( 'theme_location' => 'gp-right-small-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new Ghostpool_Custom_Menu ) ); ?>
							</div>	
										
						</nav>
					
					</div>
		
				</header>
	
			<?php } ?>
		
			<div id="gp-fixed-header-padding"></div>
		
			<?php if ( ghostpool_option( 'header_ad' ) ) { ?>
				<?php if ( ghostpool_option( 'header_ad_exclude' ) == 'enabled' && ( is_404() OR is_attachment() ) ) {} else { ?>
					<div id="gp-header-area">
						<div class="gp-container">
							<?php echo do_shortcode( ghostpool_option( 'header_ad' ) ); ?>
						</div>
					</div>
				<?php } ?>	
			<?php } ?>
			
			<div class="gp-clear"></div>
				
<?php } ?>