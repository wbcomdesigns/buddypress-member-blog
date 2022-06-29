(function ($) {
  "use strict";

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

  $(document).ready(function ($) {
    $("#bp-blog-category-select").selectize({
      placeholder: $("#bp-blog-category-select").data("placeholder"),
      plugins: ["remove_button"],
    });

    $("#bp-blog-tag-select").selectize({
      placeholder: $("#bp-blog-tag-select").data("placeholder"),
      plugins: ["remove_button"],
    });

    $(document).on(
      "click",
      ".bp-member-blog-container .post-actions a.confirm",
      function () {
        if (confirm("This post will be permanently deleted.")) {
          return true;
        }
        return false;
      }
    );

    $(document).on(
      "change",
      "#bp_member_blog_post_featured_image",
      function () {
        console.log(this.files);
        const file = this.files[0];
        if (file) {
          let reader = new FileReader();
          reader.onload = function (event) {
            $("#bp_member_post_img_preview").attr("src", event.target.result);
            $(".bp_member_blog_post_img_preview").show();
          };
          reader.readAsDataURL(file);
        }
      }
    );

    /* Member Report Subject */
    $("#bp_member_blog_post_tag").keypress(function (e) {
      var keycode = e.keyCode ? e.keyCode : e.which;

      // If user Clicks Enter
      if (keycode == 13) {
        var report_subject = $(this).val(),
          tags_form = $(".bpmb-post-tag-lists"),
          field_name = "bp_member_blog_post_tag[]",
          current_elts = $.bpmb_check_post_tags_existence(
            tags_form,
            report_subject
          );

        if ($.trim(report_subject) != "" && current_elts.length == 0) {
          // Add item in Case Isn't Already Exist
          $(".bpmb-post-tag-lists").append(
            '<li class="added-post-tag">' +
              report_subject +
              '<span class="bpmb-tag-remove">x</span>' +
              '<input type="hidden" value="' +
              report_subject +
              '" name="' +
              field_name +
              '">' +
              "</li>"
          );

          // Clear Input
          $(this).val("");
        } else {
          // Add Flash Element In Case is Already Exist.
          current_elts.addClass("flash");
          setTimeout(function () {
            current_elts.removeClass("flash");
          }, 1000);
          $(this).val("");
        }
        e.preventDefault();
      }
    });

    $.bpmb_check_post_tags_existence = function (elt, keyword) {
      // Setup Variables.
      var tag_obj = $();
      // Check Old Tags.
      elt.find(".added-post-tag").each(function () {
        var str = $.trim($.trim($(this).text()).slice(0, -1));
        if (str.toLowerCase() === $.trim(keyword).toLowerCase()) {
          tag_obj = $(this);
          return false;
        }
      });

      // Return Result.
      return tag_obj;
    };

    $(document).on("click", ".bpmb-tag-remove", function (e) {
      e.preventDefault();
      $(this).parent().remove();
    });

    //Add BP bpmb Category Show Row
    jQuery(document).on("click", ".add-bpmb-category", function () {
      jQuery(".add-bpmb-cat-row").slideToggle("slow");
      var element_height = jQuery(".add-bpmb-cat-row")
        .css("height")
        .match(/\d+/);
      if (element_height[0] > 5) {
        jQuery(".add-bpmb-category i").attr("class", "fa fa-plus");
      } else {
        jQuery(".add-bpmb-category i").attr("class", "fa fa-minus");
      }
    });

    //Add BP bpmb Category
    jQuery(document).on("click", "#add-bpmb-cat", function () {
      var name = jQuery("#bpmb-category-name").val();
      var btn_text = jQuery(this).html();
      if (name == "") {
        jQuery("#bpmb-category-name")
          .addClass("bpbpmb-add-cat-empty")
          .attr("placeholder", bpmb_ajax_object.required_cat_text);
      } else {
        jQuery(this).html(btn_text + ' <i class="fa fa-refresh fa-spin"></i>');
        jQuery.post(
          ajaxurl,
          {
            action: "bpmb_add_category_front_end",
            name: name,
            security_nonce: bpmb_ajax_object.ajax_nonce,
          },
          function (response) {
            if (response) {
              jQuery(".add-bpmb-cat-row").hide();
              jQuery("#bp-blog-category-select").append(
                '<option value="' + response + '">' + name + "</option>"
              );
              let presetvalue = jQuery("#bp-blog-category-select").val();
              if ("" != presetvalue) {
                let selectedOptions = [];
                if (presetvalue.includes(",")) {
                  selectedOptions = presetvalue.split(",");
                  selectedOptions.push(response);
                  console.log("here1");
                } else if (Array.isArray(presetvalue)) {
                  selectedOptions = presetvalue;
                  selectedOptions.push(response);
                  console.log("here2");
                } else {
                  selectedOptions = [presetvalue];
                  selectedOptions.push(response);
                  console.log("here3");
                }
                console.log(selectedOptions);
                jQuery("#bp-blog-category-select")[0].selectize.addOption({
                  text: name,
                  value: response,
                });

                jQuery("#bp-blog-category-select")[0].selectize.setValue(
                  selectedOptions
                );
              } else {
                jQuery("#bp-blog-category-select")[0].selectize.addOption({
                  text: name,
                  value: response,
                });
                jQuery("#bp-blog-category-select")[0].selectize.setValue(
                  response
                );
              }
              jQuery("#add-bpmb-cat").html(btn_text);
              jQuery("#bpmb-category-name").val("");
              jQuery(".add-bpmb-category i").attr("class", "fa fa-plus");
            }
          }
        );
      }
    });
  });
})(jQuery);
