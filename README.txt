=== Wbcom Designs - BuddyPress Member Blog ===
Contributors: Wbcom Designs
Donate link: https://wbcomdesigns.com/
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 6.7.2
Stable tag: 2.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

BuddyPress Member Blog is a free plugin that allows users to create/manage their blog/posts from their profile. The plugin also permits you to moderate user-submitted posts. Allow members to display the blogs they have submitted on their BuddyPress Profile.

== Description ==

BuddyPress Member Blog is a free plugin that allows users to create/manage their blog/posts from their profile. The plugin also permits you to moderate user-submitted posts. Allow members to display the blogs they have submitted on their BuddyPress Profile.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `buddypress-member-blog.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates


== Changelog ==

= 2.5.0 =
* Enhancement: Improved UI for BuddyBoss group activity generation.  
* Update: Adjusted notice colors for better visual appeal.  
* Fix: Notice is now hidden for pending review status when using BuddyBoss.  
* Fix: Resolved fatal error when visiting the blog tab in BuddyBoss.  
* Fix: Addressed PHPCS issues in `buddypress-member-blog-functions.php`.  
* Update: Removed Hard-G dependencies from the plugin for better compatibility.  
* Fix: Removed notices from the page if BuddyBoss is detected.  
* Fix: Resolved JavaScript issues causing improper functionality.  
* Enhancement: Updated and implemented CSS and JS improvements.  
* Fix: Ensured "Add to Draft" option appears when pending review is disabled.  
* Fix: Resolved post draft and save issues for better content management.  
* Enhancement: Updated `buddypress-member-blog.php` for improved performance.  
* Fix: Allowed admin users to add blogs from the front end in multisite environments.  
* Fix: Managed mobile and tablet UI issues for a smoother experience.  
* Fix: Resolved UI issues in the "Create Member Blog" dropdown.  
* Fix: PHPCS adjustments for category dropdown on blog edit pages.  
* Fix: Notices are now properly displayed when no blogs exist on user profiles.
* Fix: Addressed capability issue where a subscriber's pending blog could be edited by another subscriber.  
* Fix: Resolved blog count duplication issue when using Youzify.  
* Fix: Fixed blog tab functionality with Youzify integration.  

= 2.4.0 =
* Fixed issue preventing admin from adding blogs from the front-end in multisite.
* Managed mobile and tablet UI responsiveness issues.
* Resolved UI issue on the "Create a Member Blog" dropdown.
* Fixed blog category dropdown display issue on the edit blog screen.
* Fixed notices displayed when no blog posts exist
* Fixed issue where pending subscriber blogs could be edited by other subscribers.
* Resolved blog count duplication issue with Youzify.
* Fixed blog tab display issue with Youzify.

= 2.3.1 =
* Fix category exclusion logic and improve category selection

= 2.3.0 =
* Enhancement: Managed button color to align with the theme's color scheme.
* Fix: Resolved fatal error on editing posts.
* Fix: Corrected the function call for bp_member_blog_display_category_options().
* Fix: Hookable position for category now works as expected.
* Update: Adjusted image size settings and alignment for better visuals.

= 2.2.2.1 =
* Fix: Pagination issue

= 2.2.2 =
* Managed: Backend options responsive fixes
* Managed: Only Show the Blog Parent Menu
* Fixed: Removed Condition to show the menu on all pages
* Fixed: Warning in the admin side
* Fixed: Blog menu with BB Platform
* Fixed: Post updated notice UI
* Fixed: Update and manage form fields UI in frontend
* Fixed: Remove unwanted css
* Fixed: Update and manage form fields UI in frontend 
* Fixed: Move the Hook after updating the custom fields

= 2.2.1 =
* Enhancement: Added nested view for category management.
* Update: Merged branch '2.2.1' from GitHub repository for synchronization.
* Enhancement: Added <p> tag in notice messages for better formatting.
* Fix: Managed image resolution to full width and applied necessary fixes.
* Enhancement: Added image hyperlink functionality.

= 2.2.0 =
* Enhancement: Added filter for category management.
* Fix: Resolved group pagination issue with BB platform.
* Fix: Fixed pagination redirecting to 404 on the group page.
* Update: Changed descriptions for clarity.
* Fix: Ensured admins do not need approval to publish posts.
* Update: Improved pagination spacing for better UI.
* Fix: Corrected published blog count issue.
* Enhancement: Managed UI within the blog tab for a smoother experience.

= 2.1.1 =
* Fix: (#242)Fixed issue on tab Translation
* Fix: (#238)Fixed fatal error on the client site

= 2.1.0 =
* Fix: (#236) Fixed client site conflict issue
* Fix: (#230) Fixed issue with the edit link

= 2.0.4 =
* Fix: (#219) Fixed edit blog URL on member profile 
* Fix: Fixed translation issue

= 2.0.3 =
* Fix: (#227) Managed dark mode with reign theme
* Fix: Fixed can not upload media on edit post

= 2.0.2 =
* Updated: Added option for template override 

= 2.0.1 =
* Added: Filter hook
* Added: (#216) Documentation link and updated top banner
* Added: (#216) Icon classes and fixes
* Fix: Post tab hidden for admin user
* Fix: (#225) Media uploading is not working
* Fix: (#216) Media management option disabled by default
* Fix: Removed ACF code
* Fix: Count issue
* Fix: BP v12 fixes
* Updated: (#216) Member type option label and description
* Updated: (#216) Featured Image and edit form UI
* Updated: (#216) Frontend form labels
* Updated: (#216) Display the pro plugin label and version
* Updated: (#216) Backend settings label, description, and pro tabs and images

= 2.0.0 =
* Fix: PHPCS fixes
* Fix: (#247) Issue with PHP 8.2 version
* Fix: (#214) Blog option showing in moderation plugin

= 1.9.9 =
* Fix: (#205) Category dropdown issue
* Fix: (#203) Blog menu disappears when we visit other pages

= 1.9.8 =
* Fix: (#126)iframe
* Fix: (#198) query moniter issue

= 1.9.7 =
* Fix: comment issue on the create blog page.
* Fix: (#193)Unable to translate blog string.

= 1.9.6 =
* Fix: Fixed blog label and slug at admin bar menu

= 1.9.5 =
* Fix: Fixed blog post navigation count issue
* Fix: Create dynamic slug
* Fix: (#185) Fixed previous pagination issue
* Fix: (#168) Added integration of recaptcha

= 1.9.4 =
* Fix: (#183) Fixed update post do not work with BB theme
* Enhancement: (#169) blog post add pending nav, post count with UI managed
* Enhancement: (#169) post listing added subnav count
* Enhancement: (#169) post listing added pending tab
* Fix: Fixed Plugin redirect issue when multi plugin activate the same time

= 1.9.3 =
* Fix: (#152) Fixed warning

= 1.9.2 =
* Fix: (#144)Fixed author unable to post media
* Fix: (#145)Fixed managed groups activity UI
* Fix: (#148)Fixed managed issue in edit post

= 1.9.1 =
* Fix: (#140)Fixed edit blog not working

= 1.9.0 =
* Fix: Category UI fixes frontend form
* Fix: Fixed buddyboss admin notice issue
* Fix: (#119) Fixed add category option from the front end
* Fix: (#118) Fixed add draft subnav issue
* Fix: (#122) Admin categories field UI fixed

= 1.8.0 =
* Fix: Fixed asign category issue
* Fix: Fixed conflict issue with Member blog pro
* Fix: (#119) Added front-end create category option
* Fix: Updated new admin ui

= 1.7.1 =
* Fix: (#117) Fixed Subscriber user can't upload media from fronted

= 1.7.0 =
* Fix: Add Dark Mode and filter category issue
* Fix: (#108) Managed pro tabs UI
* Fix: (#105) Added Pro options as a disabled mode
* Fix: (#107) post category UI managed
* Fix: (#32) Display post category name on the blog tab
* Fix: (#103) Managed UI with buddyboss

= 1.6.1 =
* Fix: remove extra div
* Fix: #101 Profile Blog List UI issue 

= 1.6.0 =
* Fix: backend UI Updated tab icons
* Fix: (#80)Fixed wbcom wrapper display even if plugin is deactivated
* Fix: (#79)Fixed pagination issue for logout user
* Fix: Removed install plugin button from wrapper and phpcs fixes
* Fix: Managed Profile blog form UI
* Fix: (#74) Fixed add new post sub tab post form issue with youzify
* Fix: Set Groups Integration icon
* Fix: Removed blog tab and added sub tab in posts tab if youzify activated
* Fix: (#76)changed the blog tab notice for loggedin user
* Fix: #73, #74 Member Post Listing and Add New post form
* Fix: (#76) changed the blog tab notice for loggedin user
* Fix: (#75) Fixed conflict issue with BP rewrites plugin

= 1.5.0 =
* Fix: (#68) Fixed blog count issue on display user
* Fix: (#65) Managed UI conflict issue with other plugins
* Fix: (#58) Fixed category display issue on admin
* Fix: Introduce new action hook for blog profile tab
* Fix: #34 Fixed grammatical error on admin dashboard
* Fix: #57 Managed Add New Blog UI
* Fix: Fixed upload image issue
* Fix: Added hookable position after submit post

= 1.4.2 =
* Fix: (#42) Fixed BuddyPress Nav tab redirection issue with BP Rewrites plugin
* Fix: (#41) Fixed conflict issue with BP Rewrites plugin
* Fix: Managed RTL fixes
* Fix: (#40) Fixed reset setting issue after activation

= 1.4.1 =
* Fix: (#36) Fixed category and tags are not adding from front-end

= 1.4.0 =
* Fix: (#30) Fixed Grammatical error
* Fix: (#33) Fixed admin warning
* Fix: Introduce new admin action hooks
* Fix: Fixed phpcs errors
* Fix: Fixed escaping function error

= 1.3.0 =
* Fix: updated permissions for displayed member profile

= 1.2.0 =
* Fix: Hide Blog Menu for not allowed user role or member type to create post
* Fix: Added Blog in BuddyPress Mmeber Menu Section
* Fix: Post Tags issue and set Input field to add Post Tags
* Fix: #18 - when unpublish or delete the blog redirects to 404
* Fix: Managed post tags UI

= 1.1.0 =
* Fix: Blog slug on user profile

= 1.0.0 =
* Initial Release
