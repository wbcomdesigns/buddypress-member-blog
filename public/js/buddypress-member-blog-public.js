(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 
	  $(document).ready(function($) {
		 $('#bp-blog-category-select').selectize({
	 		placeholder		: $( '#bp-blog-category-select').data( 'placeholder' ),
	 		plugins			: ['remove_button']
	 	});
		
		$('#bp-blog-tag-select').selectize({
	 		placeholder		: $( '#bp-blog-tag-select').data( 'placeholder' ),
	 		plugins			: ['remove_button']
	 	});
		
		$( document ).on('change', '#bp_member_blog_post_featured_image', function () {
			console.log(this.files);
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function (event) {
					$("#bp_member_post_img_preview")
					  .attr("src", event.target.result);
					$( '#bp_member_blog_post_img_preview').show();
				};
				reader.readAsDataURL(file);
			}
		});
	 });

})( jQuery );
