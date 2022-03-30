<div class="wrap">
	<hr class="wp-header-end">
	<div class="wbcom-wrap">
		<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
		<h1 class="wbcom-plugin-heading"><?php esc_html_e( 'Plugin License Settings', 'buddypress-member-blog' ); ?></h1>
		<div class="wb-plugins-license-tables-wrap">
			<table class="form-table wb-license-form-table desktop-license-headings">
				<thead>
					<tr>
						<th class="wb-product-th"><?php esc_html_e( 'Product', 'buddypress-member-blog' ); ?></th>
						<th class="wb-version-th"><?php esc_html_e( 'Version', 'buddypress-member-blog' ); ?></th>
						<th class="wb-key-th"><?php esc_html_e( 'Key', 'buddypress-member-blog' ); ?></th>
						<th class="wb-status-th"><?php esc_html_e( 'Status', 'buddypress-member-blog' ); ?></th>
						<th class="wb-action-th"><?php esc_html_e( 'Action', 'buddypress-member-blog' ); ?></th>
					</tr>
				</thead>
			</table>
			<?php do_action( 'wbcom_add_plugin_license_code' ); ?>
			<table class="form-table wb-license-form-table">
				<tfoot>
					<tr>
						<th class="wb-product-th"><?php esc_html_e( 'Product', 'buddypress-member-blog' ); ?></th>
						<th class="wb-version-th"><?php esc_html_e( 'Version', 'buddypress-member-blog' ); ?></th>
						<th class="wb-key-th"><?php esc_html_e( 'Key', 'buddypress-member-blog' ); ?></th>
						<th class="wb-status-th"><?php esc_html_e( 'Status', 'buddypress-member-blog' ); ?></th>
						<th class="wb-action-th"><?php esc_html_e( 'Action', 'buddypress-member-blog' ); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div><!-- .wbcom-wrap -->
</div><!-- .wrap -->
