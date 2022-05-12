<?php
/**
 * Group Tab Content.
 *
 * @link       https:// wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Member_Blog_Pro
 * @subpackage Buddypress_Member_Blog_Pro/admin/inc
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wp_roles;
$user_roles            = $wp_roles->get_names();
$bpmb_pro_group_stngs  = get_option( 'group_blog_integration' );
$bpmb_pro_allow_groups = isset( $bpmb_pro_group_stngs['allow_group_linking'] ) ? $bpmb_pro_group_stngs['allow_group_linking'] : '';
$class                 = '';
if ( 'yes' !== $bpmb_pro_allow_groups ) {
	$class = 'hide';
}
if ( bp_is_active( 'groups' ) ) {
	?>
<div class="wbcom-tab-content">
<div class="wbcom-welcome-main-wrapper">
<div class="wbcom-admin-title-section">
	<h3 class="wbcom-welcome-title"><?php esc_html_e( 'Group Integration', 'buddypress-member-blog' ); ?></h3>
</div>
<div class="bp-member-blog-wrapper-admin bp-member-blog-pro-group-integration">	
	<form method="post" action="options.php">
		<?php
		settings_fields( 'bp_member_blog_pro_groups_settings' );
		do_settings_sections( 'group_blog_integration' );
		?>
		<div class="form-field preview_files">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="bp_member_blog_publish_post">
									<?php esc_html_e( 'Enable Group integration', 'buddypress-member-blog' ); ?>
								</label>
							</th>
							<td>
								<label class="wb-switch">
									<input name='group_blog_integration[allow_group_linking]' id="bp-member-blog-pro-group-link" type='checkbox' value='yes' <?php echo ( isset( $bpmb_pro_group_stngs['allow_group_linking'] ) ) ? checked( $bpmb_pro_group_stngs['allow_group_linking'], 'yes' ) : ''; ?> disabled/>
									<div class="wb-slider wb-round"></div>
								</label>
								<p class="description"><?php esc_html_e( 'Allow users to create blog posts in the groups.', 'buddypress-member-blog' ); ?></p>
							</td>
						</tr>
						<tr id="bp-member-blog-pro-can-link" class="<?php echo esc_attr( $class ); ?>">
							<th scope="row">
								<label for="bp_member_blog_publish_post">
									<?php esc_html_e( 'Who can links the group?', 'buddypress-member-blog' ); ?>
								</label>
							</th>
							<td>
								<label>
									<select name="" id="select-links" disabled>
										<option value="admin"><?php esc_html_e( 'Group Admin', 'buddypress-member-blog' ); ?></option>
										<option value="mod"><?php esc_html_e( 'Group Moderator', 'buddypress-member-blog' ); ?></option>
										<option value="member"><?php esc_html_e( 'Group Member', 'buddypress-member-blog' ); ?></option>
									</select>
								</label>
								<p class="description"><?php esc_html_e( 'Select the group roles to assign the capability of blog posting within the groups.', 'buddypress-member-blog' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="bp_member_blog_publish_post">
									<?php esc_html_e( 'Enable group posts activity', 'buddypress-member-blog' ); ?>
								</label>
							</th>
							<td>
								<label class="wb-switch">
									<input name='group_blog_integration[allow_group_activity]' type='checkbox' value='yes' <?php echo ( isset( $bpmb_pro_group_stngs['allow_group_activity'] ) ) ? checked( $bpmb_pro_group_stngs['allow_group_activity'], 'yes' ) : ''; ?> disabled/>
									<div class="wb-slider wb-round"></div>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this setting to generate the activity on blog posting in the groups.', 'buddypress-member-blog' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
		</div>
	</form>
</div>
</div>
</div>
	<?php
} else {
	?>
<div class="wbcom-tab-content">
	<div class="bp-member-blog-pro-groups">
		<?php
		$bp_groups_link = '<a href="' . admin_url( 'admin.php?page=bp-components' ) . '" target="blank">Click here</a>';
		?>
		<span>
			<?php echo sprintf( __( 'Enable BuddyPress groups component to allow groups integration %1$s.', 'buddypress-member-blog' ), '<strong>' . $bp_groups_link . '</strong>' ); ?>
		</span>
	</div>
</div>
<?php }
?>
