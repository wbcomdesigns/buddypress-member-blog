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

	$url = bp_members_get_user_url( $user_id ) . 'blog' . '/';

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
	return bp_members_get_user_url( $user_id ) . 'blog' . '/edit/';
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
	$url = bp_members_get_user_url( $post->post_author ) . 'blog' . '/';

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

	// if we are here, we can allow user to edit the post.
	return bp_members_get_user_url( $post->post_author ) . 'blog' . "/{$action_name}/" . $post->ID . '/';
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

	$url = bp_members_get_user_url( $post->post_author ) . 'blog' . "/{$action_name}/" . $post->ID . '/';

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
		// get the current page.
		$current_page = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
			$user_id = bp_displayed_user_id();
		// structure of “format” depends on whether we’re using pretty permalinks.
			$format  = '?paged=%#%';
		$blog_slug = apply_filters('bp_member_change_blog_slug', 'blog' );
		if( $blog_slug == bp_current_action() ){
			$base    = trailingslashit( bp_members_get_user_url( $user_id ) . $blog_slug );	
		}else{
			$base    = trailingslashit( bp_members_get_user_url( $user_id )  . $blog_slug . '/' . bp_current_action() );
		}

		echo wp_kses_post(
			paginate_links(
				array(
					'base'      => $base . '%_%',
					'format'    => $format,
					'current'   => $current_page,
					'total'     => $total,
					'end_size'  => 1,
					'mid_size'  => 2,
					'prev_next' => true,
					'type'      => 'plain',
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
		$post_id = wp_insert_post( array(
			'post_title'  => __( 'Auto Draft', 'buddypress-member-blog' ),
			'post_type'   => $post_type,
			'post_status' => 'auto-draft',
			'post_author' => get_current_user_id(),
		) );

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
 * It will take bp_displayed_user_id, if display id not found then it will take current user id.
 *
 * @param int  $user_id user id.
 * @param bool $is_my_profile Is user profile.
 *
 * @return int
 *
 * @todo : may need revisist
 */
function bp_member_blog_get_total_posted( $user_id = 0, $is_my_profile = false ) {
	// Needs revisit.
	global $wpdb;

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$status = array( "post_status='publish'" );

	$where_status_query = join( ' || ', $status );

	$count = $wpdb->get_var( $wpdb->prepare( "SELECT count('*') FROM {$wpdb->posts} WHERE post_author=%d AND post_type=%s AND ({$where_status_query})", $user_id, 'post' ) );

	return intval( $count );

}


/**
 * Get total no. of published post for the user
 * It will take bp_displayed_user_id, if display id not found then it will take current user id.
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