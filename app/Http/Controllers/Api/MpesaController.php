<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Safaricom\Mpesa;
use App\Transaction;


class MpesaController extends Controller
{
    //
   private $name;

    public function form(){
    	return_view('mpesa');
    }

    public function mpesaApi(Request $request){

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

   public function callback(){



       $mpesa= new \Safaricom\Mpesa\Mpesa();

       //  $callbackData=$mpesa->getDataFromCallback();

       $callbackData = file_get_contents('php://input');
       //perform your processing here, e.g. log to file....

       $myFile = time()."_log.txt";
       if($this->name == ""){
           $this->name= $myFile;
       }

       $file = fopen($this->name, "a");
       fwrite($file, $callbackData);


       $data = file_get_contents($this->name);
       $file1 = fopen('log_main.txt', 'a');
       if ($file1){
           fwrite($file1, $data);
           fclose($file1);
       }



       $jsontoarray = json_decode($data, TRUE);

       $result_code = $jsontoarray['Body']['stkCallback']['ResultCode'];
       $mpesa_receipt_number = $jsontoarray['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
       $amount= $jsontoarray['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
       // dd($jsontoarray,$result_code,$mpesa_receipt_number);



       if($result_code == 0)
       {

           $newPayment = new Transaction;
           $newPayment->receipt_no = $mpesa_receipt_number;//TEST DATA
           $newPayment->amount = $amount;
           $newPayment->save();


       }


       fclose($file);


   }
}
