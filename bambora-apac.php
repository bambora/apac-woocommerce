<?php
/*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Bambora_Apac extends WC_Payment_Gateway_CC {


    // Setup our Gateway's id, description and other values  - start __construct
    function __construct() {

        // The global ID for this Payment method
        $this->id = "bambora_apac";

        // The Title shown on the top of the Payment Gateways Page next to all the other Payment Gateways
        $this->method_title = __( "Bambora APAC ", 'bambora-apac' );

        // The description for this Payment Gateway, shown on the actual Payment options page on the backend
        $this->method_description = __( "Welcome to the Bambora APAC Plug-in for WooCommerce. Need an Account? Check us out at <a href='https://www.bambora.com'>bambora.com</a>", 'bambora-apac' );

        // The title to be used for the vertical tabs that can be ordered top to bottom
        $this->title = __( "Bambora APAC ", 'bambora-apac' );

        // If you want to show an image next to the gateway's name on the frontend, enter a URL to an image.
        $this->icon = null;

        // Bool. Can be set to true if you want payment fields to show on the checkout
        // if doing a direct integration, which we are doing in this case
        $this->has_fields = true;

        // Supports the default credit card form
        $this->supports = array(
            'subscriptions',
            'products',
            'refunds',
            'pre-orders',
            'tokenization',
            'add_payment_method',
            'subscription_cancellation',
            'subscription_reactivation',
            'subscription_suspension',
            'subscription_amount_changes',
            'subscription_payment_method_change',
            'subscription_payment_method_change_customer',
            'subscription_payment_method_change_admin',
            'subscription_date_changes',
            'multiple_subscriptions',
            'bspayment_postype',

        );

        // This basically defines your settings which are then loaded with init_settings()
        $this->init_form_fields();

        // After init_settings() is called, you can get the settings and load them into variables, e.g:
        $this->init_settings();

        $this->enabled = $this->get_option( 'enabled' );
        $this->bambora_product = $this->get_option( 'bambora_product' );
        $this->title = $this->get_option( 'title' );
        $this->enabled_sandbox = $this->get_option( 'enabled_sandbox' );
        $this->api_login = $this->get_option( 'api_login' );
        $this->api_password = $this->get_option( 'api_password' );
        $this->api_account = $this->get_option( 'api_account' );
        $this->test_api_login = $this->get_option( 'test_api_login' );
        $this->test_api_password = $this->get_option( 'test_api_password' );
        $this->test_api_account = $this->get_option( 'test_api_account' );
        $this->dl = $this->get_option( 'dl' );
        $this->checkout_mode = $this->get_option( 'checkout_mode' );
        $this->save_card_detail = $this->get_option( 'save_card_detail' );
        $this->save_card_method = $this->get_option( 'save_card_method' );
        $this->save_cust_storage_no = $this->get_option( 'save_cust_storage_no' );
        $this->payment_scheduling = $this->get_option( 'payment_scheduling' );
        $this->batch_payment = $this->get_option( 'batch_payment' );
        $this->red_3dsec = $this->get_option( 'red_3dsec' );


        $this->testWSDL = 'https://demo.ippayments.com.au/interface/api/dts.asmx?WSDL';
        $this->liveWSDL = 'https://www.ippayments.com.au/interface/api/dts.asmx?WSDL';
        $this->testTokenWSDL = 'https://demo.ippayments.com.au/interface/api/sipp.asmx?WSDL';
        $this->liveTokenWSDL = 'https://www.ippayments.com.au/interface/api/sipp.asmx?WSDL';
        $this->testBatchWSDL = 'https://demo.ippayments.com.au/interface/api/batch.asmx?WSDL';
        $this->liveBatchWSDL = 'https://www.ippayments.com.au/interface/api/batch.asmx?WSDL';
        $this->testIntUrl = 'https://demo.bambora.co.nz/access/index.aspx';
        $this->liveIntUrl = 'https://www.bambora.co.nz/access/index.aspx';
        $this->testInteParams = '?a=85569861&dl='.$this->dl.'&accountnumber='.$this->test_api_account;
        $this->liveInteParams = '?a=85569861&dl='.$this->dl.'&accountnumber='.$this->api_account;


        $this->testEnvironmentUrl = 'https://demo.ippayments.com.au/interface/api/dts.asmx';
        $this->liveEnvironmentUrl = 'https://www.ippayments.com.au/interface/api/dts.asmx';
        $this->testTokenEnvironmentUrl = 'https://demo.ippayments.com.au/interface/api/sipp.asmx';
        $this->liveTokenEnvironmentUrl = 'https://www.ippayments.com.au/interface/api/sipp.asmx';
        $this->testBatchEnvironmentUrl = 'https://demo.ippayments.com.au/interface/api/batch.asmx';
        $this->liveBatchEnvironmentUrl = 'https://www.ippayments.com.au/interface/api/batch.asmx';

        $this->testActionUrl = 'http://www.ippayments.com.au/interface/api/dts';
        $this->liveActionUrl = 'http://www.ippayments.com.au/interface/api/dts';
        $this->testTokenActionUrl = 'http://www.ippayments.com.au/interface/api/sipp';
        $this->liveTokenActionUrl = 'http://www.ippayments.com.au/interface/api/sipp';
        $this->testBatchActionUrl = 'http://www.ippayments.com.au/interface/api/batch';
        $this->liveBatchActionUrl = 'https://www.ippayments.com.au/interface/api/batch';


        $this->TokeniseAlgorithmID = '2';
        $this->cr_prefix = 'cr card';

        $this->frequency = array(
            "S"=>"Single Payment",
            "W"=>"Weekly",
            "F"=>"Fortnightly",
            "M"=>"Monthly",
            "Q"=>"Quarterly",
            "A"=>"Annually"
        );
        $this->maxbatchrows = 10;

        // Lets check for SSL
        add_action( 'admin_notices', array( $this,	'admin_notices' ) );

        // Save settings
        if ( is_admin() ) {
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        }

        if ( $this->enabled_sandbox =='yes') {
            $this->description .= ' ' . sprintf( __( 'Bambora Sandbox mode has been activated, you will be able to the use the card details mentioned on the following URL for testing.  "<a href="%s" target="_Blank" >Click here</a>".', 'bambora-apac' ), 'https://dev-apac.bambora.com/checkout/guides/get-up-and-running/test-your-setup' );
            $this->description  = trim( $this->description );
        }


        // Customer Registration for admin
        if($this->payment_scheduling=='yes' && $this->save_card_method=='customerregistration'){

            add_action( 'init',  array($this, 'bspayment_postype') );
            add_action( 'add_meta_boxes', array($this, 'bp_postypesmetabox') );
            add_action( 'save_post', array($this, 'bp_save_bspayment') );
        }

        // Tokanisation for admin
        if($this->batch_payment=='yes' && $this->save_card_method=='tokenisation'){

            add_action( 'init',  array($this, 'bbpayment_postype') );
            add_action( 'add_meta_boxes', array($this, 'bb_postypesmetabox') );
            add_action( 'save_post', array($this, 'bb_save_bspayment') );

            add_action('admin_footer-edit.php', array(&$this, 'custom_bulk_admin_footer'));
            add_action('load-edit.php',         array(&$this, 'custom_bulk_action'));
            add_action('admin_notices',         array(&$this, 'custom_bulk_admin_notices'));

        }

        // FE api links - Testing

        $this->wsdl_url = $this->testWSDL;
        $this->environment_url =  $this->testEnvironmentUrl;
        $this->action_url = $this->testActionUrl;
        $this->wsdl_token_url = $this->testTokenWSDL;
        $this->environment_token_url =  $this->testTokenEnvironmentUrl;
        $this->action_token_url = $this->testTokenActionUrl;
        $this->wsdl_batch_url = $this->testBatchWSDL;
        $this->environment_batch_url =  $this->testBatchEnvironmentUrl;
        $this->action_batch_url = $this->testBatchActionUrl;
        $this->UserName = $this->test_api_login;
        $this->Password = $this->test_api_password;
        $this->Account = $this->test_api_account;
        $this->IntegratedUrl = $this->testIntUrl;
        $this->InteParams = $this->testInteParams;

        // Are we testing right now or is it a real transaction
        $environment = ( $this->enabled_sandbox == "yes" ) ? 'TRUE' : 'FALSE';

        // FE api links - Live
        if( "FALSE" == $environment ){
            $this->wsdl_url = $this->liveWSDL;
            $this->environment_url =  $this->liveEnvironmentUrl;
            $this->action_url = $this->liveActionUrl;
            $this->wsdl_token_url = $this->liveTokenWSDL;
            $this->environment_token_url =  $this->liveTokenEnvironmentUrl;
            $this->action_token_url = $this->liveTokenActionUrl;
            $this->wsdl_batch_url = $this->liveBatchWSDL;
            $this->environment_batch_url =  $this->liveBatchEnvironmentUrl;
            $this->action_batch_url = $this->liveBatchActionUrl;
            $this->UserName = $this->api_login;
            $this->Password = $this->api_password;
            $this->Account = $this->api_account;
            $this->IntegratedUrl = $this->liveIntUrl;
            $this->InteParams = $this->liveInteParams;
        }

       // var_dump($this->environment_url,$this->action_url); die;

        add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
        add_action( 'woocommerce_order_actions', array( $this, 'add_order_meta_box_actions' ) );
        add_action( 'woocommerce_order_action_void_transaction', array( $this, 'process_void_transaction' ) );

        //add_action( 'valid_bambora_callback', array( $this, 'successful_request' ) );
        add_action( 'woocommerce_api_' . strtolower( get_class() ), array( $this, 'check_callback' ) );
        add_action( 'woocommerce_api_' . strtolower( get_class() ).'_usr', array( $this, 'check_callback_usr' ) );

        add_action( 'woocommerce_receipt_'.$this->id, array( $this, 'receipt_page' ) );

    }
    // End __construct




    // Load admin scripts. start load_admin_scripts
    public function load_admin_scripts() {

        if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
            return;
        }

        wp_enqueue_script( 'woocommerce_bambora_admin', plugins_url( 'assets/js/admin-bambora.js', BAMBORA_APAC_MAIN_FILE ) );

    }
    // End load_admin_scripts

    // Build the administration fields for this specific Gateway - start init_form_fields
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled' => array(
                'title'		=> __( 'Enable checkout', 'bambora-apac' ),
                'label'		=> __( 'Enable Bambora payment gateway', 'bambora-apac' ),
                'type'		=> 'checkbox',
                'default'	=> 'no',
            ),
            'bambora_product' => array(
                'title'       => __( 'Bambora Product', 'bambora-apac' ),
                'type'        => 'select',
                'desc_tip'    => __( 'Get started as a small business with Bambora Ready or have access to our enterprise solution Bambora Advance', 'bambora-apac' ),
                'options'     => array(
                    'ready' => __( 'Ready', 'bambora-apac' ),
                    'advance' => __( 'Advance', 'bambora-apac' ),
                ),
            ),
            'title' => array(
                'title'		=> __( 'Title', 'bambora-apac' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'Payment title the customer will see during the checkout process', 'bambora-apac' ),
                'default'	=> __( 'Pay with Bambora', 'bambora-apac' ),
            ),
            'enabled_sandbox' => array(
                'title'		=> __( 'Enable sandbox mode', 'bambora-apac' ),
                'label'		=> __( 'Enable sandbox mode to test the payments', 'bambora-apac' ),
                'type'		=> 'checkbox',
                'default'	=> 'no',
            ),
            'api_login' => array(
                'title'		=> __( 'Live API Username', 'bambora-apac' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'The Live API username in your Bambora account', 'bambora-apac' ),
            ),
            'api_password' => array(
                'title'		=> __( 'Live API Password', 'bambora-apac' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'The Live API password in your Bambora account', 'bambora-apac' ),
            ),
            'api_account' => array(
                'title'		=> __( 'Live Account Number', 'bambora-apac' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'The Live API Account Number in your Bambora account', 'bambora-apac' ),
            ),

            'test_api_login' => array(
                'title'		=> __( 'Test API Username', 'bambora-apac' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'The Test API username in your Bambora account', 'bambora-apac' ),
            ),
            'test_api_password' => array(
                'title'		=> __( 'Test API Password', 'bambora-apac' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'The Test API password in your Bambora account', 'bambora-apac' ),
            ),
            'test_api_account' => array(
                'title'		=> __( 'Test Account Number', 'bambora-apac' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'The Test API Account Number in your Bambora account', 'bambora-apac' ),
            ),
            'checkout_mode' => array(
                'title'       => __( 'Checkout Method', 'bambora-apac' ),
                'type'        => 'select',
                'desc_tip'    => __( 'Allow the customer to use the Bambora Integrated Checkout or the Built in payment page.', 'bambora-apac' ),
                'options'     => array(
                    'api' => __( 'API', 'bambora-apac' ),
                    'integrated' => __( 'Integrated', 'bambora-apac' ),
                ),
            ),
            'dl' => array(
                'title'       => __( 'DL', 'bambora-apac' ),
                'type'        => 'text',
                'desc_tip'    => __( 'The styling options of the Bambora integrated checkout page', 'bambora-apac' ),
                'default'	=> 'checkout_v1_purchase',
            ),
            /* 'save_card_detail' => array(
                'title'		=> __( 'Save card at checkout', 'bambora-apac' ),
                'label'		=> __( 'Enable merchant to save card details against account', 'bambora-apac' ),
                'type'		=> 'checkbox',
                'default'	=> 'no',
            ),
            'save_card_method' => array(
                'title'       => __( 'Card Storage Method', 'bambora-apac' ),
                'type'        => 'select',
                'desc_tip'    => __( 'Select Card Storage Method', 'bambora-apac' ),
                'options'     => array(
                    'tokenisation' => __( 'Tokenisation', 'bambora-apac' )
                    'customerregistration' => __( 'Customer Registration', 'bambora-apac' ),
                ),
            ),
           'payment_scheduling' => array(
                'title'		=> __( 'Enable Payment Scheduling', 'bambora-apac' ),
                'label'		=> __( 'Enable payment scheduling on your online store', 'bambora-apac' ),
                'type'		=> 'checkbox',
                'default'	=> 'no',
            ),
            'batch_payment' => array(
                'title'		=> __( 'Enable Batch Payments', 'bambora-apac' ),
                'label'		=> __( 'Enable batch payments on your online store', 'bambora-apac' ),
                'type'		=> 'checkbox',
                'default'	=> 'no',
            ),
            'red_3dsec' => array(
                'title'		=> __( '3D Secure or RED Enabled?', 'bambora-apac' ),
                'label'		=> __( 'Have you enabled 3D Secure or RED Enabled on your Bambora account? ', 'bambora-apac' ),
                'type'		=> 'checkbox',
                'default'	=> 'no',
            )*/
        );
    }
    // End init_form_fields

    // Payment form on checkout page - start payment_fields
    public function payment_fields() {

        $user = wp_get_current_user();
        $total = WC()->cart->total;

        // If paying from order, we need to get total from order not cart.
        if ( isset( $_GET['pay_for_order'] ) && ! empty( $_GET['key'] ) ) {
            $order = wc_get_order( wc_get_order_id_by_order_key( wc_clean(sanitize_key( $_GET['key']) ) ) );
            $total = $order->get_total();
        }

        if ( $user->ID ) {
            $user_email = get_user_meta( $user->ID, 'billing_email', true );
            $user_email = $user_email ? $user_email : $user->user_email;
        } else {
            $user_email = '';
        }

        if ( is_add_payment_method_page() ) {
            $pay_button_text = __( 'Add Card', 'bambora-apac' );
            $total        = '';
        } else {
            $pay_button_text = '';
        }

        echo '<div>';

        if ( $this->description ) {
            echo apply_filters( 'wc_bambora_description', wpautop( wp_kses_post( $this->description ) ) );
        }

        if($this->checkout_mode=='api'){
            if($this->save_card_detail=='yes'){
                $this->tokenization_script();
                if($this->save_card_method!='customerregistration'){
                    $this->saved_payment_methods();
                }

            }
            $this->form();
            if ( $this->save_card_detail =='yes' ) {
              //  $this->save_payment_method_checkbox();
            }

        }
        echo '</div>';
    }
    // End payment_fields

    // Start field_name
    public function field_name( $name ) {
        return ' name="' . esc_attr( $this->id . '-' . $name ) . '" ';
    }
    // End field_name

    // Submit payment and handle response - start process_payment
    public function process_payment( $order_id ) {


        include_once( dirname( __FILE__ ) . '/lib/bambora-apac-api.php' );

        global $woocommerce;

        // Get this Order's information so that we know
        $customer_order = new WC_Order( $order_id );

        if($this->checkout_mode=='api'){

            // Save card enabled?
            $token = '0';
            $cr = '0';
            $token_card = '';

            if($this->save_card_detail=='yes'){

                // If tokanisation is on and is it a saved card?
                if(isset($_POST['wc-bambora_apac-payment-token']) && trim($_POST['wc-bambora_apac-payment-token'])!='new'){
                    $token_id = trim(sanitize_text_field($_POST['wc-bambora_apac-payment-token']));
                    $token = WC_Payment_Tokens::get( $token_id );
                    $token_card = $token->get_token();

                }else{

                    // If its a new card?
                    if(isset($_POST['wc-bambora_apac-new-payment-method']) && $_POST['wc-bambora_apac-new-payment-method']==true){
                        switch ($this->save_card_method) {
                            case 'tokenisation':
                                $token = '1';
                                break;
                            case 'customerregistration':
                                $cr = '1';
                                break;
                        }
                    }
                }


            }

            // Create Params
            $params = array();

            if($token_card!=''){
                // Payment via tokenised card

                if($this->save_card_method =='tokenisation'){

                    $op = 'SubmitSinglePayment';
                    $params['operation'] = 'SubmitSinglePaymentToken';

                    $params['CustRef'] = $customer_order->get_order_number();
                    $params['CardNumber'] = $token_card;
                    $params['Amount'] = $customer_order->order_total*100;
                    $params['TrnType'] = '1';
                    $params['UserName'] = $this->UserName;
                    $params['Password'] = $this->Password;
                    $params['TokeniseAlgorithmID'] = $this->TokeniseAlgorithmID;

                    $params['AccountNumber']=$this->Account;


                }else{
                    // Payment via Customer Register

                    $op = 'SubmitSinglePayment';
                    $params['operation'] = 'SubmitSinglePaymentUsingCustomerRegister';
                    $params['CustRef'] = $customer_order->get_order_number();
                    $params['CustNumber'] = $customer_order->user_id;
                    $params['Amount'] = $customer_order->order_total*100;
                    $params['TrnType'] = '1';
                    $params['UserName'] = $this->UserName;
                    $params['Password'] = $this->Password;
                    $params['AccountNumber']=$this->Account;
                }


            }else{
                // Payment via CC

                $op  = 'SubmitSinglePayment';
                $params['operation'] = 'SubmitSinglePayment';

                $params['CustNumber'] = $customer_order->user_id;
                $params['CustRef'] = $customer_order->get_order_number();
                $params['Amount'] = $customer_order->order_total*100;
                $params['TrnType'] = '1';
                $params['AccountNumber']=$this->Account;
                $params['CardNumber'] = str_replace(' ', '', $_POST['bambora_apac-card-number']);
                $arrExpDate = explode('/', sanitize_text_field($_POST['bambora_apac-card-expiry']));
                $params['ExpM'] = trim($arrExpDate[0]);
                $params['ExpY'] = '20'.trim($arrExpDate[1]);
                $params['CVN'] = sanitize_text_field($_POST['bambora_apac-card-cvc']);
                $params['CardHolderName'] = $customer_order->billing_first_name.' '.$customer_order->billing_last_name;
                $params['Registered'] = "False";
                $params['UserName'] = $this->UserName;
                $params['Password'] = $this->Password;

                // Is tokenisation available?

                if($token=='1'){
                    $params['TokeniseAlgorithmID'] = $this->TokeniseAlgorithmID;
                }

                // Is CR available?

                if($cr=='1'){
                    $params['operation'] = 'SubmitSinglePaymentCustomerRegister';
                    $params['CustNumber'] = $customer_order->user_id;
                    $params['FirstName'] = $customer_order->billing_first_name;
                    $params['LastName'] = $customer_order->billing_last_name;
                }

            }

            // API Requests

            $APIRequest = new Bambora_Apac_Api($this->wsdl_url, array("trace" => 1));

            $xmlRequest = $APIRequest->soapHeader($this->action_url);
            $xmlRequest .= $APIRequest->createTransactionBody($op, $params);
            $xmlRequest .= $APIRequest->soapFooter();

            // Transaction

            $transactionRequest = $APIRequest->__APIRequest($xmlRequest,$op,$this->environment_url,$this->action_url);
            $transactionResponse = $APIRequest->__APIResposeHandler($transactionRequest);

            // Error handling and response

            if ( is_wp_error( $transactionResponse ) )
                throw new Exception( __( 'We are currently experiencing problems trying to connect to this payment gateway. Sorry for the inconvenience.', 'wc_bambora' ) );

            $arrTransactionResponse = (array)$transactionResponse;
            if (empty($arrTransactionResponse)) {
                throw new Exception( __( 'Bambora Response was empty.', 'wc_bambora' ) );
            }

            // Test the code to know if the transaction went through or not.
            // 0 means the transaction was a success
            if (  $transactionResponse->ResponseCode == '0'  ) {
                // Payment has been successful

                $customer_order->add_order_note( sprintf( __( 'Payment Successfully completed. #Receipt: %s', 'wc_bambora' ), (string)$transactionResponse->Receipt, 'wc_bambora' ) );
                // Store other data such as fees
                update_post_meta( $order_id, 'Bambora Payment Receipt', (string)$transactionResponse->Receipt );

                if($token_card==''){
                    // Add token to WooCommerce

                    if ( ($token==true) && get_current_user_id() && class_exists( 'WC_Payment_Token_CC' ) ) {

                        $token = new WC_Payment_Token_CC();
                        $token->set_token( (string)$transactionResponse->CreditCardToken );
                       // $token->set_token( '9160688351712442' );
                        $token->set_gateway_id( 'bambora_apac' );
                        $token->set_card_type( strtolower( (string)$transactionResponse->CardType ) );
                        $token->set_last4( substr( (string)$transactionResponse->TruncatedCard,-4) );
                        $token->set_expiry_month( (string)$transactionResponse->ExpM );
                        $token->set_expiry_year( (string)$transactionResponse->ExpY );
                        $token->set_user_id( get_current_user_id() );

                        $token->save();
                    }

                    // Create a fake token for CR

                    if ( ($cr==true) && get_current_user_id() && class_exists( 'WC_Payment_Token_CC' ) ) {

                        $token = new WC_Payment_Token_CC();
                        $token->set_token( '9160688351712442' );
                        $token->set_gateway_id( 'bambora_apac' );
                        //	$token->set_card_type( strtolower( $this->cr_prefix ).':'.$customer_order->get_order_number() );
                        $token->set_card_type( 'Credit card ' );
                        $token->set_last4( substr( $params['CardNumber'],-4) );
                        $token->set_expiry_month( $params['ExpM'] );
                        $token->set_expiry_year( $params['ExpY'] );
                        $token->set_user_id( get_current_user_id() );

                        $token->save();
                    }
                }

                // Mark order as Paid
                $customer_order->payment_complete();

                // Empty the cart (Very important step)
                $woocommerce->cart->empty_cart();

                // Redirect to thank you page
                return array(
                    'result'   => 'success',
                    'redirect' => $this->get_return_url( $customer_order ),
                );
            } else {

                wc_add_notice( (string)$transactionResponse->DeclinedMessage, 'error' );
                // Add note to the order for your reference
                $customer_order->add_order_note( 'Error: '. (string)$transactionResponse->DeclinedMessage );
            }
        }else{

            // Integrated checkout - Still being developed

            $order = wc_get_order( $order_id );

            return array(
                'result'     => 'success',
                'redirect'    => $order->get_checkout_payment_url( true ),
            );
        }

    }
    // End process_payment

    // Refund a payment - start process_refund
    public function process_refund( $order_id, $amount = null, $reason = '' ) {

        include_once( dirname( __FILE__ ) . '/lib/bambora-apac-api.php' );

        $order = wc_get_order( $order_id );

        // Create Params
        $params = array();

        $op = $params['operation'] = 'SubmitSingleRefund';
        $arrReceipt = get_post_meta($order_id,'Bambora Payment Receipt');
        $params['Receipt'] = $arrReceipt[0];
        $params['Amount'] = $amount*100;
        $params['UserName'] = $this->UserName;
        $params['Password'] = $this->Password;

        // Logging refund
        $this->log( "Info: Starting Bambora refund for order $order_id for the amount of {$amount}" );

        // API handling
        $APIRequest = new Bambora_Apac_Api($this->wsdl_url, array("trace" => 1));

        $xmlRequest = $APIRequest->soapHeader($this->action_url);
        $xmlRequest .= $APIRequest->createTransactionBody($op, $params);
        $xmlRequest .= $APIRequest->soapFooter();

        // Transaction, Error and response handling

        $transactionRequest = $APIRequest->__APIRequest($xmlRequest,$op,$this->environment_url,$this->action_url);
        $transactionResponse = $APIRequest->__APIResposeHandler($transactionRequest);
        if ( is_wp_error( $transactionResponse ) )
            throw new Exception( __( 'We are currently experiencing problems trying to connect to this payment gateway. Sorry for the inconvenience.', 'wc_bambora' ) );

        $arrTransactionResponse = (array)$transactionResponse;
        if (empty($arrTransactionResponse)) {
            throw new Exception( __( 'Bambora Response was empty.', 'wc_bambora' ) );
        }

        // 0 means the transaction was a success
        if (  $transactionResponse->ResponseCode == '0'  ) {

            // Refund has been successful
            $refund_message = sprintf( __( 'Bambora Refund Completed : Amount Refunded %1$s - Refund Receipt ID: %2$s - Reason: %3$s', 'wc_bambora' ), $amount, (string)$transactionResponse->Receipt, $reason );

            $order->add_order_note( $refund_message );
            $this->log( 'Refund Success: ' . html_entity_decode( strip_tags( $refund_message ) ) );
            return true;

        } else {
            // Refund was not succesful
            $this->log( 'Error: ' . (string)$transactionResponse->DeclinedMessage );
            return new WP_Error( 'wc_bambora', (string)$transactionResponse->DeclinedMessage );
        }

    }
    // End process_refund

    // Start process_void_transaction
    function process_void_transaction( $order ) {

        include_once( dirname( __FILE__ ) . '/lib/bambora-apac-api.php' );
        $order_id = $order->ID;

        // Create params
        $params = array();

        $op = $params['operation'] = 'SubmitSingleVoid';
        $arrReceipt = get_post_meta($order_id,'Bambora Payment Receipt');
        $params['Receipt'] = $arrReceipt[0];
        $arrTotal = get_post_meta($order_id,'_order_total');
        $params['Amount'] = $arrTotal[0]*100;
        $params['UserName'] = $this->UserName;
        $params['Password'] = $this->Password;

        // logging transaction
        $this->log( "Info: Starting Bambora void for order $order_id for the amount of {$amount}" );

        // API request handling
        $APIRequest = new Bambora_Apac_Api($this->wsdl_url, array("trace" => 1));

        $xmlRequest = $APIRequest->soapHeader($this->action_url);
        $xmlRequest .= $APIRequest->createTransactionBody($op, $params);
        $xmlRequest .= $APIRequest->soapFooter();

        // response, error and transaction handling
        $transactionRequest = $APIRequest->__APIRequest($xmlRequest,$op,$this->environment_url,$this->action_url);
        $transactionResponse = $APIRequest->__APIResposeHandler($transactionRequest);

        if ( is_wp_error( $transactionResponse ) )
            throw new Exception( __( 'We are currently experiencing problems trying to connect to this payment gateway. Sorry for the inconvenience.', 'wc_bambora' ) );

        $arrTransactionResponse = (array)$transactionResponse;
        if (empty($arrTransactionResponse)) {
            throw new Exception( __( 'Bambora Response was empty.', 'wc_bambora' ) );
        }

        // 0 means the transaction was a success
        if (  $transactionResponse->ResponseCode == '0'  ) {

            // Refund has been successful
            $refund_message = sprintf( __( 'Bambora Void Transaction Completed : Amount Void %1$s - Void Date: %2$s ', 'wc_bambora' ), $amount, (string)$transactionResponse->SettlementDate );
            $order->add_order_note( $refund_message );
            $this->log( 'Void Transaction Success: ' . html_entity_decode( strip_tags( $refund_message ) ) );
            return true;

        } else {
            // Refund was not succesful
            $this->log( 'Error: ' . (string)$transactionResponse->DeclinedMessage );
            $refund_message = sprintf( __( 'Bambora Void Transaction Failed :  %1$s', 'wc_bambora' ), (string)$transactionResponse->DeclinedMessage );
            $order->add_order_note( $refund_message );
            return new WP_Error( 'wc_bambora', (string)$transactionResponse->DeclinedMessage );
        }
    }
    // End process_void_transaction

    // Start check_callback
    public function check_callback() {

        global $woocommerce;

        $params = stripslashes_deep( $_REQUEST );

        $error = '';

        switch( $params['Result'] ){
            case '1':
                $error = '';
                break;
            case '0':
                $error = $params['DeclinedMessage'];
                break;
            case '2':
                $error = 'Transaction in progress';
                break;
            case '3':
                $error = 'Session Expired';
                break;
        }

        $arr_wc = explode('_', $params['SessionKey']);
        $order_id = '';

        if(count($arr_wc)>1){
            $order_id = (int)$arr_wc[1];
        }

        if($order_id!=''){
            $customer_order = new WC_Order( $order_id );
            if($error!=''){
                $customer_order->add_order_note( 'Error: '. (string)$error );
            }else{
                $customer_order->add_order_note( sprintf( __( 'Payment Successfully completed. #Receipt: %s', 'wc_bambora' ), (string)$params['Receipt'], 'wc_bambora' ) );
                update_post_meta( $order_id, 'Bambora Payment Receipt', (string)$params['Receipt'] );
                $customer_order->payment_complete();
                $woocommerce->cart->empty_cart();
            }
        }

    }
    // End check_callback

    // Start check_callback_usr
    public function check_callback_usr() {

        $params = stripslashes_deep( $_REQUEST );
        $arr_wc = explode('_', $params['SessionId']);
        $order_id = '';
        if(count($arr_wc)>1){
            $order_id = (int)$arr_wc[1];
        }

        $customer_order = new WC_Order( $order_id );
        if($customer_order->status == 'processing'){

            $order_key = get_post_meta( $order_id, '_order_key');

            echo "<script type='text/javascript'>

                setTimeout(function() {
                  window.parent.location = '/checkout/order-received/".$order_id."/?key=".$order_key[0]."';
                }, 3000);                
                    </script>";
            echo  '<link rel="stylesheet" type="text/css" href="'.get_site_url() . '/wp-content/plugins/bambora-apac-online/assets/css/ui.bambora.1.2.0.css">';
            echo  '
	       <div  style="margin:auto;width: 50px;">
	       		<div class="spinner">
				    <div class="spinner-left"></div>
				    <div class="spinner-right"></div>
				</div>
			</div>
			';
            die();

        }


    }
    // End check_callback_usr

    // Start receipt_page
    public function receipt_page( $order ) {

        include_once( dirname( __FILE__ ) . '/lib/bambora-apac-integrate.php' );
        global $woocommerce;

        $order = wc_get_order( $order );
        $Int_Request = new Bambora_Apac_Integrate();
        $serverURL = base64_encode(get_site_url().'/wc-api/'.strtolower( get_class() ).'/');
        $userURL = base64_encode(get_site_url().'/wc-api/'.strtolower( get_class() ).'_usr/');
        $UserName = $this->UserName;
        $Password = $this->Password;
        $DL = $this->dl;
        $amount = $order->total*100;
        $sessionid = rand(100000, 2500000).'_'.$order->id;
        $sessionkey = rand(100000, 2500000).'_'.$order->id;

        $url = "UserName=".$UserName."&Password=".$Password."&DL=".$DL."&SessionID=".$sessionid ."&SessionKey=".$sessionkey."&ServerURL=".$serverURL."&UserURL=".$userURL."&Amount=".$amount.'&CustRef='.$order->id.'&CustNumber='.$CustNumber.'&AccountNumber='.$this->Account;
        $return_arr = $Int_Request->getSST($this->IntegratedUrl,$url);

        if(get_current_user_id()){
            $CustNumber = get_current_user_id();
        }else{
            $CustNumber = 'N'.rand(100000, 2500000);
        }


        if($return_arr['sst']==""){
            global $error;
            echo $return_arr['error_msg'];
        }else{
            wp_enqueue_script( 'bambora_bootstrap', plugins_url( 'assets/js/bootstrap.min.js', BAMBORA_APAC_MAIN_FILE ) );
            wp_enqueue_script( 'bambora_checkout', plugins_url( 'assets/js/checkout-bambora.js', BAMBORA_APAC_MAIN_FILE ) );
            wp_enqueue_style( 'bambora_css_bootstrap', plugins_url( 'assets/css/bootstrap.css', BAMBORA_APAC_MAIN_FILE ) );
            wp_enqueue_style( 'bambora_css__model', plugins_url( 'assets/css/modal.css', BAMBORA_APAC_MAIN_FILE ) );
            echo '<form action="'.$this->IntegratedUrl.'" target="payment-iframe" method="post" id="formme" >            
                    <input type="hidden" name="SessionId" value="'.$sessionid.'">
                    <input type="hidden" id="SST" name="SST" value="'.$return_arr['sst'].'">
                    <input type="submit" id="but01" value="post" style="display:none;">                    
                 </form>';

            echo '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#FFF;">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="'.WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/assets/images/bambora-logo.png'.'" />
                                        </div>
                                        <div class="modal-body">
                                        <iframe style="border:none;width:100%;height:415px;"  name="payment-iframe"></iframe>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>';
        }

    }
    // End receipt_page

    // Start validate_fields
    public function validate_fields() {
        return true;
    }
    // End validate_fields

    // Display any notices we've collected thus far (e.g. for connection, disconnection) - start admin_notices
    public function admin_notices() {

        if ( 'no' === $this->enabled ) {
            return;
        }

        if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
            return;
        }

        // Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected.
        if ( ( function_exists( 'wc_site_is_https' ) && ! wc_site_is_https() ) && ( 'no' === get_option( 'woocommerce_force_ssl_checkout' ) && ! class_exists( 'WordPressHTTPS' ) ) ) {
            echo '<div class="error bambora-ssl-message"><p>' . sprintf( __( 'Hey we are on test mode! We noticed that your checkout is not secured as force SSL option is not enabled. 
				<a href="%s">Click here</a>', 'bambora-apac' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . '</p></div>';
        }
    }
    // End admin_notices

    // Start add_payment_method
    public function add_payment_method(){

        include_once( dirname( __FILE__ ) . '/lib/bambora-apac-api.php' );

        // is API enabled?

        if($this->checkout_mode=='api'){

            // CC saving enabled?
            $token = false;
            $cr = flase;
            $dts = '0';

            switch ($this->save_card_method) {
                case 'tokenisation':
                    $token = true;
                    break;

                case 'tokenisation':
                    $cr = true;
                    break;
            }

            // Create Params
            $params = array();
            $op = $params['operation'] = 'TokeniseCreditCard';
            $params['CardNumber'] = str_replace(' ', '', sanitize_text_field($_POST['bambora_apac-card-number']));
            $arrExpDate = explode('/', $_POST['bambora_apac-card-expiry']);
            $params['ExpM'] = trim($arrExpDate[0]);
            $params['ExpY'] = '20'.trim($arrExpDate[1]);
            $params['CVN'] = sanitize_text_field($_POST['bambora_apac-card-cvc']);
            $params['Registered'] = "False";
            $params['UserName'] = $this->UserName;
            $params['Password'] = $this->Password;

            if($token){
                $params['TokeniseAlgorithmID'] = $this->TokeniseAlgorithmID;
            }

            // API request handling
            $APIRequest = new Bambora_Apac_Api($this->wsdl_url, array("trace" => 1));

            $xmlRequest = $APIRequest->soapHeader($this->action_token_url, $dts);
            $xmlRequest .= $APIRequest->createTransactionBody($op, $params);
            $xmlRequest .= $APIRequest->soapFooter();

            // response, error and transaction handling
            $transactionRequest = $APIRequest->__APIRequest($xmlRequest,$op,$this->environment_token_url,$this->action_token_url);
            $transactionResponse = $APIRequest->__APIResposeHandler($transactionRequest);

            if ( is_wp_error( $transactionResponse ) )
                throw new Exception( __( 'We are currently experiencing problems trying to connect to this payment gateway. Sorry for the inconvenience.', 'wc_bambora' ) );

            $arrTransactionResponse = (array)$transactionResponse;
            if (empty($arrTransactionResponse)) {
                throw new Exception( __( 'Bambora Response was empty.', 'wc_bambora' ) );
            }


            // Test the code to know if the transaction went through or not.
            // 0 means the transaction was a success
            if (  $transactionResponse->ReturnValue == '0'  ) {

                // Add token to WooCommerce
                if ( $token && get_current_user_id() && class_exists( 'WC_Payment_Token_CC' ) ) {

                    $token = new WC_Payment_Token_CC();
                    $token->set_token( (string)$transactionResponse->Token );
                    $token->set_card_type( strtolower( 'Card' ) );
                    $token->set_gateway_id( 'bambora_apac' );
                    $token->set_last4( substr( (string)$params['CardNumber'],-4) );
                    $token->set_expiry_month( (string)$params['ExpM'] );
                    $token->set_expiry_year( (string)$params['ExpY'] );
                    $token->set_user_id( get_current_user_id() );

                    $token->save();
                }

                wc_add_notice( 'Payment method successfully added.', 'success' );

            } else {
                // Transaction was not succesful
                // Add notice to the cart
                wc_add_notice( 'There was an error adding card payment method. Please try again or contact us for assistance', 'error' );

            }
        }
    }
    // End add_payment_method

    // Start getCRUsers
    function getCRUsers(){

        $cr_usrs = array();
        if (  class_exists( 'WC_Payment_Token_CC' ) ) {

            $usrs = get_users();

            foreach ($usrs as $usrs_key => $usrs_value) {
                $tokens = WC_Payment_Tokens::get_customer_tokens(  $usrs_value->data->ID );
                if(is_array($tokens)){

                    foreach ($tokens as $tokens_key => $tokens_value) {
                        $ct_arr = explode(':', $tokens_value->get_card_type());
                        if(count($ct_arr)>1){
                            if(trim($ct_arr[0])==strtolower($this->cr_prefix)){
                                $cr_usrs[$usrs_value->data->ID] = $usrs_value->data->display_name;
                            }
                        }

                    }

                }
            }
            return $cr_usrs;

        }
    }
    // End getCRUsers

    // Start getTokenUsers
    function getTokenUsers(){

        $token_usrs = array();
        if (  class_exists( 'WC_Payment_Token_CC' ) ) {

            $usrs = get_users();
            foreach ($usrs as $usrs_key => $usrs_value) {
                $tokens = WC_Payment_Tokens::get_customer_tokens(  $usrs_value->data->ID );
                if(is_array($tokens)){
                    foreach ($tokens as $tokens_key => $tokens_value) {
                        if( $tokens_value->get_gateway_id() =="bambora_apac" ){

                            $ct_arr = explode(':', $tokens_value->get_card_type());
                            if(count($ct_arr)>0){
                                if(trim($ct_arr[0])!=strtolower($this->cr_prefix)){
                                    $token_usrs[$tokens_value->get_token().':'.$usrs_value->data->ID] = $usrs_value->data->display_name;
                                }
                            }

                        }

                    }

                }
            }

            return $token_usrs;

        }
    }
    // End getTokenUsers

    // Activating payment sheduling - start bspayment_postype
    function bspayment_postype() {

        $labels = array(
            'name'               => 'Schedule Payments',
            'singular_name'      => 'Schedule Payment',
            'menu_name'          => 'Schedule Payments',
            'name_admin_bar'     => 'Schedule Payments',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Schedule Payment',
            'new_item'           => 'New Schedule Payment',
            'edit_item'          => 'Edit Schedule Payment',
            'view_item'          => 'View Schedule Payment',
            'all_items'          => 'All Schedule Payments',
            'search_items'       => 'Search Schedule Payments',
            'parent_item_colon'  => 'Parent Schedule Payments:',
            'not_found'          => 'No Schedule Payments found.',
            'not_found_in_trash' => 'No Schedule Payments found in Trash.'
        );

        $args = array(
            'labels'		=> $labels,
            'public'		=> true,
            'rewrite'		=> array( 'slug' => 'bspayment' ),
            'has_archive'   => true,
            'menu_position' => 20,
            'menu_icon'     => 'dashicons-shield',
            'supports'      => array(  'author', 'vsel-event-metabox')
        );

        register_post_type( 'bspayment_postype', $args );
    }
    // End bspayment_postype

    // Start bp_postypesmetabox
    function bp_postypesmetabox() {
        add_meta_box(
            'bp-event-metabox',
            __( 'Schedule Information', 'bambora-apac' ),
            array($this, 'bp_metabox_callback'),
            'bspayment_postype',
            'side',
            'default'
        );
    }
    // End bp_postypesmetabox

    // Start bp_metabox_callback
    function bp_metabox_callback( $post ) {
        // generate a nonce field
        wp_nonce_field( 'bp_postypesmetabox', 'bp_nonce' );

        // get previously saved meta values (if any)
        $CustNumber = get_post_meta( $post->ID, 'CustNumber', true );
        $Amount = get_post_meta( $post->ID, 'Amount', true );
        $Frequency = get_post_meta( $post->ID, 'Frequency', true );
        $StartDate = get_post_meta( $post->ID, 'StartDate', true );
        $enableEndDate = get_post_meta( $post->ID, 'enableEndDate', true );
        $EndPaymentDate = get_post_meta( $post->ID, 'EndPaymentDate', true );
        $EndPaymentCount = get_post_meta( $post->ID, 'EndPaymentCount', true );
        $EndPaymentCount = get_post_meta( $post->ID, 'EndPaymentCount', true );
        $ScheduleID = get_post_meta( $post->ID, 'ScheduleID', true );


        // get date if saved else set it to current date
        $StartDate = !empty( $StartDate ) ? $StartDate : time();
        $EndPaymentDate = !empty( $EndPaymentDate ) ? $EndPaymentDate : time();

        // set dateformat to match datepicker
        $dateformat = get_option( 'date_format' );
        if ($dateformat == 'j F Y' || $dateformat == 'd/m/Y' || $dateformat == 'd-m-Y') {
            $dateformat = 'd-m-Y';
        } else {
            $dateformat = 'Y-m-d';
        }

        $use_list = $this->getCRUsers();

        // metabox fields
        ?>
        <p>
            <select class="widefat" id="CustNumber" name="CustNumber" maxlength="150" required placeholder="<?php _e( 'User ID', 'bambora-apac' ); ?>" >
                <option value="0">Select User</option>
                <?php
                if(count($use_list)>0){

                    foreach ($use_list as $use_list_key => $use_list_value) {
                        $sel = "";
                        if($use_list_key == $CustNumber){
                            $sel = "selected";
                        }
                        echo "<option value='".$use_list_key."' ".$sel.">".esc_html($use_list_value)."</option>";
                    }
                }
                ?>
            </select>

        </p>

        <p>
            <label for="Amount"><?php _e( 'Amount', 'bambora-apac' ); ?></label>
            <input class="widefat" id="Amount" type="text" name="Amount" maxlength="150" required placeholder="<?php _e( 'Amount', 'bambora-apac' ); ?>" value="<?php echo  esc_html($Amount) ; ?>" />
        </p>

        <p>
            <label for="StartDate"><?php _e( 'Start Date', 'bambora-apac' ); ?></label>
            <input class="widefat" id="StartDate" type="text" name="StartDate" required maxlength="10" placeholder="<?php _e( 'Use datepicker', 'bambora-apac' ); ?>" value="<?php echo date_i18n( $dateformat, esc_attr( $StartDate ) ); ?>" />
        </p>

        <p>
            <label for="Frequency"><?php _e( ' Frequency', 'bambora-apac' ); ?></label>
            <select class="widefat" id="Frequency" name="Frequency" maxlength="150" required placeholder="<?php _e( 'Frequency', 'bambora-apac' ); ?>" >
                <option value="0">Select Frequency</option>
                <?php

                foreach ($this->frequency as $Frequency_list_key => $Frequency_list_value) {
                    $sel = "";
                    if($Frequency_list_key == $Frequency){
                        $sel = "selected";
                    }
                    echo "<option value='".$Frequency_list_key."' ".$sel.">".esc_html($Frequency_list_value)."</option>";
                }

                ?>
            </select>

        </p>

        <p>
            <input class="checkbox" id="Enable End Date" type="checkbox" name="enableEndDate" value="yes" <?php checked( $enableEndDate, 'yes' ); ?> />
            <label for="enableEndDate"><?php _e('Enable End Date for Schedule', 'bambora-apac'); ?></label>
        </p>

        <p>
            <label for="EndPaymentDate"><?php _e( 'End Payment Date', 'bambora-apac' ); ?></label>
            <input class="widefat" id="EndPaymentDate" type="text" name="EndPaymentDate"  maxlength="10" placeholder="<?php _e( 'Use datepicker', 'bambora-apac' ); ?>" value="<?php echo date_i18n( $dateformat, esc_attr( $EndPaymentDate ) ); ?>" />
        </p>

        <p>
            <input class="checkbox" id="enableEndPaymentCount" type="checkbox" name="enableEndPaymentCount" value="yes" <?php checked( $EndPaymentCount, 'yes' ); ?> />
            <label for="enableEndPaymentCount"><?php _e('Enable Payment Count for Schedule', 'bambora-apac'); ?></label>
        </p>

        <p>
            <label for="EndPaymentCount"><?php _e( 'End Payment Count', 'bambora-apac' ); ?></label>
            <input class="widefat" id="EndPaymentCount" type="text" name="EndPaymentCount" maxlength="10" placeholder="<?php _e( 'End Payment Count', 'bambora-apac' ); ?>" value="<?php echo  esc_html($EndPaymentCount) ; ?>" />
        </p>

        <p>
            <label for="ScheduleID"><?php _e( 'Schedule ID', 'bambora-apac' ); ?></label>
            <input class="widefat" id="ScheduleID" type="text" name="ScheduleID" maxlength="15" placeholder="<?php _e( 'Schedule ID', 'bambora-apac' ); ?>" value="<?php echo  esc_html($ScheduleID) ; ?>" />
        </p>


        <?php
    }

    // End bp_metabox_callback

    // Start bp_save_bspayment
    function bp_save_bspayment( $post_id ) {
        // check if nonce is set
        global $wpdb;

        include_once( dirname( __FILE__ ) . '/lib/bambora-apac-api.php' );

        $error = '0';

        // Create Params
        $params = array();

        $op = $params['operation'] = 'SubmitPaymentSchedule';
        $params['TrnType'] = '1';
        $params['UserName'] = $this->UserName;
        $params['Password'] = $this->Password;

        if ( ! isset( $_POST['bp_nonce'] ) ) {
            return;
        }
        // verify that nonce is valid
        if ( ! wp_verify_nonce( $_POST['bp_nonce'], 'bp_postypesmetabox' ) ) {
            return;
        }
        // if this is an autosave, our form has not been submitted, so do nothing
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // check user permission
        if ( ( get_post_type() != 'bspayment_postype' ) || ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        // checking values and save fields

        if ( isset( $_POST['CustNumber'] ) ) {
            update_post_meta( $post_id, 'CustNumber', sanitize_text_field( $_POST['CustNumber'] ) );
            $params['CustNumber'] = sanitize_text_field($_POST['CustNumber']);
        }else{
            $error = '1';
        }

        if ( isset( $_POST['Amount'] ) ) {
            update_post_meta( $post_id, 'Amount', sanitize_text_field( $_POST['Amount']  ) );
            $params['Amount'] = sanitize_text_field($_POST['Amount']);
        }else{
            $error = '1';
        }

        if ( isset( $_POST['Frequency'] ) ) {
            update_post_meta( $post_id, 'Frequency', sanitize_text_field( $_POST['Frequency']  ) );
            $params['Frequency'] = sanitize_text_field($_POST['Frequency']);
        }else{
            $error = '1';
        }

        if ( isset( $_POST['StartDate'] ) ) {
            update_post_meta( $post_id, 'StartDate', sanitize_text_field(strtotime( $_POST['StartDate'] ) ) );
            $params['StartDate'] = sanitize_text_field($_POST['StartDate']);
        }else{
            $error = '1';
        }

        if ( isset( $_POST['enableEndDate'] ) ) {
            update_post_meta( $post_id, 'enableEndDate', sanitize_text_field( $_POST['enableEndDate']  ) );
        }

        if ( isset( $_POST['EndPaymentDate'] ) ) {
            update_post_meta( $post_id, 'EndPaymentDate', sanitize_text_field(strtotime( $_POST['EndPaymentDate'] ) ) );
            if ( isset( $_POST['enableEndDate'] ) ) {
                $params['EndPaymentDate'] = sanitize_text_field($_POST['EndPaymentDate']);
            }
        }

        if ( isset( $_POST['enableEndPaymentCount'] ) ) {
            update_post_meta( $post_id, 'enableEndPaymentCount', sanitize_text_field( $_POST['enableEndPaymentCount']  ) );

        }


        if ( isset( $_POST['EndPaymentCount'] ) ) {
            update_post_meta( $post_id, 'EndPaymentCount', sanitize_text_field( $_POST['EndPaymentCount']  ) );
            if ( isset( $_POST['enableEndPaymentCount'] ) ) {
                $params['EndPaymentCount'] = sanitize_text_field($_POST['EndPaymentCount']);
            }
        }

        if ( isset( $_POST['ScheduleID'] ) ) {
            update_post_meta( $post_id, 'ScheduleID', sanitize_text_field( $_POST['ScheduleID']  ) );
            $params['ScheduleID'] = sanitize_text_field($_POST['ScheduleID']);
        }

        if($error == '1'){
            return new WP_Error( 'wc_bambora', 'Manditory fields missing' );

        }

        // log transaction
        $this->log( "Info: Starting Bambora payment scheduing for Customer ID {$_POST['CustNumber']}" );

        // Handle API request
        $APIRequest = new Bambora_Apac_Api($this->wsdl_url, array("trace" => 1));

        $xmlRequest = $APIRequest->soapHeader($this->action_url);
        $xmlRequest .= $APIRequest->createTransactionBody($op, $params);
        $xmlRequest .= $APIRequest->soapFooter();

        // response, error and transaction handling

        $transactionRequest = $APIRequest->__APIRequest($xmlRequest,$op,$this->environment_url,$this->action_url);
        $transactionResponse = $APIRequest->__APIResposeHandler($transactionRequest);
        if ( is_wp_error( $transactionResponse ) )
            throw new Exception( __( 'We are currently experiencing problems trying to connect to this payment gateway. Sorry for the inconvenience.', 'wc_bambora' ) );

        $arrTransactionResponse = (array)$transactionResponse;
        if (empty($arrTransactionResponse)) {
            throw new Exception( __( 'Bambora Response was empty.', 'wc_bambora' ) );
        }

        // 0 means the transaction was a success
        if (  $transactionResponse->ResponseCode == '0'  ) {


            update_post_meta( $post_id, 'ScheduleID', sanitize_text_field( $transactionResponse->ScheduleIdentifier  ) );

            // Get user
            $usr = get_user_by('ID',sanitize_text_field($_POST['CustNumber']));

            // Update the post into the database
            $wpdb->update(
                'wp_posts',
                array(
                    'post_title'   => 'Payment Schedule for '.$usr->data->display_name.' on '.date('Y-m-d')
                ),
                array( 'ID' => $post_id )

            );

            $this->log( 'Scheulding Success: ' . html_entity_decode( strip_tags( $transactionResponse->ScheduleIdentifier ) ) );
            return true;

        } else {
            // Payment Schedule was not succesful
            update_post_meta( $post_id, 'ScheduleID', '' );
            // Get user
            $usr = get_user_by('ID',sanitize_text_field($_POST['CustNumber']));
            // Update the post into the database
            $wpdb->update(
                'wp_posts',
                array(
                    'post_title'   => 'Payment Schedule Failed for '.$usr->data->display_name.' :Error '.(string)$transactionResponse->DeclinedMessage
                ),
                array( 'ID' => $post_id )
            );
            $this->log( 'Error: ' . (string)$transactionResponse->DeclinedMessage );
            return new WP_Error( 'wc_bambora', (string)$transactionResponse->DeclinedMessage );
        }
    }
    // End bp_save_bspayment
    // Activating payment sheduling - End

    // Activating Batch Payment - start bbpayment_postype
    function bbpayment_postype() {
        $labels = array(
            'name'               => 'Batch Payments',
            'singular_name'      => 'Batch Payment',
            'menu_name'          => 'Batch Payments',
            'name_admin_bar'     => 'Batch Payments',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Batch Payment',
            'new_item'           => 'New Batch Payment',
            'edit_item'          => 'Edit Batch Payment',
            'view_item'          => 'View Batch Payment',
            'all_items'          => 'All Batch Payments',
            'search_items'       => 'Search Batch Payments',
            'parent_item_colon'  => 'Parent Batch Payments:',
            'not_found'          => 'No Batch Payments found.',
            'not_found_in_trash' => 'No Batch Payments found in Trash.'
        );

        $args = array(
            'labels'		=> $labels,
            'public'		=> true,
            'rewrite'		=> array( 'slug' => 'bbpayment' ),
            'has_archive'   => true,
            'menu_position' => 20,
            'menu_icon'     => 'dashicons-shield',
            //'taxonomies'		=> array( 'post_tag', 'category' ),
            'supports'      => array( 'author', )
        );
        register_post_type( 'bbpayment_postype', $args );
    }
    // End bbpayment_postype

    // Start bb_postypesmetabox
    function bb_postypesmetabox() {
        add_meta_box(
            'bb-event-metabox',
            __( 'Batch Payment Information', 'bambora-apac' ),
            array($this, 'bb_metabox_callback'),
            'bbpayment_postype',
            'side',
            'default'
        );
    }
    // End bb_postypesmetabox

    // Start bb_metabox_callback
    function bb_metabox_callback( $post ) {
        // generate a nonce field
        wp_nonce_field( 'bb_postypesmetabox', 'bb_nonce' );

        // get previously saved meta values (if any)
        $BatchID = get_post_meta( $post->ID, 'BatchID', true );

        $Totaltransactions = get_post_meta( $post->ID, 'Totaltransactions', true );
        $Totalamount = get_post_meta( $post->ID, 'Totalamount', true );

        // get date if saved else set it to current date
        $StartDate = !empty( $StartDate ) ? $StartDate : time();
        $EndPaymentDate = !empty( $EndPaymentDate ) ? $EndPaymentDate : time();

        // set dateformat to match datepicker
        $dateformat = get_option( 'date_format' );
        if ($dateformat == 'j F Y' || $dateformat == 'd/m/Y' || $dateformat == 'd-m-Y') {
            $dateformat = 'd-m-Y';
        } else {
            $dateformat = 'Y-m-d';
        }

        $use_list = $this->getTokenUsers();

        // metabox fields
        ?>

        <p>
            <label for="BatchID"><?php _e( 'Batch ID', 'bambora-apac' ); ?></label>
            <input class="widefat" id="BatchID" type="text" name="BatchID" maxlength="150" required placeholder="<?php _e( 'Batch ID', 'bambora-apac' ); ?>" value="<?php echo  esc_html($BatchID) ; ?>" />
        </p>

        <table style="width:50%;">
            <tr>
                <th>Cardnumber/Token</th>
                <th>Amount</th>
            </tr>
            <?php


            for ($i=0; $i < $this->maxbatchrows; $i++) {
                ?>

                <tr>

                    <td>
                        <select class="widefat" id="CustNumber" name="CustNumber_<?php echo $i; ?>" maxlength="150"  placeholder="<?php _e( 'User ID', 'bambora-apac' ); ?>" >
                            <option value="0">Select User</option>
                            <?php
                            if(count($use_list)>0){

                                foreach ($use_list as $use_list_key => $use_list_value) {
                                    $sel = "";
                                    if($use_list_key == get_post_meta( $post->ID, 'CustNumber_'.$i, true )){
                                        $sel = "selected";
                                    }
                                    echo "<option value='".$use_list_key."' ".$sel.">".$use_list_value."</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td><input class="widefat" id="Amount" type="text" name="Amount_<?php echo $i; ?>" maxlength="150"  placeholder="<?php _e( 'Amount', 'bambora-apac' ); ?>" value="<?php echo  get_post_meta( $post->ID, 'Amount_'.$i, true ) ; ?>" /></td>
                </tr>

                <?php
            }

            ?>
        </table>
        <p>


        <p>
            <label for="Totaltransactions"><?php _e( 'Total Transactions', 'bambora-apac' ); ?></label>
            <input class="widefat" id="Totaltransactions" type="text" name="Totaltransactions" maxlength="10" placeholder="<?php _e( 'Total Transactions', 'bambora-apac' ); ?>" value="<?php echo  esc_html($Totaltransactions) ; ?>" />
        </p>

        <p>
            <label for="Totalamount"><?php _e( 'Total Amount', 'bambora-apac' ); ?></label>
            <input class="widefat" id="Totalamount" type="text" name="Totalamount" maxlength="10" placeholder="<?php _e( 'Total Amount', 'bambora-apac' ); ?>" value="<?php echo  esc_html($Totalamount) ; ?>" />
        </p>


        <?php
    }
    // End bb_metabox_callback

    // Start bb_save_bspayment
    function bb_save_bspayment( $post_id ) {
        // check if nonce is set
        global $wpdb;

        include_once( dirname( __FILE__ ) . '/lib/bambora-apac-api.php' );


        $error = '0';
        // Create Params
        $params = array();

        $op = $params['operation'] = 'SubmitPaymentSchedule';
        $params['TrnType'] = '1';
        $params['UserName'] = $this->UserName;
        $params['Password'] = $this->Password;

        if ( ! isset( $_POST['bb_nonce'] ) ) {
            return;
        }
        // verify that nonce is valid
        if ( ! wp_verify_nonce( $_POST['bb_nonce'], 'bb_postypesmetabox' ) ) {
            return;
        }
        // if this is an autosave, our form has not been submitted, so do nothing
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // check user permission
        if ( ( get_post_type() != 'bbpayment_postype' ) || ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        // checking values and save fields

        // header and footer - start
        if ( isset( $_POST['BatchID'] ) ) {
            update_post_meta( $post_id, 'BatchID', sanitize_text_field( $_POST['BatchID'] ) );
        }
        if ( isset( $_POST['Totaltransactions'] ) ) {
            update_post_meta( $post_id, 'Totaltransactions', sanitize_text_field( $_POST['Totaltransactions'] ) );
        }
        if ( isset( $_POST['Totalamount'] ) ) {
            update_post_meta( $post_id, 'Totalamount', sanitize_text_field( $_POST['Totalamount'] ) );
        }
        // header and footer - end

        for ($i=0; $i < $this->maxbatchrows; $i++) {

            if ( isset( $_POST['CustNumber_'.$i] ) ) {

                if ( isset( $_POST['Amount_'.$i] ) ) {
                    if(trim($_POST['Amount_'.$i]!='')){
                        update_post_meta( $post_id, 'CustNumber_'.$i, sanitize_text_field( $_POST['CustNumber_'.$i] ) );
                        update_post_meta( $post_id, 'Amount_'.$i, sanitize_text_field( $_POST['Amount_'.$i] ) );
                    }
                }

            }


        }

        $wpdb->update(
            'wp_posts',
            array(
                'post_title'   => sanitize_text_field($_POST['BatchID']).'-'.date('Y').date('m').date('d').date('h').date('i').date('s')
            ),
            array( 'ID' => $post_id )

        );
    }
    // End bb_save_bspayment

    // Start add_order_meta_box_actions
    function add_order_meta_box_actions( $actions ) {
        $actions['void_transaction'] = __( 'Void Tranaction', $this->text_domain );
        return $actions;
    }
    // End add_order_meta_box_actions

    // Start custom_bulk_admin_footer
    function custom_bulk_admin_footer() {
        global $post_type;

        if($post_type == 'bbpayment_postype') {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('<option>').val('export').text('<?php _e('Export')?>').appendTo("select[name='action']");
                    jQuery('<option>').val('apiupload').text('<?php _e('Upload via API')?>').appendTo("select[name='action']");
                });
            </script>
            <?php
        }
    }
    // End custom_bulk_admin_footer

    // Start custom_bulk_action
    function custom_bulk_action() {
        global $typenow;
        $post_type = $typenow;
        if($post_type == 'bbpayment_postype') {

            // get the action
            $wp_list_table = _get_list_table('WP_Posts_List_Table');
            $action = $wp_list_table->current_action();

            $allowed_actions = array("export","apiupload");
            if(!in_array($action, $allowed_actions)) return;

            // security check
            check_admin_referer('bulk-posts');

            // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
            if(isset($_REQUEST['post'])) {
                $post_ids = array_map('intval', $_REQUEST['post']);
            }

            if(empty($post_ids)) return;

            // this is based on wp-admin/edit.php
            $sendback = remove_query_arg( array('exported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
            if ( ! $sendback )
                $sendback = admin_url( "edit.php?post_type=$post_type" );

            $pagenum = $wp_list_table->get_pagenum();
            $sendback = add_query_arg( 'paged', $pagenum, $sendback );

            switch($action) {
                case 'export':
                    $exported = 0;
                    foreach( $post_ids as $post_id ) {

                        if ( !$this->perform_export($post_id) )
                            wp_die( __('Error exporting.') );

                        $exported++;
                    }

                    $sendback = add_query_arg( array('exported' => $exported, 'ids' => join(',', $post_ids) ), $sendback );
                    break;

                case 'apiupload':

                    $exported = 0;
                    foreach( $post_ids as $post_id ) {

                        if ( !$this->perform_api_upload($post_id) )
                            wp_die( __('Error uploading to API.') );

                        $exported++;
                    }

                    $sendback = add_query_arg( array('exported' => $exported, 'ids' => join(',', $post_ids) ), $sendback );

                    break;

                default: return;
            }

            $sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'bbpayment_postype', 'bulk_edit', 'post_view'), $sendback );

            wp_redirect($sendback);
            exit();
        }
    }
    // End custom_bulk_action

    // Start custom_bulk_admin_notices
    function custom_bulk_admin_notices() {
        global $post_type, $pagenow;

        if($pagenow == 'edit.php' && $post_type == 'bbpayment_postype' && isset($_REQUEST['exported']) && (int) $_REQUEST['exported']) {
            $message = sprintf( _n( 'Post exported.', '%s posts exported.', $_REQUEST['exported'] ), number_format_i18n( $_REQUEST['exported'] ) );
            echo "<div class=\"updated\"><p>{$message}</p></div>";
        }
    }
    // End custom_bulk_action

    // Start perform_export
    function perform_export($post_id) {

        $post_detail = get_post( $post_id );
        $post_meta = get_post_meta( $post_id );

        echo $post_detail->post_title.",".$post_detail->post_title."-Upload"."\n\n";

        for ($i=0; $i < $this->maxbatchrows; $i++) {
            if(isset($post_meta['CustNumber_'.$i][0]) && isset($post_meta['Amount_'.$i][0]) ){
                $cust = explode(':', $post_meta['CustNumber_'.$i][0]);
                $amount = $post_meta['Amount_'.$i][0]*100;
                echo $this->Account.",1,".$cust[0].",,".$cust[1].",".$post_id.",,".$amount.",,,,,,\n\n";
            }
        }

        echo $post_meta["Totaltransactions"][0].",".$post_meta["Totalamount"][0]*100;
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename='.$post_detail->post_title.'.csv');
        header('Pragma: no-cache');
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Transfer-Encoding: binary");
        echo $csv;
        exit;
        return true;
    }
    // End perform_export

    // Start perform_api_upload
    function perform_api_upload($post_id) {

        include_once( dirname( __FILE__ ) . '/lib/bambora-apac-api.php' );

        $post_detail = get_post( $post_id );
        $post_meta = get_post_meta( $post_id );

        $var =  $post_detail->post_title.",".$post_detail->post_title."-Upload"."\n\n";

        for ($i=0; $i < $this->maxbatchrows; $i++) {
            if(isset($post_meta['CustNumber_'.$i][0]) && isset($post_meta['Amount_'.$i][0]) ){
                $cust = explode(':', $post_meta['CustNumber_'.$i][0]);
                $amount = $post_meta['Amount_'.$i][0]*100;
                $var.= $this->Account.",1,".$cust[0].",,".$cust[1].",".$post_id.",,".$amount.",,,,,,\n\n";
            }
        }

        $var .= $post_meta["Totaltransactions"][0].",".$post_meta["Totalamount"][0]*100;
        $var_base64 = base64_encode($var);

        $file_size = $this->getFileSizeInKb($var);

        // Create Params
        $params = array();

        $op = $params['operation'] = 'SubmitBatchTrnFile';
        $dts = '2';
        $params['userName'] = $this->UserName;
        $params['password'] = $this->Password;
        $params['description'] = $post_detail->post_title."-Upload";
        $params['batchNumber'] = $post_detail->post_title;
        $params['fileLength'] = $file_size;
        $params['b64TransactionFileData'] = $var_base64;

        $this->log( "Info: Starting Bambora Batch upload" );

        $APIRequest = new Bambora_Apac_Api($this->wsdl_batch_url, array("trace" => 1));

        $xmlRequest = $APIRequest->createSubmitBatchTrnFileBody($params, $this->action_batch_url);


        $transactionRequest = $APIRequest->__APIRequest($xmlRequest,$op,$this->environment_batch_url,$this->action_batch_url);
        $transactionResponse = $APIRequest->__APIBatchResposeHandler($transactionRequest,'4');

        // 0 means the transaction was a success
        if (  $transactionResponse['value'] == '0'  ) {
            // Batch has been successful
            $refund_message = sprintf( __( 'Bambora Batch Transaction Completed', 'wc_bambora' ) );
            update_post_meta( $post_id, 'Bambora Unique Batch Identifier', (string)$transactionResponse['uniqueBatchIdentifier'] );

            // Create Params
            $params = array();

            $op = $params['operation'] = 'AuthoriseBatchTrnFile';
            $params['userName'] = $this->UserName;
            $params['password'] = $this->Password;
            $params['uniqueBatchIdentifier'] = (string)$transactionResponse['uniqueBatchIdentifier'];
            $xmlRequest = $APIRequest->createAuthoriseBatchTrnFileBody($params, $this->action_batch_url);

            $transactionRequest = $APIRequest->__APIRequest($xmlRequest,$op,$this->environment_batch_url,$this->action_batch_url);
            $transactionResponse = $APIRequest->__APIBatchResposeHandler($transactionRequest,'3');

            if (  $transactionResponse['value'] == '0'  ) {
                $this->log( 'Bambora Batch Transaction Completed ') ;
                return true;
            }else{
                $this->log( 'Error in validating ' );
                return false;
            }

        } else {
            // Batch was not succesful
            $this->log( 'Error: ' . (string)$transactionResponse['value'] );
            return false;
        }

    }
    // End perform_api_upload

    // Start getFileSizeInKb
    function getFileSizeInKb($base64string){
        $file = dirname( __FILE__ ) . '\newfile.csv';
        $myfile = fopen($file, "w") or die("Unable to open file!");
        fwrite($myfile, $base64string);
        fclose($myfile);

        return filesize($file);
    }
    // End getFileSizeInKb
    // Activating Batch Payment  - End

    // Start log
    function log($message){
        $logger = new WC_Logger();
        $logger->add( 'wc_bambora', $message);
    }
    // End log
}
