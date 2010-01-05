<?php

function bp_group_documents_register_widgets() {
	add_action('widgets_init', create_function('', 'return register_widget("BP_Group_Documents_Widget");') );
}
add_action( 'plugins_loaded', 'bp_group_documents_register_widgets' );

class BP_Group_Documents_Widget extends WP_Widget {

	function bp_group_documents_widget() {
		parent::WP_Widget( false, $name = __( 'Recent Documents', 'bp-group-documents' ) );
	}

	function widget( $args, $instance ) {
		global $bp;

		extract( $args );

		echo $before_widget;
		echo $before_title .
			 $widget_name .
		     $after_title; ?>

	<?php

	/***
	 * Main HTML Display
	 */

	$document_list = BP_Group_Documents::get_list_for_widget( $instance['num_items'] ); 

	if( $document_list && count($document_list) >=1 ) {
		echo '<ul class="group-documents-recent">';
		foreach( $document_list as $item ) {
			$document = new BP_Group_Documents( $item['id'] );
			$group = new BP_Groups_Group( $document->group_id );
			echo '<li>';
			echo sprintf( __('%s posted in %s','bp-group-documents'),'<a href="' . BP_GROUP_DOCUMENTS_FILE_URL . $document->file . '">' . attribute_escape( $document->name ) . '</a>','<a href="' . bp_get_group_permalink( $group ) . '">' . attribute_escape( $group->name ) . '</a>');
			echo '</li>';
		}
		echo '</ul>';
	} else {
		echo '<div class="widget-error">' . __('There are no documents to display.', 'bp-group-documents') .'</div></p>';	
	}

	?>

	<?php echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['num_items'] = strip_tags( $new_instance['num_items'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'num_items' => 5 ) );
		$num_items = strip_tags( $instance['num_items'] );
		?>

		<p><label for="bp-example-widget-num"><?php _e( 'Number of items to show:', 'bp-group-documents' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'num_items' ); ?>" name="<?php echo $this->get_field_name( 'num_items' ); ?>" type="text" value="<?php echo attribute_escape( $num_items ); ?>" style="width: 30%" /></label></p>
	<?php
	}
}

?>
