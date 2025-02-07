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

 
class SslcommerzController extends Controller
{
       
    public function sslcommerz_pay()
    {   
        $sslcommerz_mode= getPaymentGatewayInfo(14,'mode');
        $store_id= getPaymentGatewayInfo(14,'store_id');
        $store_password= getPaymentGatewayInfo(14,'store_password');

          
        $currency_code=getcong('currency_code')?getcong('currency_code'):'USD';
         
        $plan_id = Session::get('plan_id');
 
        $plan_info = SubscriptionPlan::where('id',$plan_id)->where('status','1')->first();
        $plan_name=$plan_info->plan_name;
        $amount=$plan_info->plan_price;

        if(Session::get('coupon_percentage'))
        {   
            //If coupon used
            $discount_price_less =  $amount * Session::get('coupon_percentage') / 100;

            $plan_amount=number_format($amount - $discount_price_less,2);

            $coupon_code= Session::get('coupon_code');
            $coupon_percentage= Session::get('coupon_percentage');

        }
        else
        {
            //If no coupon used
            $plan_amount=number_format($amount,2);
            $coupon_code= NULL;
            $coupon_percentage= NULL;
        }

        $success_url=\URL::to('sslcommerz/success');
        $cancel_url=\URL::to('sslcommerz/fail');
          
        $order_id='CGORDER-'.rand(0,999999);


        $cus_name = Auth::user()->name;
        $cus_email = Auth::user()->email;

        $post_data = array();
        $post_data['store_id'] = $store_id;
        $post_data['store_passwd'] = $store_password;
        $post_data['total_amount'] = $plan_amount;
        $post_data['currency'] = $currency_code;
        $post_data['tran_id'] = uniqid();
        $post_data['success_url'] = $success_url;
        $post_data['fail_url'] = $cancel_url;
        $post_data['cancel_url'] = $cancel_url;
        $post_data['cus_name'] = $cus_name;
        $post_data['cus_email'] = $cus_email;
        $post_data['cus_add1'] = "Dhaka";
        $post_data['cus_add2'] = "Dhaka";
        $post_data['cus_city'] = "Dhaka";
        $post_data['cus_state'] = "Dhaka";
        $post_data['cus_postcode'] = "1000";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = "01711111111";
        $post_data['cus_fax'] = "01711111111";

        # Add shipping details (even if not used)
        $post_data['shipping_method'] = "NO";  # If no shipping, use "NO"
        $post_data['num_of_item'] = "1";
        $post_data['ship_name'] = $cus_name;
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_country'] = "Bangladesh";

        # Add product details
        $post_data['product_name'] = $plan_name;  # Specify the name of the product being purchased
        $post_data['product_category'] = "Service";  # You can specify a category like "Electronics", "Service", etc.
        $post_data['product_profile'] = "non-physical-goods";  # Options include "general", "physical-goods", "non-physical-goods", "airline-tickets", "travel", etc.


        # REQUEST SEND TO SSLCOMMERZ
        if($sslcommerz_mode=="live")
        {
            $direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";
        }
        else
        {
            $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
        }
        
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

        $content = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($code == 200 && !( curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = json_decode($content, true);

            //print_r($sslcommerzResponse);exit;

            if(isset($sslcommerzResponse['GatewayPageURL']) && $sslcommerzResponse['GatewayPageURL'] != "") {
                 
                //echo "<meta http-equiv='refresh' content='0;url=".$sslcommerzResponse['GatewayPageURL']."'>";
                return redirect($sslcommerzResponse['GatewayPageURL']);
                exit;                
            } else {
                
                Session::flash('plan_id',Session::get('plan_id'));
                Session::flash('coupon_code',Session::get('coupon_code'));
                Session::flash('coupon_percentage',Session::get('coupon_percentage'));

                \Session::put('error_flash_message','JSON Data parsing error!');
                return redirect('dashboard');
            }
        } else {
            curl_close($handle);
            Session::flash('plan_id',Session::get('plan_id'));

            Session::flash('coupon_code',Session::get('coupon_code'));
            Session::flash('coupon_percentage',Session::get('coupon_percentage'));

            \Session::put('error_flash_message',trans('words.payment_failed'));
            return redirect('dashboard');
        }
   
        
    }

   
    public function sslcommerz_success(Request $request)
    {   
        $inputs = $request->all();

        $sslcommerz_mode= getPaymentGatewayInfo(14,'mode');
        $store_id= getPaymentGatewayInfo(14,'store_id');
        $store_password= getPaymentGatewayInfo(14,'store_password');

        $status=$inputs['status'];
        $val_id=$inputs['val_id'];
 
            if ($status == 'VALID' OR $status == 'VALIDATED')
            {
 
                if($sslcommerz_mode=="live")
                { 
                    $requested_url = ("https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_password."&v=1&format=json");
                }
                else
                {
                    
                    $requested_url = ("https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_password."&v=1&format=json");
                }

                $handle = curl_init();
                curl_setopt($handle, CURLOPT_URL, $requested_url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

                $result = curl_exec($handle);

                $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

                if($code == 200 && !( curl_errno($handle)))
                { 
                    # TO CONVERT AS OBJECT
                    $result = json_decode($result);

                    # TRANSACTION INFO
                    
                    if($result->status=="VALIDATED" OR $result->status=="VALID")
                    {
                        $payment_id = $result->tran_id;    
                        
                        $plan_id = Session::get('plan_id');

                        $plan_info = SubscriptionPlan::where('id',$plan_id)->first();
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
        
                        $currency_code=getcong('currency_code')?getcong('currency_code'):'USD';
        
                        $user_id=Auth::user()->id;
                        
                        $user = User::findOrFail($user_id);
        
                        $user->plan_id = $plan_id;                    
                        $user->start_date = strtotime(date('m/d/Y'));             
                        $user->exp_date = strtotime(date('m/d/Y', strtotime("+$plan_days days")));
                        
                        $user->plan_amount = $plan_amount;
                        $user->save();
        
        
                        $payment_trans = new Transactions;
        
                        $payment_trans->user_id = Auth::user()->id;
                        $payment_trans->email = Auth::user()->email;
                        $payment_trans->plan_id = $plan_id;
                        $payment_trans->gateway = 'sslcommerz';
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
        
                        Session::flash('coupon_code',Session::get('coupon_code'));
                        Session::flash('coupon_percentage',Session::get('coupon_percentage'));
        
                        Session::flash('plan_id',Session::get('plan_id'));
        
                        \Session::flash('success',trans('words.payment_success'));
                        return redirect('dashboard'); 

                    }
                    else
                    {
                        Session::flash('coupon_code',Session::get('coupon_code'));
                        Session::flash('coupon_percentage',Session::get('coupon_percentage'));
    
                        Session::flash('plan_id',Session::get('plan_id'));
        
                        \Session::flash('error_flash_message',trans('words.payment_failed'));
                        return redirect('dashboard');      
                    }

                     

                } else {

                    Session::flash('coupon_code',Session::get('coupon_code'));
                    Session::flash('coupon_percentage',Session::get('coupon_percentage'));

                    Session::flash('plan_id',Session::get('plan_id'));
    
                    \Session::flash('error_flash_message','Failed to connect with SSLCOMMERZ');
                    return redirect('dashboard');
                }
             
        
            }
            else{

                Session::flash('coupon_code',Session::get('coupon_code'));
                Session::flash('coupon_percentage',Session::get('coupon_percentage'));

                Session::flash('plan_id',Session::get('plan_id'));
 
                \Session::flash('error_flash_message',trans('words.payment_failed'));
                return redirect('dashboard');
            }    
            
    }

    public function sslcommerz_fail(Request $request)
    {   

        Session::flash('coupon_code',Session::get('coupon_code'));
        Session::flash('coupon_percentage',Session::get('coupon_percentage'));

        Session::flash('plan_id',Session::get('plan_id'));

        \Session::put('error_flash_message',trans('words.payment_failed'));
        return redirect('dashboard');
    }
     
      
}