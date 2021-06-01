<?php
/**
 *
 * This file is used for rendering and saving plugin general settings.
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

$files_per_post = ( isset( $blpro_nl_settings['files_per_post'] ) ) ? $blpro_nl_settings['files_per_post'] : '5';

$category = get_terms( array(
    'taxonomy' => 'category',
    'hide_empty' => false,
) );

?>
<div class="wbcom-tab-content">
<form method="post" action="options.php" class="bp-member-blog-gen-form">
	<?php
	settings_fields( 'bp_member_blog_general_settigs' );
	do_settings_sections( 'bp_member_blog_general_settigs' );
	?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="bp_member_blog_publish_post">
					<?php esc_html_e( 'Enable user publishing', 'buddypress-member-blog' ); ?>
				</label>
			</th>
			<td>
				<label class="wb-switch">
					<input name='bp_member_blog_gen_stngs[publish_post]' type='checkbox' value='yes' <?php ( isset( $bp_member_blog_gen_stngs['publish_post'] ) ) ? checked( $bp_member_blog_gen_stngs['publish_post'], 'yes' ) : ''; ?> id="bp_member_blog_publish_post"/>
					<div class="wb-slider wb-round"></div>
				</label>
				<p class="description"><?php esc_html_e( 'Allow user to publish posts. if not enable, they can only submit post as pending for review.', 'buddypress-member-blog' ); ?></p>
		    </td>
	    </tr>
		
		<tr>
			<th scope="row">
				<label for="bp_member_blog_auto_save_post">
					<?php esc_html_e( 'Enable auto save', 'buddypress-member-blog' ); ?>
				</label>
			</th>
			<td>
				<label class="wb-switch">
					<input name='bp_member_blog_gen_stngs[auto_save_post]' type='checkbox' value='yes' <?php ( isset( $bp_member_blog_gen_stngs['auto_save_post'] ) ) ? checked( $bp_member_blog_gen_stngs['auto_save_post'], 'yes' ) : ''; ?> id="bp_member_blog_auto_save_post"/>
					<div class="wb-slider wb-round"></div>
				</label>
				<p class="description"><?php esc_html_e( 'Enable post auto saving while editing.', 'buddypress-member-blog' ); ?></p>
		    </td>
	    </tr>
		
		<tr>
			<th scope="row">
				<label for="bp_member_blog_files_per_post">
					<?php esc_html_e( 'Max files per post', 'buddypress-member-blog' ); ?>
				</label>
			</th>
			<td>
				<input type="text" id="custom_form_content" name="bp_member_blog_gen_stngs[files_per_post]" value="<?php echo esc_attr( $files_per_post) ; ?>" class="reguler-text" id="bp_member_blog_files_per_post">
				<p class="description"><?php esc_html_e( 'Maximum number of images that user can upload in post.', 'buddypress-member-blog' ); ?></p>
		    </td>
	    </tr>
		
		<tr>
			<th scope="row">
				<label for="bp_member_blog_image_delete">
					<?php esc_html_e( 'Media management', 'buddypress-member-blog' ); ?>
				</label>
			</th>
			<td>
				<label class="wb-switch">
					<input name='bp_member_blog_gen_stngs[image_delete]' type='checkbox' value='yes' <?php ( isset( $bp_member_blog_gen_stngs['image_delete'] ) ) ? checked( $bp_member_blog_gen_stngs['image_delete'], 'yes' ) : ''; ?> id="bp_member_blog_image_delete"/>
					<div class="wb-slider wb-round"></div>
				</label>
				<p class="description"><?php esc_html_e( 'When blog post removed permanently delete the associated media file.', 'buddypress-member-blog' ); ?></p>
		    </td>
	    </tr>
		
		<tr>
			<th scope="row">
				<label>
					<?php esc_html_e( 'Exclude categories', 'buddypress-member-blog' ); ?>
				</label>
			</th>
			<td>
				<select id="bp-blog-category-select" name="bp_member_blog_gen_stngs[exclude_category][]" multiple data-placeholder="<?php esc_html_e( 'Select category to exclude on fronted', 'buddypress-member-blog');?>">
				<?php foreach ( $category as $cat ) { 
					$selected = (!empty( $bp_member_blog_gen_stngs['exclude_category'] ) && in_array( $cat->term_id, $bp_member_blog_gen_stngs['exclude_category'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $cat->term_id; ?>" <?php echo $selected; ?>><?php echo $cat->name; ?></option>
				<?php } ?>
				</select>			
		    </td>
	    </tr>
		<?php

		/**
		 * Fires after the display of default general options.
		 *
		 * Allows plugins to add their own options.
		 *
		 * @since 1.0.0
		 * @param array $bp_member_blog_gen_stngs Plugin general setting option.
		 */
		do_action( 'bmpro_add_general_settings_options', $bp_member_blog_gen_stngs ); ?>
	</table>
	<?php submit_button(); ?>
</form>	
</div>