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
		
		wp_enqueue_style( 'selectize', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), $this->version, 'all' );
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
		wp_enqueue_script( 'selectize', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );
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
		
		global  $bp,$current_user;
		$user_id       = bp_displayed_user_id();
		$is_my_profile = bp_is_my_profile();
		
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		
		
		// Add 'Blog' to the main navigation.
		bp_core_new_nav_item(
				array(
					'name'                => esc_html__( 'Blog', 'buddypress-member-blog' ),
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
		
		/* 
		 * Check current user role to allowed create post or not
		 *
		 */
		 
		$member_types = bp_get_member_type( get_current_user_id(), false );		
		if ( (isset($bp_member_blog_gen_stngs['bp_create_post']) && !empty($bp_member_blog_gen_stngs['bp_create_post']) )
		|| (isset($bp_member_blog_gen_stngs['member_types']) && !empty($bp_member_blog_gen_stngs['member_types']) ) ) {
			$bp_member_blog_gen_stngs['bp_create_post'] = ( isset($bp_member_blog_gen_stngs['bp_create_post'])) ? $bp_member_blog_gen_stngs['bp_create_post'] : array();
			$bp_member_blog_gen_stngs['member_types'] = ( isset($bp_member_blog_gen_stngs['member_types'])) ? $bp_member_blog_gen_stngs['member_types'] : array();
			$user_roles = array_intersect ((array) $current_user->roles, $bp_member_blog_gen_stngs['bp_create_post']);
			$user_types = array_intersect ((array) $member_types, $bp_member_blog_gen_stngs['member_types']);
			if ( empty($user_roles) && empty($user_types)) {
				return;
			}
		}
		
		$link = '';
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );	
		if ( isset($bp_member_blog_gen_stngs['bp_post_page']) && $bp_member_blog_gen_stngs['bp_post_page'] != 0 ) {
			$link = get_permalink( $bp_member_blog_gen_stngs['bp_post_page'] );			
		} 
		bp_core_new_subnav_item(
				array(
					'name'                  => __( 'New Post', 'buddypress-member-blog' ),
					'slug'                  => 'bp-new-post',
					'parent_url'            => trailingslashit( bp_loggedin_user_domain() . 'bp-member-blog' ),
					'parent_slug'           => 'bp-member-blog',
					'screen_function'       => array( $this, 'bp_member_new_post' ),
					'link'					=> $link,
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
		
		$id = bp_action_variable( 0 );		
		
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
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		if ( isset($bp_member_blog_gen_stngs['image_delete'])) {
			$image_id = get_post_meta( $post_id, '_thumbnail_id', true );
			wp_delete_post( $image_id, true );
		}

		wp_delete_post( $post_id, true );
		
		bp_core_add_message( __( 'Post deleted successfully' ), 'buddypress-member-blog' );
		// redirect.
		wp_redirect( bp_member_blog_get_home_url() );
		exit( 0 );
		
	}
	
	
	public function buddypress_member_blog_post_submit() {
				
		
		if ( isset( $_POST[ '_wpnonce' ] ) && wp_verify_nonce( $_POST[ '_wpnonce' ], 'bp_member_blog_post' ) && isset( $_POST[ 'bp_member_blog_form_subimitted' ] )  && isset($_POST['action']) && $_POST['action'] == 'bp_member_blog_post' ) {
			
			$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
			
			$post_title = '';
			if ( ! empty( $_POST['bp_member_blog_post_title'] ) ) {
				$post_title =  $_POST['bp_member_blog_post_title'] ;
			}

			$post_content = '';
			if ( ! empty( $_POST['bp_member_blog_post_content'] ) ) {
				$post_content = $_REQUEST['bp_member_blog_post_content']  ;
			}
			
			if ( isset($_POST['bp_member_blog_post_id']) && $_POST['bp_member_blog_post_id'] != 0 && $_POST['bp_member_blog_post_id'] != '' ) {
				
				/* Update Post */
				$post_id = wp_update_post( array(
						'ID'			=> $_POST['bp_member_blog_post_id'],
						'post_title'  	=> $post_title,
						'post_type'   	=> 'post',
						'post_content'   => $post_content,
						'post_status' 	=> ( isset($bp_member_blog_gen_stngs['publish_post'])) ? 'publish' : 'pending',
						'post_author' 	=> get_current_user_id(),
					) );
				bp_core_add_message( __( 'Post updated successfully.', 'buddypress-member-blog' ) );
				
			} else {
				/* Create Post */
				$post_id = wp_insert_post( array(
						'post_title'  	=> $post_title,
						'post_type'   	=> 'post',
						'post_content'   => $post_content,
						'post_status' 	=> ( isset($bp_member_blog_gen_stngs['publish_post'])) ? 'publish' : 'pending',
						'post_author' 	=> get_current_user_id(),
					) );
				
				bp_core_add_message( __( 'Post created successfully.', 'buddypress-member-blog' ) );
			}
			
			/*  Assign Category */
			wp_set_post_terms( $post_id, $_POST['bp_member_blog_post_category'],'category', false);
			
			/*  Assign Post Tags */
			wp_set_post_terms( $post_id, $_POST['bp_member_blog_post_tag'],'post_tag', false);
			
			
			if ( isset($_FILES['bp_member_blog_post_featured_image']) && !empty($_FILES['bp_member_blog_post_featured_image']) ) {
				
				// These files need to be included as dependencies when on the front end.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				 
				// Let WordPress handle the upload.
				// Remember, 'my_image_upload' is the name of our file input in our form above.
				$attachment_id = media_handle_upload( 'bp_member_blog_post_featured_image', $post_id );
				 
				if ( !is_wp_error( $attachment_id ) ) {
					update_post_meta( $post_id,'_thumbnail_id', $attachment_id );
				} 
				
			}
			
		}
	}
	
	/**
	 * Save Post
	 *
	 * @since 1.0.0
	 */
	public function buddypress_member_blog_save_post( $post_ID, $post,  $update) {
		global $update_post;
		if ( is_admin() ) {
			return;			
		}
		
		if ( isset($update_post) && $update_post == true) {
			return;
		}
		/*
		if ( isset($_POST['acf']['field_60b73fa05b244']) ) {
			$update_post = true;
			$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
			$save = array(
						'ID'			=> $post_ID,
						'post_status'	=> ( isset($bp_member_blog_gen_stngs['publish_post'])) ? 'publish' : 'pending',												
						'post_content'	=> $_POST['acf']['field_60b73fa05b244'],
					);
			wp_update_post( $save );
		}
		*/
		/*
		if ( isset($_POST['acf']['field_60b73fe35b246']) && $_POST['acf']['field_60b73fe35b246'] !='') {
			update_post_meta( $post_ID,'_thumbnail_id', $_POST['acf']['field_60b73fe35b246'] );
		} else {
			delete_post_meta( $post_ID, '_thumbnail_id' );
		}
		*/
		$update_post = true;
		if ( $update == true && $post_ID != '' ) {
			bp_core_add_message( __( 'Post updated successfully.', 'buddypress-member-blog' ) );
		}
		
		if ( $update == false && $post_ID != '' ) {
			bp_core_add_message( __( 'Post created successfully.', 'buddypress-member-blog' ) );
		}
	}
	
	
	/**
	 * call acf_form_head() function on acf form
	 *
	 * @since 1.0.0
	 */
	
	public function buddypress_member_blog_wp_loaded() {
		global $wp_query, $post;
		
		if ( isset($wp_query->query_vars['pagename']) && $wp_query->query_vars['pagename'] == 'bp-new-post' ) {
			global $update_post;
			$update_post = false;
			acf_form_head();
		}
		
		if( isset($post->post_content) && ( has_shortcode( $post->post_content, 'bp-member-blog' ) ) ) {
			global $update_post;
			$update_post = false;
			acf_form_head();
		}
	}
	
	
	/**
	 * Edit Post data using shortcode 
	 *
	 * @since 1.0.0
	 */
	
	
	public function buddypress_shortcodes_member_blog( $atts, $content = null ) {
		
		$bp = buddypress();
		
		ob_start();
		?>
		<div class="buddypress-wrap">
			<?php
			if ( !empty( $bp->template_message ) ) {
				$type    = ( 'success' === $bp->template_message_type ) ? 'success' : 'error';
				?>
				<aside class="bp-feedback bp-messages bp-template-notice <?php echo esc_attr( $type ); ?>">
					<span class="bp-icon" aria-hidden="true"></span>
					<?php echo $bp->template_message; ?>
				</aside>
			<?php
			}
			
			load_template( BUDDYPRESS_MEMBER_BLOG_PLUGIN_PATH . 'templates/edit.php' );
			?>
		</div>
		<?php

		return ob_get_clean();
	}

}
