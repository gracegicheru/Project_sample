<?php

namespace App\Repositories;

use Carbon\Carbon;
use Storage;

class IpayAfrica
{
    protected $vendor_id = "demo";
    protected $consumer_secret = "fL08rbEVHPo750A1";
    protected $endpoint = "https://apis.ipayafrica.com/payments/v2/";


    public function initiateMpesa($phone,$amount){
//        $milestone
        $amounttopay =(int)$amount;

        $req_data = [
            'live'=>0,
            'oid'=>1,
            'inv'=>1,
            'amount'=>10,
            'tel'=>'254713060941',
//            'eml'=>request()->user()->email,
            'eml'=>'gracegicheru57@gmail.com',
            'vid'=>$this->vendor_id,
            'curr'=>'KES',
            'cst'=>'0',
//            'cbk'=>url("http://jointpesa.co.ke/ipayresponse/$milestone->id"),

        ];
        $data_string = implode('',array_values($req_data));
        $url = $this->endpoint.'transact';
        $hash = hash_hmac('sha256',$data_string,'demoCHANGED');
        $req_data['hash'] = $hash;
        $res = $this->execCurl($url,$req_data);

        $sid = $res->data->sid;
        $this->mpesaSTKPush($sid,$phone);

    }

    public function topUpAccountIpay($phone,$amount,$user)
    {
        $req_data = [
            'live'=>0,
            'oid'=>$user->id,
            'inv'=>$user->id,
            'amount'=>10,
            'tel'=>'25410132715',
            'eml'=>request()->user()->email,
            'vid'=>$this->vendor_id,
            'curr'=>'KES',
            'cst'=>'0',
            'cbk'=>url("http://jointpesa.co.ke/saveipaytopup/$user->id"),

        ];
        $data_string = implode('',array_values($req_data));
        $url = $this->endpoint.'transact';
        $hash = hash_hmac('sha256',$data_string,'demoCHANGED');
        $req_data['hash'] = $hash;
        $res = $this->execCurl($url,$req_data);

        $sid = $res->data->sid;
        $this->mpesaSTKPush($sid,$phone);

    }

    protected function formatPhone($phone)
    {
        $len = strlen($phone);
        if($len==10){
            $phone = "repl".$phone;
            $phone = str_replace('repl07','+2547',$phone);
        }
        if($len==12){
            $phone = '+'.$phone;
        }

        return $phone;
    }

    protected function mpesaSTKPush($sid,$phone)
    {
        $format_phone =$this->formatPhone($phone);
        $hash_2 = hash_hmac('sha256',$format_phone.$this->vendor_id.$sid,'demoCHANGED');
        $req_2_data = [
            'phone'=>$format_phone,
            'sid'=>$sid,
            'vid'=>$this->vendor_id,
            'hash'=>$hash_2,
            'autopay'=>1,

        ];
        $url2 = $this->endpoint.'transact/push/mpesa';
        $res = $this->execCurl($url2,$req_2_data);
    }

    protected function execCurl($url, $data, $header = null)
    {
        if (!$header) {
            $header = array(
                'Content-Type: application/json',
            );
        }
        $method = 'POST';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $content = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $json_response = null;
        if ($status == 200 || $status == 201) {
            $json_response = json_decode($content);
        } else {
            throw new \Exception($content.'Curl error: ' . curl_error($curl).'Status: '.$status.' Url: '.$url.'  '.json_encode($data));
        }

        return $json_response;

    }


}
