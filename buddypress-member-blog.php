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
 * Plugin Name:       Wbcom Designs - BuddyPress Member Blog
 * Plugin URI:        https://wbcomdesigns.com/downloads/buddypress-member-blog
 * Description:       Allow your BuddyPress members to create and manage their blog posts from there profile.  Allow them to publish their posts directly or send them for review. you can easily navigate to any member's profile to read  their posts.
 * Version:           1.9.3
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
define( 'BUDDYPRESS_MEMBER_BLOG_VERSION', '1.9.3' );


define( 'BUDDYPRESS_MEMBER_BLOG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

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
add_action( 'bp_include', 'run_buddypress_member_blog' );

/**
 * redirect to plugin settings page after activated
 */

add_action( 'activated_plugin', 'bp_member_blog_activation_redirect_settings' );
function bp_member_blog_activation_redirect_settings( $plugin ) {

	if ( $plugin == plugin_basename( __FILE__ ) && class_exists( 'Buddypress' ) ) {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) {
			wp_redirect( admin_url( 'admin.php?page=buddypress-member-blog' ) );
			exit;
		}
	}
}



/**
 *  Check if buddypress activate.
 */
function buddypress_member_blog_requires_buddypress() {

	if ( ! class_exists( 'Buddypress' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'buddypress_member_blog_required_plugin_admin_notice' );
	}
}

add_action( 'admin_init', 'buddypress_member_blog_requires_buddypress' );


/**
 * Throw an Alert to tell the Admin why it didn't activate.
 *
 * @author wbcomdesigns
 * @since  2.3.0
 */
function buddypress_member_blog_required_plugin_admin_notice() {

	$bpmb_plugin = esc_html__( ' BuddyPress Member Blog', 'buddypress-member-blog' );
	$bp_plugin   = esc_html__( 'BuddyPress', 'buddypress-member-blog' );
	echo '<div class="error"><p>';
	echo sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'buddypress-member-blog' ), '<strong>' . esc_html( $bpmb_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>' );
	echo '</p></div>';
}

require plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://demos.wbcomdesigns.com/exporter/free-plugins/buddypress-member-blog.json',
	__FILE__, // Full path to the main plugin file or functions.php.
	'buddypress-member-blog'
);


/*
 *  Remove edit post cap for subscriber user onadmin and fronted
 */
add_action( 'init', 'bp_member_blog_user_upload_file_permission' );
function bp_member_blog_user_upload_file_permission() {
	global $post;
	$subscriber = get_role( 'subscriber' );
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		$subscriber->remove_cap( 'upload_files' );
	} else {
		$subscriber->add_cap( 'upload_files' );
	}
	if ( ! defined( 'DOING_AJAX' ) ) {
		$subscriber->remove_cap( 'edit_published_posts' );
		$subscriber->remove_cap( 'edit_others_pages' );
		$subscriber->remove_cap( 'edit_others_posts' );
		$subscriber->remove_cap( 'edit_published_pages' );
		$subscriber->remove_cap( 'unfiltered_html' );
		$subscriber->remove_cap( 'edit_posts' );
	}

}


/*
 *  Assign edit post cap for subscriber user on bp-member-blog shortcode
 */
add_action( 'wp_head', 'bp_member_blog_wp_head' );
function bp_member_blog_wp_head() {
	global $post;
	if ( is_user_logged_in() && is_a( $post, 'WP_Post' ) && isset( $post->post_content ) && has_shortcode( $post->post_content, 'bp-member-blog' ) ) {
		global $current_user;
		if ( in_array( 'subscriber', $current_user->roles ) ) {
			$subscriber = get_role( 'subscriber' );
			$subscriber->add_cap( 'edit_published_posts' );
			$subscriber->add_cap( 'edit_others_pages' );
			$subscriber->add_cap( 'edit_others_posts' );
			$subscriber->add_cap( 'edit_posts' );
			$subscriber->add_cap( 'unfiltered_html' );
			$subscriber->add_cap( 'edit_published_pages' );
			$subscriber->add_cap( 'upload_files' );
		}
	}
}

/*
 *  Assign edit post cap for subscriber user on media-form action
 */
add_action( 'check_ajax_referer', 'bp_member_blog_check_ajax_referer', 10, 2 );
function bp_member_blog_check_ajax_referer( $action, $result ) {

	if ( $action == 'media-form' && is_user_logged_in() ) {
		global $current_user;
		if ( in_array( 'subscriber', $current_user->roles ) ) {
			$subscriber = get_role( 'subscriber' );
			$subscriber->add_cap( 'edit_published_posts' );
			$subscriber->add_cap( 'edit_others_pages' );
			$subscriber->add_cap( 'edit_others_posts' );
			$subscriber->add_cap( 'edit_posts' );
			$subscriber->add_cap( 'unfiltered_html' );
			$subscriber->add_cap( 'edit_published_pages' );
			$subscriber->add_cap( 'upload_files' );
		}
	}
}


/*
 *  Display only uploaded user media on fronted.
 */

add_filter( 'ajax_query_attachments_args', 'bp_member_blog_ajax_query_attachments_args' );

function bp_member_blog_ajax_query_attachments_args( $query ) {
	if ( is_user_logged_in() ) { // check if there is a logged in user

		$user  = wp_get_current_user(); // getting & setting the current user
		$roles = (array) $user->roles; // obtaining the role
		if ( ! in_array( 'administrator', $roles ) ) {
			$query['author'] = get_current_user_id();
		}
	}
	return $query;
}
