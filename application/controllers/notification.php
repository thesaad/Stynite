<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this -> load -> helper(array('form', 'url', 'notification_helper', 'function_helper'));
		$this -> load -> model('notification_model');
		$this -> load -> model('admin_model');
		$this -> load -> library(array('session'));

		$user = $this -> session -> userdata('logged_in');
		if (!$user) {
			redirect('login/index');
		}

	}

	public function index() {

		$this -> load -> view('site-nav/header', TRUE);
		$this -> load -> view('send_notification');
		$this -> load -> view('site-nav/footer');
	}
	// request notice //
	function request_notice() {
		$device_type = 'ALL';
		$product_id = $product_link = "";
		if(isset($_POST['product_id'])){
			$product_id = $_POST['product_id'];
			$product_info = $this -> admin_model -> get_products($product_id);
			
			$product_link = site_url("product/detail")."?id=".md5($product_id); 
		}
		
		$message_text = $_POST['message'];
		$device_tokens_i = array();
		$registration = array();
		 $inputdata = array('message'=>$message_text, 'type'=>$_POST['type'],
		 "product_id"=>$product_id);
		$response = $this->notification_model->save_requested_notice($inputdata);
		 if($response){
		 	echo 1;
		 }else{
		 	echo 0;
		 }
	}
	
	function send_retailernotice()
	{
		//code start
		$product_link ='';
		$noticeid = $_POST['id'];
		$noticeresult = $this -> notification_model -> get_notification($noticeid);
		if(count($noticeresult)>0){
		$product_id =	$noticeresult[0]['product_id'];
		$message = $noticeresult[0]['notification'];
		$type = $noticeresult[0]['notification_type'];
		//code end
		$device_type = 'ALL';
		$product_id = $product_link = "";
		if($product_id>0){
			 
			$product_info = $this -> admin_model -> get_products($product_id);
			
			$product_link = site_url("product/detail")."?id=".md5($product_id); 
		}
		
		$message_text = $message;
		$device_tokens_i = array();
		$registration = array();
		 $otherdata['data'] = array('actor_id'=>'', 'action'=>$type,
		"post_id"=>'',"product_id"=>$product_id,"product_link"=>$product_link);
		if ($device_type == 'ALL') {
			$device = $this -> notification_model -> fetch_all_device($type);
			if ($device['iphone']) {

				foreach ($device['iphone'] as $device_tokens) {
					if ($device_tokens['device_token'] == "") {
						continue;
					} else {
						$device_tokens_i[] = $device_tokens['device_token'];
					}

				}
 try {
			 	
				send_push($device_tokens_i, $message_text,$otherdata);
				} catch (Exception $e) {
  //alert the user.
// var_dump($e->getMessage());
}
			} 
			if ($device['andorid']) {

				

				foreach ($device['andorid'] as $device_tokens) {
					$registration[] = $device_tokens['device_token'];

				}
				try {
				//print_r($registration);
				send_andorid_push($message_text, $registration,$otherdata);
				} catch (Exception $e) {
  //alert the user.
// var_dump($e->getMessage());
}
			}
			echo 1;
		} 
		}else{
			echo 0;
		}
	}

	/*send push notification function */
	function push_notify() {
		$device_type = 'ALL';
		$product_id = $product_link = "";
		if(isset($_POST['product_id'])){
			$product_id = $_POST['product_id'];
			$product_info = $this -> admin_model -> get_products($product_id);
			
			$product_link = site_url("product/detail")."?id=".md5($product_id); 
		}
		
		$message_text = $_POST['message'];
		$device_tokens_i = array();
		$registration = array();
		 $otherdata['data'] = array('actor_id'=>'', 'action'=>$_POST['type'],
		"post_id"=>'',"product_id"=>$product_id,"product_link"=>$product_link);
		if ($device_type == 'ALL') {
			$device = $this -> notification_model -> fetch_all_device($_POST['type']);
			if ($device['iphone']) {

				foreach ($device['iphone'] as $device_tokens) {
					if ($device_tokens['device_token'] == "") {
						continue;
					} else {
						$device_tokens_i[] = $device_tokens['device_token'];
					}

				}
 try {
			 	
				send_push($device_tokens_i, $message_text,$otherdata);
				} catch (Exception $e) {
  //alert the user.
// var_dump($e->getMessage());
}
			} 
			if ($device['andorid']) {

				

				foreach ($device['andorid'] as $device_tokens) {
					$registration[] = $device_tokens['device_token'];

				}
				try {
				//print_r($registration);
				send_andorid_push($message_text, $registration,$otherdata);
				} catch (Exception $e) {
  //alert the user.
// var_dump($e->getMessage());
}
			}
			echo 1;
		} 
		
		/*
		else if ($device_type == 'SPECIFIC') {

			$usersids = implode(",", $_POST['selected_users']);
			/////
			$device = $this -> notification_model -> fetch_device_selecteduser($usersids);

			foreach ($device as $device_tokens) {
				if ($device_tokens['device_type'] == 'A') {
					$registration[] = $device_tokens['device_token'];
					send_andorid_push($message_text, $registration);
				} else if ($device_tokens['device_type'] == 'I') {
					$registration[] = $device_tokens['device_token'];
					send_push($registration, $message_text);
				}
				$registration = array();

			}

			echo 1;

			///
		}
		

		$device = $this -> notification_model -> fetch_device($device_type);

		$device_typ = $device[0]['device_type'];

		if ($device_typ == 'A') {
			foreach ($device as $device_tokens) {
				$registration[] = $device_tokens['device_token'];

			}
			send_andorid_push($message_text, $registration);
			echo 1;
		} elseif ($device_typ == 'I') {

			foreach ($device as $device_tokens) {
				$device_tokens_i[] = $device_tokens['device_token'];

			}
			send_push($device_tokens_i, $message_text);
			echo 1;
		}*/
	}

}
