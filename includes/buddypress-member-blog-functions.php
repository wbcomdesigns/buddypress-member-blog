<?php
/**
 * BuddyPress Member Blog plugin functions file.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/admin/includes
 */

/**
 * Get the url of the bp member blog component for the given user
 *
 * @param int|bool $user_id id of user.
 *
 * @return string
 */
function bp_member_blog_get_home_url( $user_id = false ) {

	if ( ! $user_id ) {
		$user_id = bp_displayed_user_id();
	}

	$url = bp_member_blog_get_user_url( $user_id ) . 'blog' . '/';

	return $url;
}


/**
 * Link to create new post
 *
 * @return string
 */
function bp_member_blog_get_new_url() {

	$bp = buddypress();

	$user_id = get_current_user_id();

	if ( ! $user_id ) {
		return '';
	}

	// if we are here, we can allow user to edit the post.
	return bp_member_blog_get_user_url( $user_id ) . 'blog' . '/edit/';
}


/**
 * Get a link that allows to publish/unpublish the post
 *
 * @param int    $post_id post id.
 * @param string $label_ac label activate.
 * @param string $label_de label deactivate.
 *
 * @return string link
 */
function bp_member_blog_get_post_publish_unpublish_link( $post_id = 0, $label_ac = '', $label_de = '' ) {

	if ( ! $post_id ) {
		return '';
	}

	if ( get_post_field( 'post_status', $post_id ) == 'pending' ) {
		return '';
	}

	$is_published = bp_member_blog_is_post_published( $post_id );

	$post = get_post( $post_id );

	$url = '';
	$url = bp_member_blog_get_post_publish_unpublish_url( $post_id );

	if ( empty( $label_ac ) ) {
		$label_ac = __( 'Publish', 'buddypress-member-blog' );
	}

	if ( empty( $label_de ) ) {
		$label_de = __( 'Unpublish', 'buddypress-member-blog' );
	}

	if ( $is_published ) {
		$link = "<a href='{$url}'>{$label_de}</a>";
	} else {
		$link = "<a href='{$url}'>{$label_ac}</a>";
	}

	return $link;
}


/**
 * Get the url for publishing/unpublishing the post
 *
 * @param int $post_id post id.
 *
 * @return string
 */
function bp_member_blog_get_post_publish_unpublish_url( $post_id = 0 ) {

	if ( ! $post_id ) {
		return '';
	}

	$post = get_post( $post_id );
	$url  = '';

	// check if post is published.
	$url = bp_member_blog_get_user_url( $post->post_author ) . 'blog' . '/';

	if ( bp_member_blog_is_post_published( $post_id ) ) {
		$url = $url . 'unpublish/' . $post_id . '/';
	} else {
		$url = $url . 'publish/' . $post_id . '/';
	}

	return $url;

}

/**
 * Is this post published?
 *
 * @param int $post_id post id.
 *
 * @return bool
 */
function bp_member_blog_is_post_published( $post_id ) {
	return get_post_field( 'post_status', $post_id ) == 'publish';
}

/**
 * Get the link for editing this Post
 *
 * @param int    $id post id.
 * @param string $label label.
 *
 * @return string
 */
function bp_member_blog_get_edit_link( $id = 0, $label = '' ) {

	if ( empty( $label ) ) {
		$label = __( 'Edit', 'buddypress-member-blog' );
	}
	$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

	if ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) && $bp_member_blog_gen_stngs['bp_post_page'] != '' ) {
		if ( empty( $id ) ) {
			$id = get_the_ID();
		}
		$url = get_permalink( $bp_member_blog_gen_stngs['bp_post_page'] ) . '?post_id=' . $id . '&action=edit';

	} else {

		$url = bp_member_blog_get_edit_url( $id );
	}

	if ( ! $url ) {
		return '';
	}

	return "<a href='{$url}'>{$label}</a>";
}

/**
 * Get the url of the Post for editing
 *
 * @param int $post_id post id.
 *
 * @return string
 */
function bp_member_blog_get_edit_url( $post_id = 0 ) {

	$bp = buddypress();

	$user_id = get_current_user_id();

	if ( ! $user_id ) {
		return '';
	}

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}
	// check if current user can edit the post.
	$post = get_post( $post_id );

	if ( $post->post_author != $user_id && ! is_super_admin() ) {
		return '';
	}

	$action_name = 'bp-new-post';
	$active_plugins = get_option( 'active_plugins' );
	if ( in_array( 'buddypress-member-blog-pro/class-buddypress-member-blog-pro.php', $active_plugins ) ) {
		$bp_blog_pro_option = get_option( 'bp_member_blog_gen_stngs' );
		$blog_slug = isset( $bp_blog_pro_option['blog_slug'] ) && ( '' !== $bp_blog_pro_option['blog_slug'] ) ? $bp_blog_pro_option['blog_slug'] : 'blog';
	} else {
		$blog_slug = 'blog';
	}

	// if we are here, we can allow user to edit the post.
	return bp_member_blog_get_user_url( $post->post_author ) . $blog_slug . '/bp-new-post/?post_id=' . $post->ID . '&action=edit';
}


