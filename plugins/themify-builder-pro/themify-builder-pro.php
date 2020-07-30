<?php

/**
 * Plugin Name:       Themify Builder Pro
 * Plugin URI:        https://themify.me/builder-pro
 * Description:       Build custom WordPress themes and templates (header, footer, post/page templates, etc.) using Themify Builder.
 * Version:           1.0.8
 * Author:            Themify
 * Author URI:        https://themify.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tbp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TBP_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'TBP_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tbp-activator.php
 */
function tbp_activate() {
	require_once TBP_DIR . 'includes/class-tbp-utils.php';
	Tbp::register_cpt();
	flush_rewrite_rules();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require TBP_DIR. 'includes/class-tbp.php';

register_activation_hook( __FILE__, 'tbp_activate' );



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function tbp_run() {
	Tbp::get_instance();
}
add_action( 'themify_builder_setup_modules', 'tbp_run', 1 );

function tbp_admin_notices() {
	if ( ! class_exists( 'Themify_Builder' ) ) {
	?>
		<div class="error">
			<p><?php printf( __( 'Builder Pro plugin requires <a href="%s">Themify framework</a>, or the free <a href="%s">Themify Builder plugin</a>.', 'themify' ), 'https://themify.me/themes', 'https://wordpress.org/plugins/themify-builder/' ); ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'tbp_admin_notices' );