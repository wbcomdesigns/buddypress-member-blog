<?php
/**
 * Restrictions Tab Content.
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
$user_roles     = $wp_roles->get_names();
$bpmb_pro_stngs = get_option( 'blog_restriction' );
?>
<div class="wbcom-tab-content">
<div class="wbcom-welcome-main-wrapper">
<div class="wbcom-admin-title-section">
	<h3 class="wbcom-welcome-title"><?php esc_html_e( 'Member Blog Restriction', 'buddypress-member-blog' ); ?></h3>
</div>
<div class="bp-member-blog-wrapper-admin">	
<form method="post" action="options.php">
	<?php
	settings_fields( 'bp_member_blog_pro_restrictions_settings' );
	do_settings_sections( 'blog_restriction' );
	?>
	<div class="form-field preview_files">
			<table class="widefat bp-member-blog-pro-table" id="bp-member-blog-pro-table">
				<tbody class="ui-sortable wcap_pro_preview-tr">
						<a href="javascript:void(0)"  class="bp-member-blog-add-form-section" disabled><span><?php echo esc_html_e( 'Add Restriction', 'buddypress-member-blog' ); ?></span></a>	
					<?php if ( ! empty( $bpmb_pro_stngs ) ) { ?>
						<?php foreach ( $bpmb_pro_stngs as $key => $value ) { ?>					
					<tr class="bp-member-blog-pro-form-section">
						<td>
							<label>User Role Restriction</label>
							<select name="blog_restriction[restriction_<?php echo esc_html( $i ); ?>][user_role]" id="selected-user-role">
								<option><?php esc_html_e( 'Select Role', 'buddypress-member-blog' ); ?></option>
							<?php foreach ( $user_roles as $slug => $role_name ) { ?>
								<?php
								$selected = ( ! empty( $bpmb_pro_stngs[ 'restriction_' . $i . '' ] ) && in_array( $slug, $bpmb_pro_stngs[ 'restriction_' . $i . '' ] ) ) ? 'selected' : '';
								?>
							<option value="<?php echo esc_attr( $slug ); ?>"  	
													<?php
													if ( $slug == 'administrator' ) :
														?>
							style="display:none;" <?php endif; ?><?php echo esc_attr( $selected ); ?>><?php echo esc_html( $role_name ); ?></option>
						<?php } ?>
							</select>	
						</td>
						<td>
							<label>Limit Per Hour</label>
							<input type="number" name="" id="blogs-per-hour"  placeholder="Limit Per Hour" required disbaled>
						</td>
						<td>
							<label>Limit Per Day</label>
							<input type="number" name="" id="blogs-per-day"  placeholder="Limit Per Day" required>
						</td>
						<td>
							<label>Limit Per Week</label>
							<input type="number" name="" id="blogs-per-week" placeholder="Limit Per Week" required>
						</td>
						<td>
							<label>Limit Per Month</label>
							<input type="number" name="" id="blogs-per-month" placeholder="Limit Per Month" required>
						</td>
						<td>
							<label>Limit Per Year</label>
							<input type="number" name="" id="blogs-per-year" placeholder="Limit Per Year" required>
						</td>
						<td class="delete-restriction">
							<a href="" class="bp-member-blog-pro-remove-form-section" id="bp-member-blog-pro-delete-table"><span class="dashicons dashicons-dismiss"></span></a>
						</td>
					</tr>
							<?php
							$i++; }
					} else {
						?>
						<tr class="bp-member-blog-pro-form-section">
						<td>
							<label>User Role Restriction</label>
							<select name="" id="selected-user-role" disabled>
								<option><?php esc_html_e( 'Select Role', 'buddypress-member-blog' ); ?></option>
							</select>	
						</td>
						<td>
							<label>Limit Per Hour</label>
							<input type="number" name="" id="blogs-per-hour"  placeholder="Limit Per Hour" required disabled>
						</td>
						<td>
							<label>Limit Per Day</label>
							<input type="number" name="" id="blogs-per-day"  placeholder="Limit Per Day" required disabled>
						</td>
						<td>
							<label>Limit Per Week</label>
							<input type="number" name="" id="blogs-per-week" placeholder="Limit Per Week" required disabled>
						</td>
						<td>
							<label>Limit Per Month</label>
							<input type="number" name="" id="blogs-per-month" placeholder="Limit Per Month" required disabled>
						</td>
						<td>
							<label>Limit Per Year</label>
							<input type="number" name="" id="blogs-per-year" placeholder="Limit Per Year" required disabled>
						</td>
						<td class="delete-restriction" disabled>
							<a href="" class="bp-member-blog-pro-remove-form-section" id="bp-member-blog-pro-delete-table"><span class="dashicons dashicons-dismiss"></span></a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
</form>
</div>
</div>
</div>
