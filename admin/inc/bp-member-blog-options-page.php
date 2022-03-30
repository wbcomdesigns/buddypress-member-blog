<?php
	/**
	 * This template file is used for fetching desired options page file at admin settings end.
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

	$bautof_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'welcome';

switch ( $bautof_tab ) {
	case 'welcome':
		include 'bp-member-blog-welcome-page.php';
		break;
	case 'general':
		include 'bp-member-blog-setting-general-tab.php';
		break;
}

