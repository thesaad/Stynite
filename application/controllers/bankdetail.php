<?php
class Bankdetail extends CI_Controller
{

//    function Bankdetail()
//    {
//        parent::__construct();
//        $this->load->helper(array('form', 'url','constant_helper','function_helper','stripe_helper'));
//        $this->load->model('product_model');
//		$this->load->model('bankdetail_model');
//        $this->load->model('users_photos_model');
//        $this->load->library('session');
//
//
//    }
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','constant_helper','function_helper','stripe_helper'));
        $this->load->model('product_model');
        $this->load->model('bankdetail_model');
        $this->load->model('users_photos_model');
        $this->load->library('session');
    }

    function index()
	{
			
		$data['active_page'] = 'bank';
        $bank_detail = $this->bankdetail_model->get_retailer_bankdetail();
        $data['bank_detail'] = $bank_detail;
		
        $this->load->view('templates/header', $data);
        $this->load->view('retailer_store/bankdetail',$data);
	}

    function detail()
    {
    	$user_id = 0;
    	$id =  $_REQUEST['id'] ;
		if(isset($_REQUEST['user_id'])){
			$user_id = $_REQUEST['user_id'];
		}
    	 $product = $this->product_model->get_products($id);
      $data['product'] = $product;
	 $data['user_id'] = $user_id;
        $this->load->view('app_product',$data);
    }
	
	function buy()
	{
		$id =  $_REQUEST['id'] ;
    	 $product = $this->product_model->get_products($id);
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
	 function uploadVerifyDoc()
	{
	$res	=  $this -> bankdetail_model -> SaveDoc();
	if($res>0){
		$retailer_id = $this->session->userdata('id');
		$input_method = array("user_id"=>$retailer_id,"file_id"=>$res);
	   $result	= $this->AccountVerify($input_method);
	    
	   
		 
	}else{
		$result = array("status"=>0,"message"=>"File uploading error");
	}
	echo json_encode($result);
	}
	
	/// verify doc
			function AccountVerify($input_method)
		{
			//error_reporting(1);
			//$input_method = $this -> webservices_inputs();
			
			//print_r($input_method);
			$user_id = $input_method['user_id'];
			$file_id = $input_method['file_id'];
			//get the user strip account id and file path
		    $account_id = $this -> bankdetail_model -> GetStripeAccountId($user_id);
			if (!$account_id)
			{
                
			 $res = array(
					'status' => 0,
					'message' => "Your account bank detail missing",
					
				);

			}
			else
			{
				//check the file exist or not
				$FilePath = $this -> bankdetail_model -> GetFile($user_id, $file_id);
				if (!$FilePath)
				{

				$res =array(
						'status' => 0,
						'message' => "Verification doc not found",
						'show_message_to_user' => 'yes'
					) ;

				}
				else
				{
					//upload file on strip server
					$FileDetail = array(
						"acc_id" => $account_id,
						"file_path" => $FilePath
					);
					$result = stripe_fileupload($FileDetail);
					//print_r($result);
					if ($result['status'] == 1)
					{
						$file = $result['file'];
						$doc_detail = array(
							"acc_id" => $account_id,
							"file" => $file
						);
						$result = DOCverify($doc_detail);

						//print_r($result);

						//save account verification detail in db
						$verify_status = $result['verify_status'];
						$doc = $result['doc'];
						$input_method['doc'] = $doc;
						$input_method['status'] = $verify_status;
						$input_method['reason'] = "";
						$update = $this -> bankdetail_model -> UpdateStatus($input_method);

						if ($update)
						{

							$res =array(
								'status' => 1,
								'message' => ACCOUNT_VERIFY_SUCCESS,
								"verify_status" => $verify_status,
								
							) ;
						}
						else
						{
							$res =array(
								'status' => 0,
								'message' => SERVER_ERROR,
								
							) ;
						}

					}
					else
					{
						$input_method['doc'] = $doc;
						$input_method['status'] = $verify_status;
						$input_method['reason'] = "";
						$update = $this -> BankDetail_model -> UpdateStatus($input_method);
						if ($update)
						{

							$res =array(
								'status' => 0,
								'developer_message' => $result['message'],
								'message' => $result['message'],
								
							) ;
						}
						else
						{
							$res = array(
								'status' => 0,
								'message' => SERVER_ERROR,
								
							);

						}

					}

				}
			}
return $res;
		}
          
	
	///end 
		
	function addBankDetail()
	{
		
		  $ip_address	= $this->input->ip_address();
		$user_id = 0;
		date_default_timezone_set("UTC");
		$current=gmdate('Y-m-d H:i:s O');
		 	$current_timpstamp =strtotime($current); 
			
			
		$input = array("routing_number"=>$_POST['routing_no'],
		              "account_number"=>$_POST['ac_no'],
		              "first_name"=>$_POST['firstname'],
		              "last_name"=>$_POST['lastname'],
		              "day"=>$_POST['date'],
		              "country" => $_POST['country'],					  
		              "month"=>$_POST['month'],
		              "year"=>$_POST['year'],
		              "address"=>$_POST['address'],
		              "postal_code"=>$_POST['postal_code'],
		              "city"=>$_POST['city'],
		              "state"=>$_POST['state'],
		              "ssn"=> $_POST['ssn_no'],
		              "personal_id"=>$_POST['personal_id'],
		              "date"=>$current_timpstamp,
		              "type"=> 'individual',
		              "ip"=>$ip_address
		);
		
		//$input = json_decode($str,true);
	
	$acarr	= ManageAccount($input);
	$saveaccount_response = json_decode($acarr, true);
			 
			$saveaccountinfo = $saveaccount_response['status'];
			$errormessage = $saveaccount_response['message'];
			if ($saveaccountinfo == '1')
			{
				$chkaccupdate = $this -> bankdetail_model -> savestripe_detail($saveaccount_response, $user_id);
			  $this -> bankdetail_model -> saveretailer_bankdetail($input);
				if ($chkaccupdate)
				{
				$response	=array(
						'status' => true,
						'developer_message' => 'All Set',
						'message' => "Account information saved successfully",
						'show_message_to_user' => 'yes'
					);
					//echo 1;
				}
				else
				{
				$response=	array(
						'status' => false,
						'developer_message' => 'no data in table',
						'message' => "Account add operation fail",
						'show_message_to_user' => 'yes'
					);
					//echo 0;
				}

			}
			else
			{
				$response = array(
					'status' => false,
					'developer_message' => 'no data in table',
					'message' => $errormessage,
					'show_message_to_user' => 'yes'
				);
				//echo 0;
			}
			
	echo	json_encode($response);
		//echo 1;
			
	}

    
	
	function addBankDetailBack()
	{
		print_r($_POST);exit;
		  $ip_address	= $this->input->ip_address();
		$user_id = 0;
		date_default_timezone_set("UTC");
		$current=gmdate('Y-m-d H : i : s O');
			$current_timpstamp =strtotime($current);
			
			
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
		              "date"=>$current_timpstamp,
		              "type"=> 'individual',
		              "ip"=>$ip_address
		);
		
		//$input = json_decode($str,true);
	
	$acarr	= ManageAccount($input);
	$saveaccount_response = json_decode($acarr, true);
			//print_r($saveaccount_response);
			$saveaccountinfo = $saveaccount_response['status'];
			$errormessage = $saveaccount_response['message'];
			if ($saveaccountinfo == '1')
			{
				$chkaccupdate = $this -> bankdetail_model -> savestripe_detail($saveaccount_response, $user_id);
				if ($chkaccupdate)
				{
					$this -> response(array(
						'status' => true,
						'developer_message' => 'All Set',
						'message' => ACCOUNTINFO_UPDATE,
						'show_message_to_user' => 'yes'
					), 200);
				}
				else
				{
					$this -> response(array(
						'status' => false,
						'developer_message' => 'no data in table',
						'message' => ADDACC_FAIL,
						'show_message_to_user' => 'yes'
					), 200);
				}
			}
			else
			{
				$this -> response(array(
					'status' => false,
					'developer_message' => 'no data in table',
					'message' => $errormessage,
					'show_message_to_user' => 'yes'
				), 200);
			}
	}
	
 
	function get_accountbal()
	{
		$res = get_accountbal();
		echo "<pre>";
		print_r($res);exit;
	}
	
	
	
	   function payment_submit()
    {
    	$data = $_POST;
		
		$data = array("token"=>$_POST['stripeToken']);
    	
        $cost = $_POST['price'];
         
 
            $cost = $cost;
 
            
            $pay_amount =  $cost*100;
            


            \Stripe\Stripe::setApiKey("sk_test_r1Ks8A3jbWJk9yTnCVmYkmwO");
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
                    "description" => "Example charge"));
            }
            catch (\Stripe\Error\Card $e) {
                // The card has been declined
            }
			echo "<pre>";
			print_r($charge);
			exit;
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

            if ($charge->status == 'succeeded') {
/*
                $payment_arr = array(
                    "email" => $email,
                    "plan_id" => $plan,
                    "start_on" => $start_on,
                    "expire_on" => $expire_on,
                    "customer_id" => $charge->id,
                    "amount_paid" => $charge->amount,
                    "business_limit" => $limit);
                $res = $this->login_model->save_payment($payment_arr);
                if ($res) {
                    redirect('login/logout');
                }
				*/
            }
          

    }

}
?>