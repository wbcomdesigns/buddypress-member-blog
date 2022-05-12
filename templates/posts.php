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
$member_types  = bp_get_member_type( get_current_user_id(), false );
if ( ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) && ! empty( $bp_member_blog_gen_stngs['bp_create_post'] ) )
		|| ( isset( $bp_member_blog_gen_stngs['member_types'] ) && ! empty( $bp_member_blog_gen_stngs['member_types'] ) ) ) {
	$bp_member_blog_gen_stngs['bp_create_post'] = ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) ) ? $bp_member_blog_gen_stngs['bp_create_post'] : array();
	$bp_member_blog_gen_stngs['member_types']   = ( isset( $bp_member_blog_gen_stngs['member_types'] ) ) ? $bp_member_blog_gen_stngs['member_types'] : array();
	$user_roles                                 = array_intersect( (array) $current_user->roles, $bp_member_blog_gen_stngs['bp_create_post'] );

	$user_types = array_intersect( (array) $member_types, $bp_member_blog_gen_stngs['member_types'] );
	if ( empty( $user_roles ) && empty( $user_types ) ) {
		$action_button = false;
	} elseif ( ! is_user_logged_in() || get_current_user_id() != bp_displayed_user_id() ) {
		$action_button = false;
	}
}


// let us build the post query.
if ( $is_my_profile || is_super_admin() ) {
	$status = 'any';
} else {
	$status = 'publish';
}

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$query_args = array(
	'author'      => $user_id,
	'post_type'   => 'post',
	'post_status' => $status,
	'paged'       => intval( $paged ),
);
// do the query.
query_posts( $query_args );

?>
<?php do_action( 'bp_member_blog_before_posts' ); ?>
<div  class="bp-member-blog-container bpmb-blog-posts">
	<?php if ( have_posts() ) : ?>

		<?php
		while ( have_posts() ) :
			the_post();
			global $post;
			?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="post-featured-image">
					<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) ) : ?>
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( BUDDYPRESS_MEMBER_BLOG_PLUGIN_URL ) . 'public/images/no-post-image.jpg'; ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="<?php the_title(); ?>" width="150" height="150">
					<?php endif; ?>
				</div>



				<div class="post-content">

					<h3 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_html_e( 'Permanent Link to', 'buddypress-member-blog' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						<?php if ( $action_button == true ) : ?>
							<span class="bp-edit-post"><?php echo wp_kses_post( bp_member_blog_get_edit_link() ); ?></span>
						<?php endif; ?>
					</h3>


					<div class="post-date">
						<?php
						/* translators: %s: */
						printf( esc_html__( '%1$s', 'buddypress-member-blog' ), get_the_date(), wp_kses_post( get_the_category_list( ', ' ) ) );
						?>
					</div>
					<div class="post-categories">
							<?php
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								foreach ( $categories as $category ) {
									?>
									<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
									<?php
									echo esc_html( $category->name );
									?>
									</a>
									<?php
								}
							}
							?>
					</div>
					<div class="entry-content">

						<?php the_excerpt( __( 'Read the rest of this entry &rarr;', 'buddypress-member-blog' ) ); ?>
						<?php
						wp_link_pages(
							array(
								'before'         => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress-member-blog' ),
								'after'          => '</p></div>',
								'next_or_number' => 'number',
							)
						);
						?>
					</div>

					<?php if ( $action_button == true ) : ?>
						<div class="post-actions">
							<span class="publish-post"><?php echo wp_kses_post( bp_member_blog_get_post_publish_unpublish_link( get_the_ID() ) ); ?></span>
							<span class="delete-post"><?php echo wp_kses_post( bp_member_blog_get_delete_link() ); ?></span>
						</div>
					<?php endif; ?>
				</div>

			</div>

		<?php endwhile; ?>
			<div class="navigation pagination">
				<?php bp_member_blog_paginate(); ?>
			</div>
		<?php
	else :
		if ( is_user_logged_in() ) {
			if ( bp_is_my_profile() ) {
				$bp_template_option = bp_get_option( '_bp_theme_package_id' );
				if ( 'nouveau' === $bp_template_option ) {
					echo '<div id="message" class="info bp-feedback bp-messages bp-template-notice">';
					echo '<span class="bp-icon" aria-hidden="true"></span>';
				} else {
					echo '<div id="message" class="info">';
				}
				echo '<p>';
				esc_html_e( 'You have not posted anything yet.', 'buddypress-member-blog' );
				echo '</p>';
				echo '</div>';
			} else {
				$bp_template_option = bp_get_option( '_bp_theme_package_id' );
				if ( 'nouveau' === $bp_template_option ) {
					echo '<div id="message" class="info bp-feedback bp-messages bp-template-notice">';
					echo '<span class="bp-icon" aria-hidden="true"></span>';
				} else {
					echo '<div id="message" class="info">';
				}
				echo sprintf( "<p>%s hasn't posted anything yet.</p>", esc_html( bp_get_displayed_user_fullname() ) );
				echo '</div>';
			}
		} else {
			$bp_template_option = bp_get_option( '_bp_theme_package_id' );
			if ( 'nouveau' === $bp_template_option ) {
				echo '<div id="message" class="info bp-feedback bp-messages bp-template-notice">';
				echo '<span class="bp-icon" aria-hidden="true"></span>';
			} else {
				echo '<div id="message" class="info">';
			}
			echo sprintf( "<p>%s hasn't posted anything yet.</p>", esc_html( bp_get_displayed_user_fullname() ) );
			echo '</div>';
		}
	endif;
	?>

	<?php
	wp_reset_postdata();
	wp_reset_query();
	?>
</div>
