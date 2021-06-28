<?php
$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
$post_id = bp_action_variable( 0 );
$blog_post = (object) array(
			'post_title'	=> '',
			'post_content'	=> ''
		);
$post_selected_category = $post_selected_tag = array();
$post_thumbnail = '';
if ( isset($_GET['post_id']) && $_GET['post_id'] != 0 && isset($_GET['action']) && $_GET['action'] == 'edit' ) {
	$post_id = $_GET['post_id'];	
	$blog_post = get_post($post_id);	
	
	$post_selected_category = wp_get_object_terms( $post_id, 'category', array_merge( $args, array( 'fields' => 'ids' ) ) );	
	$post_seleced_tag = wp_get_object_terms( $post_id, 'post_tag', array_merge( $args, array( 'fields' => 'ids' ) ) );
	
	$post_thumbnail = get_the_post_thumbnail_url($post_id, 'post-thumbnail');
}


$args = array(
			'taxonomy' => 'category',
			'hide_empty' => false,
		);
if (  isset($bp_member_blog_gen_stngs['exclude_category']) && !empty($bp_member_blog_gen_stngs['exclude_category']) )  {
	$args['exclude'] = $bp_member_blog_gen_stngs['exclude_category'];
}

$category = get_terms(  $args );


$args = array(
			'taxonomy' => 'post_tag',
			'hide_empty' => false,
		);
$post_tag = get_terms(  $args );


$submit_btn_value = ( ! empty( $post_id ) ) ? __("Update post", 'buddypress-member-blog') : __("Create a new post", 'buddypress-member-blog');


?>
<div  class="bp-member-blog-container">
	<div class="bp-member-blog-post-form">
		<form id="bp-member-post" class="standard-form bp-member-blog-post-form" method="post" action="" enctype="multipart/form-data" >
			
			
			<?php do_action( 'bp_post_before_title', $post_id ); ?>            

            <label for="bp_member_blog_post_title"><?php _e( 'Title:', 'bp-simple-front-end-post' ); ?>
                <input type="text" name="bp_member_blog_post_title" value="<?php echo $blog_post->post_title; ?>" required/>
            </label>

			<?php do_action( 'bp_post_after_title', $post_id ); ?>
		
		
			<?php do_action( 'bp_post_after_content',  $post_id ); ?>

            <label for="bp_member_blog_post_content"><?php _e( 'Post Content:', 'buddypress-member-blog' ); ?>

				<?php wp_editor( $blog_post->post_content, 'bp_member_blog_post_content', array(
					'media_buttons' => true,					
				) ); ?>
            </label>
			
			<?php do_action( 'bp_post_after_content', $post_id ); ?>
			
			
			
			<?php do_action( 'bp_post_before_category', $post_id ); ?>
			
			<label for="bp_member_blog_post_category"><?php _e( 'Post Category:', 'buddypress-member-blog' ); ?>
			
				<select id="bp-blog-category-select" name="bp_member_blog_post_category[]" multiple data-placeholder="<?php esc_html_e( 'Select post category', 'buddypress-member-blog');?>">
				<?php foreach ( $category as $cat ) { 
				$selected = (!empty( $post_selected_category ) && in_array( $cat->term_id, $post_selected_category ) ) ? 'selected' : '';
				?>
					<option value="<?php echo $cat->term_id; ?>" <?php echo $selected;?>><?php echo $cat->name; ?></option>
				<?php } ?>
				</select>
			
			</label>
			
			<?php do_action( 'bp_post_after_category', $post_id ); ?>
			
			
			
			<?php do_action( 'bp_post_before_tag', $post_id ); ?>
			
			<label for="bp_member_blog_post_tag"><?php _e( 'Post tag:', 'buddypress-member-blog' ); ?>
			
				<select id="bp-blog-tag-select" name="bp_member_blog_post_tag[]" multiple data-placeholder="<?php esc_html_e( 'Select post tag', 'buddypress-member-blog');?>">
				<?php foreach ( $post_tag as $tag ) { 
					$selected = (!empty( $post_selected_tag ) && in_array( $tag->term_id, $post_selected_tag ) ) ? 'selected' : '';
				?>
					<option value="<?php echo $tag->name; ?>"  <?php echo $selected;?>><?php echo $tag->name; ?></option>
				<?php } ?>
				</select>
			
			</label>
			
			<?php do_action( 'bp_post_after_tag', $post_id ); ?>
			
			
			<?php do_action( 'bp_post_before_featured_image', $post_id ); ?>
			
			<label for="bp_member_blog_post_featured_image"><?php _e( 'Featured Image:', 'buddypress-member-blog' ); ?>
			
				<input type="file" id="bp_member_blog_post_featured_image" name="bp_member_blog_post_featured_image" value="<?php echo $title; ?>"/>
				
				<div class="bp_member_blog_post_img_preview" <?php if ($post_thumbnail == '' ):?>style="display:none;" <?php endif;?>>
					<img id="bp_member_post_img_preview" src="<?php echo $post_thumbnail;?>" alt="pic"  width="200", height="200"/>
				</div>
			</label>
			
			<?php do_action( 'bp_post_after_featured_image', $post_id ); ?>
			
						
            <input type="hidden" name="action" value="bp_member_blog_post"/>
			
			<?php if ( $post_id ) : ?>
                <input type="hidden" name="bp_member_blog_post_id" value="<?php echo $post_id; ?>" id="post_ID"/>
			<?php endif; ?>
			
			<input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="post_form_url"/>
            <input id="submit" name="bp_member_blog_form_subimitted" class="bp-member-blog-btn btn button button-primary button-large" type="submit" value="<?php echo esc_attr( $submit_btn_value );?>"/>
			
			<?php wp_nonce_field( 'bp_member_blog_post' ); ?>
		</form>
	
	</div>
	
</div>	