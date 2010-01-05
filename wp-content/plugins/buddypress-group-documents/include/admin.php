<?php

/**
 * bp_group_documents_admin()
 *
 * Checks for form submission, saves component settings and outputs admin screen HTML.
 */
function bp_group_documents_admin() { 
	global $bp, $bbpress_live;
		
	/* If the form has been submitted and the admin referrer checks out, save the settings */
	if ( isset( $_POST['submit'] ) && check_admin_referer('group-documents-settings') ) {
		//strip whitespace from comma separated list
		$formats = preg_replace('/\s+/','',$_POST['valid_file_formats']);
		//keep everything lowercase for consistancy
		$formats = strtolower( $formats);

		if( isset($_POST['display_file_size']) && $_POST['display_file_size'] ) {
			$size = 1;
		} else {
			$size = 0;
		}
		update_option( 'bp_group_documents_valid_file_formats', $formats );
		update_option( 'bp_group_documents_display_file_size', $size );

		if( $_POST['items_per_page'] && ctype_digit( $_POST['items_per_page'] ) ){
			update_option( 'bp_group_documents_items_per_page', $_POST['items_per_page'] );
		}
		$updated = true;
	}
	
	$valid_file_formats = get_option( 'bp_group_documents_valid_file_formats');
	//add consistant whitepace for readability
	$valid_file_formats = str_replace( ',',', ',$valid_file_formats);
	$display_file_size = get_option( 'bp_group_documents_display_file_size' );
	$items_per_page = get_option( 'bp_group_documents_items_per_page' );
?>
	<div class="wrap">
		<h2><?php _e( 'Group Documents Admin', 'bp-group-documents' ) ?></h2>
		<br />
		
		<?php if ( isset($updated) ) : ?><?php echo "<div id='message' class='updated fade'><p>" . __( 'Settings Updated.', 'bp-group-documents' ) . "</p></div>" ?><?php endif; ?>
			
		<form action="<?php echo site_url() . '/wp-admin/admin.php?page=bp-group-documents-settings' ?>" name="group-documents-settings-form" id="group-documents-settings-form" method="post">				

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="target_uri"><?php _e( 'Valid File Formats', 'bp-group-documents' ) ?></label></th>
					<td>
						<textarea style="width:95%" cols="45" rows="5" name="valid_file_formats" id="valid_file_formats"><?php echo attribute_escape( $valid_file_formats ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th><label><?php _e('Items per Page','bp-group-documents') ?></label></th>
					<td>
						<input type="text" name="items_per_page" id="items_per_page" value="<?php echo $items_per_page; ?>" /></td>
				</tr>
				<tr>
					<th><label><?php _e('Display File Size','bp-group-documents') ?></label></th>
					<td>
						<input type="checkbox" name="display_file_size" id="display_file_size" <?php if( $display_file_size ) echo 'checked="checked"'; ?> value="1" /></td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="submit" value="<?php _e( 'Save Settings', 'bp-group-documents' ) ?>"/>
			</p>
			
			<?php wp_nonce_field( 'group-documents-settings' ); ?>
		</form>
	</div>
<?php
}
?>
