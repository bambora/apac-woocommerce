jQuery( function( $ ) {
	'use strict';
	/**
	 * Script to control interface functions.
	 */

	$('<option>').val('export').text('Export').appendTo("select[name='action']");
	$('<option>').val('apiupload').text('Upload via API').appendTo("select[name='action']");
              
});
