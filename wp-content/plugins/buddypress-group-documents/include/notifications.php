<?php

/**
 * Notification functions are used to send email notifications to users on specific events
 * They will check to see the users notification settings first, if the user has the notifications
 * turned on, they will be sent a formatted email notification. 
 *
 */

function bp_group_documents_email_notification( $document ) {
	global $bp;

	$user_name = bp_core_get_userlink($bp->loggedin_user->id,true);
	$user_profile_link = bp_core_get_userlink($bp->loggedin_user->id,false,true);
	$group_name = $bp->groups->current_group->name;
	$group_link = bp_get_group_permalink( $bp->groups->current_group );
	$document_name = $document->name; 
	$document_link = BP_GROUP_DOCUMENTS_FILE_URL . $document->file;


	$subject = '[' . get_blog_option( 1, 'blogname' ) . '] ' . sprintf( __( 'A document was uploaded to %s', 'bp-group-documents' ), $bp->groups->current_group->name );

	foreach ( $bp->groups->current_group->user_dataset as $user ) {
		if( $user->is_admin || $user->is_mod ) {
			if ( 'no' == get_usermeta( $user->user_id, 'notification_group_documents_upload_mod' ) ) continue;
		} else {
			if ( 'no' == get_usermeta( $user->user_id, 'notification_group_documents_upload_member' ) ) continue;
		}

	$message = sprintf( __(
'%s uploaded a new file: %s to the group: %s.

To see %s\'s profile: %s

To see the group %s\'s homepage: %s

To download the new document directly: %s

------------------------
', 'bp-group-documents'), $user_name, $document_name, $group_name, $user_name, $user_profile_link, $group_name, $group_link, $document_link );


		$settings_link = bp_core_get_user_domain( $user->user_id ) . $bp->settings->slug . '/notifications/';
		$message .= sprintf( __( 'To disable these notifications please log in and go to: %s', 'bp-group-documents' ), $settings_link );

		// Set up and send the message
		$ud = get_userdata( $user->user_id );
		$to = $ud->user_email;

		wp_mail( $to, $subject, $message );
		unset( $to, $message);
	}
}
add_action('bp_group_documents_add_success','bp_group_documents_email_notification',10);

?>
