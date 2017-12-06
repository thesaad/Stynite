<?php
//require APPPATH .'libraries/Stripe/init.php';
class Registration extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('registration_model');
        $this->load->library('session');
        $this->clear_cache();
    }

    function index()
    {
          
            $this->load->view("registration");
      

    }
    function registration_submit()
    {
       
    $reg = $this->registration_model->save_registration(); 
    if ($reg) {
                $data = array("status" => 1);
            } else {
                $data = array("status" => 0);
            }
            echo json_encode($data);
        
    }
   function payment()
   {
    
   $basicplan =  $this->login_model->get_priceplan(1);
   $basicplan[0]= $basicplan;
    $bestseller =  $this->login_model->get_priceplan(2);
   $bestseller[0]= $bestseller;
    $superload =  $this->login_model->get_priceplan(3);
   $superload[0]= $superload;
  $basicplanfeature = $this->login_model->get_priceplan_feature(1);
  $basicplanfeature[0] = $basicplanfeature;
   $bestsellerfeature = $this->login_model->get_priceplan_feature(2);
$bestsellerfeature[0] = $bestsellerfeature;
    $superloadfeature = $this->login_model->get_priceplan_feature(3);
  $superloadfeature[0] = $superloadfeature;
   $data['basicplan']=$basicplan[0];
   $data['bestseller']=$bestseller[0];
   $data['superload']=$superload[0];
    $data['basicplanfeature']=$basicplanfeature[0];
   $data['bestsellerfeature']=$bestsellerfeature[0];
   $data['superloadfeature']=$superloadfeature[0];
   //$data['plan']=$plan;
    $this->load->view("payment",$data);
   }
   function check_username()
   {
    $check = $this->login_model->check_username();
    if($check)
    {
        echo 0;
    }else{
        echo 1; 
    }
   }
    function check_email()
   {
    $check = $this->registration_model->check_email();
    if($check)
    {
        echo 0;
    }else{
        echo 1; 
    }
   }
   
     function check_businessname()
   {
    $check = $this->registration_model->check_businessname();
    if($check)
    {
        echo 0;
    }else{
        echo 1; 
    }
   }
   
   function payment_submit()
   {
    $cost = 0;
    $email = mysql_real_escape_string($_POST['email']);
   $check_merchant = $this->login_model->check_merchant($email);
   if($check_merchant)
   {
    $plan = $_POST['plan'];
      $priceplan = $this->login_model->get_priceplan($plan);
  $limit = $priceplan[0]->business_limit;
    $cost = $priceplan[0]->cost_cent;
    
     $validity = $_POST['validity'];
    $pay_amount = $validity * $cost;
     $start_on = date("Y-m-d h:m:s");
     
     $expire_on = date("Y-m-d h:m:s", strtotime(" +".$validity." months"));
    
   
        \Stripe\Stripe::setApiKey("sk_test_H2REZyG8KXJ9ca7560Az9xD2");
		//sk_test_dhBTKqegRurmqqlXMWpXyiyD
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here https://dashboard.stripe.com/account/apikeys 
        
        
        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];
        
        // Create the charge on Stripe's servers - this will charge the user's card
        try {
        $charge = \Stripe\Charge::create(array(
          "amount" => $pay_amount, // amount in cents, again
          "currency" => "usd",
          "source" => $token,
          "description" => "Example charge")
        );
        } catch(\Stripe\Error\Card $e) {
          // The card has been declined
        }
     //-- taking payment history
       $payment_history = array( "charge_id"=>$charge->id,
        "charge_created_on"=>$charge->created,
        "charge_status"=>$charge->status,
       "charge_amount"=>$charge->amount,
        "balance_transaction"=>$charge->balance_transaction,
        "failure_message"=>$charge->failure_message,
        "failure_code"=>$charge->failure_code,
        "failure_description"=>$charge->description,
        "email"=>$email,
        "business_plan"=>$plan,
        "business_limit"=>$limit
        );
        $save_history  = $this->login_model->payment_history($payment_history);
     //---end of payment history
        
       
        if($charge->status=='succeeded')
        {
            
            $payment_arr = array("email"=>$email,
                                "plan_id"=>$plan,
                                "start_on"=>$start_on,
                                "expire_on"=>$expire_on,
                                "customer_id"=>$charge->id,
                                "amount_paid"=>$charge->amount,
                                 "business_limit"=>$limit
                                
            );
        $res  = $this->login_model->save_payment($payment_arr); 
            if($res)
            {
                 redirect('registration/payment_success');
            }
        }
        redirect('registration/payment_error'); 
   }else{
    redirect('registration/payment');
   }
     
        
   }
   function payment_success()
   {
       $this->load->view("payment_success");
   }
   
    function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
    function forget_password()
    {
       $user = $this->pilon_model->get_forget_user();
       $user[0] = $user;
       $data['user'] = $user[0];
      $this->load->view("reset_password",$data);  
    }
    function reset_password()
    {
      $res =  $this->pilon_model->save_new_password();
      echo $res;
    }
}
?>