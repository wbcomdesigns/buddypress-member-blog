
<div>
	<?php	
	$post_id = bp_action_variable( 0 );
	
	$settings = array(
		'id'                    => 'bp-member-post-form',
		'post_id'               => ( ! empty( $post_id ) ) ? $post_id : 'new_post',
		'new_post'              => false,
		'field_groups'          => array( 'group_60b73f7d07ce4' ),
		'fields'                => false,
		'post_title'            => true,
		'post_content'          => false,
		'form'                  => true,
		'form_attributes'       => array(),
		'return'                => '',
		'html_before_fields'    => '',
		'html_after_fields'     => '',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'field_el'              => 'div',
		'uploader'              => 'wp',
		'honeypot'              => false,
		'submit_value' 			=> ( ! empty( $post_id ) ) ? __("Update post", 'buddypress-member-blog') : __("Create a new post", 'buddypress-member-blog'),
		'html_updated_message'  => '',
		'html_submit_button'    => '<input type="submit" class="acf-button btn button button-primary button-large" value="%s" />',
		'html_submit_spinner'   => '<span class="acf-spinner"></span>',
		'kses'                  => true,
		
	);
	acf_form( $settings );

	?>
</div>	