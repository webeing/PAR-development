<?php

class BP_Group_Documents {
	public $id;
	public $user_id;
	public $group_id;
	public $created_ts;
	public $modified_ts;
	public $file;
	public $name;
	public $description;
	
	/**
	 * __construct()
	 *
	 * The constructor will either create a new empty object if no ID is set, or fill the object
	 * with a row from the table, or the passed parameters, if an ID is provided.
	 */
	public function __construct( $id = null, $params = false ) {
		global $wpdb, $bp;
		
		if ( $id && ctype_digit( $id ) ) {
			$this->id = $id;
			if( $params ) {
				$this->populate_passed($params);
			} else {
				$this->populate( $this->id );
			}
		}
	}
	
	/**
	 * populate()
	 *
	 * This method will populate the object with a row from the database, based on the
	 * ID passed to the constructor.
	 */
	private function populate() {
		global $wpdb, $bp, $creds;
		
		if ( $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$bp->group_documents->table_name} WHERE id = %d", $this->id ) ) ) {
			foreach( $this as $field => $value ) {
				$this->$field = $row->$field;
			}
		}
	}
	
	/**
	 * populate_passed()
	 *
	 * This method will populate the object with the passed parameters, 
	 * saving a call to the database
	 */
	private function populate_passed($params) {
		
		foreach( $this as $key => $value ) {
			if( isset( $params[$key] ) )
				$this->$key = $params[$key];	
		}
	}
	
	/**
	 * save()
	 *
	 * This method will save an object to the database. It will dynamically switch between
	 * INSERT and UPDATE depending on whether or not the object already exists in the database.
	 */
	public function save() {
		global $wpdb, $bp;
		

		//currently doing filtering inline when outputting, will consolitate and organize in the future
		//$this->name = apply_filters( 'bp_group_documents_before_save', $this->name);
		//$this->description = apply_filters( 'bp_group_documents_before_save', $this->description);
		
		do_action( 'bp_group_documents_data_before_save', $this );

		if ( $this->id ) {
			// Update
			$result = $wpdb->query( $wpdb->prepare( 
					"UPDATE {$bp->group_documents->table_name} SET 
						modified_ts = %d,
						name = %s,
						description = %s
					WHERE id = %d",
						time(),
						$this->name,
						$this->description,
						$this->id 
					) );
		} else {
			// Save
			if( $this->UploadFile() ) {
				$result = $wpdb->query( $wpdb->prepare( 
					"INSERT INTO {$bp->group_documents->table_name} ( 
						user_id,
						group_id, 
						created_ts,
						modified_ts,
						file,
						name,
						description
					) VALUES ( 
						%d, %d, %d, %d, %s, %s, %s 
					)", 
						$this->user_id,
						$this->group_id,
						time(),
						time(),
						$this->file,
						$this->name,
						$this->description
					) );
			}

		}
				
		if ( !$result )
			return false;
		
		if ( !$this->id ) {
			$this->id = $wpdb->insert_id;
		}	
		
		do_action( 'bp_group_documents_data_after_save', $this ); 
		
		return $result;
	}

	/**
	 * delete()
	 *
	 * This method will delete the corresponding row for an object from the database.
	 */	
	public function delete() {
		global $wpdb, $bp;
		
		if( $this->current_user_can('delete') ) {
			if( $this->file && file_exists( BP_GROUP_DOCUMENTS_FILE_PATH . $this->file ) )
				unlink( BP_GROUP_DOCUMENTS_FILE_PATH . $this->file );

			return $wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->group_documents->table_name} WHERE id = %d", $this->id ) );
		}
	}


	private function UploadFile() {

		//check that file exists
		if( !$_FILES['bp_group_documents_file']['name'] ) {
			bp_core_add_message( __('Whoops!  There was no file selected for upload.','bp-group-documents'),'error' );
			return false;
		}
		//check that file has an allowed extension
		if( !bp_group_documents_check_ext( $_FILES['bp_group_documents_file']['name'] ) ) {
			bp_core_add_message( __('The type of document submitted is not allowed','bp-group-documents'),'error' );	
			return false;
		}

		//if there was any upload errors, spit them out
		if( $_FILES['bp_group_documents_file']['error'] ) {
			switch( $_FILES['bp_group_documents_file']['error'] ) {
				case UPLOAD_ERR_INI_SIZE:
					bp_core_add_message( __('There was a problem; your file is larger than is allowed by the site administrator.','bp-group-documents'),'error');
				break;
				case UPLOAD_ERR_PARTIAL:
					bp_core_add_message( __('There was a problem; the file was only partially uploaded.','bp-group-documents'), 'error');
				break;
				case UPLOAD_ERR_NO_FILE:
					bp_core_add_message( __('There was a problem; no file was found for the upload.','bp-group-documents'),'error');
				break;
				case UPLOAD_ERR_NO_TMP_DIR:
					bp_core_add_message( __('There was a problem; the temporary folder for the file is missing.','bp-group-documents'),'error');
				break;
				case UPLOAD_ERR_CANT_WRITE:
					bp_core_add_message( __('There was a problem; the file could not be saved.','bp-group-documents'),'error');
				break;
			}
			return false;
		}

		//if the user didn't specify a display name, use the file name (before the timestamp)
		if ( !$this->name )
			if( get_magic_quotes_gpc() ){
				$this->name = stripslashes( basename( $_FILES['bp_group_documents_file']['name'] ) );
			} else {
				$this->name = basename( $_FILES['bp_group_documents_file']['name'] );
			}

		//full path of the upload (prepend file name with timestamp for uniqueness)
		$new_file_name = time() . '-' . basename($_FILES['bp_group_documents_file']['name']);
		$new_file_name = preg_replace('/[^0-9a-zA-Z-_.]+/','',$new_file_name);

		$file_path = BP_GROUP_DOCUMENTS_FILE_PATH . $new_file_name;

		if( move_uploaded_file( $_FILES['bp_group_documents_file']['tmp_name'], $file_path ) ) {
			$this->file = $new_file_name;
			return true;
		} else {
			bp_core_add_message( __('There was a problem saving your file, please try again.','bp-group-documents'),'error');
			return false;
		}
	}

	/*
	 * current_user_can()
	 * 
	 * When passed an action, it returns true if the user has the privilages
	 * to perfrom that action and false if they do not
	 */
	public function current_user_can( $action ) {
		global $bp;

		if( bp_group_is_admin() ) {
			return true;
		}

		$user_is_owner = ($this->user_id == get_current_user_id() );

		switch( $action ) {
			case 'add':
				if( bp_group_is_member($bp->groups->current_group) )
					return true;
			break;
			case 'edit':
				if( bp_group_is_mod($bp->groups->current_group) ||
					(bp_group_is_member($bp->groups->current_group) && $user_is_owner) ) {
					return true;
				}
			break;
			case 'delete':
				if( bp_group_is_mod($bp->groups->current_group) ||
					(bp_group_is_member($bp->groups->current_group) && $user_is_owner) ) {
					return true;
				}
			break;
		}
		return false;
	}


	/* Static Functions */

	public static function get_total( $group_id ) {
		global $wpdb, $bp;

		$result = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$bp->group_documents->table_name} WHERE group_id = %d",$group_id) );
		return $result;
	}

	public static function get_list_by_group( $group_id, $start, $items ){
		global $wpdb, $bp;

		//convert from 1-based paging to 0-based limit
		--$start;	

 		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$bp->group_documents->table_name} WHERE group_id = %d ORDER BY name ASC LIMIT %d, %d", $group_id, $start, $items ), ARRAY_A );

		return $result;
	}

	public static function get_list_for_widget($num) {
		global $wpdb,$bp;

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT d.* FROM {$bp->group_documents->table_name} d INNER JOIN {$bp->groups->table_name} g ON d.group_id = g.id WHERE g.status = 'public' ORDER BY modified_ts DESC LIMIT %d", $num), ARRAY_A );

		return $result;
	}
}

?>
