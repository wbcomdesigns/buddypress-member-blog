<?php
add_action( 'init', 'bp_member_blog_post_acf_fields');
function bp_member_blog_post_acf_fields() {	
	
	if ( is_admin() && ! defined( 'DOING_AJAX' )) {
		return;
	}
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_60b73f7d07ce4',
			'title' => 'BuddyPress Member Blog',
			'fields' => array(
				array(
					'key' => 'field_60b73fa05b244',
					'label' => 'Blog Content',
					'name' => 'post_content',
					'type' => 'medium_editor',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '100',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'standard_buttons' => array(
						0 => 'bold',
						1 => 'italic',
						2 => 'underline',
						3 => 'anchor',
						4 => 'quote',
						5 => 'orderedlist',
						6 => 'unorderedlist',
						7 => 'indent',
						8 => 'outdent',
						9 => 'justifyLeft',
						10 => 'justifyCenter',
						11 => 'justifyRight',
						12 => 'justifyFull',
						13 => 'h1',
						14 => 'h2',
						15 => 'h3',
						16 => 'h4',
						17 => 'h5',
						18 => 'h6',
						19 => 'removeFormat',
					),
					'custom_buttons' => '',
					'other_options' => array(
						0 => 'disableReturn',
						1 => 'disableDoubleReturn',
						2 => 'disableExtraSpaces',
					),
					'delay' => 0,
				),
				array(
					'key' => 'field_60b73fb75b245',
					'label' => 'Post Category',
					'name' => 'post_category',
					'type' => 'taxonomy',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'taxonomy' => 'category',
					'field_type' => 'multi_select',
					'allow_null' => 0,
					'add_term' => 0,
					'save_terms' => 1,
					'load_terms' => 0,
					'return_format' => 'id',
					'multiple' => 0,
				),
				array(
					'key' => 'field_60b895a119306',
					'label' => 'Post tag',
					'name' => 'post_tag',
					'type' => 'taxonomy',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'taxonomy' => 'post_tag',
					'field_type' => 'multi_select',
					'allow_null' => 0,
					'add_term' => 0,
					'save_terms' => 1,
					'load_terms' => 0,
					'return_format' => 'id',
					'multiple' => 0,
				),
				array(
					'key' => 'field_60b73fe35b246',
					'label' => 'Featured Image',
					'name' => 'featured_image',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'id',
					'preview_size' => 'medium',
					'library' => 'uploadedTo',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'post',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));

	endif;

}



/*
 * exclude selected category 
 *
 */
add_filter('acf/fields/taxonomy/query', 'my_acf_fields_taxonomy_query', 10, 2);
function my_acf_fields_taxonomy_query( $args, $field ) {
	if ( $field['key'] == 'field_60b73fb75b245' ) {
		$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
		if (  isset($bp_member_blog_gen_stngs['exclude_category']) && !empty($bp_member_blog_gen_stngs['exclude_category']) )  {
			$args['exclude'] = $bp_member_blog_gen_stngs['exclude_category'];
		}
		
	}
    return $args;
}