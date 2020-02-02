<div class="gp-theme-setup">

	<div class="gp-tabs">

		<div class="gp-page-title">		
			<?php $theme = wp_get_theme(); ?>
			<?php esc_html_e( 'Socialize', 'socialize' ); ?>
			<div class="gp-theme-version"><?php esc_html_e( 'Version', 'socialize' );?> <?php echo SOCIALIZE_THEME_VERSION; ?></div>
		</div>

		<nav>
			<ul class="gp-tabs-navigation">
				<li><a data-content="welcome" class="selected" href="#welcome"><span class="count-tab">1</span> <?php esc_html_e( 'Welcome', 'socialize' ); ?></a></li>
				<li><a data-content="updates" href="#updates"><span class="count-tab">2</span> <?php esc_html_e( 'Updates', 'socialize' ); ?></a></li>
				<li><a data-content="addons" href="#addons"><span class="count-tab">3</span> <?php esc_html_e( 'Theme Addons', 'socialize' ); ?></a></li>
				<li><a data-content="demo-data" href="#demo-data"><span class="count-tab">4</span> <?php esc_html_e( 'Import Demo Data', 'socialize' ); ?></a></li>
				<li><a data-content="support" href="#support"><span class="count-tab">5</span> <?php esc_html_e( 'Support', 'socialize' ); ?></a></li>
			</ul>
		</nav>

		<ul class="gp-tabs-content">
		
			<li data-content="welcome" class="selected">

				<div class="gp-row">

					<div class="gp-col-6">
					
						<h3 class="gp-primary-header"><?php esc_html_e( 'Welcome to Socialize', 'socialize' ); ?></h3>
						
						<div class="gp-primary-text">
						
							<p><?php esc_html_e( 'Please follow the steps on this page to setup your theme. It is completely optional but it will help you get started.', 'socialize' ); ?></p>
						
							<p><?php esc_html_e( 'First of all make sure all the server stats to the right are green, meaning the theme is compatible with your server. If any stats are red please fix this.', 'socialize' ); ?></p>
							
						</div>
							
					</div>

					<div class="gp-col-6">

						<h3 class="gp-primary-header"><?php esc_html_e( 'Server status:', 'socialize' ); ?></h3>
						
						<div class="gp-sstatus-wrapper">

							<?php
							
							$statuses = array();

							// Writable directory
							$upload_dir = wp_upload_dir();
							$icon = 'dashicons-yes';
							$color_class = 'gp-sstatus-ok';
							$message = esc_html__( 'Uploads folder is writable', 'socialize' );
							if ( ! wp_is_writable( trailingslashit( $upload_dir['basedir'] ) ) ) {
								$icon = 'dashicons-warning';
								$color_class = 'gp-sstatus-notok';
								$message = esc_html__( 'Uploads folder is not writable. Please check with your hosting provider.', 'socialize' );
							}
							$statuses[] = array(
								'name' => esc_html__( 'File permissions', 'socialize' ),
								'title' => esc_html__( 'Whether or not your uploads folder is writable', 'socialize' ),
								'icon' => $icon,
								'color_class' => $color_class,
								'message' => $message,
							);

							// PHP version
							$icon = 'dashicons-yes';
							$color_class = 'gp-sstatus-ok';
							$php_version = phpversion();
							$message = 'v. ' . $php_version;
							if ( version_compare( $php_version, '5.3', '<' ) ) {
								$icon = 'dashicons-warning';
								$color_class = 'gp-sstatus-notok';
								$message = esc_html__( ' - You are using an outdated PHP version. A version greater than 5.6 is recommended.', 'socialize' );
							}
							$statuses[] = array(
								'name' => esc_html__( 'PHP version', 'socialize' ),
								'title' => esc_html__( 'Server PHP version', 'socialize' ),
								'icon' => $icon,
								'color_class' => $color_class,
								'message' => $message

							);

							// Memory limit
							$icon = 'dashicons-yes';
							$color_class = 'gp-sstatus-ok';
							$memory = wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) );
							$message = size_format( $memory );
							if ( $memory < 64000000 ) {
								$icon = 'dashicons-warning';
								$color_class = 'gp-sstatus-notok';
								$message .= esc_html__( ' - We recommend setting the memory to 128MB. See how to', 'socialize' ) . ' <a href="' . esc_url( 'https://ghostpool.ticksy.com/article/608' ) . '" target="_blank">' . esc_html__( 'increase the memory limit.', 'socialize' ) . '</a>';
							}
							$statuses[] = array(
								'name' => esc_html__( 'PHP Memory limit', 'socialize' ),
								'title' => esc_html__( 'The maximum amount of memory (RAM) that your site can use at one time', 'socialize' ),
								'icon' => $icon,
								'color_class' => $color_class,
								'message' => $message

							);
							
							// Max execution time
							$message = '';
							$icon = 'dashicons-yes';
							$color_class = 'gp-sstatus-ok';
							$time_limit = @ini_get( 'max_execution_time' );
							$message = $time_limit;
							if ( $time_limit < 180 && $time_limit != 0 ) {
								$icon = 'dashicons-warning';
								$color_class = 'gp-sstatus-notok';
								$message .= esc_html__( ' - We recommend setting the max execution time to at least 180 for importing the demo data. See how to', 'socialize' ) . ' <a href="' . esc_url( 'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceede' ) . '" target="_blank">' . esc_html__( 'increase the max execution', 'socialize' ) . '</a>';
							}
							$statuses[] = array(
								'name' => esc_html__( 'PHP Time limit', 'socialize' ),
								'title' => esc_html__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out', 'socialize' ),
								'icon' => $icon,
								'color_class' => $color_class,
								'message' => $message
							);

							// Max input vars
							$icon = 'dashicons-yes';
							$color_class = 'gp-sstatus-ok';
							$input_vars = ini_get('max_input_vars');
							$message = $input_vars;
							if ( $input_vars < 1000 ) {
								$icon = 'dashicons-warning';
								$color_class = 'gp-sstatus-notok';
								$message .= esc_html__( ' - Max input vars limitation will truncate POST data such as menus. See how to', 'socialize' ) . ' <a href="' . esc_url( 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '" target="_blank">' . esc_html__( 'increase the max input vars limit.', 'socialize' ) . '</a>';
							}
							$statuses[] = array(
								'name' => esc_html__( 'PHP Max Input Vars', 'socialize' ),
								'title' => esc_html__( 'The maximum number of variables your server can use for a single function to avoid overloads', 'socialize' ),
								'icon' => $icon,
								'color_class' => $color_class,
								'message' => $message
							);

							// ZipArchive
							$message = esc_html__( 'Installed' , 'socialize' );
							$icon = 'dashicons-yes';
							$color_class = 'gp-sstatus-ok';
							if ( ! class_exists( 'ZipArchive' ) ) {
								$icon = 'dashicons-warning';
								$color_class = 'gp-sstatus-notok';
								$message = esc_html__( 'Not installed - ZipArchive is required for importing content. Please contact your server administrator and ask them to enable it.', 'socialize' );
							}
							$statuses[] = array(
								'name' => esc_html__( 'ZipArchive', 'socialize' ),
								'title' => esc_html__( 'ZipArchive is required for importing the demo data and WordPress content', 'socialize' ),
								'icon' => $icon,
								'color_class' => $color_class,
								'message' => $message
							);

							// WP DEBUG Mode
							$message = esc_html__( 'OK - DEBUG is OFF' , 'socialize' );
							$icon = 'dashicons-yes';
							$color_class = 'gp-sstatus-ok';
							if ( defined( 'WP_DEBUG' ) && WP_DEBUG === TRUE ) {
								$icon = 'dashicons-warning';
								$color_class = 'gp-sstatus-notok';
								$message = esc_html__( 'DEBUG is ON - We recommend disabling WordPress debugging on your live site.', 'socialize' );
							}
							$statuses[] = array(
								'name' => esc_html__( 'WP Debug', 'socialize' ),
								'title' => esc_html__( 'Displays whether or not WordPress is in Debug Mode. We recommend disabling debug mode for a live site', 'socialize' ),
								'icon' => $icon,
								'color_class' => $color_class,
								'message' => $message
							);

							?>

							<?php foreach ( $statuses as $status ) : ?>

								<div class="gp-sstatus-row">
									<div class="gp-sstatus-col gp-sstatus-col-name"><?php echo $status['name']; ?></div>
									<div class="gp-sstatus-col"><span class="gp-sstatus-col-icon tooltip-me dashicons-before <?php echo $status['icon']; ?>" title="<?php echo $status['title'];?>"></span></div>
									<div class="gp-sstatus-col gp-sstatus-col-value <?php echo $status['color_class']; ?>"><?php echo $status['message']; ?></div>
								</div>

							<?php endforeach; ?>

						</div>
					</div>

				</div>


			</li>

			<li data-content="updates">
				
				<div class="gp-row">
					
					<div class="gp-col-7">
						<h3 class="gp-primary-header"><?php esc_html_e( 'Get automatic theme updates', 'socialize' ); ?></h3>
						<div class="gp-primary-text">
							<p><?php esc_html_e( 'To get automatic theme updates do the following:', 'socialize' ); ?></p>
							<ul class="ul-square">
								<li><?php esc_html_e( 'Enter your ThemeForest (Envato) username.', 'socialize' ); ?></li>
								<li><?php esc_html_e( 'Generate an API key on ThemeForest and enter it.', 'socialize' ); ?> <a href="https://ticksy_attachments.s3.amazonaws.com/9076411986.jpg" target="_blank"><?php esc_html_e( 'How do I get my API key?', 'socialize' ); ?></a></li>
								<li><?php esc_html_e( 'Click the "Register" button.', 'socialize' ); ?></li>
							</ul>
						</div>
					</div>

					<div class="gp-col-5">
						<?php
						$tf_username  = ghostpool_option( 'tf_username' );
						$tf_api       = ghostpool_option( 'tf_apikey' );
						?>
						<form action="<?php echo admin_url( 'themes.php?page=' . GhostPool_Setup::getInstance()->slug ); ?>#updates" class="gp-theme-updates-form">
							<div class="gp-setup-form-field">
								<label for="gp-tf-username"><?php esc_html_e( 'ThemeForest (Envato) Username', 'socialize' ); ?></label>
								<input type="text" id="gp-tf-username" class="gp-theme-updates-form-username" value="<?php echo $tf_username; ?>">
							</div>
							<div class="gp-setup-form-field">
								<label for="gp-tf-apikey"><?php esc_html_e( 'Themeforest API Key', 'socialize' ); ?></label>
								<input type="text" id="gp-tf-apikey" name="gp-tf-apikey" class="gp-theme-updates-form-api" value="<?php echo $tf_api; ?>">
							</div>

							<div class="gp-response-area hidden"></div>

							<?php wp_nonce_field( 'ghostpool_theme_updates_action', 'ghostpool_theme_updates_nonce' ); ?>
							
							<input type="submit" class="gp-theme-updates-form-submit gp-register-button" value="<?php esc_attr_e( 'Register', 'socialize' ); ?>">
						</form>
					</div>
					
				</div>
				
			</li>


			<li data-content="addons">

				<h3 class="gp-primary-header"><?php esc_html_e( 'Install Addons', 'socialize' ); ?></h3>
					
				<div class="gp-primary-text">
					<p><?php esc_html_e( 'Below you will find a list of required and recommended theme plugins. Please make sure you install all the required plugins before proceeding to the next step.', 'socialize' ); ?></p>
				</div>

				<div class="gp-addons-list">
					<?php foreach ( GhostPool_Addons_Manager()->plugins as $plugin ) : ?>

						<?php
						
						$plugin_status = GhostPool_Addons_Manager()->get_plugin_status( $plugin['slug'] );
						
						$button = '<a class="gp-addon-button"' .
						          ' data-action="' . $plugin_status['action'] . '"' .
						          ' data-status="' . $plugin_status['status'].'"' .
						          ' data-nonce="' . wp_create_nonce( 'ghostpool_addons_action' ) . '"' .
						          ' href="#"' .
						          ' data-slug="' . $plugin['slug'] . '">' . 
						          $plugin_status['action_text'] . 
					          '</a>' . '<span class="spinner"></span>';
						?>
						<div class="gp-addon <?php echo $plugin_status['status']; ?>" id="addon-<?php echo $plugin['slug']; ?>">
				
							<h4 class="gp-addon-title"><?php echo $plugin['name']; ?></h4>
							
							<div class="gp-addon-extra<?php if ( isset( $plugin['required'] ) && $plugin['required'] == true ) { echo ' gp-required-addon'; } ?>"><?php echo ( isset( $plugin['required'] ) && $plugin['required'] == true ) ? esc_html__( 'Required', 'socialize' ) : esc_html__( 'Optional', 'socialize' ); ?></div>
							
							<div class="gp-addon-desc"><?php echo isset( $plugin['description'] ) ? $plugin['description'] : '' ; ?></div>
																
							<?php echo $button; ?>
							
							<div class="gp-addon-ajax-text"></div>

						</div>
					<?php endforeach; ?>
				</div>
			</li>

			<li data-content="demo-data">

				<?php if ( class_exists( 'GhostPool_Importer' ) ) {
					$GhostPool_Importer = GhostPool_Importer::getInstance();
					$GhostPool_Importer->demo_installer(); 
				} else { ?>
				
					<h3 class="gp-secondary-header"><?php esc_html_e( 'To use the importer please install and active the Socialize Plugin from the Theme Addons tab. If you have activated this plugin please refresh this page.', 'socialize' ); ?></h3>
				
				<?php } ?>
				
			</li>

			<li data-content="support">
			
				<div class="gp-row">
				
					<div class="gp-col-7">
				
						<h3 class="gp-primary-header"><?php esc_html_e( 'Need support?', 'socialize' ); ?></h3>
				
						<div class="gp-primary-text">
							<p><?php esc_html_e( 'If you need help setting up the theme check out our documentation and articles:', 'socialize'  ); ?></p>
						</div>	
				
						<a href="<?php echo esc_url( 'http://ghostpool.com/help/socialize/help.html' ); ?>" class="gp-support-button" target="_blank"><?php esc_html_e( 'Documentation', 'socialize' ); ?></a>
				
						<a href="<?php echo esc_url( 'https://ghostpool.ticksy.com/articles/100000267' ); ?>" class="gp-support-button" target="_blank"><?php esc_html_e( 'Articles', 'socialize' ); ?></a>
				
					</div>
				
					<div class="gp-col-5">
						
						<h3 class="gp-secondary-header"><?php esc_html_e( 'Still need support?', 'socialize' ); ?></h3>
				
						<div class="gp-primary-text">
							<p><?php esc_html_e( 'If you still need help after reading through the documentation and articles please open up a support ticket and we will reply to your question within 24 hours Monday to Friday.', 'socialize' ); ?></p>
						</div>
				
						<a href="<?php echo esc_url( 'https://ghostpool.ticksy.com' ); ?>" class="gp-support-button" target="_blank"><?php esc_html_e( 'Ask a question', 'socialize' ); ?></a>
				
					</div>						
				
				</div>
									
			</li>
			
		</ul>
	</div>
	
</div>