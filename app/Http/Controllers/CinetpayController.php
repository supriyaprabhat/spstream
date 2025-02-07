<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Transactions;
use App\SubscriptionPlan;
use App\Coupons;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use URL;
use Session;
use Redirect;
use Input;

class CinetpayController extends Controller
{
     
    
    public function pay()
    {   
        $plan_id = Session::get('plan_id');  

        $plan_info = SubscriptionPlan::where('id',$plan_id)->where('status','1')->first();
        $plan_name=$plan_info->plan_name;
        $plan_days=$plan_info->plan_days;

        $amount=$plan_info->plan_price;
        

        if(Session::get('coupon_percentage'))
        {   
            //If coupon used
            $discount_price_less =  $amount * Session::get('coupon_percentage') / 100;

            $plan_amount=$amount - $discount_price_less;

            $coupon_code= Session::get('coupon_code');
            $coupon_percentage= Session::get('coupon_percentage');

        }
        else
        {
            //If no coupon used
            $plan_amount=$amount;
            $coupon_code= NULL;
            $coupon_percentage= NULL;
        }

        $currency_code=getcong('currency_code')?getcong('currency_code'):'USD';  

        $success_url=\URL::to('cinetpay/success/');
        $notify_url=\URL::to('cinetpay/notify/');           

        $cinetpay_api_key=getPaymentGatewayInfo(15,'cinetpay_api_key');
        $cinetpay_site_id=getPaymentGatewayInfo(15,'cinetpay_site_id');
        $cinetpay_secret_key=getPaymentGatewayInfo(15,'cinetpay_secret_key');
        $base_url = 'https://api-checkout.cinetpay.com/v2/payment';
 
        
        // Replace this with your actual payment data
        $data = array(
            'apikey' => $cinetpay_api_key,
            'site_id' => $cinetpay_site_id,
            'transaction_id' => 'CP'.rand(0,99999),
            'amount' => $amount,  // Example: 1000 = 10.00 XOF
            'currency' => $currency_code,
            'description' => $plan_name,
            'return_url' => $success_url,
            'notify_url' => $notify_url,
            'channels' => 'ALL',
            // Add any other required parameters
        );
        
        // Build the request payload
        $payload = json_encode($data);
        
        // Set cURL options
        $ch = curl_init($base_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode("$cinetpay_api_key:$cinetpay_site_id"),
        ));
        
        // Execute the request
        $response = curl_exec($ch);
        
        // Check for errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        
        // Close cURL session
        curl_close($ch);
        
        // Process the response
        $decoded_response = json_decode($response, true);
         
        //dd($decoded_response);

