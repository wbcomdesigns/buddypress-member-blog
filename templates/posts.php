<?php
/**
 * This file is used for listing the posts on profile
 *
 * @package Buddypress_Member_Blog
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
global $current_user;
$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

$user_id       = bp_displayed_user_id();
$is_my_profile = bp_is_my_profile();

/*
 * Check current user role to allowed create post or not
 *
 */
$action_button = true;
$member_types = bp_get_member_type( get_current_user_id(), false );
if ( (isset($bp_member_blog_gen_stngs['bp_create_post']) && !empty($bp_member_blog_gen_stngs['bp_create_post']) )
		|| (isset($bp_member_blog_gen_stngs['member_types']) && !empty($bp_member_blog_gen_stngs['member_types']) ) ) {
	$bp_member_blog_gen_stngs['bp_create_post'] = ( isset($bp_member_blog_gen_stngs['bp_create_post'])) ? $bp_member_blog_gen_stngs['bp_create_post'] : array();
	$bp_member_blog_gen_stngs['member_types'] = ( isset($bp_member_blog_gen_stngs['member_types'])) ? $bp_member_blog_gen_stngs['member_types'] : array();
	$user_roles = array_intersect ((array) $current_user->roles, $bp_member_blog_gen_stngs['bp_create_post']);

	$user_types = array_intersect ((array) $member_types, $bp_member_blog_gen_stngs['member_types']);
	if ( empty($user_roles) && empty($user_types)) {
		$action_button = false;
	}
}


//let us build the post query
if ( $is_my_profile || is_super_admin() ) {
	$status = 'any';
} else {
	$status = 'publish';
}

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$query_args = array(
	'author'        => $user_id,
	'post_type'     => 'post',
	'post_status'   => $status,
	'paged'         => intval( $paged )
);
//do the query
query_posts( $query_args );
?>
<div  class="bp-member-blog-container">
	<?php if ( have_posts() ): ?>

		<?php while ( have_posts() ): the_post();
			global $post;
		?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="post-featured-image">
					<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) ):?>
						<?php  the_post_thumbnail( 'thumbnail' );?>
					<?php else : ?>
						<img src="<?php echo BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL."public/images/no-post-image.jpg";?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="<?php the_title(); ?>" width="150" height="150">
					<?php endif;?>
				</div>



				<div class="post-content">

					<h3 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress-member-blog' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						<?php if ( $action_button == true ): ?>
							<span class="bp-edit-post"><?php echo bp_member_blog_get_edit_link();?></span>
						<?php endif;?>
					</h3>


					<div class="post-date"><?php printf( __( '%1$s', 'buddypress-member-blog' ), get_the_date(), get_the_category_list( ', ' ) ); ?></div>

					<div class="entry-content">

						<?php the_excerpt( __( 'Read the rest of this entry &rarr;', 'buddypress-member-blog' ) ); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress-member-blog' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
					</div>

					<?php if ( $action_button == true ): ?>
						<div class="post-actions">
							<span class="publish-post"><?php echo bp_member_blog_get_post_publish_unpublish_link( get_the_ID() );?></span>
							<span class="delete-post"><?php echo bp_member_blog_get_delete_link();?></span>
						</div>
					<?php endif; ?>
				</div>

			</div>

		<?php endwhile;?>
			<div class="navigation pagination">
				<?php bp_member_blog_paginate(); ?>
			</div>
	<?php else: ?>
			<p><?php echo sprintf( "<p>%s haven't posted anything yet.</p>", bp_get_displayed_user_fullname() );?></p>
	<?php endif; ?>

	<?php
	   wp_reset_postdata();
	   wp_reset_query();
	?>
</div>
