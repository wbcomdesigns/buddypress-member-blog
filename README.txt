=== Wbcom Designs- BuddyPress Member Blog ===
Contributors: Wbcom Designs
Donate link: https://wbcomdesigns.com/
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 5.9.1
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

BuddyPress Member Blog is a free plugin that allows users to create/manage their blog/posts from the profile. The plugin also allows you to moderate user-submitted posts. Allow members to display their submitted blogs at their BuddyPress Profile

== Description ==

BuddyPress Member Blog is a free plugin that allows users to create/manage their blog/posts from the profile. The plugin also allows you to moderate user-submitted posts. Allow members to display their submitted blogs at their BuddyPress Profile

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `buddypress-member-blog.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates


== Changelog ==
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
