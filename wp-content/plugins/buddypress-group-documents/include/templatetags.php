<?php

class BP_Group_Documents_Template {

	//Paging
	private $total_records;
	private $total_pages;
	private $page = 1;
	private $start_record = 1;
	private $end_record;
	private $items_per_page;

	//Misc
	public $action_link;

	//Top display - "list view"
	public $document_list;

	//bottom display - "detail view"
	public $show_detail = 0;
	public $name = '';
	public $description = '';
	public $operation = 'add';
	public $id = '';
	public $header;

	public function __construct() {
		global $bp;

		$this->do_post_logic();

		$this->do_url_logic();

		$this->do_paging_logic();
		
		$this->document_list = BP_Group_Documents::get_list_by_group( $bp->groups->current_group->id, $this->start_record, $this->items_per_page );
	}
	
	private function do_post_logic() {
		global $bp;

		do_action('bp_group_documents_template_do_post_action');

		//if user just submitted a form - Processing logic
		if( isset( $_POST['bp_group_documents_operation'] ) ) {
			if ( get_magic_quotes_gpc() ) {
				$_POST = array_map( 'stripslashes_deep', $_POST );
			}

			switch( $_POST['bp_group_documents_operation'] ) {
				case 'add':
					$document = new BP_Group_Documents();
					$document->user_id = get_current_user_id();
					$document->group_id = $bp->groups->current_group->id;
					$document->name = $_POST['bp_group_documents_name'];
					$document->description = $_POST['bp_group_documents_description'];
					if( $document->save() ) {
						do_action('bp_group_documents_add_success',$document);
						bp_core_add_message( __('Document successfully uploaded','bp-group-documents') );
					}
				break;
				case 'edit':
					$document = new BP_Group_Documents($_POST['bp_group_documents_id']);
					$document->name = $_POST['bp_group_documents_name'];
					$document->description = $_POST['bp_group_documents_description'];
					if( $document->save() ) {
						do_action('bp_group_documents_edit_success',$document);
						bp_core_add_message( __('Document successfully edited', 'bp-group-documents') );
					}
				break;
			} //end switch
		} //end if operation
	}

	private function do_url_logic() {
		global $bp;

		do_action('bp_group_documents_template_do_url_logic');

		//figure out what to display in the bottom "detail" area based on url
		//assume we are adding a new document
		$document = new BP_Group_Documents();
		if( $document->current_user_can('add') ) {
			$this->header =  __( 'Upload a New Document', 'bp-group-documents' );
			$this->show_detail = 1;
		}
		//if we're editing, grab existing data
		if( ($bp->current_action == $bp->group_documents->slug ) && ($bp->action_variables[0] == 'edit') ) {
			if( ctype_digit( $bp->action_variables[1] ) ){
				$document = new BP_Group_Documents( $bp->action_variables[1] );
				$this->name = htmlspecialchars($document->name);
				$this->description = htmlspecialchars($document->description);
				$this->operation = 'edit';
				$this->id = $bp->action_variables[1];
				$this->header =  __( 'Edit Document', 'bp-group-documents' );
			}
		//otherwise, we might be deleting
		} else if ( $bp->current_action == $bp->group_documents->slug && $bp->action_variables[0] == 'delete' ) {
			if( bp_group_documents_delete( $bp->action_variables[1] ) ){
				bp_core_add_message( __('Document successfully deleted','bp-group-documents') );
			}
		}
	}

	private function do_paging_logic(){
		global $bp;

		do_action('bp_group_documents_template_do_paging_logic');

		$this->items_per_page = get_option('bp_group_documents_items_per_page');

		$this->total_records = BP_Group_Documents::get_total( $bp->groups->current_group->id );
		$this->total_pages = ceil( $this->total_records / $this->items_per_page );

		if( isset($_GET['page']) && ctype_digit($_GET['page'])){
			$this->page = $_GET['page'];
			$this->start_record = (($this->page-1) * $this->items_per_page) +1;
		}
		$last_possible = $this->items_per_page * $this->page;
		$this->end_record = ($this->total_records < $last_possible)?$this->total_records:$last_possible;

		$this->action_link = get_bloginfo('url') . '/' . $bp->current_component . '/' . $bp->current_item . '/' . $bp->current_action;

	}

	public function pagination_count(){

		printf( __('Viewing item %s to %s (of %s items)','bp-group-documents'), $this->start_record, $this->end_record, $this->total_records );

	}

	public function pagination_links() {

		if( $this->page != 1 ) {
			echo "<a class='page-numbers prev' href='{$this->action_link}?page=" . ($this->page - 1) . "'>&laquo;</a>";
		}
		for( $i=1; $i<= $this->total_pages; $i++ ) {
			if( $i == $this->page ) {
				echo "<span class='page-numbers current'>$i</span>";
			}else {
				echo "<a class='page-numbers' href='{$this->action_link}?page=$i'>$i</a>";	
			}
		}
		if( $this->page != $this->total_pages ) {
			echo "<a class='page-numbers next' href='{$this->action_link}?page=" . ($this->page + 1) . "'>&raquo;</a>";
		}
	}

	public function show_pagination() {

		return ($this->total_pages > 1);
	}


}
