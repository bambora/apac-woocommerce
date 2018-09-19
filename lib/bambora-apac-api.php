<?php
/**
 * Plugin Name: Bambora APAC Online Plug-in for WooCommerce.
 * Plugin URI: https://dev-apac.bambora.com/
 * Description:  Welcome to the Bambora APAC Plug-in for WooCommerce. Need an Account? Check us out at https://www.bambora.com
 * Version: 1.1.2.1
 * Author: Bambora APAC
 * Author URI: http://www.bambora.com/
 * Developer: Bambora APAC
 * Developer URI: http://www.bambora.com/
 * Text Domain: bambora-apac
 *
 * Copyright: © 2017 Bambora APAC.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Bambora_Apac_Api extends SoapClient {


    // Start __construct
    function __construct($wsdl, $options) {
        parent::__construct($wsdl, $options);
        $this->server = new SoapServer($wsdl, $options);
    }
    // End __construct

    // Start __doRequest
    public function __doRequest($request, $location, $action, $version) {
        $result = parent::__doRequest($request, $location, $action, $version);
        return $result;
    }
    // End __doRequest

    // Start __APIRequest
    function __APIRequest($array,$op,$location,$action_url) {
        $request = $array;
        $location = $location;
        $action = $action_url.'/'.$op;
        $version = '1';
        $result =$this->__doRequest($request, $location, $action, $version,$one_way = NULL);

        return $result;
    }
    // End __APIRequest

    // Start soapHeader
    function soapHeader($action_url,$bdts='1'){
        $dts = 'dts';
        if($bdts!='1'){
            $dts = 'sipp';
        }
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:'.$dts.'="'.$action_url.'">
   				<soapenv:Header/>
   				<soapenv:Body>';
    }
    // End soapHeader

    // Start soapFooter
    function soapFooter(){
        return '</soapenv:Body>
				</soapenv:Envelope>';
    }
    // End soapFooter

    // Start createTransactionBody
    function createTransactionBody($op, $params){

        switch($params['operation']){
            case 'SubmitSinglePayment':
                return $this->createSubmitSinglePaymentBody($params);
                break;

            case 'SubmitSingleRefund':
                return $this->createSubmitSingleRefundBody($params);
                break;

            case 'SubmitSinglePaymentToken':
                return $this->SubmitSinglePaymentToken($params);
                break;

            case 'TokeniseCreditCard':
                return $this->createTokeniseCreditCardBody($params);
                break;

            case 'SubmitSinglePaymentCustomerRegister':
                return $this->createSubmitSinglePaymentCustomerRegisterBody($params);
                break;

            case 'SubmitSinglePaymentUsingCustomerRegister':
                return $this->SubmitSinglePaymentUsingCustomerRegisterBody($params);
                break;

            case 'SubmitPaymentSchedule':
                return $this->SubmitPaymentScheduleBody($params);
                break;

            case 'SubmitSingleVoid':
                return $this->createSubmitSingleVoidBody($params);
                break;

        }
    }
    // End createTransactionBody

    // Start createSubmitSinglePaymentBody
    function createSubmitSinglePaymentBody($params){

        $return = '<dts:SubmitSinglePayment>
		         <!--Optional:-->
		         <dts:trnXML><![CDATA[';

        $return .= 	'<Transaction>';
         if(isset($params['CustomerStorgaeNumber'])){
             $return .= 	'<CustomerStorgaeNumber>'.$params['CustomerStorgaeNumber'].'</CustomerStorgaeNumber>';
         }
        $return .= 	'<CustNumber>'.$params['CustNumber'].'</CustNumber>';
        $return .= 	'<CustRef>'.$params['CustRef'].'</CustRef>';
        $return .= 	'<Amount>'.$params['Amount'].'</Amount>';
        $return .= 	'<TrnType>'.$params['TrnType'].'</TrnType>';
        $return .= 	'<AccountNumber>'.$params['AccountNumber'].'</AccountNumber>';

        $return .= 	'<CreditCard Registered="'.$params['Registered'].'">';
        $return .= 	'<CardNumber>'.$params['CardNumber'].'</CardNumber>';
        $return .= 	'<ExpM>'.$params['ExpM'].'</ExpM>';
        $return .= 	'<ExpY>'.$params['ExpY'].'</ExpY>';
        $return .= 	'<CVN>'.$params['CVN'].'</CVN>';
        $return .= 	'<CardHolderName>'.$params['CardHolderName'].'</CardHolderName>';

        if(isset($params['TokeniseAlgorithmID'])){
            $return .= 	'<TokeniseAlgorithmID>'.$params['TokeniseAlgorithmID'].'</TokeniseAlgorithmID>';
        }

        $return .= 	'</CreditCard>';

        $return .= 	'<Security>';
        $return .= 	'<UserName>'.$params['UserName'].'</UserName>';
        $return .= 	'<Password>'.$params['Password'].'</Password>';
        $return .= 	'</Security>';
        $return .= 	'</Transaction>
					]]> </dts:trnXML>
		      	</dts:SubmitSinglePayment>';

        return 	$return;
    }
    // End createSubmitSinglePaymentBody

    // Start createSubmitSingleRefundBody
    function createSubmitSingleRefundBody($params){

        $return = '<dts:SubmitSingleRefund>
		         <!--Optional:-->
		         <dts:trnXML><![CDATA[';

        $return .= 	'<Refund>';
        $return .= 	'<Receipt>'.$params['Receipt'].'</Receipt>';
        $return .= 	'<Amount>'.$params['Amount'].'</Amount>';
        $return .= 	'<Security>';
        $return .= 	'<UserName>'.$params['UserName'].'</UserName>';
        $return .= 	'<Password>'.$params['Password'].'</Password>';
        $return .= 	'</Security>';
        $return .= 	'</Refund>
					]]> </dts:trnXML>
		      	</dts:SubmitSingleRefund>';

        return 	$return;
    }
    // End createSubmitSingleRefundBody

    // Start createSubmitSingleVoidBody
    function createSubmitSingleVoidBody($params){

        $return = '<dts:SubmitSingleVoid>
		         <!--Optional:-->
		         <dts:trnXML><![CDATA[';

        $return .= 	'<Void>';
        $return .= 	'<Receipt>'.$params['Receipt'].'</Receipt>';
        $return .= 	'<Amount>'.$params['Amount'].'</Amount>';
        $return .= 	'<Security>';
        $return .= 	'<UserName>'.$params['UserName'].'</UserName>';
        $return .= 	'<Password>'.$params['Password'].'</Password>';
        $return .= 	'</Security>';
        $return .= 	'</Void>
					]]> </dts:trnXML>
		      	</dts:SubmitSingleVoid>';

        return 	$return;

    }
    // End createSubmitSingleVoidBody

    // Start createTokeniseCreditCardBody
    function createTokeniseCreditCardBody($params){

        $return = '<sipp:TokeniseCreditCard>
		         <!--Optional:-->
		         <sipp:tokeniseCreditCardXML><![CDATA[';

        $return .= 	'<TokeniseCreditCard>';
        $return .= 	'<UserName>'.$params['UserName'].'</UserName>';
        $return .= 	'<Password>'.$params['Password'].'</Password>';
        $return .= 	'<CardNumber>'.$params['CardNumber'].'</CardNumber>';
        $return .= 	'<ExpM>'.$params['ExpM'].'</ExpM>';
        $return .= 	'<ExpY>'.$params['ExpY'].'</ExpY>';
        $return .= 	'<TokeniseAlgorithmID>'.$params['TokeniseAlgorithmID'].'</TokeniseAlgorithmID>';

        $return .= 	'</TokeniseCreditCard>
					]]> </sipp:tokeniseCreditCardXML>
		      	</sipp:TokeniseCreditCard>';

        return 	$return;
    }
    // End createTokeniseCreditCardBody

    // Start SubmitSinglePaymentToken
    function SubmitSinglePaymentToken($params){

        $return = '<dts:SubmitSinglePayment>
		         <!--Optional:-->
		         <dts:trnXML><![CDATA[';

        $return .= 	'<Transaction>';
        $return .= 	'<CustomerStorgaeNumber>'.$params['CustomerStorgaeNumber'].'</CustomerStorgaeNumber>';
        $return .= 	'<CustRef>'.$params['CustRef'].'</CustRef>';
        $return .= 	'<Amount>'.$params['Amount'].'</Amount>';
        $return .= 	'<TrnType>'.$params['TrnType'].'</TrnType>';
        $return .= 	'<AccountNumber>'.$params['AccountNumber'].'</AccountNumber>';

        $return .= 	'<CreditCard>';
        $return .= 	'<CardNumber>'.$params['CardNumber'].'</CardNumber>';
        $return .= 	'<TokeniseAlgorithmID>'.$params['TokeniseAlgorithmID'].'</TokeniseAlgorithmID>';
        $return .= 	'</CreditCard>';

        $return .= 	'<Security>';
        $return .= 	'<UserName>'.$params['UserName'].'</UserName>';
        $return .= 	'<Password>'.$params['Password'].'</Password>';
        $return .= 	'</Security>';
        $return .= 	'</Transaction>
					]]> </dts:trnXML>
		      	</dts:SubmitSinglePayment>';

        return 	$return;
    }
    // End SubmitSinglePaymentToken

    // Start createSubmitSinglePaymentCustomerRegisterBody
    function createSubmitSinglePaymentCustomerRegisterBody($params){

        $return = '<dts:SubmitSinglePayment>
		         <!--Optional:-->
		         <dts:trnXML><![CDATA[';

        $return .= 	'<Transaction>';
        $return .= 	'<CustNumber>'.$params['CustNumber'].'</CustNumber>';
        $return .= 	'<CustRef>'.$params['CustRef'].'</CustRef>';
        $return .= 	'<Amount>'.$params['Amount'].'</Amount>';
        $return .= 	'<TrnType>'.$params['TrnType'].'</TrnType>';
        $return .= 	'<AccountNumber>'.$params['AccountNumber'].'</AccountNumber>';

        $return .= 	'<CreditCard Registered="'.$params['Registered'].'">';
        $return .= 	'<CardNumber>'.$params['CardNumber'].'</CardNumber>';
        $return .= 	'<ExpM>'.$params['ExpM'].'</ExpM>';
        $return .= 	'<ExpY>'.$params['ExpY'].'</ExpY>';
        $return .= 	'<CVN>'.$params['CVN'].'</CVN>';
        $return .= 	'<CardHolderName>'.$params['CardHolderName'].'</CardHolderName>';
        $return .= 	'</CreditCard>';

        $return .= 	'<Security>';
        $return .= 	'<UserName>'.$params['UserName'].'</UserName>';
        $return .= 	'<Password>'.$params['Password'].'</Password>';
        $return .= 	'</Security>';

        $return .= 	'<Register>';
        $return .= 	'<Customer>';

        $return .= 	'<CreditCard>';
        $return .= 	'<CardNumber>'.$params['CardNumber'].'</CardNumber>';
        $return .= 	'<ExpM>'.$params['ExpM'].'</ExpM>';
        $return .= 	'<ExpY>'.$params['ExpY'].'</ExpY>';
        $return .= 	'<CardHolderName>'.$params['CardHolderName'].'</CardHolderName>';
        $return .= 	'</CreditCard>';

        $return .= 	'<CustNumber>'.$params['CustNumber'].'</CustNumber>';

        $return .= 	'<Contact>';
        $return .= 	'<FirstName>'.$params['FirstName'].'</FirstName>';
        $return .= 	'<LastName>'.$params['LastName'].'</LastName>';
        $return .= 	'</Contact>';

        $return .= 	'</Customer>';
        $return .= 	'</Register>';
        $return .= 	'</Transaction>
					]]> </dts:trnXML>
		      	</dts:SubmitSinglePayment>';

        return 	$return;
    }
    // End createSubmitSinglePaymentCustomerRegisterBody

    // Start SubmitSinglePaymentUsingCustomerRegisterBody
    function SubmitSinglePaymentUsingCustomerRegisterBody($params){

        $return = '<dts:SubmitSinglePayment>
		         <!--Optional:-->
		         <dts:trnXML><![CDATA[';

        $return .= 	'<Transaction>';
        $return .= 	'<CustNumber>'.$params['CustNumber'].'</CustNumber>';
        $return .= 	'<CustomerStorgaeNumber>'.$params['CustomerStorgaeNumber'].'</CustomerStorgaeNumber>';
        $return .= 	'<CustRef>'.$params['CustRef'].'</CustRef>';
        $return .= 	'<Amount>'.$params['Amount'].'</Amount>';
        $return .= 	'<TrnType>'.$params['TrnType'].'</TrnType>';
        $return .= 	'<AccountNumber>'.$params['AccountNumber'].'</AccountNumber>';

        $return .= 	'<CreditCard Registered="True">';
        $return .= 	'</CreditCard>';

        $return .= 	'<Security>';
        $return .= 	'<UserName>'.$params['UserName'].'</UserName>';
        $return .= 	'<Password>'.$params['Password'].'</Password>';
        $return .= 	'</Security>';
        $return .= 	'</Transaction>
					]]> </dts:trnXML>
		      	</dts:SubmitSinglePayment>';
        return 	$return;
    }
    // End SubmitSinglePaymentUsingCustomerRegisterBody

    // Start SubmitPaymentScheduleBody
    function SubmitPaymentScheduleBody($params){

        $return = '<dts:SubmitPaymentSchedule>
		         <!--Optional:-->
		         <dts:scheduleXML><![CDATA[';


        $return .= 	'<Schedule>';
        $return .= 	'<CustNumber>'.$params['CustNumber'].'</CustNumber>';
        $return .= 	'<Amount>'.$params['Amount'].'</Amount>';
        $return .= 	'<TrnType>'.$params['TrnType'].'</TrnType>';

        $return .= 	'<CreditCard Registered="True">';
        $return .= 	'</CreditCard>';

        $return .= 	'<Schedule>';
        $return .= 	'<Frequency>'.$params['Frequency'].'</Frequency>';
        $return .= 	'<StartDate>'.$params['StartDate'].'</StartDate>';
        if(isset($params['EndPaymentDate'])){
            $return .= 	'<EndPaymentDate>'.$params['EndPaymentDate'].'</EndPaymentDate>';
        }
        if(isset($params['EndPaymentCount'])){
            $return .= 	'<EndPaymentCount>'.$params['EndPaymentCount'].'</EndPaymentCount>';
        }
        $return .= 	'</Schedule>';

        $return .= 	'<Security>';
        $return .= 	'<UserName>'.$params['UserName'].'</UserName>';
        $return .= 	'<Password>'.$params['Password'].'</Password>';
        $return .= 	'</Security>';
        $return .= 	'</Schedule>
					]]> </dts:scheduleXML>
		      	</dts:SubmitPaymentSchedule>';

        return 	$return;
    }
    // End SubmitPaymentScheduleBody

    // Start createSubmitBatchTrnFileBody
    function createSubmitBatchTrnFileBody($params,$url){

        $return = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bat="'.$url.'">
				   <soapenv:Header/>
				   <soapenv:Body>';
        $return .= '<bat:SubmitBatchTrnFile>';
        $return .= '<bat:trn>';
        $return .= '<bat:userName>'.$params['userName'].'</bat:userName>';
        $return .= '<bat:password>'.$params['password'].'</bat:password>';
        $return .= '<bat:description>'.$params['description'].'</bat:description>';
        $return .= '<bat:batchNumber>'.$params['batchNumber'].'</bat:batchNumber>';
        $return .= '<bat:trnTypes>1</bat:trnTypes>';
        $return .= '<bat:zipped>0</bat:zipped>';
        $return .= '<bat:fileLength>'.$params['fileLength'].'</bat:fileLength>';
        $return .= '<bat:fileCRC32>0</bat:fileCRC32>';
        $return .= '<bat:b64TransactionFileData>';
        $return .=  $params['b64TransactionFileData'];
        $return .= '</bat:b64TransactionFileData>';
        $return .= '</bat:trn>';
        $return .= '</bat:SubmitBatchTrnFile>';
        $return .= ' </soapenv:Body>
						</soapenv:Envelope>';

        return $return;
    }
    // End createSubmitBatchTrnFileBody

    // Start createAuthoriseBatchTrnFileBody
    function createAuthoriseBatchTrnFileBody($params,$url){

        $return = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bat="'.$url.'">';
        $return .= '<soapenv:Header/>';
        $return .= '<soapenv:Body>';
        $return .= '<bat:AuthoriseBatchTrnFile>';
        $return .= '<bat:userName>'.$params['userName'].'</bat:userName>';
        $return .= '<bat:password>'.$params['password'].'</bat:password>';
        $return .= '<bat:uniqueBatchIdentifier>'.$params['uniqueBatchIdentifier'].'</bat:uniqueBatchIdentifier>';
        $return .= '</bat:AuthoriseBatchTrnFile>';
        $return .= '</soapenv:Body>';
        $return .= '</soapenv:Envelope>';

        return $return;
    }
    // End createAuthoriseBatchTrnFileBody

    // Start __APIResposeHandler
    function __APIResposeHandler($respose){

        $p = xml_parser_create();
        xml_parse_into_struct($p, $respose, $vals, $index);
        xml_parser_free($p);

        $xml = simplexml_load_string($vals[3]['value'], 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        return $xml;
    }
    // End __APIResposeHandler

    // Start __APIBatchResposeHandler
    function __APIBatchResposeHandler($respose,$id){

        $p = xml_parser_create();
        xml_parse_into_struct($p, $respose, $vals, $index);
        xml_parser_free($p);

        if($id == '4'){
            if( ($vals[$id]['tag'] == 'RESULTSUMMARY') && ($vals[$id]['value'] == '0') ){
                $arr['value'] = '0';
                $arr['uniqueBatchIdentifier'] = $vals['6']['value'];
                return $arr;
            }
        }else{
            return $vals[$id];
        }
    }
    // End __APIBatchResposeHandler

}
