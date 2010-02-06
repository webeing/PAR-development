jQuery(document).ready( function($) {

	$('form#bp-group-documents-form').submit(function(){
	alert($('input[name=bp_group_documents_operation]').val());
		if( $('input[name=bp_group_documents_operation]').val() == 'add' ) {
			if($('input#bp-group-documents-file').val()) {
				return true;
			}
			alert('You must select a file to upload!');
			return false;
		}
	});	
	$('a#bp-group-documents-delete').click(function(){
		return confirm('Are you sure you wish to permanently delete this document?');
	});

});
