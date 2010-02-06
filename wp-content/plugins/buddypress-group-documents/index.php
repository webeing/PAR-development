<?php
/*
Plugin Name: BP Group Documents
Description: This BuddyPress component creates a document storage area within each group
Version: 0.2.4
Revision Date: January 8, 2009
Requires at least: WPMU 2.8, BuddyPress 1.1
Tested up to: WPMU 2.9, BuddyPress 1.2
License: Example: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: Peter Anselmo, Studio66
Author URI: http://www.studio66design.com
Site Wide Only: true
*/

define ( 'BP_GROUP_DOCUMENTS_IS_INSTALLED', 1 );
define ( 'BP_GROUP_DOCUMENTS_VERSION', '0.2.4' );
define ( 'BP_GROUP_DOCUMENTS_DB_VERSION', '1' );
define ( 'BP_GROUP_DOCUMENTS_FILE_PATH', WP_PLUGIN_DIR . '/buddypress-group-documents/documents/');
define ( 'BP_GROUP_DOCUMENTS_FILE_URL', WP_PLUGIN_URL . '/buddypress-group-documents/documents/');
define ( 'BP_GROUP_DOCUMENTS_DEFAULT_FORMATS', 'odt,rtf,txt,doc,docx,xls,xlsx,ppt,pps,pptx,pdf,jpg,jpeg,gif,png,zip,tar,gz');

if ( !defined( 'BP_GROUP_DOCUMENTS_SLUG' ) )
	define ( 'BP_GROUP_DOCUMENTS_SLUG', 'documents' );

//longer text descriptions to go with the documents can be toggled on or off.
//this will toggle both the textarea input, and the display;
if ( !defined( 'BP_GROUP_DOCUMENTS_SHOW_DESCRIPTIONS' ) )
	define ( 'BP_GROUP_DOCUMENTS_SHOW_DESCRIPTIONS', true );

if ( file_exists( WP_PLUGIN_DIR . '/buddypress-group-documents/languages/' . get_locale() . '.mo' ) )
	load_textdomain( 'bp-group-documents', WP_PLUGIN_DIR . '/buddypress-group-documents/languages/' . get_locale() . '.mo' );

//Go get me some files!
require ( WP_PLUGIN_DIR . '/buddypress-group-documents/include/classes.php' );
require ( WP_PLUGIN_DIR . '/buddypress-group-documents/include/cssjs.php' );
require ( WP_PLUGIN_DIR . '/buddypress-group-documents/include/widgets.php' );
require ( WP_PLUGIN_DIR . '/buddypress-group-documents/include/notifications.php' );
require ( WP_PLUGIN_DIR . '/buddypress-group-documents/include/activity.php' );
require ( WP_PLUGIN_DIR . '/buddypress-group-documents/include/templatetags.php' );
//TODO: make a more consistant filter system
//require ( WP_PLUGIN_DIR . '/buddypress-group-documents/include/filters.php' );


/**********************************************************************
******************SETUP AND INSTALLATION*******************************
**********************************************************************/

/**
 * bp_group_documents_check_prereqs()
 *
 * on activation, makes sure that the server is set up properly for the plugin
 */
function bp_group_documents_check_prereqs() {

	//if the permissions are not correct
	if( !is__writable( BP_GROUP_DOCUMENTS_FILE_PATH ) ) {
		//Initially, I had the plugin deactivate if the requirements were not met. 
		//I have decided against it, as it makes the consequences of a false positive too great
		//deactivate_plugins(__FILE__);

		$string = '<p>' .  __('The webserver does not appear to have write access to the document folder:','bp-group-documents') . ' ' . BP_GROUP_DOCUMENTS_FILE_PATH . '</p>';
		$string .= '<p>' . __('Please check this, as must be enabled for the Group Documents plugin to work correctly.','bp-group-documents') . '</p>';
		$string .= '<p><a href="' . admin_url() . '">' . __('Click here</a> to return to the dashboard','bp-group-documents') . '</p>';

		wp_die( $string );
	}
}
register_activation_hook(__FILE__,'bp_group_documents_check_prereqs');


