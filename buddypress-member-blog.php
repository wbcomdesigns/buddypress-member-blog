<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Buddypress_Member_Blog
 *
 * @wordpress-plugin
 * Plugin Name:       BuddyPress Member Blog
 * Plugin URI:        https://wbcomdesigns.com/downloads/buddypress-member-blog
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       buddypress-member-blog
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
define( 'BUDDYPRESS_MEMBER_BLOG_VERSION', '1.0.0' );


define( 'BUDDYPRESS_MEMBER_BLOG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BUDDYPRESS_MEMBER_BLOG_ACF_PATH', BUDDYPRESS_MEMBER_BLOG_PLUGIN_PATH . 'includes/acf-pro/' );
define( 'BUDDYPRESS_MEMBER_BLOG_ACF_URL', BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL . 'includes/acf-pro/' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-buddypress-member-blog-activator.php
 */
function activate_buddypress_member_blog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buddypress-member-blog-activator.php';
	Buddypress_Member_Blog_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-buddypress-member-blog-deactivator.php
 */
function deactivate_buddypress_member_blog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buddypress-member-blog-deactivator.php';
	Buddypress_Member_Blog_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_buddypress_member_blog' );
register_deactivation_hook( __FILE__, 'deactivate_buddypress_member_blog' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-buddypress-member-blog.php';

/**
 * The core plugin functions that is used to define internationalization, 
 */
require plugin_dir_path( __FILE__ ) . 'includes/buddypress-member-blog-functions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_buddypress_member_blog() {

	$plugin = new Buddypress_Member_Blog();
	$plugin->run();

}
run_buddypress_member_blog();


/**
 * redirect to plugin settings page after activated
 */

add_action( 'activated_plugin', 'bp_member_blog_activation_redirect_settings' );
function bp_member_blog_activation_redirect_settings( $plugin ){

	if( $plugin == plugin_basename( __FILE__ ) ) {
		wp_redirect( admin_url( 'admin.php?page=buddypress-member-blog' ) ) ;
		exit;
	}
}


function buddypress_member_blo_admin_notices() {
	?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'BuddyPress Member Blog Plugin already use ACF Pro plugin. So, Please deactivate ACF Free plugin to use Woo Sell Service Plugin.', 'woo-sell-services' ); ?></p>
    </div>
    <?php
}