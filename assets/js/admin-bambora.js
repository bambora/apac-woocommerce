jQuery(function ($) {
    'use strict';

    /**
     * Script to control interface functions.
     */
    var bambora_apac_admin = {
        isTestMode: function () {
            return $('#woocommerce_bambora_apac_enabled_sandbox').is(':checked');
        },

        isReadyMode: function () {
            var bval = true;
            if ($('#woocommerce_bambora_apac_bambora_product').val() != "ready") {
                bval = false;
            }
            return bval;
        },

        isAPIMode: function () {
            var bval = true;
            if ($('#woocommerce_bambora_apac_checkout_mode').val() != "api") {
                bval = false;
            }
            return bval;
        },
        /**
         * Initialize.
         */
        init: function () {
            $(document.body).on('change', '#woocommerce_bambora_apac_enabled_sandbox', function () {
                var test_login = $('#woocommerce_bambora_apac_test_api_login').parents('tr').eq(0),
                    test_password = $('#woocommerce_bambora_apac_test_api_password').parents('tr').eq(0),
                    test_account = $('#woocommerce_bambora_apac_test_api_account').parents('tr').eq(0),
                    live_login = $('#woocommerce_bambora_apac_api_login').parents('tr').eq(0),
                    live_password = $('#woocommerce_bambora_apac_api_password').parents('tr').eq(0),
                    live_account = $('#woocommerce_bambora_apac_api_account').parents('tr').eq(0);

                if ($(this).is(':checked')) {
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
            });

            $('#woocommerce_bambora_apac_enabled_sandbox').change();

            var dl = $('#woocommerce_bambora_apac_dl').parents('tr').eq(0);
            var save_card = $('#woocommerce_bambora_apac_save_card_detail').parents('tr').eq(0);
            var card_storage = $('#woocommerce_bambora_apac_save_card_method').parents('tr').eq(0);
            var batch_payment = $('#woocommerce_bambora_apac_batch_payment').parents('tr').eq(0);
            var red_3dsec = $('#woocommerce_bambora_apac_red_3dsec').parents('tr').eq(0);

            if (bambora_apac_admin.isAPIMode()) {
                dl.hide();
                save_card.show();
            } else {
                dl.show();
                save_card.hide();

            }

            if (bambora_apac_admin.isReadyMode()) {
                card_storage.hide();
                save_card.hide();
                red_3dsec.hide();
            } else {
                card_storage.show();
                save_card.show();
                red_3dsec.show();
            }

            if ($('#woocommerce_bambora_apac_save_card_method').val() == "customerregistration") {
                batch_payment.show();
            } else {
                batch_payment.hide();
            }
        }
    };

    bambora_apac_admin.init();

    $('#woocommerce_bambora_apac_checkout_mode').change(function () {

        var dl = $('#woocommerce_bambora_apac_dl').parents('tr').eq(0);
        var save_card = $('#woocommerce_bambora_apac_save_card_detail').parents('tr').eq(0);

        if (bambora_apac_admin.isAPIMode()) {
            dl.hide();
            save_card.show();
        } else {
            dl.show();
            save_card.hide();
        }
    });

    $('#woocommerce_bambora_apac_bambora_product').change(function () {

        var card_storage = $('#woocommerce_bambora_apac_save_card_method').parents('tr').eq(0);
        var save_card = $('#woocommerce_bambora_apac_save_card_detail').parents('tr').eq(0);
        var red_3dsec = $('#woocommerce_bambora_apac_red_3dsec').parents('tr').eq(0);

        if (bambora_apac_admin.isReadyMode()) {
            card_storage.hide();
            save_card.hide();
            red_3dsec.hide();
        } else {
            card_storage.show();
            save_card.show();
            red_3dsec.show();
        }

    });

    $('#woocommerce_bambora_apac_bambora_product').change(function () {

        if (bambora_apac_admin.isReadyMode()) {
            $('#woocommerce_bambora_apac_dl').val('checkout_v1_purchase');

            $('option:selected', 'select[name="woocommerce_bambora_apac_save_card_method"]').removeAttr('selected');
            $('select[name="woocommerce_bambora_apac_save_card_method"]').find('option[value="customerregistration"]').attr("selected", true);

        } else {

            $('#woocommerce_bambora_apac_dl').val('');
        }

    });

    $('#woocommerce_bambora_apac_save_card_method').change(function () {
        var batch_payment = $('#woocommerce_bambora_apac_batch_payment').parents('tr').eq(0);
        if ($('#woocommerce_bambora_apac_save_card_method').val() == "customerregistration") {
            batch_payment.show();
        } else {
            batch_payment.hide();
        }
    });
});


