<?php
/**
* Plugin Name: Bambora APAC Online Plug-in for WooCommerce.
* Plugin URI: https://dev-apac.bambora.com/
* Description:  Welcome to the Bambora APAC Plug-in for WooCommerce. Need an Account? Check us out at https:///www.bambora.com
* Version: 1.0
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

class Bambora_Apac_Integrate {


    // Start getSST
    public function getSST($int_url,$url) { 
      
      
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$int_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        echo $server_output;

        $arr = explode('<input type="hidden"', $server_output );
        $sst = '';
        $sst_error = '';
       

        if(count($arr)>0){

            $str = trim(str_replace(array('/>','"'),'', $arr[1]));
            $arr_inner = explode('=', $str );
           
            if(count($arr_inner)>0){

              if(trim($arr_inner[2])=="True"){
              
                $str_inner = trim(str_replace(array('/>','"'),'', $arr[3]));
                $arr_inner_val = explode('=', $str_inner );
                $sst = trim(str_replace('</form></body></html>', '', $arr_inner_val[2]));

              }else{
                $str_inner = trim(str_replace(array('/>','"'),'', $arr[2]));
                $arr_inner_val = explode('=', $str_inner );
                $sst_error = trim($arr_inner_val[2]);   
              
              }

            }

        }
     
       
        return array('sst'=>$sst,'error_msg'=>$sst_error);
    } 
    // End getSST

}