/**
 * bp_group_documents_install()
 *
 * Installs and/or upgrades the database tables
 */
function bp_group_documents_install() {
	global $wpdb, $bp;
	
	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	
	$sql[] = "CREATE TABLE {$bp->group_documents->table_name} (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		user_id bigint(20) NOT NULL,
		  		group_id bigint(20) NOT NULL,
		  		created_ts int NOT NULL,
				modified_ts int NOT NULL,
				file VARCHAR(255) NOT NULL,
				name VARCHAR(255) NULL,
				description TEXT NULL,
			    KEY user_id (user_id),
			    KEY group_id (group_id),
				KEY created_ts (created_ts),
				KEY modified_ts (modified_ts)
		 	   ) {$charset_collate};";

	require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );
	dbDelta($sql);
	
	update_site_option( 'bp-group-documents-db-version', BP_GROUP_DOCUMENTS_DB_VERSION );
}

/**
 * bp_group_documents_setup_globals()
 *
 * Sets up global variables for group documents
 */
function bp_group_documents_setup_globals() {
	global $bp, $wpdb;

	/* For internal identification */
	$bp->group_documents->id = 'group_documents';
	$bp->group_documents->table_name = $wpdb->base_prefix . 'bp_group_documents';
	$bp->group_documents->format_notification_function = 'bp_group_documents_format_notifications';
	$bp->group_documents->slug = BP_GROUP_DOCUMENTS_SLUG;
	
	/* Register this in the active components array */
	$bp->active_components[$bp->group_documents->slug] = $bp->group_documents->id;
	
	switch( substr( BP_VERSION, 0, 3 ) ) {
		case '1.2':
			if( 'BuddyPress Classic' == get_current_theme() ) {
				define( 'BP_GROUP_DOCUMENTS_THEME_VERSION', '1.1' );
			} else {
				define( 'BP_GROUP_DOCUMENTS_THEME_VERSION', '1.2' );
			}
		break;
		case '1.1':
			define( 'BP_GROUP_DOCUMENTS_THEME_VERSION', '1.1' );
		break;
	}

	do_action('bp_group_documents_globals_loaded');
}
add_action( 'plugins_loaded', 'bp_group_documents_setup_globals', 5 );	
add_action( 'admin_menu', 'bp_group_documents_setup_globals', 2 );

/**
 * bp_group_documents_check_installed()
 *
 * Checks to see if the DB tables exist or if we are running an old version
 * of the component. If it matches, it will run the installation function.
 */
function bp_group_documents_check_installed() {	
	global $wpdb, $bp;

	if ( !is_site_admin() )
		return false;
	
	//Add the component's administration tab under the "BuddyPress" menu for site administrators
	require ( WP_PLUGIN_DIR . '/buddypress-group-documents/include/admin.php' );

	add_submenu_page( 'bp-general-settings', __( 'Group Documents Admin', 'bp-group-documents' ), __( 'Group Documents', 'bp-group-documents' ), 'manage-options', 'bp-group-documents-settings', "bp_group_documents_admin" );	


	/* Need to check db tables exist, activate hook no-worky in mu-plugins folder. */
	if ( get_site_option('bp-group-documents-db-version') < BP_GROUP_DOCUMENTS_DB_VERSION )
		bp_group_documents_install();

	add_option('bp_group_documents_valid_file_formats', BP_GROUP_DOCUMENTS_DEFAULT_FORMATS );
	add_option('bp_group_documents_items_per_page', 20 );
}
add_action( 'admin_menu', 'bp_group_documents_check_installed',30);

/**
 * bp_group_documents_setup_nav()
 *
 * Sets up the navigation items for the component.  
 * Adds one item under the group navigation
 */
