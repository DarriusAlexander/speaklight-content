<?php if ( $GLOBALS['ghostpool_layout'] == 'gp-both-sidebars' OR $GLOBALS['ghostpool_layout'] == 'gp-left-sidebar' ) { ?>

	<aside id="gp-sidebar-left" class="gp-sidebar">

		<?php if ( is_active_sidebar( $GLOBALS['ghostpool_left_sidebar'] ) ) {
			dynamic_sidebar( $GLOBALS['ghostpool_left_sidebar'] );
		} ?>		

	</aside>

<?php } ?>