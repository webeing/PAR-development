<?php
/*
Plugin Name: BuddyPress
Plugin URI: http://buddypress.org/download/
Description: BuddyPress will add social networking features to a new or existing WordPress MU installation.
Author: The BuddyPress Community
Version: 1.1.3
Author URI: http://buddypress.org/developers/
Site Wide Only: true
*/

define( 'BP_VERSION', '1.1.3' );

/***
 * This file will load in each BuddyPress component based on which
 * of the components have been activated on the "BuddyPress" admin menu.
 */

require_once( WP_PLUGIN_DIR . '/buddypress/bp-core.php' );
$deactivated = apply_filters( 'bp_deactivated_components', get_site_option( 'bp-deactivated-components' ) );

/* Activity Streams */
if ( !isset( $deactivated['bp-activity.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-activity.php') )
	include( BP_PLUGIN_DIR . '/bp-activity.php' );

/* Blog Tracking */
if ( !isset( $deactivated['bp-blogs.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-blogs.php') )
	include( BP_PLUGIN_DIR . '/bp-blogs.php' );

/* bbPress Forum Integration */
if ( !isset( $deactivated['bp-forums.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-forums.php') )
	include( BP_PLUGIN_DIR . '/bp-forums.php' );

/* Friend Connections */
if ( !isset( $deactivated['bp-friends.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-friends.php') )
	include( BP_PLUGIN_DIR . '/bp-friends.php' );

/* Groups Support */
if ( !isset( $deactivated['bp-groups.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-groups.php') )
	include( BP_PLUGIN_DIR . '/bp-groups.php' );

/* Private Messaging */
if ( !isset( $deactivated['bp-messages.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-messages.php') )
	include( BP_PLUGIN_DIR . '/bp-messages.php' );

/* Wire Support */
if ( !isset( $deactivated['bp-wire.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-wire.php') )
	include( BP_PLUGIN_DIR . '/bp-wire.php' );

/* Extended Profiles */
if ( !isset( $deactivated['bp-xprofile.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-xprofile.php') )
	include( BP_PLUGIN_DIR . '/bp-xprofile.php' );

/* Status Updates */
if ( !isset( $deactivated['bp-status.php'] ) && file_exists( BP_PLUGIN_DIR . '/bp-status.php') )
	include( BP_PLUGIN_DIR . '/bp-status.php' );

?>