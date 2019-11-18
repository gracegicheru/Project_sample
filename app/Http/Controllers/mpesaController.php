<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Mpesa extends Controller
{
    
    public function mpesaApi(Request $request){

    	$phone_number = $request->phone_number;
    	$amount= $request->amount;

    	$lastninedigits = substr($phone_number, -9);
        $formatted_user_phone = '254'.$lastninedigits;

          $PartyA =$formatted_user_phone;
        $passkey ='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
//        $Password = base64_encode($BusinessShortCode.$passkey.$Timestamp);
        $TransactionDesc ='Mpesa Deposit to Nyasomo Sacco';
        $BusinessShortCode = '174379';
        $LipaNaMpesaPasskey = $passkey;
        $Timestamp = '20180116143600';
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = $amount;
        $PartyB = '174379';
        $PhoneNumber = $PartyA;
        $callbackurl = 'http://nyasomo.fikacab.co.ke/api/callback/';
        $AccountReference = 'Nyasomo Sacco Application';
        $Remarks = 'Your transaction is being processed';

        /*
         * Innitiate the online payment transaction
         */
        $mpesa= new \Safaricom\Mpesa\Mpesa();

        /*
         * Innitiate online payment on behalf of the customer
         */
        $stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

    }
}