/**
 * Get delete link for post
 *
 * @param int    $id post id.
 * @param string $label label.
 *
 * @return string
 */
function bp_member_blog_get_delete_link( $id = 0, $label = '' ) {

	if ( empty( $label ) ) {
		$label = __( 'Delete', 'buddypress-member-blog' );
	}

	$bp = buddypress();

	$post = get_post( $id );

	$action_name = 'delete';

	$url = bp_member_blog_get_user_url( $post->post_author ) . 'blog' . "/{$action_name}/" . $post->ID . '/';

	return "<a href='{$url}' class='confirm' >{$label}</a>";

}



/**
 * Generate pagination links
 *
 * @global WP_Query $wp_query
 */
function bp_member_blog_paginate() {

	// get total number of pages.
	global $wp_query;
	$total = $wp_query->max_num_pages;

	// only bother with the rest if we have more than 1 page!
	if ( $total > 1 ) {
		$base = " ";
		// get the current page.
		$current_page = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		$user_id  = bp_displayed_user_id();
		// structure of “format” depends on whether we’re using pretty permalinks.
		$format = '?paged=%#%';
		$blog_slug  = apply_filters( 'bp_member_change_blog_slug', 'blog' );		
		if ( $blog_slug == bp_current_action() ) {
			$base = trailingslashit( bp_member_blog_get_user_url( $user_id ) . $blog_slug );
		}
		echo wp_kses_post(
			paginate_links(
				array(
					'base'      => $base . '%_%',
					'format'    => $format,
					'current'   => $current_page,
					'total'     => $total,					
				)
			)
		);

	}
}

/**
 * Default post information to use when populating the "Write Post" form.
 * A clone of get_default_post_to_edit ( wp-admin/includes/post.php
 *  *
 *
 * @param string $post_type Optional. A post type string. Default 'post'.
 * @param bool   $create_in_db Optional. Whether to insert the post into database. Default false.
 *
 * @return WP_Post Post object containing all the default post data as attributes
 */
function bp_member_blog_get_default_post_to_edit( $post_type = 'post', $create_in_db = false ) {
	$post_title = '';
	if ( ! empty( $_REQUEST['post_title'] ) ) { //phpcs:ignore
		$post_title = esc_html( wp_unslash( $_REQUEST['post_title'] ) ); //phpcs:ignore
	}

	$post_content = '';
	if ( ! empty( $_REQUEST['content'] ) ) { //phpcs:ignore
		$post_content = esc_html( wp_unslash( $_REQUEST['content'] ) ); //phpcs:ignore
	}

	$post_excerpt = '';
	if ( ! empty( $_REQUEST['excerpt'] ) ) { //phpcs:ignore
		$post_excerpt = esc_html( wp_unslash( $_REQUEST['excerpt'] ) ); //phpcs:ignore
	}

	if ( $create_in_db ) {
		$post_id = wp_insert_post(
			array(
				'post_title'  => __( 'Auto Draft', 'buddypress-member-blog' ),
				'post_type'   => $post_type,
				'post_status' => 'auto-draft',
				'post_author' => get_current_user_id(),
			)
		);

		$post = get_post( $post_id );

		if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) && get_option( 'default_post_format' ) ) {
			set_post_format( $post, get_option( 'default_post_format' ) );
		}
	} else {
		$post                 = new stdClass;
		$post->ID             = 0;
		$post->post_author    = '';
		$post->post_date      = '';
		$post->post_date_gmt  = '';
		$post->post_password  = '';
		$post->post_name      = '';
		$post->post_type      = $post_type;
		$post->post_status    = 'auto-draft';
		$post->to_ping        = '';
		$post->pinged         = '';
		$post->comment_status = get_default_comment_status( $post_type );
		$post->ping_status    = get_default_comment_status( $post_type, 'pingback' );
		//$post->post_pingback = get_option( 'default_pingback_flag' );
		//$post->post_category = get_option( 'default_category' );
		$post->page_template = 'default';
		$post->post_parent   = 0;
		$post->menu_order    = 0;
		$post                = new WP_Post( $post );
	}

	/**
	 * Filter the default post content initially used in the "Write Post" form.
	 *
	 * @param string $post_content Default post content.
	 * @param WP_Post $post Post object.
	 */
	$post->post_content = apply_filters( 'default_content', $post_content, $post );

	/**
	 * Filter the default post title initially used in the "Write Post" form.
	 *
	 * @param string $post_title Default post title.
	 * @param WP_Post $post Post object.
	 */
	$post->post_title = apply_filters( 'default_title', $post_title, $post );

	/**
	 * Filter the default post excerpt initially used in the "Write Post" form.
	 *
	 * @param string $post_excerpt Default post excerpt.
	 * @param WP_Post $post Post object.
	 */
	$post->post_excerpt = apply_filters( 'default_excerpt', $post_excerpt, $post );

	return $post;
}