function bp_group_documents_setup_nav() {
	global $bp,$current_blog,$group_object;

	if( !class_exists('BP_Groups_Group') ) {
		return;
	}

	if ( $group_id = BP_Groups_Group::group_exists($bp->current_action) ) {

		/* This is a single group page. */
		$bp->is_single_item = true;
		$bp->groups->current_group = &new BP_Groups_Group( $group_id );

	}	

	//$groups_link = $bp->loggedin_user->domain . $bp->groups->slug . '/';
	$groups_link = $bp->root_domain . '/' . $bp->groups->slug . '/' . $bp->groups->current_group->slug . '/';

	/* Add the subnav item only to the single group nav item*/
	if ( $bp->is_single_item )
    bp_core_new_subnav_item( array( 
		'name' => __( 'Documents', 'bp-group-documents' ), 
		'slug' => $bp->group_documents->slug, 
		'parent_url' => $groups_link, 
		'parent_slug' => $bp->groups->slug, 
		'screen_function' => 'bp_group_documents_display', 
		'position' => 35, 
		'user_has_access' => $bp->groups->current_group->user_has_access,
		'item_css_id' => 'group-documents' ) );

	do_action('bp_group_documents_nav_setup');
}
add_action( 'wp', 'bp_group_documents_setup_nav', 2 );
add_action( 'admin_menu', 'bp_group_documents_setup_nav', 2 );


/**
 * bp_group_documents_display()
 *
 * Sets up the default template file and calls the dislay output function
 */
function bp_group_documents_display() {
	global $bp;

	do_action( 'bp_group_documents_display' );
	
	add_action( 'bp_template_content_header', 'bp_group_documents_display_header' );
	add_action( 'bp_template_title', 'bp_group_documents_display_title' );
	add_action( 'bp_template_content', 'bp_group_documents_display_content' );

	// Load the plugin template file.
	// BP 1.1 includes a generic "plugin-template file
	// BP 1.2 breaks it out into a group-specific template
	if( '1.1' == BP_GROUP_DOCUMENTS_THEME_VERSION ) {
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'plugin-template' ) );
	} else {
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'groups/single/plugins' ) );
	}
}


function bp_group_documents_display_header() {
	_e( 'Group Documents', 'bp-group-documents' );
}
function bp_group_documents_display_title() {
	_e( 'Document List', 'bp-group-documents' );
}

/**********************************************************************
******************BEGIN MAIN DISPLAY***********************************
**********************************************************************/

