<?php
class Paymentrecords extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','constant_helper','function_helper','stripe_helper'));
        $this->load->model('product_model');
        $this->load->model('users_photos_model');
        $this->load->library('session');
    }
    function getPayments()
	{
		$acarr	= InvoiceAllCustomer();
	echo "<pre>";
	print_r($acarr);exit;
	}
    function detail()
    {
    	$user_id = 0;
    	$id =  $_REQUEST['id'] ;
		if(isset($_REQUEST['user_id'])){
			$user_id = $_REQUEST['user_id'];
		}
    	 $product = $this->product_model->get_products($id,"VIEWED");
      $data['product'] = $product;
	 $data['user_id'] = $user_id;
        $this->load->view('app_product',$data);
    }
	
	function buy()
	{
		$id =  $_REQUEST['id'] ;
    	 $product = $this->product_model->get_products($id,"CLICK_BUY_LINK");
      $data['product'] = $product;
	
        $this->load->view('product_buy',$data);
	}
 
	function createcustomer()
	{
		//test function to save user information for future.
		$customer_detail = CreateCustomer($data);
		echo "<pre>";
		print_r($customer_detail);
		exit;
		
	}	
	 
	function testaccountcreate()
	{
		$input = array("routing_number"=>'110000000',
		              "account_number"=>'000123456789',
		              "first_name"=>"Sohan",
		              "last_name"=>"Sirvi",
		              "day"=>30,
		              "country" => "USA",					  
		              "month"=>"04",
		              "year"=>"2001",
		              "address"=>"Raj",
		              "postal_code"=>"94110",
		              "city"=>"San",
		              "state"=>"as",
		              "ssn"=> 6789,
		              "personal_id"=>"123456789",
		              "date"=>1481006340,
		              "type"=> 'individual',
		              "ip"=>"220.225.144.234"
		);
		
		//$input = json_decode($str,true);
	
	$acarr	= ManageAccount($input);
	echo "<pre>";
	print_r($acarr);exit;
	}
	
 
	
	
	
	
	   function payment_submit()
    {
    	$data = $_POST;
		
		 
    	
        $cost = $_POST['price'];
         
 $token = $_POST['stripeToken'];
         
             
 
		//$user_account= "acct_19NdDPAhzdo7fvjW";
		$product_id = $_POST['product_id'];
		$user_account = $this->product_model->getProductRetailerAccount($product_id);
		 
		$payment_res	= PayMent($token, $cost, $product_id, $user_account);
			//echo "<pre>";
			//print_r($payment_res);
			//exit;
			$payresult = json_decode($payment_res,true);
            //-- taking payment history
          /*  $payment_history = array(
                "charge_id" => $charge->id,
                "charge_created_on" => $charge->created,
                "charge_status" => $charge->status,
                "charge_amount" => $charge->amount,
                "balance_transaction" => $charge->balance_transaction,
                "failure_message" => $charge->failure_message,
                "failure_code" => $charge->failure_code,
                "failure_description" => $charge->description,
                "email" => $email,
                "business_plan" => $plan,
                "business_limit" => $limit);*/
            //$save_history = $this->login_model->payment_history($payment_history);
            //---end of payment history

            if ($payresult['status'] == '1') {
            	$user_id = $this -> product_model -> getUseridByMd5($_POST['user_id']);
               $balance_transaction_id = $payresult['transaction_id'];
					$balance_ch_id = $payresult['tid'];
				//	$application_fee = $payment_response['application_fee'];
				
				$input_method['token'] = $token;
					$input_method['balance_transaction_id'] = $balance_transaction_id;
					$input_method['balance_ch_id'] = $balance_ch_id;
					//$input_method['application_fee'] = $application_fee;
					$input_method['payment_response'] = $payment_res;
					$input_method['product_id'] = $product_id;
					$input_method['user_id'] = $user_id;
					$input_method['amount'] = $cost;
					
					$save_transaction = $this -> product_model -> save_transaction_info($input_method);
					
					redirect('product/success');
            }else{
            	redirect('product/fail');
            }
          

   }
    
	function success()
	{
		$this->load->view('product_buy_success');
	}
	function fail()
	{
		$this->load->view('product_buy_fail');
	}

}
?>