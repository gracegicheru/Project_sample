<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\IpayAfrica;

class IpayAfricaController extends Controller
{
    //
    public function IpayStk(){
        $ipay = new IpayAfrica();


        $ipay->initiateMpesa(\request()->phone,request('amount'));
//        $milestone
    }
}
