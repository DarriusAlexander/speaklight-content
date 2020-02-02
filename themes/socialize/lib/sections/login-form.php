<?php if ( ! is_user_logged_in() ) { ?>

	<div id="login">
		
		<div id="gp-login-modal">
			
			<div id="gp-login-close"></div>

			<div class="gp-login-wrapper">

				<div class="gp-login-form-wrapper">
				
					<h3><?php esc_html_e( 'Login', 'socialize' ); ?></h3>

					<form name="loginform" class="gp-login-form" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">

						<p class="username"><span class="gp-login-icon"></span><input type="text" name="log" class="user_login" value="<?php if ( ! empty( $user_login ) ) { echo esc_attr( stripslashes( $user_login ), 1 ); } ?>" size="20" placeholder="<?php esc_attr_e( 'Username or Email', 'socialize' ); ?>" required /></p>

						<p class="password"><span class="gp-password-icon"></span><input type="password" name="pwd" class="user_pass" size="20" placeholder="<?php esc_attr_e( 'Password', 'socialize' ); ?>" required /></p>

						<p class="gp-lost-password-link"><a href="#" class="gp-switch-to-lost-password"><?php esc_html_e( 'Forgot your password?', 'socialize' ); ?></a></p>
	
						<?php if ( function_exists( 'ghostpool_custom_captcha_display' ) ) {
							echo ghostpool_custom_captcha_display();
						} elseif ( function_exists( 'gglcptch_display' ) ) { 
							echo gglcptch_display(); 
						} elseif ( has_filter( 'hctpc_verify' ) ) {
							echo apply_filters( 'hctpc_display', '' );
						} elseif ( has_filter( 'cptch_verify' ) ) {
							echo apply_filters( 'cptch_display', '' ); 
						} ?>
						
						<span class="gp-login-results" data-verify="<?php esc_html_e( 'Verifying...', 'socialize' ); ?>"></span>

						<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e( 'Login', 'socialize' ); ?>" /></p>
			
						<p class="rememberme"><input name="rememberme" class="rememberme" type="checkbox" checked="checked" value="forever" /> <?php esc_html_e( 'Remember Me', 'socialize' ); ?></p>
				
						<?php if ( get_option( 'users_can_register' ) ) { ?>
							<p class="gp-register-link"><?php esc_html_e( 'No account?', 'socialize' ); ?> <a href="<?php if ( function_exists( 'bp_is_active' ) ) { echo esc_url( bp_get_signup_page( false ) ); } else { echo '#register'; } ?>" class="gp-switch-to-register"><?php esc_html_e( 'Sign up', 'socialize' ); ?></a></p>
						<?php } ?>
				
						<?php if ( has_action ( 'wordpress_social_login' ) ) { ?>
							<div class="gp-social-login">
								<?php do_action( 'wordpress_social_login' ); ?>
							</div>
						<?php } ?>

						<input type="hidden" name="action" value="ghostpool_login" />
							
						<?php wp_nonce_field( 'ghostpool_login_action', 'ghostpool_login_nonce' ); ?>
			
					</form>

				</div>
				
				<div class="gp-lost-password-form-wrapper">
								
					<h3><?php esc_html_e( 'Lost Password', 'socialize' ); ?></h3>
		
					<form name="lostpasswordform" class="gp-lost-password-form" action="#" method="post">
	
						<p><?php esc_html_e( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'socialize' ); ?></p>	
		
						<p><span class="gp-login-icon"></span><input type="text" name="user_login" class="user_login" value="" size="20" placeholder="<?php esc_attr_e('Username or Email', 'socialize' ); ?>" required /></p>

						<span class="gp-login-results" data-verify="<?php esc_html_e( 'Verifying...', 'socialize' ); ?>"></span>

						<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e('Reset Password', 'socialize' ); ?>" /></p>
				
						<p class="gp-login-link"><?php esc_html_e( 'Already have an account?', 'socialize' ); ?> <a href="#" class="gp-switch-to-login"><?php esc_html_e( 'Login instead', 'socialize' ); ?></a></p>

						<input type="hidden" name="action" value="ghostpool_lost_password" />
						
						<?php wp_nonce_field( 'ghostpool_lost_password_action', 'ghostpool_lost_password_nonce' ); ?>
					
					</form>

				</div>
			
				<?php if ( get_option( 'users_can_register' ) && ! function_exists( 'bp_is_active' ) ) { ?>
	
					<div class="gp-register-form-wrapper">
										
						<h3><?php esc_html_e( 'Register', 'socialize' ); ?></h3>

						<form name="registerform" class="gp-register-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post">

							<p class="user_login"><span class="gp-login-icon"></span><input type="text" name="user_login" class="user_login" value="<?php if ( ! empty( $user_login ) ) { echo esc_attr( stripslashes( $user_login ), 1 ); } ?>" size="20" placeholder="<?php esc_attr_e( 'Username', 'socialize' ); ?>" required /></p>

							<p class="user_email"><span class="gp-email-icon"></span><input type="email" name="user_email" class="user_email" size="25" placeholder="<?php esc_attr_e( 'Email', 'socialize' ); ?>" required /></p>
						
							<p class="user_confirm_pass"><span class="gp-password-icon"></span><input type="password" name="user_confirm_pass" class="user_confirm_pass" size="25" placeholder="<?php esc_attr_e( 'Password', 'socialize' ); ?>" required /></p>
						
							<p class="user_pass"><span class="gp-password-icon"></span><input type="password" name="user_pass" class="user_pass" size="25" placeholder="<?php esc_attr_e( 'Confirm Password', 'socialize' ); ?>" required /></p>
						
							<?php if ( function_exists( 'ghostpool_custom_captcha_display' ) ) {
								echo ghostpool_custom_captcha_display();
							} elseif ( function_exists( 'gglcptch_display' ) ) { 
								echo gglcptch_display(); 
							} elseif ( has_filter( 'hctpc_verify' ) ) {
								echo apply_filters( 'hctpc_display', '' );
							} elseif ( has_filter( 'cptch_verify' ) ) {
								echo apply_filters( 'cptch_display', '' ); 
							} ?>
							
							<span class="gp-login-results" data-verify="<?php esc_html_e( 'Verifying...', 'socialize' ); ?>"></span>
	
							<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e( 'Sign Up', 'socialize' ); ?>" /></p>
		
							<?php if ( ghostpool_option( 'registration_gdpr' ) == 'enabled' ) { ?>
								<p class="gp-gdpr"><input name="gdpr" class="gdpr" type="checkbox" value="1" required /> <label><?php echo wp_kses_post( ghostpool_option( 'registration_gdpr_text' ) ); ?></label></p>
							<?php } ?>
						
							<p class="gp-login-link"><?php esc_html_e( 'Already have an account?', 'socialize' ); ?> <a href="#" class="gp-switch-to-login"><?php esc_html_e( 'Login instead', 'socialize' ); ?></a></p>
				
							<input type="hidden" name="action" value="ghostpool_register" />
							
							<?php wp_nonce_field( 'ghostpool_register_action', 'ghostpool_register_nonce' ); ?>
				
						</form>
		
					</div>
									
				<?php } ?>	
	
			</div>
		
		</div>
			
	</div>
	
<?php } ?>