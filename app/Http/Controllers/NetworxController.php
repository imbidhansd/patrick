<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Custom;

class NetworxController extends Controller {

    public function index() {
        $lead = Lead::find(23);

        $networx_type = env('NETWORX_MODE');
        $networx_user_id = env('NETWORX_USER_ID');
        $networx_access_key = env('NETWORX_ACCESS_KEY');

        $pass_data = 'nx_userId=' . $networx_user_id . '&nx_access_key=' . $networx_access_key;
        $pass_data .= '&task_id=270' . $lead->service_category->networx_task_id;
        $pass_data .= '&zipcode=' . (($networx_type == 'sandbox') ? '00001' : $lead->zipcode);
        $full_name = explode(" ", $lead->full_name);
        if (isset($full_name[1]) && $full_name[1] != '') {
            $pass_data .= '&f_name=' . $full_name[0] . '&l_name=' . $full_name[1];
        } else {
            $pass_data .= '&f_name=' . $full_name[0] . '&l_name=' . $full_name[0];
        }

        $pass_data .= '&phone=' . trim(str_replace('-', '', preg_replace('/[^A-Za-z0-9\-]/', '', $lead->phone)));
        $pass_data .= '&email=' . $lead->email;
        $pass_data .= '&comments=' . trim(str_replace('-', '', preg_replace('/[^A-Za-z0-9\-]/', '', $lead->timeframe)));
        if(isset($lead->trustedCert))
        {
            $pass_data .= '&cert_url=' . $lead->trustedCert;
        }

        $url = "https://api.networx.com?" . $pass_data;
        
        /*dd($url);
        
        $networx_type = env('NETWORX_MODE');
        $nx_userId = env('NETWORX_USER_ID');
        $nx_access_key = env('NETWORX_ACCESS_KEY');

        $url = 'https://api.networx.com?nx_userId=' . $nx_userId . '&nx_access_key=' . $nx_access_key . '&task_id=245&zipcode=00001&f_name=john&l_name=smith&phone=8188188188&email=test@test.com&comments=testfromnetworx';*/

        $data = Custom::networxCall($url);

        /* $response = simplexml_load_string($data);
          $con = json_encode($response);
          $newArr = json_decode($con, true);

          if ($newArr['statusCode'] == '200') {
          echo $newArr['successCode'];
          } else if ($newArr['statusCode'] == '400') {
          echo $newArr['errorMessage'];
          } */
        dd($data);
    }

}
