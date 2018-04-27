<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Bambora_Apac_Integrate {


    // Start getSST
    public function getSST($int_url,$url) { 


        $args = array(
            'body' => $url
        );

        $server_output_response = wp_remote_post( $int_url, $args );
        $server_output = $server_output_response['body'];

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