        if($decoded_response['code']=="201")  
        {
            return Redirect::away($decoded_response['data']['payment_url']);     
        }
        else
        {
            $error_msg=$decoded_response['description'];

            Session::flash('plan_id',Session::get('plan_id'));

            Session::flash('coupon_code',Session::get('coupon_code'));
            Session::flash('coupon_percentage',Session::get('coupon_percentage'));

            \Session::put('error_flash_message',$error_msg);
            return redirect('dashboard');
        }    

 
         

     }
     
    public function success(Request $request)
    {
        $input = $request->all();

       
        $transaction_id= $input['transaction_id'];
        $payment_id= $input['transaction_id'];
           
        $plan_id = Session::get('plan_id');
 
        $plan_info = SubscriptionPlan::where('id',$plan_id)->where('status','1')->first();
        $plan_name=$plan_info->plan_name;
        $plan_days=$plan_info->plan_days;
        $amount=$plan_info->plan_price;


        $currency_code=getcong('currency_code')?getcong('currency_code'):'USD';

        $cinetpay_api_key=getPaymentGatewayInfo(15,'cinetpay_api_key');
        $cinetpay_site_id=getPaymentGatewayInfo(15,'cinetpay_site_id');
        $cinetpay_secret_key=getPaymentGatewayInfo(15,'cinetpay_secret_key');
        $base_url = 'https://api-checkout.cinetpay.com/v2/payment/check';

          
         // Replace this with your actual payment data
        $data = array(
            'transaction_id' => $transaction_id,
            'site_id' => $cinetpay_site_id,
            'apikey' => $cinetpay_api_key
        );
        
        // Build the request payload
        $payload = json_encode($data);
        
        // Set cURL options
        $ch = curl_init($base_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode("$cinetpay_api_key:$cinetpay_site_id"),
        ));
        
        // Execute the request
        $response = curl_exec($ch);
        
        // Check for errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        
        // Close cURL session
        curl_close($ch);
        
        // Process the response
        $decoded_response = json_decode($response, true);
        
        if($decoded_response['message'] == 'SUCCES') 
        {
            if(Session::get('coupon_percentage'))
            {   
                //If coupon used
                $discount_price_less =  $amount * Session::get('coupon_percentage') / 100;

                $plan_amount=$amount - $discount_price_less;

                $coupon_code= Session::get('coupon_code');
                $coupon_percentage= Session::get('coupon_percentage');

                //Update Counpon Used
                Coupons::where('coupon_code', $coupon_code)->update([
                    'coupon_used'=> DB::raw('coupon_used+1') 
                ]);

            }
            else
            {
                //If no coupon used
                $plan_amount=$amount;
                $coupon_code= NULL;
                $coupon_percentage= NULL;
            }

             
            /**
            * Write Here Your Database insert logic.
            */

            $user_id=Auth::user()->id;
   
            $user = User::findOrFail($user_id);

            $user->plan_id = $plan_id;                    
            $user->start_date = strtotime(date('m/d/Y'));             
            $user->exp_date = strtotime(date('m/d/Y', strtotime("+$plan_days days")));            
            $user->plan_amount = $plan_amount;
            //$user->subscription_status = 0;
            $user->save();


            $payment_trans = new Transactions;

            $payment_trans->user_id = Auth::user()->id;
            $payment_trans->email = Auth::user()->email;
            $payment_trans->plan_id = $plan_id;
            $payment_trans->gateway = 'Cinetpay';
            $payment_trans->payment_amount = $plan_amount;
            $payment_trans->payment_id = $payment_id;

            $payment_trans->coupon_code = $coupon_code;
            $payment_trans->coupon_percentage = $coupon_percentage;

            $payment_trans->date = strtotime(date('m/d/Y H:i:s'));                    
            $payment_trans->save();

            Session::flash('plan_id',Session::get('plan_id'));
            
             //Subscription Create Email
            $user_full_name=$user->name;

            $data_email = array(
                'name' => $user_full_name
                 );    

             
            try{

                \Mail::send('emails.subscription_created', $data_email, function($message) use ($user,$user_full_name){
                $message->to($user->email, $user_full_name)
                    ->from(getcong('site_email'), getcong('site_name')) 
                    ->subject('Subscription Created');
                });
            
            }catch (\Throwable $e) {
             
                \Log::info($e->getMessage());  
                           
            }

            Session::flash('plan_id',Session::get('plan_id'));
            Session::flash('coupon_code',Session::get('coupon_code'));
            Session::flash('coupon_percentage',Session::get('coupon_percentage'));

            \Session::flash('success',trans('words.payment_success'));
            return redirect('dashboard');
        }
        else
        {
            Session::flash('plan_id',Session::get('plan_id'));
            Session::flash('coupon_code',Session::get('coupon_code'));
            Session::flash('coupon_percentage',Session::get('coupon_percentage'));

            \Session::flash('error_flash_message','Payment fail!');
            return redirect('dashboard');
        }

         
    }    

    public function notify()
    {
         Session::flash('plan_id',Session::get('plan_id'));
         Session::flash('coupon_code',Session::get('coupon_code'));
         Session::flash('coupon_percentage',Session::get('coupon_percentage'));
         
        return true; 
    }
}