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
 * Description:       Allow your BuddyPress members to create and manage their blog posts from their profile.  Allow them to publish their posts directly or send them for review. You can easily navigate to any member's profile to read  their posts.
 * Version:           2.3.0
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
define( 'BUDDYPRESS_MEMBER_BLOG_VERSION', '2.3.0' );


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

require_once __DIR__ . '/vendor/autoload.php';
HardG\BuddyPress120URLPolyfills\Loader::init();

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
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) { //phpcs:ignore
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

	$bpmb_plugin = esc_html__( 'BuddyPress Member Blog', 'buddypress-member-blog' );
	$bp_plugin   = esc_html__( 'BuddyPress', 'buddypress-member-blog' );
	echo '<div class="error"><p>';
	// translators: %1$s is replaced with the BuddyPress Member Blog and %2$s is replaced with the BuddyPress.
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
 *  Remove edit post cap for subscriber user on admin and fronted
 */
function bp_member_blog_update_capabilities() {
	$current_version = get_option( 'bp_member_blog_version' );
	$plugin_version = BUDDYPRESS_MEMBER_BLOG_VERSION; // Define your current plugin version here

	if ( version_compare( $current_version, $plugin_version, '<' ) ) {
		$default_capabilities = array(
			'subscriber' => array(
				'read' => true,
				'upload_files' => false,
				'edit_posts' => false,
				'unfiltered_html' => false,
			),
			'contributor' => array(
				'read' => true,
				'edit_posts' => true,
				'upload_files' => false,
				'unfiltered_html' => false,
			),
			// Add other roles and their expected capabilities as necessary
		);
		if( ! empty( $default_capabilities ) ){
			foreach ( $default_capabilities as $role => $caps ) {
				$role_object = get_role( $role );
				if ( $role_object ) {
					foreach ( $caps as $cap => $enabled ) {
						if ( $enabled && ! $role_object->has_cap( $cap ) ) {
							$role_object->add_cap( $cap );
						} elseif ( ! $enabled && $role_object->has_cap( $cap ) ) {
							$role_object->remove_cap( $cap );
						}
					}
				}
			}
		}

		update_option( 'bp_member_blog_version', $plugin_version );
	}
}

add_action( 'plugins_loaded', 'bp_member_blog_update_capabilities' );


/*
 *  Assign edit post cap for subscriber user on bp-member-blog shortcode
 */
add_action( 'wp_head', 'bp_member_blog_wp_head' );
function bp_member_blog_wp_head() {
	global $post;
	if ( is_user_logged_in() && is_a( $post, 'WP_Post' ) && isset( $post->post_content ) && has_shortcode( $post->post_content, 'bp-member-blog' ) ) {
		global $current_user;

		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		$user_roles               = array_intersect( $current_user->roles, $bp_member_blog_gen_stngs['bp_create_post'] );

		if ( ! empty( $user_roles ) && ! user_can( $current_user, 'edit_posts' ) ) {
			foreach ( $user_roles as $user_role ) {
				$role = get_role( $user_role );

				$role->add_cap( 'edit_published_posts' );
				$role->add_cap( 'edit_others_pages' );
				$role->add_cap( 'edit_others_posts' );
				$role->add_cap( 'edit_posts' );
				$role->add_cap( 'unfiltered_html' );
				$role->add_cap( 'edit_published_pages' );
				$role->add_cap( 'upload_files' );

			}
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

		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		$user_roles               = array_intersect( $current_user->roles, $bp_member_blog_gen_stngs['bp_create_post'] );

		if ( ! empty( $user_roles ) && ! user_can( $current_user, 'edit_posts' ) ) {
			foreach ( $user_roles as $user_role ) {
				$role->add_cap( 'upload_files' );
				$role->add_cap( 'edit_posts' );
				$role->add_cap( 'unfiltered_html' );
			}
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

/**
 * Add function for iframe
 *
 * @param  mixed $tags
 * @param  mixed $context
 * @return void
 */
function bp_member_blog_wpkses_post_tags( $tags, $context ) {
	if ( 'post' === $context ) {
		$tags['iframe'] = array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		);
	}

	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'bp_member_blog_wpkses_post_tags', 10, 2 );
