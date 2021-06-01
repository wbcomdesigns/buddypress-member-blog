<?php
/**
 *
 * This template file is used for fetching desired options page file at admin settings end.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( isset( $_GET['tab'] ) ) {
	$bautof_tab = sanitize_text_field( $_GET['tab'] );
} else {
	$bautof_tab = 'welcome';
}

switch ( $bautof_tab ) {
	case 'welcome':
		include 'bp-member-blog-welcome-page.php';
		break;
	case 'general':
		include 'bp-member-blog-setting-general-tab.php';
		break;
	default:
		include 'bp-member-blog-welcome-page.php';
		break;
}

