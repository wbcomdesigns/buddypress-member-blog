<?php
/**
 * Buddypress Member Blog Fields

 * @package Buddypress Member Blog
 */

add_action( 'init', 'bp_member_blog_post_acf_fields' );
/**
 * Bp_member_blog_post_acf_fields
 *
 * @return void
 */
function bp_member_blog_post_acf_fields() {

	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	if ( function_exists( 'acf_add_local_field_group' ) ) :

		acf_add_local_field_group(
			array(
				'key'                   => 'group_60b73f7d07ce4',
				'title'                 => 'BuddyPress Member Blog',
				'fields'                => array(
					array(
						'key'               => 'field_60b73fa05b244',
						'label'             => 'Blog Content',
						'name'              => 'post_content',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '100',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'delay'             => 0,
					),
					array(
						'key'               => 'field_60b73fb75b245',
						'label'             => 'Post Category',
						'name'              => 'post_category',
						'type'              => 'taxonomy',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'taxonomy'          => 'category',
						'field_type'        => 'multi_select',
						'allow_null'        => 0,
						'add_term'          => 0,
						'save_terms'        => 1,
						'load_terms'        => 0,
						'return_format'     => 'id',
						'multiple'          => 0,
					),
					array(
						'key'               => 'field_60b895a119306',
						'label'             => 'Post tag',
						'name'              => 'post_tag',
						'type'              => 'taxonomy',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'taxonomy'          => 'post_tag',
						'field_type'        => 'multi_select',
						'allow_null'        => 0,
						'add_term'          => 0,
						'save_terms'        => 1,
						'load_terms'        => 0,
						'return_format'     => 'id',
						'multiple'          => 0,
					),
					array(
						'key'               => 'field_60b73fe35b246',
						'label'             => 'Featured Image',
						'name'              => 'featured_image',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'id',
						'preview_size'      => 'medium',
						'library'           => 'uploadedTo',
						'min_width'         => '',
						'min_height'        => '',
						'min_size'          => '',
						'max_width'         => '',
						'max_height'        => '',
						'max_size'          => '',
						'mime_types'        => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'post',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			)
		);

	endif;

}




add_filter( 'acf/fields/taxonomy/query', 'my_acf_fields_taxonomy_query', 10, 2 );
/**
 * My_acf_fields_taxonomy_query
 *
 * @param  mixed $args args.
 * @param  mixed $field field.
 * @return $args
 */
function my_acf_fields_taxonomy_query( $args, $field ) {
	if ( 'field_60b73fb75b245' === $field['key'] ) {
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		if ( isset( $bp_member_blog_gen_stngs['exclude_category'] ) && ! empty( $bp_member_blog_gen_stngs['exclude_category'] ) ) {
			$args['exclude'] = $bp_member_blog_gen_stngs['exclude_category'];
		}
	}
	return $args;
}