function bp_group_documents_display_content() {
	global $bp;

	$template = new BP_Group_Documents_Template();?>

	<div class="bp-widget">
	<div id="bp-group-documents">


	<?php do_action( 'template_notices' ) // (error/success feedback) ?>

	<?php //-------------------------------------------------------LIST VIEW-- ?>

	<?php if( $template->document_list && count($template->document_list >= 1) ) { ?>

		<div class="pagination">
			<div id="group-documents-count" class="pag-count">
				<?php $template->pagination_count(); ?>
			</div>
		<?php if( $template->show_pagination() ){ ?>
			<div id="group-documents-count" class="pagination-links">
				<?php $template->pagination_links(); ?>
			</div>
		<?php } ?>
		</div>


		<ul id="forum-topic-list" class="item-list">

		<?php //loop through each document and display content along with admin options
		$count = 0;
		foreach( $template->document_list as $document_params ) {
			$document = new BP_Group_Documents($document_params['id'], $document_params); ?>

			<li <?php if( ++$count%2 ) echo 'class="alt"';?> ><a href="<?php echo BP_GROUP_DOCUMENTS_FILE_URL . $document->file; ?>" target="_blank"><?php echo ($document->name)?($document->name):($document->file); ?><?php if( get_option( 'bp_group_documents_display_file_size' )) { echo ' (' . get_file_size( $document ) . ')'; } ?></a> &nbsp;
			<?php printf( __( 'Uploaded by %s on %s', 'bp-group-documents'),bp_core_get_userlink($document->user_id),date( 'n/j/Y', $document->created_ts ));
			if( BP_GROUP_DOCUMENTS_SHOW_DESCRIPTIONS){
				echo '<br />' . nl2br($document->description);
			}
			//show edit and delete options if user is privileged
			echo '<div class="admin-links">';
			if( $document->current_user_can('edit') ) {
				$edit_link = wp_nonce_url( $template->action_link . '/edit/' . $document->id, 'group-documents-edit-link' );
				echo "<a href='$edit_link'>" . __('Edit','bp-group-documents') . "</a> | ";
			}
			if( $document->current_user_can('delete') ) {
				$delete_link = wp_nonce_url( $template->action_link . '/delete/' . $document->id, 'group-documents-delete-link' );
				echo "<a href='$delete_link' id='bp-group-documents-delete'>" . __('Delete','bp-group-documents') . "</a>";
			}
			echo '</div>';
			echo '</li>';		
		} ?>
		</ul>

	<?php } else { ?>
	<div id="message" class="info">
		<p><?php _e( 'There have been no documents uploaded for this group', 'bp-group-documents') ?></p>
	</div>

	<?php } ?>
	<div class="spacer">&nbsp;</div>

	<?php //-------------------------------------------------------DETAIL VIEW-- ?>
	<?php if( $template->show_detail ){ ?>
	<div id="post-new-topic">
	<h3><?php echo $template->header ?></h3>

	<form method="post" id="bp-group-documents-form" class="standard-form" action="<?php echo $template->action_link; ?>" enctype="multipart/form-data" />
	<input type="hidden" name="bp_group_documents_operation" value="<?php echo $template->operation; ?>" />
	<input type="hidden" name="bp_group_documents_id" value="<?php echo $template->id; ?>" />
			<?php if( $template->operation == 'add' ) { ?>
			<label><?php _e('Choose File:','bp-group-documents'); ?></label>
			<input type="file" name="bp_group_documents_file" id="bp-group-documents-file" />
			<?php } ?>
			<label><?php _e('Display Name:','bp-group-documents'); ?></label>
			<input type="text" name="bp_group_documents_name" id="bp-group-documents-name" value="<?php echo $template->name ?>" />
			<?php if( BP_GROUP_DOCUMENTS_SHOW_DESCRIPTIONS ) { ?>
			<label><?php _e('Description:', 'bp-group-documents'); ?></label>
			<textarea name="bp_group_documents_description" id="bp-group-documents-description"><?php echo $template->description; ?></textarea>
			<?php } ?>
			<label></label>
			<input type="submit" class="button" value="<?php _e('Submit','bp-group-documents'); ?>" />
	</form>
	</div><!--end #post-new-topic-->
	<?php } ?>
		
	</div><!--end #group-documents-->
	</div><!--end .bp-widget-->
<?php }



/**********************************************************************
********************NOTIFICATION SETTINGS******************************
**********************************************************************/

/**
 * bp_group_documents_screen_notification_settings()
 *
 * Adds notification settings for the component, so that a user can turn off email
 * notifications set on specific component actions.
 */
