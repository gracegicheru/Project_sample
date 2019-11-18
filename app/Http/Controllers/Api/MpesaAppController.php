<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Safaricom\Mpesa;
class MpesaAppController extends Controller
{

    public function MpesaApp(Request $request){

        $phone_number = $request->phone_number;
        $amount= $request->amount;

        $lastninedigits = substr($phone_number, -9);
        $formatted_user_phone = '254'.$lastninedigits;

        $PartyA =$formatted_user_phone;
        $passkey ='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
//        $Password = base64_encode($BusinessShortCode.$passkey.$Timestamp);
        $TransactionDesc ='Mpesa Deposit to Gracy';
        $BusinessShortCode = '174379';
        $LipaNaMpesaPasskey = $passkey;
        $Timestamp = '20180116143600';
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = $amount;
        $PartyB = '174379';
        $PhoneNumber = $PartyA;
        $callbackurl = 'http://mpesaapp.bwareyearlymeetingoffriends.org/api/callback/';
        $AccountReference = 'Gracy Application';
        $Remarks = 'Your transaction is being processed';

        /*
         * Innitiate the online payment transaction
         */
        $mpesa= new \Safaricom\Mpesa\Mpesa();

        /*
         * Innitiate online payment on behalf of the customer
         */
        $stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $callbackurl, $AccountReference, $TransactionDesc, $Remarks);

    }

}