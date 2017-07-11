jQuery( function( $ ) {
	'use strict';

	/**
	 * Script to control interface functions.
	 */
	var wc_bambora_admin = {
		isTestMode: function() {
			return $( '#woocommerce_wc_bambora_enabled_sandbox' ).is( ':checked' );
		},

		isReadyMode: function() {
			var bval = true;
			 if( $( '#woocommerce_wc_bambora_bambora_product' ).val()!="ready"){
			 	bval = false;
			 }
			 return bval;
		},

		isAPIMode: function() {
			var bval = true;
			 if( $( '#woocommerce_wc_bambora_checkout_mode' ).val()!="api"){
			 	bval = false;
			 }
			 return bval;
		},
		/**
		 * Initialize.
		 */
		init: function() {
			$( document.body ).on( 'change', '#woocommerce_wc_bambora_enabled_sandbox', function() {
				var test_login = $( '#woocommerce_wc_bambora_test_api_login' ).parents( 'tr' ).eq( 0 ),
					test_password = $( '#woocommerce_wc_bambora_test_api_password' ).parents( 'tr' ).eq( 0 ),
					test_account = $( '#woocommerce_wc_bambora_test_api_account' ).parents( 'tr' ).eq( 0 ),
					live_login = $( '#woocommerce_wc_bambora_api_login' ).parents( 'tr' ).eq( 0 ),
					live_password = $( '#woocommerce_wc_bambora_api_password' ).parents( 'tr' ).eq( 0 ),
					live_account = $( '#woocommerce_wc_bambora_api_account' ).parents( 'tr' ).eq( 0 );					

				if ( $( this ).is( ':checked' ) ) {
					test_login.show();
					test_password.show();
					test_account.show();
					live_login.hide();
					live_password.hide();
					live_account.hide();			

				} else {
					test_login.hide();
					test_password.hide();
					test_account.hide();
					live_login.show();
					live_password.show();
					live_account.show();
				}
			} );
			$( '#woocommerce_wc_bambora_enabled_sandbox' ).change();			
		}
	};

	wc_bambora_admin.init();

	$( '#woocommerce_wc_bambora_checkout_mode').change(function() {

		var dl = $( '#woocommerce_wc_bambora_dl' ).parents( 'tr' ).eq( 0 );	
		var save_card = $( '#woocommerce_wc_bambora_save_card_detail' ).parents( 'tr' ).eq( 0 );	

		if ( wc_bambora_admin.isAPIMode() ) {
			dl.hide();
			save_card.show();
		} else {
			dl.show();
			save_card.hide();			
		}
	} );

	$( '#woocommerce_wc_bambora_bambora_product').change(function() {
								
		if ( wc_bambora_admin.isReadyMode() ) {
			$( '#woocommerce_wc_bambora_dl' ).val( 'checkoutv1_hpp_purchase' );

			$('option:selected', 'select[name="woocommerce_wc_bambora_save_card_method"]').removeAttr('selected');
			$('select[name="woocommerce_wc_bambora_save_card_method"]').find('option[value="customerregistration"]').attr("selected",true);

		} else {
			
			$( '#woocommerce_wc_bambora_dl' ).val( '' );
		}

	} );
});
