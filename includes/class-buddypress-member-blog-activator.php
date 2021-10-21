<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Member_Blog_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

		$add_new_post = get_page_by_title( 'Add new post' );
		if ( empty( $add_new_post ) ) {
			$new_post_page = wp_insert_post(
				array(
					'post_title'     => 'Add new post',
					'post_content'   => '[bp-member-blog]',
					'post_status'    => 'publish',
					'post_author'    => 1,
					'post_type'      => 'page',
					'comment_status' => 'closed',
				)
			);

			$reign_wbcom_metabox_data = array(
				'layout'        => array(
					'site_layout'       => 'full_width',
					'primary_sidebar'   => '0',
					'secondary_sidebar' => '0',
				),
				'header_footer' => array(
					'elementor_topbar' => '0',
					'elementor_header' => '0',
					'elementor_footer' => '0',
				),
			);
			update_post_meta( $new_post_page, 'reign_wbcom_metabox_data', $reign_wbcom_metabox_data );

		} else {
			$new_post_page = $add_new_post->ID;
		}

		$bp_member_blog_gen_stngs = array(
			'bp_post_page'   => $new_post_page,
			'bp_create_post' => array( 'administrator' ),
			'image_delete'   => 'yes',
		);

		update_option( 'bp_member_blog_gen_stngs', $bp_member_blog_gen_stngs );
	}

}
