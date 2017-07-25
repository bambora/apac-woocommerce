jQuery( function( $ ) {
	'use strict';
	/**
	 * Script to control interface functions.
	 */
	var bambora_checkout = {
		isBamboraIntegrated: function() {
			return $('input#payment_method_bambora_apac').is(':checked');
		},
		onSubmit: function( e ) {
				if(bambora_checkout.isBamboraIntegrated()){
					e.preventDefault();					
					$('#myModal').modal({
					    backdrop: 'static',
					    keyboard: false
					},'show');
					return false;					
				}else{
					return true;
				}			
		},
		/**
		 * Initialize form.
		 */
		init: function( form ) {
		
			$('form#formme').submit();
			$('#myModal').modal({
					    backdrop: 'static',
					    keyboard: false
					},'show');
		
		}
	};

	bambora_checkout.init($( "form.checkout" ));
	$("body").on("click", "button.close", function(){	  
		$('#myModal').modal('hide');
		window.parent.location = '/checkout/';
	});
});
