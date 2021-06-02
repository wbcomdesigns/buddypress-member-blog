<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Member_Blog_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-member-blog-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-member-blog-public.js', array( 'jquery' ), $this->version, false );

	}	
	
	
	/**
	 * Setup BuddyPress navigation
	 * Sets up user tabs
	 *	 
	 * @since 1.0.0
	 */
	
	public function buddypress_member_blog_setup_nav(){
		
		if ( ! is_user_logged_in() || get_current_user_id() != bp_displayed_user_id() ) {
			return;
		}	
		
		
		global  $bp;
		$user_id       = bp_displayed_user_id();
		$is_my_profile = bp_is_my_profile();
		
		
		// Add 'Blog' to the main navigation.
		bp_core_new_nav_item(
				array(
					'name'                => esc_html__( 'BP Blog', 'buddypress-member-blog' ),
					'slug'                => 'bp-member-blog',
					'screen_function'     => array( $this, 'bp_member_posts' ),
					'default_subnav_slug' => 'bp-member-blog',
					'position'            => 80,
					'item_css_id'         => 'bp-member-blog',
				)
			);
		bp_core_new_subnav_item(
				array(
					'name'                  => __( 'Posts', 'buddypress-member-blog' ),
					'slug'                  => 'bp-member-blog',
					'parent_url'            => trailingslashit( bp_loggedin_user_domain() . 'bp-member-blog' ),
					'parent_slug'           => 'bp-member-blog',
					'screen_function'       => array( $this, 'bp_member_posts' ),
					'position'        		=> 30,
				)
			);
		bp_core_new_subnav_item(
				array(
					'name'                  => __( 'New Post', 'buddypress-member-blog' ),
					'slug'                  => 'bp-new-post',
					'parent_url'            => trailingslashit( bp_loggedin_user_domain() . 'bp-member-blog' ),
					'parent_slug'           => 'bp-member-blog',
					'screen_function'       => array( $this, 'bp_member_new_post' ),
					'position'        		=> 30,
				)
			);
	}
	
	
	/**
	 * Handles My Posts screen with the single post/edit post view
	 *
	 * @since 1.0.0
	 */
	public function bp_member_posts() {
		
		add_action( 'bp_template_content', array( $this, 'load_member_blog_content_nav_content' ) );
		bp_core_load_template( 'members/single/plugins' );
	}
	
	/**
	 * List of Posts data
	 *
	 * @since 1.0.0
	 */
	public function load_member_blog_content_nav_content() {
		 load_template( BUDDYPRESS_MEMBER_BLOG_PLUGIN_PATH . 'templates/posts.php' );
	}
	
	
	/**
	 * New  post form
	 *
	 * @since 1.0.0
	 */
	public function bp_member_new_post() {
		// the new post form.
		add_action( 'bp_template_content', array( $this, 'get_edit_member_post_data' ) );

		bp_core_load_template( array( 'members/single/plugins' ) );
	}
	
	/**
	 * Edit Post data
	 *
	 * @since 1.0.0
	 */
	public function get_edit_member_post_data() {
		load_template( BUDDYPRESS_MEMBER_BLOG_PLUGIN_PATH . 'templates/edit.php' );
	}
	
	/**
	 * Publish Post
	 *
	 * @since 1.0.0
	 */
	
	public function buddypress_member_blog_publish(){
		
		if ( !bp_is_current_action( 'publish' )  ) {
			return;
		}
		
		$id = bp_action_variable( 0 );		
		if ( ! $id ) {
			return;
		}
		
		$post = get_post( $id );
		// generate slug.
		if ( $post && empty( $post->post_name ) ) {
			$post->post_name = sanitize_title( $post->post_title );
		}
		$post->post_status = 'publish';
		wp_update_post( $post );
		bp_core_add_message( __( 'Post Published', 'buddypress-member-blog' ) );
	

		bp_core_redirect( bp_member_blog_get_home_url() );
		exit( 0 );
	}
	
	/**
	 * Unpublish Post
	 *
	 * @since 1.0.0
	 */
	
	public function buddypress_member_blog_unpublish(){		
		
		
		if ( !bp_is_current_action( 'unpublish' )  ) {
			return;
		}
		
		$id = bp_action_variable( 0 );

		if ( ! $id ) {
			return;
		}
		
		$post                = get_post( $id, ARRAY_A );
		$post['post_status'] = 'draft';
		wp_update_post( $post );
		// unpublish.
		bp_core_add_message( __( 'Post unpublished', 'buddypress-member-blog' ) );

		bp_core_redirect( bp_member_blog_get_home_url() );
		exit( 0 );
	}
	
	/**
	 * Delete Post
	 *
	 * @since 1.0.0
	 */
	
	public function buddypress_member_blog_delete() {
		
		if ( !bp_is_current_action( 'delete' )  ) {
			return;
		}
		
		$post_id = bp_action_variable( 0 );

		if ( ! $post_id ) {
			return;
		}

		wp_delete_post( $post_id, true );
		bp_core_add_message( __( 'Post deleted successfully' ), 'buddypress-member-blog' );
		// redirect.
		wp_redirect( bp_member_blog_get_home_url() );
		exit( 0 );
		
	}

}
