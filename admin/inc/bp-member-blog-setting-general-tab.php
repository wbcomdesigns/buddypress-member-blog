<?php
/**
 * This file is used for rendering and saving plugin general settings.
 *
 * @link       https://wbcomdesigns.com/
 *
 * @since      1.0.0
 *
 * @package    Buddypress_Member_Blog
 * @subpackage Buddypress_Member_Blog/admin/inc
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );

$files_per_post = ( isset( $blpro_nl_settings['files_per_post'] ) ) ? $blpro_nl_settings['files_per_post'] : '5';

$category = get_terms(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
	)
);


$member_types_exist = false;
$member_types       = bp_get_member_types( $args = array(), $output = 'object' );
if ( ! empty( $member_types ) ) {
	$member_types_exist = true;
}

?>
<div class="wbcom-tab-content">
<div class="wbcom-wrapper-admin">	
<div class="wbcom-admin-title-section">
	<h3><?php esc_html_e( 'General Settings', 'buddypress-member-blog' ); ?></h3>
</div>

<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
<form method="post" action="options.php" class="bp-member-blog-gen-form">
	<?php
	settings_fields( 'bp_member_blog_general_settigs' );
	do_settings_sections( 'bp_member_blog_general_settigs' );
	?>
	<div class="form-table">
		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label><?php esc_html_e( 'Create new post page', 'buddypress-member-blog' ); ?></label>
				<p class="description"><?php esc_html_e( 'This sets the page used to create new post. This page should contain the following shortcode. [bp-member-blog]', 'buddypress-member-blog' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options wbcom-settings-section-options-flex">
				<?php
				$args = array(
					'name'             => 'bp_member_blog_gen_stngs[bp_post_page]',
					'id'               => 'my_dashboard_page',
					'sort_column'      => 'menu_order',
					'sort_order'       => 'ASC',
					'show_option_none' => ' ',
					'class'            => 'my_dashboard_page',
					'echo'             => false,
					'selected'         => absint( ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) ) ? $bp_member_blog_gen_stngs['bp_post_page'] : 0 ),
					'post_status'      => 'publish',
				);

				if ( isset( $value['args'] ) ) {
					$args = wp_parse_args( $value['args'], $args );
				}

				echo wp_dropdown_pages( $args ); // WPCS: XSS ok.
				?>
				<?php if ( isset( $bp_member_blog_gen_stngs['bp_post_page'] ) && $bp_member_blog_gen_stngs['bp_post_page'] != 0 ) : ?>
					<a href="<?php echo esc_url( get_permalink( $bp_member_blog_gen_stngs['bp_post_page'] ) ); ?>" class="button-secondary" target="_bp">
						<?php esc_html_e( 'View', 'buddypress-member-blog' ); ?>
						<span class="dashicons dashicons-external" aria-hidden="true"></span>
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'buddypress-member-blog' ); ?></span>
					</a>
				<?php endif; ?>				
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label><?php esc_html_e( 'Allowed user roles to create post?', 'buddypress-member-blog' ); ?></label>
				<p class="description description-bp"><?php esc_html_e( 'Selected user roles will be allowed to create post.', 'buddypress-member-blog' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
				<ul class="wbcom-settings-member-retraction wbcom-settings-section-options-flex">
					<?php
					foreach ( get_editable_roles() as $id => $role ) {
						?>
						<li 
						<?php
						if ( $id == 'administrator' ) :
							?>
							style="display:none;" <?php endif; ?>>
							<label class="wb-switch">
								<input type="checkbox" id="bp_create_post_<?php echo esc_attr( $id ); ?>" name="bp_member_blog_gen_stngs[bp_create_post][]" value="<?php echo esc_attr( $id ); ?>"
								<?php
								if ( isset( $bp_member_blog_gen_stngs['bp_create_post'] ) && in_array( $id, $bp_member_blog_gen_stngs['bp_create_post'] ) ) {
									echo 'checked';}
								?>
								/>
								<div class="wb-slider wb-round"></div>
							</label>
							<label for="bp_create_post_<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $role['name'] ); ?></label>
						</li>
						<?php
					}
					?>
				</ul>				
			</div>
		</div>

		<?php if ( $member_types_exist ) : ?>
		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label><?php esc_html_e( 'Allowed member types wise to create post?', 'buddypress-member-blog' ); ?></label>
				<p class="description"><?php esc_html_e( 'Selected member type will be allowed to create post.', 'buddypress-member-blog' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
				<select id="bp-member-types-list" name="bp_member_blog_gen_stngs[member_types][]" multiple data-placeholder="<?php esc_html_e( 'Select member type to create post', 'buddypress-member-blog' ); ?>">
					<?php foreach ( $member_types as $key => $type_obj ) { ?>
						<?php $selected = ( ! empty( $bp_member_blog_gen_stngs['member_types'] ) && in_array( $key, $bp_member_blog_gen_stngs['member_types'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $type_obj->labels['name'] ); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<?php endif; ?>
		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="bp_member_blog_publish_post">
					<?php esc_html_e( 'Enable user publishing', 'buddypress-member-blog' ); ?>
				</label>
				<p class="description"><?php esc_html_e( 'Allow user to publish posts. if not enable, they can only submit post as pending for review.', 'buddypress-member-blog' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
				<label class="wb-switch">
					<input name='bp_member_blog_gen_stngs[publish_post]' type='checkbox' value='yes' <?php ( isset( $bp_member_blog_gen_stngs['publish_post'] ) ) ? checked( $bp_member_blog_gen_stngs['publish_post'], 'yes' ) : ''; ?> id="bp_member_blog_publish_post"/>
					<div class="wb-slider wb-round"></div>
				</label>				
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="bp_member_blog_publish_post">
					<?php esc_html_e( 'Enable create category', 'buddypress-member-blog' ); ?>
				</label>
				<p class="description"><?php esc_html_e( 'Enable this option if you want to create category from the font-end.', 'buddypress-member-blog' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
				<label class="wb-switch">
					<input name='bp_member_blog_gen_stngs[create_category]' type='checkbox' value='yes' <?php ( isset( $bp_member_blog_gen_stngs['create_category'] ) ) ? checked( $bp_member_blog_gen_stngs['create_category'], 'yes' ) : ''; ?> id="bp_member_blog_publish_post"/>
					<div class="wb-slider wb-round"></div>
				</label>				
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label for="bp_member_blog_image_delete">
					<?php esc_html_e( 'Media management', 'buddypress-member-blog' ); ?>
				</label>
				<p class="description"><?php esc_html_e( 'When blog post removed permanently delete the associated media file.', 'buddypress-member-blog' ); ?></p>
			</div>
			<div class="wbcom-settings-section-options">
				<label class="wb-switch">
					<input name='bp_member_blog_gen_stngs[image_delete]' type='checkbox' value='yes' <?php ( isset( $bp_member_blog_gen_stngs['image_delete'] ) ) ? checked( $bp_member_blog_gen_stngs['image_delete'], 'yes' ) : ''; ?> id="bp_member_blog_image_delete"/>
					<div class="wb-slider wb-round"></div>
				</label>				
			</div>
		</div>

		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
				<label>
					<?php esc_html_e( 'Exclude categories', 'buddypress-member-blog' ); ?>
				</label>
			</div>
			<div class="wbcom-settings-section-options">
				<select id="bp-blog-category-select" name="bp_member_blog_gen_stngs[exclude_category][]" multiple data-placeholder="<?php esc_html_e( 'Select category to exclude on fronted', 'buddypress-member-blog' ); ?>">
				<?php
				foreach ( $category as $cat ) {
					$selected = ( ! empty( $bp_member_blog_gen_stngs['exclude_category'] ) && in_array( $cat->term_id, $bp_member_blog_gen_stngs['exclude_category'] ) ) ? 'selected' : '';
					?>
					<option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $cat->name ); ?></option>
				<?php } ?>
				</select>
			</div>
		</div>
		<?php

		/**
		 * Fires after the display of default general options.
		 *
		 * Allows plugins to add their own options.
		 *
		 * @since 1.0.0
		 * @param array $bp_member_blog_gen_stngs Plugin general setting option.
		 */
		do_action( 'bmpro_add_general_settings_options', $bp_member_blog_gen_stngs );
		?>
	</div>
	<?php submit_button(); ?>
</form>
</div>
</div>
</div>
