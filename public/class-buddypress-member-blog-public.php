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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $post,$wp_query;
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
		$post_id = $wp_query->get_queried_object_id();
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		$bp_add_new_page_id = ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) ) ? (int) $bp_member_blog_gen_stngs['bp_post_page'] : 0;
		$blog_slug  = apply_filters( 'bp_member_change_blog_slug', 'blog' );
		if( bp_is_activity_directory() || bp_is_group() || bp_is_user_activity() || bp_is_current_component( $blog_slug ) || $post_id === $bp_add_new_page_id ){

			wp_enqueue_style( 'dashicons' );

			wp_enqueue_style( 'selectize', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-member-blog-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $post,$wp_query;
		if ( ! is_user_logged_in() && is_a( $post, 'WP_Post' ) && isset( $post->post_content ) && has_shortcode( $post->post_content, 'bp-member-blog' ) ) {
			wp_redirect( site_url() );
			exit;
		}

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
		$blog_slug  = apply_filters( 'bp_member_change_blog_slug', 'blog' );
		$post_id = $wp_query->get_queried_object_id();
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		$bp_add_new_page_id = ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) ) ? (int) $bp_member_blog_gen_stngs['bp_post_page'] : 0;
		if( bp_is_activity_directory() || bp_is_group() || bp_is_user_activity() || bp_is_current_component( $blog_slug ) || $post_id === $bp_add_new_page_id ){
			wp_enqueue_script( 'selectize', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-member-blog-public.js', array( 'jquery' ), $this->version, false );
	
			$user_id                    = bp_displayed_user_id();
			$bp_publish_blogs           = array(
				'author'      => $user_id,
				'post_type'   => 'post',
				'post_status' => 'publish',
				'posts_per_page' => -1,
			);
			$current_user_publish_posts = count( get_posts( $bp_publish_blogs ) );
			$bp_draft_blogs             = array(
				'author'      => $user_id,
				'post_type'   => 'post',
				'post_status' => 'draft',
			);
			$current_user_draft_posts   = count( get_posts( $bp_draft_blogs ) );
			$bp_pending_blogs           = array(
				'author'      => $user_id,
				'post_type'   => 'post',
				'post_status' => 'pending',
			);
			$current_user_pending_posts = count( get_posts( $bp_pending_blogs ) );
	
			wp_localize_script(
				$this->plugin_name,
				'bpmb_ajax_object',
				array(
					'ajax_url'           => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'         => wp_create_nonce( 'bpmb-blog-nonce' ),
					'required_cat_text'  => esc_html__( 'Category name is required.', 'buddypress-member-blog' ),
					'publish_post_count' => $current_user_publish_posts,
					'pending_post_count' => $current_user_pending_posts,
					'draft_post_count'   => $current_user_draft_posts,
				)
			);
		}
	}


	/**
	 * Setup BuddyPress navigation
	 * Sets up user tabs
	 *
	 * @since 1.0.0
	 */
	public function buddypress_member_blog_setup_nav() {
		global  $bp,$current_user;
		$user_id = bp_displayed_user_id();

		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

		$is_my_profile = bp_is_my_profile();

		if ( $is_my_profile ) {
			$total_posts = bp_member_blog_get_total_posted( $user_id, $is_my_profile );
		} else {
			$total_posts = bp_member_blog_get_total_published_posts( $user_id );
		}
		// translators: %s is replaced with a count of total posts
		$blog_label = apply_filters( 'bp_member_change_blog_label', sprintf( esc_html__( 'Blog %s', 'buddypress-member-blog' ), '<span class="count">' . $total_posts . '</span>' ) );
		$blog_slug  = apply_filters( 'bp_member_change_blog_slug', 'blog' );
		if ( is_user_logged_in() ) {
			bp_core_new_nav_item(
				array(
					/* translators: %s: */
					'name'                => $blog_label,
					'slug'                => $blog_slug,
					'screen_function'     => array( $this, 'bp_member_posts' ),
					'default_subnav_slug' => $blog_slug,
					'position'            => 80,
					'item_css_id'         => 'bp-member-blog',
				)
			);
		}

		/*
		* Check current user role to allowed create post or not
		*
		*/
		$member_types = bp_get_member_type( bp_displayed_user_id(), false );
		$display_user = get_userdata( bp_displayed_user_id() );			
		if ( empty( $display_user ) ) {
			return;
		}
		if ( ! isset( $bp_member_blog_gen_stngs['bp_create_post'] ) ) {
			$bp_member_blog_gen_stngs['bp_create_post'] = array( 'administrator' );
		}

		if ( !empty( $display_user ) && ( ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) && ! empty( $bp_member_blog_gen_stngs['bp_create_post'] ) )
		|| ( isset( $bp_member_blog_gen_stngs['member_types'] ) && ! empty( $bp_member_blog_gen_stngs['member_types'] ) ) ) ) {
			$bp_member_blog_gen_stngs['bp_create_post'] = ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) ) ? $bp_member_blog_gen_stngs['bp_create_post'] : array();
			$bp_member_blog_gen_stngs['member_types']   = ( isset( $bp_member_blog_gen_stngs['member_types'] ) ) ? $bp_member_blog_gen_stngs['member_types'] : array();
			$user_roles                                 = array_intersect( (array) $display_user->roles, $bp_member_blog_gen_stngs['bp_create_post'] );
			$user_types                                 = array_intersect( (array) $member_types, $bp_member_blog_gen_stngs['member_types'] );
			// translators: %s is replaced with a count of total posts
			$blog_label                                 = apply_filters( 'bp_member_change_blog_label', sprintf( esc_html__( 'Blog %s', 'buddypress-member-blog' ), '<span class="count">' . $total_posts . '</span>' ) );
			$blog_slug                                  = apply_filters( 'bp_member_change_blog_slug', 'blog' );
			if ( empty( $user_roles ) && empty( $user_types ) ) {
				return;
			} else {
				bp_core_new_nav_item(
					array(
						/* translators: %s: */
						'name'                => $blog_label,
						'slug'                => $blog_slug,
						'screen_function'     => array( $this, 'bp_member_posts' ),
						'default_subnav_slug' => $blog_slug,
						'position'            => 80,
						'item_css_id'         => 'bp-member-blog',
					)
				);
			}
		}

		if (  ( ! is_user_logged_in() || get_current_user_id() != bp_displayed_user_id() ) ) {
			return;
		}
		
		// Add 'Blog' to the main navigation.
		bp_core_new_nav_item(
			array(
				/* translators: %s: */
				'name'                => class_exists( 'Youzify' ) ? sprintf( esc_html__('%1$s', 'buddypress-member-blog'), $blog_label ) :  sprintf( esc_html__('%1$s %2$s', 'buddypress-member-blog'), $blog_label, '<span class="count">' . $total_posts . '</span>' ),
				'slug'                => $blog_slug,
				'screen_function'     => array( $this, 'bp_member_posts' ),
				'default_subnav_slug' => $blog_slug,
				'position'            => 80,
				'item_css_id'         => 'bp-member-blog',
			)
		);
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'Published', 'buddypress-member-blog' ),
				'slug'            => $blog_slug,
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $blog_slug ),
				'parent_slug'     => $blog_slug,
				'screen_function' => array( $this, 'bp_member_posts' ),
				'position'        => 30,
			)
		);

		bp_core_new_subnav_item(
			array(
				'name'            => __( 'Pending', 'buddypress-member-blog' ),
				'slug'            => 'pending',
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $blog_slug ),
				'parent_slug'     => $blog_slug,
				'screen_function' => array( $this, 'bp_member_pending_posts' ),
				'position'        => 30,
			)
		);

		bp_core_new_subnav_item(
			array(
				'name'            => __( 'Draft Posts', 'buddypress-member-blog' ),
				'slug'            => 'draft',
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $blog_slug ),
				'parent_slug'     => $blog_slug,
				'screen_function' => array( $this, 'bp_member_draft_posts' ),
				'position'        => 30,
			)
		);

		$link                     = '';
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		if ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) && $bp_member_blog_gen_stngs['bp_post_page'] != 0 ) {
			$link = get_permalink( $bp_member_blog_gen_stngs['bp_post_page'] );
		}
		bp_core_new_subnav_item(
			array(
				'name'            => __( 'New Post', 'buddypress-member-blog' ),
				'slug'            => 'bp-new-post',
				'parent_url'      => trailingslashit( bp_loggedin_user_domain() . $blog_slug ),
				'parent_slug'     => $blog_slug,
				'screen_function' => array( $this, 'bp_member_new_post' ),
				'link'            => $link,
				'position'        => 30,
			)
		);
		
	}


	/**
	 * Adds the user's navigation in WP Admin Bar
	 *
	 * @since 1.0.0
	 */
	public function buddypress_member_blog_setup_admin_bar( $wp_admin_nav = array() ) {
		global $wp_admin_bar, $current_user;
		$blog_label   = apply_filters( 'bp_member_change_blog_label', 'Blog' );
		$bp_blog_slug = apply_filters( 'bp_member_change_blog_slug', 'blog' );
		$blog_slug    = bp_loggedin_user_domain() . $bp_blog_slug;

		// Menus for logged in user.
		if ( is_user_logged_in() ) {
			$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

			/*
			 * Check current user role to allowed create post or not
			 *
			 */
			$member_types = bp_get_member_type( get_current_user_id(), false );
			if ( ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) && ! empty( $bp_member_blog_gen_stngs['bp_create_post'] ) )
			|| ( isset( $bp_member_blog_gen_stngs['member_types'] ) && ! empty( $bp_member_blog_gen_stngs['member_types'] ) ) ) {
				$bp_member_blog_gen_stngs['bp_create_post'] = ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) ) ? $bp_member_blog_gen_stngs['bp_create_post'] : array();
				$bp_member_blog_gen_stngs['member_types']   = ( isset( $bp_member_blog_gen_stngs['member_types'] ) ) ? $bp_member_blog_gen_stngs['member_types'] : array();
				$user_roles                                 = array_intersect( (array) $current_user->roles, $bp_member_blog_gen_stngs['bp_create_post'] );
				$user_types                                 = array_intersect( (array) $member_types, $bp_member_blog_gen_stngs['member_types'] );
				if ( empty( $user_roles ) && empty( $user_types ) ) {
					return;
				}
			}

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'my-account-buddypress',
					'id'     => 'my-account-blog',
					'title'  => $blog_label,
					'href'   => trailingslashit( $blog_slug ),
				)
			);

			$create_new_post_page = $blog_slug . '/bp-new-post';
			if ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) && $bp_member_blog_gen_stngs['bp_post_page'] != 0 ) {
				$create_new_post_page = get_permalink( $bp_member_blog_gen_stngs['bp_post_page'] );
			}

			$href = trailingslashit( $create_new_post_page );

			// Keeping addnew post same if network activated.
			if ( is_multisite() ) {
				if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
					require_once ABSPATH . '/wp-admin/includes/plugin.php';
				}
				if ( is_plugin_active_for_network( basename( constant( 'BP_PLUGIN_DIR' ) ) . '/bp-loader.php' ) && is_plugin_active_for_network( basename( constant( 'BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL' ) ) . '/bp-user-blog.php' ) ) {
					$href = trailingslashit( get_blog_permalink( 1, $create_new_post_page ) );
				}
			}

			// Add add-new submenu.
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'my-account-blog',
					'id'     => 'my-account-blog-' . 'posts',
					'title'  => __( 'Posts', 'buddypress-member-blog' ),
					'href'   => trailingslashit( $blog_slug ),
				)
			);

			if ( $create_new_post_page ) {
				$wp_admin_bar->add_menu(
					array(
						'parent' => 'my-account-blog',
						'id'     => 'my-account-blog-' . __( 'add-new', 'buddypress-member-blog' ),
						'title'  => __( 'New Post', 'buddypress-member-blog' ),
						'href'   => $href,
					)
				);
			}
		}
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
	 * Display post blog template layout
	 *
	 * @since 1.0.0
	 */
	public function load_member_blog_content_nav_content() {
		bp_member_blog_load_template( 'posts.php' );
	}

	/**
	 * Handles My Draft Posts screen with the single post/edit post view
	 *
	 * @since 1.0.0
	 */
	public function bp_member_draft_posts() {
		add_action( 'bp_template_content', array( $this, 'load_member_draft_blogs_content_nav_content' ) );
		bp_core_load_template( 'members/single/plugins' );
	}

	/**
	 * Handles My Pending Posts screen with the single post/edit post view
	 *
	 * @since 1.0.0
	 */
	public function bp_member_pending_posts() {
		add_action( 'bp_template_content', array( $this, 'load_member_pending_blogs_content_nav_content' ) );
		bp_core_load_template( 'members/single/plugins' );
	}

	/**
	 * List of Draft Posts data
	 *
	 * @since 1.0.0
	 */
	public function load_member_draft_blogs_content_nav_content() {
		bp_member_blog_load_template( 'draft-posts.php' );
	}

	/**
	 * List of Pending Posts data
	 *
	 * @since 1.0.0
	 */
	public function load_member_pending_blogs_content_nav_content() {
		bp_member_blog_load_template( 'pending-posts.php' );
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

		$id = bp_action_variable( 0 );

		bp_member_blog_load_template( 'edit.php' );

	}

	/**
	 * Publish Post
	 *
	 * @since 1.0.0
	 */
	public function buddypress_member_blog_publish() {

		if ( ! bp_is_current_action( 'publish' ) ) {
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
	public function buddypress_member_blog_unpublish() {

		if ( ! bp_is_current_action( 'unpublish' ) ) {
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

		if ( ! bp_is_current_action( 'delete' ) ) {
			return;
		}

		$post_id = bp_action_variable( 0 );

		if ( ! $post_id ) {
			return;
		}
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		if ( isset( $bp_member_blog_gen_stngs['image_delete'] ) ) {
			$image_id = get_post_meta( $post_id, '_thumbnail_id', true );
			wp_delete_post( $image_id, true );
		}

		wp_delete_post( $post_id, true );

		bp_core_add_message( __( 'Post deleted successfully', 'buddypress-member-blog' ) );
		// redirect.
		wp_redirect( bp_member_blog_get_home_url() );
		exit( 0 );

	}


	/**
	 * Blogs posts submit from front-end.
	 */
	public function buddypress_member_blog_post_submit() {

		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'bp_member_blog_post' ) && ( isset( $_POST['bp_member_blog_form_subimitted'] ) || isset( $_POST['bp_member_blog_form_save'] ) ) && isset( $_POST['action'] ) && $_POST['action'] == 'bp_member_blog_post' ) {
			// for recaptcha
			do_action( 'buddypress_member_blog_post_save' );
			if ( array_key_exists( 'g-recaptcha-response', $_POST ) ) {
				if ( empty( $_POST['g-recaptcha-response'] ) ) {
					return;
				}
			}
			$user_id = get_current_user_id();
			$user = get_userdata($user_id);
			// for recaptcha
			$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

			$post_title = '';
			if ( ! empty( $_POST['bp_member_blog_post_title'] ) ) {
				$post_title = wp_kses_post( wp_unslash( $_POST['bp_member_blog_post_title'] ) );
			}

			$post_content = '';
			if ( isset( $_REQUEST['bp_member_blog_post_content'] ) && ! empty( $_POST['bp_member_blog_post_content'] ) ) {
				$post_content = wp_kses_post( wp_unslash( $_REQUEST['bp_member_blog_post_content'] ) );
			}
			if( isset( $bp_member_blog_gen_stngs['publish_post'] ) || in_array('administrator', $user->roles ) ){
                if( isset( $_POST['bp_member_blog_form_save'] ) ){
                    $post_status = 'draft';
                }else{
                    $post_status = 'publish';
                }
            }else{
                if( isset( $_POST['bp_member_blog_form_save'] ) ){
                    $post_status = 'draft';
                }else{
                    $post_status = 'pending';
                }
            }
			if ( isset( $_POST['post_id'] ) && $_POST['post_id'] != 0 && $_POST['post_id'] != '' ) {
				$user_id = get_current_user_id();
				$user = get_userdata($user_id);

				/* Update Post */
				$post_id   = wp_update_post(
					array(
						'ID'           => wp_kses_post( wp_unslash( $_POST['post_id'] ) ),
						'post_title'   => $post_title,
						'post_type'    => 'post',
						'post_content' => $post_content,
						'post_status'  => $post_status,
						'post_author'  => get_current_user_id(),
					)
				);
				$post_link = get_permalink( $post_id ); 
				if( ( function_exists('buddypress') && ! buddypress()->buddyboss ) ){
					if ( isset( $bp_member_blog_gen_stngs['publish_post'] ) || in_array('administrator', $user->roles ) ) {
						bp_core_add_message( __( 'Post updated successfully.', 'buddypress-member-blog' ) . '<span class="bp-blog-view-link"><a href="' . $post_link . '">' . __( 'View Post', 'buddypress-member-blog' ) . '</a></span>' );
					} else {
						bp_core_add_message( __( 'Your post is under review, It will appear after the approval.', 'buddypress-member-blog' ) );
					}
				}elseif( function_exists('buddypress') && buddypress()->buddyboss ){
					if ( ! isset( $bp_member_blog_gen_stngs['publish_post'] ) && ( ! isset( $user->roles ) || ! in_array( 'administrator', $user->roles, true ) ) ) {
						bp_core_add_message(
							__( 'Your post is under review. It will appear after approval.', 'buddypress-member-blog' )
						);
					}
				}
			} else {
				/* Create Post */
				$post_id = wp_insert_post(
					array(
						'post_title'   => $post_title,
						'post_type'    => 'post',
						'post_content' => $post_content,
						'post_status'  => $post_status,
						'post_author'  => get_current_user_id(),
					)
				);

				if ( isset( $bp_member_blog_gen_stngs['publish_post'] ) ) {
					$post_link = get_permalink( $post_id );
					bp_core_add_message( __( 'Post created successfully.', 'buddypress-member-blog' ) . '<span class="bp-blog-view-link"><a href="' . $post_link . '">' . __( 'View Post', 'buddypress-member-blog' ) . '</a></span>' );
				} else {
					bp_core_add_message( __( 'Your post is under review, It will appear after the approval.', 'buddypress-member-blog' ) );
				}
			}

			$post_category = '';
			if ( ! empty( $_POST['bp_member_blog_post_category'] ) ) {
				$post_category = map_deep( wp_unslash( $_POST['bp_member_blog_post_category'] ), 'sanitize_text_field' );
			}
			/* Assign Category. */
			wp_set_post_terms( $post_id, $post_category, 'category', false );

			$post_tag = '';
			if ( ! empty( $_POST['bp_member_blog_post_tag'] ) ) {
				$post_tag = map_deep( wp_unslash( $_POST['bp_member_blog_post_tag'] ), 'sanitize_text_field' );
			}
			/*  Assign Post Tags */
			wp_set_post_tags( $post_id, $post_tag, false );

			if ( isset( $_FILES['bp_member_blog_post_featured_image'] ) && ! empty( $_FILES['bp_member_blog_post_featured_image'] ) ) {

				// These files need to be included as dependencies when on the front end.
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';

				// Let WordPress handle the upload.
				// Remember, 'my_image_upload' is the name of our file input in our form above.
				$attachment_id = media_handle_upload( 'bp_member_blog_post_featured_image', $post_id );

				if ( ! is_wp_error( $attachment_id ) ) {
					update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
				}
			}
			
			
			do_action( 'buddypress_member_blog_post_submit', $post_id );
			
			if ( isset( $_POST['bp_member_blog_form_save'] ) ) {

				if ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) && $bp_member_blog_gen_stngs['bp_post_page'] != 0 ) {

					$url = get_permalink( $bp_member_blog_gen_stngs['bp_post_page'] ) ;

				} else {

					$url = bp_member_blog_get_edit_url( $post_id ) . '?is_draft=1';
				}
				bp_core_add_message( __( 'Post saved successfully.', 'buddypress-member-blog' ) );
				wp_redirect( $url );
				exit;
			}
			
			
			/* Redirect to single post page when user publish post */
			if ( isset( $bp_member_blog_gen_stngs['publish_post'] ) ) {
				$post_link = get_permalink( $post_id );
				wp_redirect( $post_link );
				exit;
			}
		}
	}

	/**
	 * Save Post.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post_ID Post ID.
	 * @param object $post Post Object.
	 * @param string $update  Update Post.
	 */
	public function buddypress_member_blog_save_post( $post_ID, $post, $update ) {
		global $update_post;
		if ( is_admin() ) {
			return;
		}

		if ( isset( $update_post ) && $update_post == true ) {
			return;
		}
		$update_post = true;
		if ( $update == true && $post_ID != '' ) {
			bp_core_add_message( __( 'Post updated successfully.', 'buddypress-member-blog' ) );
		}

		if ( $update == false && $post_ID != '' ) {
			bp_core_add_message( __( 'Post created successfully.', 'buddypress-member-blog' ) );
		}
	}


	/**
	 * Call acf_form_head() function on acf form
	 *
	 * @since 1.0.0
	 */
	public function buddypress_member_blog_wp_loaded() {
		global $wp_query, $post;

		if ( isset( $wp_query->query_vars['pagename'] ) && $wp_query->query_vars['pagename'] == 'bp-new-post' ) {
			global $update_post;
			$update_post = false;
		}

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'bp-member-blog' ) ) ) {
			global $update_post;
			$update_post = false;
		}
	}


	/**
	 * Edit Post data using shortcode.
	 *
	 * @since 1.0.0
	 * @param array|string $atts User defined attributes for this shortcode instance.
	 * @param string       $content Shortcode Content.
	 */
	public function buddypress_shortcodes_member_blog( $atts, $content = null ) {

		$bp = buddypress();

		if ( ! is_user_logged_in() ) {
			return $content;
		}

		global  $bp,$current_user;
		// Check current user role to allowed create post or not.
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

		$member_types = bp_get_member_type( get_current_user_id(), false );
		if ( ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) && ! empty( $bp_member_blog_gen_stngs['bp_create_post'] ) )
		|| ( isset( $bp_member_blog_gen_stngs['member_types'] ) && ! empty( $bp_member_blog_gen_stngs['member_types'] ) ) ) {
			$bp_member_blog_gen_stngs['bp_create_post'] = ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) ) ? $bp_member_blog_gen_stngs['bp_create_post'] : array();
			$bp_member_blog_gen_stngs['member_types']   = ( isset( $bp_member_blog_gen_stngs['member_types'] ) ) ? $bp_member_blog_gen_stngs['member_types'] : array();
			if( is_multisite() ){
				$current_roles = bp_member_get_user_roles();
			}else{
				$current_roles = $current_user->roles;
			}
			if( is_multisite() ){
				$member_types = bp_member_get_member_type();
			}else{
				$member_types = $member_types;
			}
			
			$user_roles                                 = array_intersect( (array) $current_roles, $bp_member_blog_gen_stngs['bp_create_post'] );
			$user_types                                 = array_intersect( (array) $member_types, $bp_member_blog_gen_stngs['member_types'] );
			if ( empty( $user_roles ) && empty( $user_types ) ) {
				ob_start();
				echo '<div class="bp-feedback bp-messages bp-template-notice error"><span class="bp-icon" aria-hidden="true"></span>';
				echo '<p>';
				esc_html_e( 'You are not allowed to access this page.', 'buddypress-member-blog' );
				echo '</p>';
				echo '</div>';
				return ob_get_clean();
			}
		}

		ob_start();
		?>
		<div class="buddypress-wrap">
			<?php
			if ( ! empty( $bp->template_message ) ) {
				$type = ( 'success' === $bp->template_message_type ) ? 'success' : 'error';
				?>
				<aside class="bp-feedback saved-successfully bp-messages bp-template-notice <?php echo esc_attr( $type ); ?>">
					<span class="bp-icon" aria-hidden="true"></span>
					<p><?php echo wp_kses_post( $bp->template_message ); ?></p>
				</aside>
				<?php
				$bp->template_message      = '';
				$bp->template_message_type = '';
			}

			bp_member_blog_load_template( 'edit.php' );
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Filter to edit the URL of the nav item.
	 *
	 * @since 3.0.0
	 *
	 * @param string $link     The URL for the nav item.
	 * @param object $nav_item The current nav item object.
	 */
	public function buddypress_member_blog_bp_nouveau_get_nav_link( $link, $nav_item ) {
		$bp_nouveau = bp_nouveau();
		$nav_item   = $bp_nouveau->current_nav_item;
		$link       = '#';
		if ( ! empty( $nav_item->link ) ) {
			$link = $nav_item->link;
		}
		if ( 'personal' === $bp_nouveau->displayed_nav && ! empty( $nav_item->primary ) ) {
			if ( bp_loggedin_user_domain() ) {
				$link = str_replace( bp_loggedin_user_domain(), bp_displayed_user_domain(), $link );
			} else {
				$link = trailingslashit( bp_displayed_user_domain() . $link );
			}
		}
		return $link;
	}

	/**
	 * Fire the 'bp_setup_nav' action, where plugins should register their navigation items.
	 */
	public function buddypress_member_blog_bp_legecy_get_nav_link() {
		$user_nav_items = buddypress()->members->nav->get_primary();
		if ( $user_nav_items ) {
			foreach ( $user_nav_items as $user_nav_item ) {
				if ( ! isset( $user_nav_item->css_id ) || ! $user_nav_item->css_id ) {
					continue;
				}
				remove_filter( 'bp_get_displayed_user_nav_' . $user_nav_item->css_id, 'BP\Rewrites\bp_get_displayed_user_nav', 1, 2 );
			}
		}

	}

	/**
	 * Check if a contributor have the needed rights to upload images and add this capabilities if needed.
	 */
	public function buddypress_member_blog_users_to_upload_media() {
		$subscriber = get_role( 'subscriber' );
		if ( ! empty( $subscriber ) ) {
			$subscriber->add_cap( 'upload_files' );
		}

	}

	/**
	 * Actions Performed To Add BP bpmb Category.
	 *
	 * @author  wbcomdesigns
	 * @since   1.0.0
	 * @access  public
	 */
	public function bpmb_add_category_front_end() {
		check_ajax_referer( 'bpmb-blog-nonce', 'security_nonce' );
		if ( isset( $_POST['action'] ) && 'bpmb_add_category_front_end' === $_POST['action'] ) {
			if ( isset( $_POST['name'] ) ) {
				$term       = sanitize_text_field( wp_unslash( $_POST['name'] ) );
				$term_label = sanitize_text_field( wp_unslash( $_POST['name'] ) );
				if ( str_contains( $term, ' ' ) ) {
					$term_arr   = explode( ' ', $term );
					$output_arr = array_map(
						function( $val ) {
							return strtolower( $val );
						},
						$term_arr
					);
					$term       = implode( '_', $output_arr );
				} else {
					$term = strtolower( $term );
				}
				$taxonomy    = 'category';
				$term_exists = term_exists( $term, $taxonomy );
				if ( 0 === $term_exists || null === $term_exists ) {
					$demo = wp_insert_term(
						$term_label,
						$taxonomy,
						array(
							'description' => '',
							'slug'        => $term,
						)
					);
				}
				$cat_id = isset( $demo['term_id'] ) ? $demo['term_id'] : '';
				echo esc_html( $cat_id );
			}
			die;
		}
	}
}
