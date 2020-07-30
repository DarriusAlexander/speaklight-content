<?php

class Builder_Shortcodes_Admin {

	public $options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'setup_options' ), 100 );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	public function setup_options() {
		add_submenu_page( 'options-general.php', __( 'Themify Shortcodes', 'themify-shortcodes' ), __( 'Themify Shortcodes', 'themify-shortcodes' ), 'manage_options', 'themify-shortcodes', array( $this, 'create_admin_page' ) );
	}

    public function create_admin_page() {
		$this->options = get_option( 'themify_shortcodes' );
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e( 'Themify Shortcodes', 'themify-shortcodes' ); ?></h2>           
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'themify_shortcodes' );   
				do_settings_sections( 'themify-shortcodes' );
				submit_button(); 
				?>
			</form>
		</div>
		<?php
    }

	/**
	 * Register and add settings
	 */
	public function page_init() {        
		register_setting(
			'themify_shortcodes', // Option group
			'themify_shortcodes' // Option name
		);

		add_settings_section(
			'themify-shortcodes-twitter', // ID
			__( 'Twitter API Settings', 'themify-shortcodes' ), // Title
			array( $this, 'twitter_help' ), // Callback
			'themify-shortcodes' // Page
		);

		add_settings_section(
			'themify-shortcodes-gmaps', // ID
			__( 'Google Maps', 'themify-shortcodes' ), // Title
			array( $this, 'gmap_help' ), // Callback
			'themify-shortcodes' // Page
		);

		add_settings_field(
			'twitter_consumer_key', // ID
			__( 'Consumer Key', 'themify-shortcodes' ), // Title 
			array( $this, 'twitter_consumer_key_callback' ), // Callback
			'themify-shortcodes', // Page
			'themify-shortcodes-twitter' // Section           
		);

		add_settings_field(
			'twitter_consumer_secret', // ID
			__( 'Consumer Secret', 'themify-shortcodes' ), // Title 
			array( $this, 'twitter_consumer_secret_callback' ), // Callback
			'themify-shortcodes', // Page
			'themify-shortcodes-twitter' // Section           
		);

		add_settings_field(
			'gmap_api_key', // ID
			__( 'API Key', 'themify-shortcodes' ), // Title 
			array( $this, 'gmap_api_key' ), // Callback
			'themify-shortcodes', // Page
			'themify-shortcodes-gmaps' // Section           
		);
    }

	public function twitter_consumer_key_callback() {
		printf(
			'<input type="text" class="regular-text" id="title" name="themify_shortcodes[twitter_consumer_key]" value="%s" />',
			esc_attr( isset( $this->options['twitter_consumer_key'] ) ? esc_attr( $this->options['twitter_consumer_key']) : '' )
		);
	}

	public function twitter_consumer_secret_callback() {
		printf(
			'<input type="text" class="regular-text" id="title" name="themify_shortcodes[twitter_consumer_secret]" value="%s" />',
			esc_attr( isset( $this->options['twitter_consumer_secret'] ) ? esc_attr( $this->options['twitter_consumer_secret']) : '' )
		);
	}

	public function gmap_api_key() {
		printf(
			'<input type="text" class="regular-text" id="title" name="themify_shortcodes[gmap_api_key]" value="%s" />',
			esc_attr( isset( $this->options['gmap_api_key'] ) ? esc_attr( $this->options['gmap_api_key']) : '' )
		);
	}

	public function twitter_help() { ?>
		<p><?php printf( __( '<a href="%s">Twitter access</a> is required for Themify Twitter widget and Twitter shortcode, read this <a href="%s">documentation</a> for more details.' , 'themify-shortcodes' ), 'https://dev.twitter.com/apps/new', 'https://themify.me/docs/setting-up-twitter' ); ?></p>
	<?php }

	public function gmap_help() { ?>
		<p><?php printf( __( 'Google API key is required to use the Map shortcode. <a href="%s">Generate an API key</a> and insert it here.' , 'themify-shortcodes' ), 'http://developers.google.com/maps/documentation/javascript/get-api-key#key' ); ?></p>
	<?php }
}