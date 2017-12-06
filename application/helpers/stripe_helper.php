<?php

	require APPPATH . '/third_party/stripe-php/init.php';
	\Stripe\Stripe::setApiKey(STRIPE_KEY);

	function PayMent($token, $amount, $product_id, $user_account)
	{
		
		$usd_price = $amount*100;
	$appfee = (($usd_price * OWNER_AMOUNT) / 100);
		
	$ownersent_price_amt	 =   intval ($appfee);
		$pro_infodata = 'product id=' . $product_id;
		try
		{
			$charge = \Stripe\Charge::create(array(
				"amount" => $usd_price, // amount in cents
				"currency" => "usd",
				"source" => $token,
				"description" => $pro_infodata,
				"application_fee" => $ownersent_price_amt // amount in cents
			), array("stripe_account" => $user_account));
			$transaction_array = $charge -> __toArray(true);
			 

			$tid = $transaction_array['id'];
			$transaction_id = $transaction_array['balance_transaction'];
			$application_fee = $transaction_array['application_fee'];
			$data = json_encode(array(
				"status" => 1,
				"message" => 'success',
				'tid' => $tid,
				'transaction_id' => $transaction_id,
				'application_fee' => $application_fee
			));
		}
		catch (Stripe\Error\Base $e)
		{
			// Code to do something with the $e exception object when an error
			// occurs
			$error_msg = ($e -> getMessage());
			$data = json_encode(array(
				"status" => 0,
				"message" => $error_msg
			));
		}
		 
		return $data;
	}
    function get_accountbal()
	{
		
    	$acct = \Stripe\Balance::retrieve();
	     echo "<pre>";
		print_r($acct);exit;
	}
	function ManageAccount($input_method)
	{
		//print_r($input_method);exit;
		try
		{

			$acct = \Stripe\Account::create(array(
				"managed" => true,
				"country" => "US",
				"external_account" => array(
					"object" => "bank_account",
					"country" => "US",
					"currency" => "usd",
					"routing_number" => $input_method['routing_number'],
					"account_number" => $input_method['account_number'],
				),
				"legal_entity" => array(
					"first_name" => $input_method['first_name'],
					"last_name" => $input_method['last_name'],
					"type" => "individual",
					"dob" => array(
						"day" => $input_method['day'],
						"month" => $input_method['month'],
						"year" => $input_method['year']
					),
					"address" => array(
						"line1" => $input_method['address'],
						"postal_code" => $input_method['postal_code'],
						"city" => $input_method['city'],
						"state" => $input_method['state'],
					),
					"ssn_last_4" => $input_method['ssn'],
					"personal_id_number" => $input_method['personal_id']
				),
				"tos_acceptance" => array(
					"date" => ($input_method['date']),
					//"personal_id_number"=>"123456789",
					"ip" => $input_method['ip']
				)
			));

			$customer_array = $acct -> __toArray(true);
			// print_r($customer_array);
			//exit;
			$customerDetail = $customer_array['external_accounts']['data'][0];
			
			if (key_exists("error", $customer_array))
		{

			$data = array(
				"status" => 0,
				"message" => $customer_array['error']['message'],
				"type" => $customer_array['error']['type']
			);
			return json_encode($data);
		}

			$Strip_accountID = $customerDetail['account'];
			$bank_name = $customerDetail['bank_name'];
			$last_digit = $customerDetail['last4'];
			$bank_id = $customerDetail['id'];
			$charges_enable = $customer_array['charges_enabled'];
			$transfers_enabled = $customer_array['transfers_enabled'];
			$data = json_encode(array(
				"status" => 1,
				"message" => 'success',
				'stripe_accountid' => $Strip_accountID,
				'bank_name' => $bank_name,
				'bank_id' => $bank_id,
				"last_digit" => $last_digit,
				"charges_enable" => $charges_enable,
				"transfer_enable" => $transfers_enabled
			));
		}
		catch (Stripe\Error\Base $e)
		{
			// Code to do something with the $e exception object when an error
			// occurs
			$error_msg = ($e -> getMessage());
			$data = json_encode(array(
				"status" => 0,
				"message" => $error_msg
			));

		}
		return $data;

	}

	function DOCverify($DocDetail)
	{

		$account_id = $DocDetail['acc_id'];
		$file = $DocDetail['file'];

		$account = \Stripe\Account::retrieve($account_id);
		//$account -> legal_entity -> personal_id_number = 123456789;
		$account -> legal_entity -> verification -> document = $file;
		$result = $account -> save();

		$result = $result -> __toArray(true);

		if (key_exists("error", $result))
		{

			$docverify = array(
				"status" => 0,
				"message" => $result['error']['message'],
				"type" => $result['error']['type']
			);
			return $docverify;
		}
		else
		{
			// echo $result['id'];
			// echo $result['legal_entity']['verification']['status'];
			// echo $result['legal_entity']['verification']['document'];
			$docverify = array(
				"status" => 1,
				"verify_status" => $result['legal_entity']['verification']['status'],
				"doc" => $result['legal_entity']['verification']['document'],
				"acc_id" => $result['id'],
			);
			return $docverify;
		}
	}

	function stripe_fileupload($FileDetail)
	{
		$account_id = $FileDetail['acc_id'];
		$file_name = $FileDetail['file_path'];
		$result = \Stripe\FileUpload::create(array(
			"purpose" => "identity_document",
			"file" => fopen($file_name, 'r')
		), array("stripe_account" => $account_id));

		$result = $result -> __toArray(true);
		//print_r($result);
		if (key_exists("error", $result))
		{
			$fileuploadresult = array(
				"status" => 0,
				"type" => $result['error']['type'],
				"message" => $result['error']['messge']
			);
			return $fileuploadresult;
		}
		else
		{
			$fileuploadresult = array(
				"status" => 1,
				"file" => $result['id'],
				"message" => "success"
			);
			return $fileuploadresult;
		}
	}

	function DeleteCustomer($acc_id)
	{

		$account = \Stripe\Account::retrieve($acc_id);
		$result = $account -> delete();
		$result = $result -> __toArray(true);
		if ($result['deleted'])
		{
			return true;
		}
		else
		{
			return FALSE;
		}
	}

	function CreateCustomer($data)
	{
		$result = \Stripe\Customer::create(array(
			"source" => $data['token'] // obtained with Stripe.js
		));

		$result = $result -> __toArray(true);
		//print_r($result);

		// echo $result['id'];
		// echo "<br>" . $result['brand'];
		// echo "<br>" . $result['last4'];
		// echo "<br/>customer_id" . $result['customer'];
		if (key_exists("error", $result))
		{
			$fileuploadresult = array(
				"status" => 0,
				"type" => $result['error']['type'],
				"message" => $result['error']['message']
			);
			return $fileuploadresult;
		}
		else
		{
			$result = $result['sources']['data'][0];
			//echo "<pre>";
			//print_r($result);
			$fileuploadresult = array(
				"status" => 1,
				"card_id" => $result['id'],
				"customer_id" => $result['customer'],
				"brand" => $result['brand'],
				"last4" => $result['last4'],
				"message" => "success"
			);
			return $fileuploadresult;
		}

	}
	
		function InvoiceAllCustomer()
	{
		 
		
	//$result = \Stripe\Charge::retrieve("ch_19NcClKcUTaOjtNAb1EN6Igu");
		//echo "<pre>";
//print_r($result);
//exit;	
$result =\Stripe\ApplicationFee::all();
		echo "<pre>";
print_r($result);
exit;
		$result = $result -> __toArray(true);
		
		
		//print_r($result);

		// echo $result['id'];
		// echo "<br>" . $result['brand'];
		// echo "<br>" . $result['last4'];
		// echo "<br/>customer_id" . $result['customer'];
		if (key_exists("error", $result))
		{
			$fileuploadresult = array(
				"status" => 0,
				"type" => $result['error']['type'],
				"message" => $result['error']['message']
			);
			return $fileuploadresult;
		}
		else
		{
			$result = $result['sources']['data'][0];
			//echo "<pre>";
			//print_r($result);
			$fileuploadresult = array(
				"status" => 1,
				"card_id" => $result['id'],
				"customer_id" => $result['customer'],
				"brand" => $result['brand'],
				"last4" => $result['last4'],
				"message" => "success"
			);
			return $fileuploadresult;
		}

	}

	function Refund($payment_code)
	{
		try
		{

			$data = \Stripe\Refund::create(array("charge" => $payment_code));
		}
		catch (Stripe\Error\Base $e)
		{
			// Code to do something with the $e exception object when an error
			// occurs
			$error_msg = ($e -> getMessage());
			$data = json_encode(array(
				"status" => 0,
				"message" => $error_msg
			));
		}
		/*echo "<pre>";
		 $response =json_decode($data,true);
		 print_r($response);*/
		return $data;
	}

	function createtoken($data)
	{
		$result = \Stripe\Token::create(array("customer" => $data['customer_id']), //,
		// "card" => "card_18ZdGuC09WU9erINxmZ2q98w")
		array("stripe_account" => $data['acc_id']) // id of the connected account
		);

		$result = $result -> __toArray(true);

		if (key_exists("error", $result))
		{
			$tokenresponse = array(
				"status" => 0,
				"type" => $result['error']['type'],
				"message" => $result['error']['message']
			);
			return $tokenresponse;
		}
		else
		{
			$tokenresponse = array(
				"status" => 1,
				"token" => $result['id']
			);
			return $tokenresponse;
		}
	}

	function ChainTransaction($data)
	{
		$ownersent_price_amt = 0;
		$token = $data['token'];
		

		//$ownersent_price_amt = (($data['amount'] * OWNER_AMOUNT) / 100);
		//$ownersent_price_amt = number_format($ownersent_price_amt, 2);
	//	$ownersent_price_amt = $ownersent_price_amt * 100;

		$product_infodata = 'product  id=' . $data['product_id'];
		// Create the charge on Stripe's servers - this will charge the user's card
		$charge = \Stripe\Charge::create(array(
			"amount" => $data['amount'] * 100, // amount in cents
			"currency" => "usd",
			"source" => $token,			
			"description" => $product_infodata,
			"application_fee" => $ownersent_price_amt // amount in cents
		), array("stripe_account" => $data['acc_id']));
		


		$result = $charge -> __toArray(true);
		if (key_exists("error", $result))
		{
			$chargeresponse = array(
				"status" => 0,
				"type" => $result['error']['type'],
				"message" => $result['error']['message'],
				"charge_id" => "",
				"app_fee_id" => "",
				"tranaction_id" => "",
				"token"=>$token,
				"amount"=>"",
				"app_fee"=>$ownersent_price_amt,
				"payment_response"=>$result['error']['message']
			);
			return $tokenresponse;
		}
		else
		{
		
			$chargeresponse = array(
				"status" => 1,
				"charge_id" => $result['id'],
				"app_fee_id" => $result['application_fee'],
				"tranaction_id" => $result['balance_transaction'],
				"token"=>$token,
				"amount"=>$data['amount'],
				"app_fee"=>$ownersent_price_amt,
				"payment_response"=>"success"
			);
             return $chargeresponse;
		}
	}
?>
