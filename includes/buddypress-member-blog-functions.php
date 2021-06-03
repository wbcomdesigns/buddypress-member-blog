<?php
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
	

	$url = bp_core_get_user_domain( $user_id ) . "bp-member-blog" . '/';

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
	return bp_core_get_user_domain( $user_id ) . "bp-member-blog" . '/edit/';
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
	
	if ( get_post_field( 'post_status', $post_id ) ==  'pending' ) {
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
	$url = bp_core_get_user_domain( $post->post_author ) . "bp-member-blog" . '/';

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

	$url = bp_member_blog_get_edit_url( $id );

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
	return bp_core_get_user_domain( $post->post_author ) . "bp-member-blog" . "/{$action_name}/" . $post->ID . '/';
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

	$url = bp_core_get_user_domain( $post->post_author ) . "bp-member-blog" . "/{$action_name}/" . $post->ID . '/';

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

		// structure of “format” depends on whether we’re using pretty permalinks.
		$format = '?paged=%#%';
		$user_id = get_current_user_id();
		$base   = trailingslashit( bp_core_get_user_domain( $user_id ) . "bp-member-blog" );
		
		echo paginate_links( array(
			'base'     => $base . '%_%',
			'format'   => $format,
			'current'  => $current_page,
			'total'    => $total,						
			'end_size' =>   1,
			'mid_size' =>   2,
			'prev_next'=>   true,      
			'type'     =>   'plain',
		) );
		
	}
}