/**
 * Get total no. of Posts  posted by a user
 *
 * @param int  $user_id user id.
 * @param bool $is_my_profile Is user profile.
 *
 * @return int
 */
function bp_member_blog_get_total_posted( $user_id = 0, $is_my_profile = false ) {
	// Needs revisit.
	global $wpdb;

	if ( ! $user_id ) {
		$user_id = bp_displayed_user_id();
	}

	$status = array( "post_status='publish'" );

	if ( $is_my_profile ) {
		$status[] = $wpdb->prepare( 'post_status=%s', 'draft' );
		$status[] = $wpdb->prepare( 'post_status=%s', 'pending' );
	}

	$where_status_query = join( ' || ', $status );

	$count = $wpdb->get_var( $wpdb->prepare( "SELECT count('*') FROM {$wpdb->posts} WHERE post_author=%d AND post_type=%s AND ({$where_status_query})", $user_id, 'post' ) );		// phpcs:ignore.

	return intval( $count );

}


/**
 * Get total no. of published post for the user
 *
 * @param int $user_id user id.
 *
 * @return int
 */
function bp_member_blog_get_total_published_posts( $user_id = 0 ) {

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}
	// Needs revisit.
	global $wpdb;

	$count = $wpdb->get_var( $wpdb->prepare( "SELECT count('*') FROM {$wpdb->posts} WHERE  post_author=%d AND post_type=%s AND post_status='publish'", $user_id, 'post' ) );

	return intval( $count );
}



/**
 * Loads a template from theme or the plugin directory
 * It checks theme directory first. looks inside the bp-member-blog dir of the theme first.
 *
 * @param string $template template name.
 */
function bp_member_blog_load_template( $template ) {

	$template_dir = apply_filters( 'bp_member_blog_template_dir', 'bp-member-blog' );

	// check for bp-member-blog/template-file.php in the child theme's dir and then in parent's.
	$located = locate_template( array( $template_dir . '/' . $template ), false );

	if ( ! $located ) {
		$located = BUDDYPRESS_MEMBER_BLOG_PLUGIN_PATH . 'templates/' . $template;
	}

	if ( is_readable( $located ) ) {
		require $located;
	}
}

// Function to display categories as nested options.
function bp_member_blog_display_category_options( $categories, $post_selected_category = array(), $depth = 0 ) {
	foreach ( $categories as $cat ) {
		$selected = ( ! empty( $post_selected_category ) && in_array( $cat->term_id, $post_selected_category ) ) ? 'selected' : '';
		echo '<option value="' . esc_attr( $cat->term_id ) . '" ' . esc_attr( $selected ) . '>';		
		echo wp_kses_post( str_repeat( '&nbsp;', $depth * 4 ) ) . esc_html( $cat->name );			
		echo '</option>';

		if ( ! empty( $cat->children ) ) {
			bp_member_blog_display_category_options( $cat->children, $post_selected_category, $depth + 1 );
		}
	}
}



function bp_member_get_user_roles(){
	$user_id = get_current_user_id();

	if ($user_id) {
		$sites = get_sites(['fields' => 'ids']); // Get all site IDs
		$roles_across_sites = [];

		foreach ($sites as $site_id) {
			switch_to_blog($site_id);
			if (function_exists('buddypress')) {
				$user = get_userdata($user_id);
				if ($user && !empty($user->roles)) {
					$roles_across_sites = array_merge($user->roles,$roles_across_sites);
				}
			}
			restore_current_blog(); // Restore to the original site
		}

		return $roles_across_sites;
	} else {
		return [];
	}
}

function bp_member_get_member_type(){
	$user_id = get_current_user_id();
	
	if ($user_id) {
		$sites = get_sites(['fields' => 'ids']); // Get all site IDs
		$member_type_across_sites = [];

		foreach ($sites as $site_id) {
			switch_to_blog($site_id);
			if (function_exists('buddypress')) {
				$member_types = bp_get_member_type( get_current_user_id(), false );
				if ( !empty($member_types) ) {
					$member_type_across_sites = array_merge($member_types,$member_type_across_sites);
				}
			}
			restore_current_blog(); // Restore to the original site
		}

		return $member_type_across_sites;
	} else {
		return [];
	}
}

function bp_member_blog_get_user_url( $author_id ){
	if( function_exists('buddypress') && buddypress()->buddyboss ){
        $url = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $author_id ): '';
    }else{
        $url = function_exists( 'bp_members_get_user_url' ) ? bp_members_get_user_url( $author_id ): '';
    }
	return apply_filters( 'bp_member_blog_get_user_url' , $url, $author_id);
}