function bp_group_documents_screen_notification_settings() { 
	global $current_user; ?>
	
		<tr>
			<td></td>
			<td><?php _e( 'A member uploads a document to a group you belong to', 'bp-group-documents' ) ?></td>
			<td class="yes"><input type="radio" name="notifications[notification_group_documents_upload_member]" value="yes" <?php if ( !get_usermeta( $current_user->id,'notification_group_documents_upload_member') || 'yes' == get_usermeta( $current_user->id,'notification_group_documents_upload_member') ) { ?>checked="checked" <?php } ?>/></td>
			<td class="no"><input type="radio" name="notifications[notification_group_documents_upload_member]" value="no" <?php if ( get_usermeta( $current_user->id,'notification_group_documents_upload_member') == 'no' ) { ?>checked="checked" <?php } ?>/></td>
		</tr>
		<tr>
			<td></td>
			<td><?php _e( 'A member uploads a document to a group for which you are an moderator/admin', 'bp-group-documents' ) ?></td>
			<td class="yes"><input type="radio" name="notifications[notification_group_documents_upload_mod]" value="yes" <?php if ( !get_usermeta( $current_user->id,'notification_group_documents_upload_mod') || 'yes' == get_usermeta( $current_user->id,'notification_group_documents_upload_mod') ) { ?>checked="checked" <?php } ?>/></td>
			<td class="no"><input type="radio" name="notifications[notification_group_documents_upload_mod]" value="no" <?php if ( 'no' == get_usermeta( $current_user->id,'notification_group_documents_upload_mod') ) { ?>checked="checked" <?php } ?>/></td>
		</tr>
		
		<?php do_action( 'bp_group_documents_notification_settings' ); ?>
<?php	
}
add_action( 'groups_screen_notification_settings', 'bp_group_documents_screen_notification_settings' );


/**********************************************************************
********************EVERYTHING ELSE************************************
**********************************************************************/

function bp_group_documents_delete( $id ) {
	if( !ctype_digit( $id ) ) {
		bp_core_add_message( __('The item to delete could not be found','bp-group-documents'),'error');
		return false;
	}

	//check nonce
	if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'group-documents-delete-link')) {
	  bp_core_add_message( __('There was a security problem', 'bp-group-documents'), 'error' );
	  return false;
	}
	
	$document = new BP_Group_Documents($id);
	if( $document->current_user_can('delete') ){
		if( $document->delete() ){
			do_action('bp_group_documents_delete_success',$document);
			return true;
		}
	}
	return false;
}
/*
 * bp_group_documents_check_ext()
 *
 * checks whether the passed filename ends in an extension
 * that is allowed by the site admin
 */
function bp_group_documents_check_ext( $filename ) {

	if( !$filename ) {
		return false;
	}

	$valid_formats_string = get_option( 'bp_group_documents_valid_file_formats');
	$valid_formats_array = explode( ',', $valid_formats_string );

	$extension = substr($filename,(strpos($filename, ".")+1));
	$extension =  strtolower($extension);

	if(in_array($extension, $valid_formats_array)){
		return true;
	}
	return false;
}

/*
 * is__writable()
 *
 * This function was taken from the PHP manual comments.  It checks whether a directory or
 * file can be written to by php.  Although there is a function is_writable() already,
 * it does not take into account group, or ACL permissions.  This one does it better by 
 * actually trying to write a file.
 *
 * Be sure to use a trailing slash for folders!
 */
function is__writable($path) {

    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}

/*
 * get_file_size()
 *
 * returns a human-readable file-size for the passed file
 * adapted from a function in the php manual comments
 */
function get_file_size( $document, $precision = 1 ) {

    $units = array('b', 'k', 'm', 'g');
  
	$bytes = filesize( BP_GROUP_DOCUMENTS_FILE_PATH . $document->file );
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
  
    $bytes /= pow(1024, $pow);
  
    return round($bytes, $precision) .  $units[$pow];
} 

/**
 * bp_group_documents_remove_data()
 *
 * Cleans out both the files and the database records when a group is deleted
 */
function bp_group_documents_remove_data( $group_id ) {
	
	$results = BP_Group_Documents::get_list_by_group( $group_id );
	if( count( $results ) >= 1 ) {
		foreach($results as $document_params) {
			$document = new BP_Group_Documents( $document_params['id'], $document_params);
			$document->delete();
			do_action('bp_group_documents_delete_with_group',$document);
		}
	}
}
add_action('groups_group_deleted','bp_group_documents_remove_data');

//Whoah, You made it down here. VIM > Emacs
?>
