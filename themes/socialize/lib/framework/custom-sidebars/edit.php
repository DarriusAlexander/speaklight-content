<div class="themes-php">

	<div class="wrap">

		<h2><?php esc_html_e( 'Sidebars', 'socialize' ); ?></h2>
	
		<?php $this->message(); ?>
		
		<div id="poststuff">

			<?php global $wp_registered_sidebars; ?>

			<div id="customsidebarspage">

				<div id="poststuff">

					<form action="themes.php?page=sidebars" method="post">
					
						<?php wp_nonce_field( 'ghostpool_edit_sidebar_action' ); ?>

						<div id="namediv" class="stuffbox">
							<h3><label for="sidebar_name"><?php esc_html_e( 'ID', 'socialize' ); ?></label></h3>
							<div class="inside">
								<input type="text" name="sidebar_id" value="<?php echo esc_attr( $sb['id'] ); ?>" readonly="readonly" size="30" />
							</div>
						</div>
					
						<div id="namediv" class="stuffbox">
							<h3><label for="sidebar_name"><?php esc_html_e( 'Name', 'socialize' ); ?></label></h3>
							<div class="inside">
								<input type="text" name="sidebar_name" value="<?php echo esc_attr( $sb['name'] ); ?>" id="title" size="30" />
							</div>
						</div>
		
						<div id="namediv" class="stuffbox">
							<h3><label for="sidebar_description"><?php esc_html_e( 'Description', 'socialize' ); ?></label></h3>
							<div class="inside">
								<input type="text" name="sidebar_description" value="<?php echo esc_attr( $sb['description'] ); ?>" size="30" />
							</div>
						</div>
			
						<p class="submit"><input type="submit" class="button-primary" name="update-sidebar" value="<?php esc_html_e( 'Save Changes', 'socialize' ); ?>" /></p>
		
					</form>

				</div>

			</div>

		</div>
	
	</div>

</div>