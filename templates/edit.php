<?php
global $post;
$temp_post = $post;
if (!isset($_GET['action'])) { //phpcs:ignore
	$post = bp_member_blog_get_default_post_to_edit( 'post', true );
}

$bp_member_blog_gen_stngs = get_option( 'bp_member_blog_gen_stngs' );
$post_id                  = bp_action_variable( 0 ) ? bp_action_variable( 0 ) : $post->ID;
$blog_post                = (object) array(
	'post_title'   => '',
	'post_content' => '',
);
$post_selected_category   = $post_selected_tag = array();
$post_thumbnail           = '';
$args                     = array();
if ( isset( $_GET['post_id'] ) && $_GET['post_id'] != 0 && isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$post_id   = sanitize_text_field( wp_unslash( $_GET['post_id'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$blog_post = get_post( $post_id );
	$user_id   = get_current_user_id();
	$author_id = $blog_post->post_author;
	if ( $user_id != $author_id ) { ?>
		<script>
			window.location = '<?php echo esc_html( get_the_permalink() ); ?>';
		</script>
		<?php
	}

	$post_selected_category = wp_get_object_terms( $post_id, 'category', array_merge( $args, array( 'fields' => 'ids' ) ) );
	$post_selected_tag      = wp_get_object_terms( $post_id, 'post_tag', array_merge( $args, array( 'fields' => 'names' ) ) );
	$post_thumbnail         = get_the_post_thumbnail_url( $post_id, 'post-thumbnail' );
}

$args = array(
	'taxonomy'   => 'category',
	'hide_empty' => false,
);
if ( isset( $bp_member_blog_gen_stngs['exclude_category'] ) && ! empty( $bp_member_blog_gen_stngs['exclude_category'] ) ) {
	$args['exclude'] = $bp_member_blog_gen_stngs['exclude_category'];
}

$create_cat = '';
if ( isset( $bp_member_blog_gen_stngs['create_category'] ) && ! empty( $bp_member_blog_gen_stngs['create_category'] ) ) {
	$create_cat = $bp_member_blog_gen_stngs['create_category'];
}

$category = get_terms( apply_filters( 'bp_member_blog_terms_args', $args ) );

$submit_btn_value = (isset($_GET) && isset($_GET['action']) && 'edit' === $_GET['action']) ? __('Update post', 'buddypress-member-blog') : __('Create a new post', 'buddypress-member-blog'); //phpcs:ignore

if ( ! isset( $bp_member_blog_gen_stngs['publish_post'] ) && ( $post_id == 0 || $post_id == '' || ( isset( $_GET['is_draft'] ) && $_GET['is_draft'] == 1 ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$submit_btn_value = __( 'Submit for Review', 'buddypress-member-blog' );
}

?>
<div class="bp-member-blog-container">
	<div class="bp-member-blog-post-form">
		<form id="bp-member-post" class="standard-form bp-member-blog-post-form" method="post" action="" enctype="multipart/form-data">

			<?php do_action( 'bp_post_before_title', $post_id ); ?>

			<div class="bp-member-blog-field-container">
				<label for="bp_member_blog_post_title">
					<span><?php esc_html_e( 'Title:', 'buddypress-member-blog' ); ?></span>
					<input type="text" name="bp_member_blog_post_title" value="<?php echo esc_attr( $blog_post->post_title ); ?>" required />
				</label>
			</div>

			<?php do_action( 'bp_post_after_title', $post_id ); ?>

			<?php do_action( 'bp_post_after_content', $post_id ); ?>

			<div class="bp-member-blog-field-container">
				<label for="bp_member_blog_post_content">
					<span><?php esc_html_e( 'Content:', 'buddypress-member-blog' ); ?></span>

					<?php
					if ( ! is_admin() ) {
						wp_editor(
							apply_filters( 'bp_member_blog_post_content', $blog_post->post_content ),
							'bp_member_blog_post_content',
							array(
								'media_buttons' => true,
							)
						);
					}
					?>
				</label>
			</div>

			<?php do_action( 'bp_post_after_content', $post_id ); ?>

			<?php do_action( 'bp_post_before_category', $post_id ); ?>

			<div class="bp-member-blog-field-container">
				<label class="bpmb_category" for="bp_member_blog_post_category">
					<span><?php esc_html_e( 'Category:', 'buddypress-member-blog' ); ?></span>
					<div class="bp-member-blog-post-category-wrapper">
						<select id="bp-blog-category-select" name="bp_member_blog_post_category[]" multiple data-placeholder="<?php esc_html_e( 'Select post category', 'buddypress-member-blog' ); ?>">
							<?php
							// Fetch categories hierarchically.
							$categories = get_categories(
								array(
									'hide_empty'   => false,
									'hierarchical' => true,
									'orderby'      => 'name',
									'order'        => 'ASC',
								)
							);

							// Function to display categories as nested options.
							function display_category_options( $categories, $post_selected_category = array(), $depth = 0 ) {
								foreach ( $categories as $cat ) {
									$selected = ( ! empty( $post_selected_category ) && in_array( $cat->term_id, $post_selected_category ) ) ? 'selected' : '';
									echo '<option value="' . esc_attr( $cat->term_id ) . '" ' . esc_attr( $selected ) . '>';
									echo str_repeat( '&nbsp;', $depth * 4 ) . esc_html( $cat->name );
									echo '</option>';

									if ( ! empty( $cat->children ) ) {
										display_category_options( $cat->children, $post_selected_category, $depth + 1 );
									}
								}
							}

							// Fetch top level categories and their children.
							$category_tree = array();
							foreach ( $categories as $category ) {
								if ( $category->parent == 0 ) {
									$category_tree[ $category->term_id ]           = $category;
									$category_tree[ $category->term_id ]->children = array();
								}
							}

							foreach ( $categories as $category ) {
								if ( $category->parent != 0 && isset( $category_tree[ $category->parent ] ) ) {
									$category_tree[ $category->parent ]->children[] = $category;
								}
							}

							// Display the category options.
							display_category_options( $category_tree, $post_selected_category );
							?>
						</select>
						<?php if ( 'yes' === $create_cat ) { ?>
							<a href="javascript:void(0);" class="add-bpmb-category"><span class="dashicons dashicons-plus"></span></a>
						<?php } ?>
					</div>
				</label>

				<?php if ( 'yes' === $create_cat ) { ?>
					<div class="add-bpmb-cat-row">
						<?php /* translators: Display plural label name */ ?>
						<input type="text" id="bpmb-category-name" placeholder="<?php echo esc_html_e( 'Add Category', 'buddypress-member-blog' ); ?>">
						<?php $add_cat_nonce = wp_create_nonce( 'bpmb-add-bpmb-category' ); ?>
						<input type="hidden" id="bpmb-add-category-nonce" value="<?php echo esc_html( $add_cat_nonce ); ?>">
						<button type="button" id="add-bpmb-cat" class="btn button"><?php esc_html_e( 'Add', 'buddypress-member-blog' ); ?></button>
					</div>
				<?php } ?>
			</div>

			<?php do_action( 'bp_post_after_category', $post_id ); ?>

			<?php do_action( 'bp_post_before_tag', $post_id ); ?>

			<div class="bp-member-blog-field-container">
				<label for="bp_member_blog_post_tag">
					<span><?php esc_html_e( 'Tag:', 'buddypress-member-blog' ); ?></span>

					<input type="text" id="bp_member_blog_post_tag" class="regular-text" tabindex="-1" name="bp_member_blog_post_tag" placeholder="<?php esc_html_e( 'Please add post tags with comma separator.', 'buddypress-member-blog' ); ?>" value="">

					<ul class="bpmb-post-tag-lists">
						<?php if ( ! empty( $post_selected_tag ) ) : ?>
							<?php foreach ( $post_selected_tag as $post_tag ) : ?>
								<li class="added-post-tag">
									<?php echo wp_kses_post( $post_tag ); ?>
									<span class="bpmb-tag-remove">x</span>
									<input type="hidden" value="<?php echo esc_attr( $post_tag ); ?>" name="bp_member_blog_post_tag[]">
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>

				</label>
			</div>

			<?php do_action( 'bp_post_after_tag', $post_id ); ?>

			<?php do_action( 'bp_post_before_featured_image', $post_id ); ?>

			<div class="bp-member-blog-field-container">
				<label for="bp-member-blog-featured-image-wrapper">
					<span><?php esc_html_e( 'Featured Image:', 'buddypress-member-blog' ); ?></span>

					<div class="bp-member-blog-featured-image-wrapper">
						<input type="file" id="bp_member_blog_post_featured_image" name="bp_member_blog_post_featured_image" />
						<label for="bp_member_blog_post_featured_image" class="custom-file-upload"><?php esc_html_e( 'Add Image', 'buddypress-member-blog' ); ?></label>
					</div>

					<div class="bp_member_blog_post_img_preview" 
					<?php
					if ( $post_thumbnail == '' ) :
						?>
					style="display:none;" <?php endif; ?>>
						<img id="bp_member_post_img_preview" src="<?php echo esc_url( $post_thumbnail ); ?>" alt="pic" width="200" , height="200" />
					</div>
				</label>
			</div>

			<?php do_action( 'bp_post_after_featured_image', $post_id ); ?>

			<input type="hidden" name="action" value="bp_member_blog_post" />

			<?php if ( $post_id ) : ?>
				<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>" id="post_ID" />
			<?php endif; ?>

			<input type="hidden" value="<?php echo ( isset( $_SERVER['REQUEST_URI'] ) ) ? esc_attr( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : ''; ?>" name="post_form_url" />
			<?php do_action( 'bp_post_before_submit_button', $post_id ); ?>
			<input id="blog-submit" name="bp_member_blog_form_subimitted" class="bp-member-blog-btn btn button button-primary button-large" type="submit" value="<?php echo esc_attr( $submit_btn_value ); ?>" />

			<?php if ( ! isset( $bp_member_blog_gen_stngs['publish_post'] ) ) : ?>
				<input id="blog-submit" name="bp_member_blog_form_save" class="bp-member-blog-btn btn button button-primary button-large" type="submit" value="<?php echo esc_attr__( 'Save', 'buddypress-member-blog' ); ?>" />
			<?php endif; ?>

			<?php wp_nonce_field( 'bp_member_blog_post' ); ?>
		</form>

	</div>

</div>
<?php
$post = $temp_post;
