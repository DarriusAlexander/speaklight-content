<div class="wrap">

	<h2><?php esc_html_e( 'Sidebars', 'socialize' ); ?></h2>

	<?php $this->message(); ?>

	<div id="poststuff">

		<h3 class="title"><?php esc_html_e( 'New Sidebar', 'socialize' ); ?></h3>
		
		<p><?php esc_html_e( 'Create your new sidebars below. When a sidebar is created, it is shown on the widgets page where you will be able to configure it.', 'socialize' ); ?></p>
		
		<form action="themes.php?page=sidebars" method="post">
			
			<?php wp_nonce_field( 'ghostpool_new_sidebars_action' ); ?>
			
			<div id="namediv" class="stuffbox">
				
				<h3><label for="sidebar_name"><?php esc_html_e( 'Name', 'socialize' ); ?></label></h3>
				<div class="inside">
					<input type="text" name="sidebar_name" size="30" tabindex="1" value="" id="link_name" />
					<p><?php esc_html_e( 'This name has to be unique.', 'socialize' )?></p>
				</div>

				<h3><label for="sidebar_description"><?php esc_html_e( 'Description', 'socialize' ); ?></label></h3>
				<div class="inside">
					<input type="text" name="sidebar_description" size="30" tabindex="1" value="" id="link_url" />
				</div>
				
			</div>
	
			<input type="submit" class="button-primary" name="create-sidebars" value="<?php esc_html_e( 'Create Sidebar', 'socialize' ); ?>" /><br/><br/>
			
		</form>

		<div id="sidebarslistdiv">
	
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					$( '.gp-delete-link' ).click(function() {
						return confirm( "<?php esc_html_e( 'Are you sure to delete this sidebar?', 'socialize' ); ?>" );
					});
				});
			</script>
	
			<h3><?php esc_html_e( 'Custom Sidebars', 'socialize' ); ?></h3>

			<table class="widefat fixed" cellspacing="0">

				<thead>
					<tr class="thead">
						<th scope="col" id="name" class="manage-column column-name" style=""><?php esc_html_e( 'Name', 'socialize' ); ?></th>
						<th scope="col" id="email" class="manage-column column-email" style=""><?php esc_html_e( 'Description', 'socialize' ); ?></th>
						<th scope="col" id="email" class="manage-column column-email" style=""><?php esc_html_e( 'ID', 'socialize' ); ?></th>
						<th scope="col" id="config" class="manage-column column-date" style=""></th>
						<th scope="col" id="edit" class="manage-column column-rating" style=""></th>
						<th scope="col" id="delete" class="manage-column column-rating" style=""></th>
					</tr>
				</thead>
			
				<tbody id="custom-sidebars" class="list:user user-list">

					<?php if ( sizeof( $custom_sidebars ) > 0 ) {
						foreach( $custom_sidebars as $custom_sidebar ) { ?>
							<tr id="gp-1" class="alternate">
								<td class="name column-name"><?php echo esc_attr( $custom_sidebar['name'] ); ?></td>
								<td class="email column-email"><?php echo esc_attr( $custom_sidebar['description'] ); ?></td>
								<td class="email column-email"><?php echo esc_attr( $custom_sidebar['id'] ); ?></td>
								<td class="role column-date"><a class="" href="widgets.php"><?php esc_html_e( 'Configure Widgets', 'socialize' ); ?></a></td>
								<td class="role column-rating"><a class="" href="themes.php?page=sidebars&p=edit&id=<?php echo esc_attr( $custom_sidebar['id'] ); ?>"><?php esc_html_e( 'Edit', 'socialize' ); ?></a></td>
								<td class="role column-rating"><a class="gp-delete-link" href="themes.php?page=sidebars&delete=<?php echo esc_attr( $custom_sidebar['id'] ); ?>&_n=<?php echo esc_attr( $delete_nonce ); ?>"><?php esc_html_e( 'Delete', 'socialize' ); ?></a></td>
							</tr>
						<?php } 
					} else { ?>
						<tr id="gp-1" class="alternate">
							<td colspan="3"><?php esc_html_e( 'There are no custom sidebars available.', 'socialize' ); ?></td>
						</tr>
					<?php } ?>
	
				</tbody>

			</table>
	
		</div><br/>

		<div id="resetsidebarsdiv">

			<form action="themes.php?page=sidebars" method="post">
		
				<input type="hidden" name="reset-n" value="<?php echo esc_attr( $delete_nonce ); ?>" />
		
				<h3><?php esc_html_e( 'Reset Sidebars', 'socialize' ); ?></h3>
		
				<p><?php esc_html_e( 'Click on the button below to delete ALL sidebar data from the database. This deletes all custom sidebars and removes all widgets from both the theme and custom sidebars.', 'socialize' ); ?></p>

				<p class="submit"><input onclick="return confirm('<?php esc_html_e( 'Are you sure you want to delete all sidebar data?', 'socialize' ); ?>')"type="submit" class="button-primary" name="reset-sidebars" value="<?php esc_html_e( 'Reset Sidebars', 'socialize' ); ?>" /></p>

			</form>
	
		</div>

	</div>

</div>