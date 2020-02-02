<?php if ( $GLOBALS['ghostpool_layout'] == 'gp-both-sidebars' OR $GLOBALS['ghostpool_layout'] == 'gp-right-sidebar' ) { ?>

	<aside id="gp-sidebar-right" class="gp-sidebar">

		<?php if ( is_active_sidebar( $GLOBALS['ghostpool_right_sidebar'] ) ) {
			dynamic_sidebar( $GLOBALS['ghostpool_right_sidebar'] );
		} ?>		

	</aside>

<?php } ?>