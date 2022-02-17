<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Member_Blog_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Member_Blog_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Member_Blog_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'selectize', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-member-blog-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Member_Blog_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Member_Blog_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'selectize', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-member-blog-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add admin sub menu for plugin settings.
	 *
	 * @since 1.0.0
	 */
	public function bp_member_blog_add_plugin_settings_page() {

		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {

			add_menu_page( esc_html__( 'WB Plugins', 'buddypress-member-blog' ), esc_html__( 'WB Plugins', 'buddypress-member-blog' ), 'manage_options', 'wbcomplugins', array( $this, 'bp_member_blog_settings_page' ), 'dashicons-lightbulb', 59 );

			add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'buddypress-member-blog' ), esc_html__( 'General', 'buddypress-member-blog' ), 'manage_options', 'wbcomplugins' );
		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'BuddyPress Member BlogSettings Page', 'buddypress-member-blog' ), esc_html__( 'Member Blog', 'buddypress-member-blog' ), 'manage_options', 'buddypress-member-blog', array( $this, 'bp_member_blog_settings_page' ) );

	}

	/**
	 * Plugin register settings.
	 *
	 * @since 1.0.0
	 */
	public function bp_member_blog_add_plugin_settings() {
		register_setting( 'bp_member_blog_general_settigs', 'bp_member_blog_gen_stngs' );
	}

	/**
	 * Callable function for settings page.
	 *
	 * @since 1.0.0
	 */
	public function bp_member_blog_settings_page() {
		$current          = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'welcome';
		$member_blog_tabs = apply_filters(
			'bp_member_blog_admin_setting_tabs',
			array(
				'welcome' => __( 'Welcome', 'buddypress-member-blog' ),
				'general' => __( 'General', 'buddypress-member-blog' ),
			)
		);
		?>
		<div class="wrap">
			<hr class="wp-header-end">
			<div class="wbcom-wrap bp-member-blog-wrap">
				<div class="blpro-header">
					<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
					<h1 class="wbcom-plugin-heading">
						<?php esc_html_e( 'BuddyPress Member Blog Settings', 'buddypress-member-blog' ); ?>
					</h1>
				</div>
				<div class="wbcom-admin-settings-page">
					<div class="wbcom-tabs-section">
						<div class="nav-tab-wrapper">
							<div class="wb-responsive-menu">
								<span><?php esc_html_e( 'Menu', 'buddypress-member-blog' ); ?></span>
								<input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn">
								<label class="wb-toggle-icon" for="wb-toggle-btn">
									<span class="wb-icon-bars"></span>
								</label>
							</div>
							<ul>
							<?php
							foreach ( $member_blog_tabs as $bmpro_tab => $bmpro_name ) {
								$class = ( $bmpro_tab == $current ) ? 'nav-tab-active' : '';
								echo '<li><a class="nav-tab ' . esc_attr( $class ) . '" href="admin.php?page=buddypress-member-blog&tab=' . esc_attr( $bmpro_tab ) . '">' . esc_html( $bmpro_name ) . '</a></li>';
							}
							?>
							</ul>
						</div>
					</div>
					<?php
					include 'inc/bp-member-blog-options-page.php';
					do_action( 'bp_member_blog_tab_contents' );
					?>
				</div>
			</div> <!-- closing div class wbcom-wrap -->
		</div> <!-- closing div class wrap -->
		<?php
	}

}
