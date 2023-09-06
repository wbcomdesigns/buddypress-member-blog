<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Member_Blog {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Buddypress_Member_Blog_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BUDDYPRESS_MEMBER_BLOG_VERSION' ) ) {
			$this->version = BUDDYPRESS_MEMBER_BLOG_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'buddypress-member-blog';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Buddypress_Member_Blog_Loader. Orchestrates the hooks of the plugin.
	 * - Buddypress_Member_Blog_i18n. Defines internationalization functionality.
	 * - Buddypress_Member_Blog_Admin. Defines all hooks for the admin area.
	 * - Buddypress_Member_Blog_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-member-blog-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-member-blog-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-buddypress-member-blog-admin.php';

		/* Enqueue wbcom plugin folder file. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-admin-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-buddypress-member-blog-public.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/bp-member-blog-fields.php';
		$this->loader = new Buddypress_Member_Blog_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Buddypress_Member_Blog_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Buddypress_Member_Blog_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Buddypress_Member_Blog_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/* Plugin settings page */
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'bp_member_blog_add_plugin_settings_page' );

		/* Plugin register settings */
		$this->loader->add_action( 'admin_init', $plugin_admin, 'bp_member_blog_add_plugin_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wbcom_hide_all_admin_notices_from_setting_page' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Buddypress_Member_Blog_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'bp_setup_nav', $plugin_public, 'buddypress_member_blog_setup_nav', 100 );
		$this->loader->add_action( 'bp_setup_admin_bar', $plugin_public, 'buddypress_member_blog_setup_admin_bar', 100 );

		$this->loader->add_action( 'bp_actions', $plugin_public, 'buddypress_member_blog_publish' );
		$this->loader->add_action( 'bp_actions', $plugin_public, 'buddypress_member_blog_unpublish' );
		$this->loader->add_action( 'bp_actions', $plugin_public, 'buddypress_member_blog_delete' );
		$this->loader->add_action( 'init', $plugin_public, 'buddypress_member_blog_users_to_upload_media' );

		// $this->loader->add_action( 'save_post', $plugin_public, 'buddypress_member_blog_save_post', 999, 3);
		// $this->loader->add_action( 'wp_head', $plugin_public, 'buddypress_member_blog_wp_loaded', 999, 3);
		$this->loader->add_shortcode( 'bp-member-blog', $plugin_public, 'buddypress_shortcodes_member_blog' );

		$this->loader->add_action( 'wp_loaded', $plugin_public, 'buddypress_member_blog_post_submit' );

		// if ( in_array( 'bp-rewrites/class-bp-rewrites.php', get_option( 'active_plugins' ) ) ) {
		// $this->loader->add_filter( 'bp_nouveau_get_nav_link', $plugin_public, 'buddypress_member_blog_bp_nouveau_get_nav_link', 10, 2 );
		// $this->loader->add_action( 'bp_setup_nav', $plugin_public, 'buddypress_member_blog_bp_legecy_get_nav_link', 9999 );
		// }

		$this->loader->add_action( 'wp_ajax_bpmb_add_category_front_end', $plugin_public, 'bpmb_add_category_front_end' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Buddypress_Member_Blog_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
