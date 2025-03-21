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
		$rtl_css = is_rtl() ? '-rtl' : '';

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$css_extension = '.css';
		} else {
			$css_extension = '.min.css';
		}

		wp_enqueue_style( 'selectize', plugin_dir_url( __FILE__ ) . 'css/vendor/selectize.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $rtl_css . '/buddypress-member-blog-admin' . $css_extension, array(), $this->version, 'all' );
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

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$js_extension = '.js';
		} else {
			$js_extension = '.min.js';
		}

		wp_enqueue_script( 'selectize', plugin_dir_url( __FILE__ ) . 'js/vendor/selectize.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-member-blog-admin' . $js_extension, array( 'jquery' ), $this->version, false );
	}

		/**
		 * Hide all notices from the setting page.
		 *
		 * @return void
		 */
	public function wbcom_hide_all_admin_notices_from_setting_page() {
		$wbcom_pages_array  = array( 'wbcomplugins', 'wbcom-plugins-page', 'wbcom-support-page', 'buddypress-member-blog' );
		$wbcom_setting_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';

		if ( in_array( $wbcom_setting_page, $wbcom_pages_array, true ) ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
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
		$current          = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'welcome';
		$member_blog_tabs = apply_filters(
			'bp_member_blog_admin_setting_tabs',
			array(
				'welcome'                => __( 'Welcome', 'buddypress-member-blog' ),
				'general'                => __( 'General', 'buddypress-member-blog' ),
				'restriction-pro'        => __( 'Restrictions', 'buddypress-member-blog' ),
				'group-integration-pro'  => __( 'Groups Integration', 'buddypress-member-blog' ),
				'notifications-pro'      => __( 'Notifications', 'buddypress-member-blog' ),
				'email-notification-pro' => __( 'Email Notification Settings', 'buddypress-member-blog' ),
			)
		);
		?>
		<div class="wrap">
			<div class="wbcom-bb-plugins-offer-wrapper">
				<div id="wb_admin_logo">
					<a href="https://wbcomdesigns.com/downloads/buddypress-community-bundle/?utm_source=pluginoffernotice&utm_medium=community_banner" target="_blank">
						<img src="<?php echo esc_url( BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL ) . 'admin/wbcom/assets/imgs/wbcom-offer-notice.png'; ?>">
					</a>
				</div>
			</div>
			<div class="wbcom-wrap bp-member-blog-wrap">
				<div class="blpro-header">
					<div class="wbcom_admin_header-wrapper">
						<div id="wb_admin_plugin_name">
							<?php
							if ( class_exists( 'BuddyPress_Member_Blog_Pro' ) ) {
								esc_html_e( 'BuddyPress Member Blog Pro', 'buddypress-member-blog' );
							} else {
								esc_html_e( 'BuddyPress Member Blog', 'buddypress-member-blog' );
							}
							?>
							<span>
							<?php
							/* translators: %s: */
							if ( class_exists( 'BuddyPress_Member_Blog_Pro' ) ) {
								// translators: %s is replaced with the plugin version
								printf( esc_html__( 'Version %s', 'buddypress-member-blog' ), esc_html( BuddyPress_Member_Blog_Pro_PLUGIN_VERSION ) );
							} else {
								// translators: %s is replaced with the plugin version
								printf( esc_html__( 'Version %s', 'buddypress-member-blog' ), esc_html( BUDDYPRESS_MEMBER_BLOG_VERSION ) );
							}
							?>
							</span>
						</div>
						<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
					</div>
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
								$class     = ( $bmpro_tab == $current ) ? 'nav-tab-active' : '';
								$bmb_nonce = wp_create_nonce( 'bmb_nonce' );
								echo '<li id="' . esc_attr( $bmpro_tab ) . '"><a class="nav-tab ' . esc_attr( $class ) . '" href="admin.php?page=buddypress-member-blog&tab=' . esc_attr( $bmpro_tab ) . '&nonce=' . esc_attr( $bmb_nonce ) . '">' . esc_html( $bmpro_name ) . '</a></li>';
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
