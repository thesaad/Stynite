<?php

	class Bankdetail_model extends CI_Model
	{

		public function __construct()
		{
			$this -> load -> database();
		}

		function SaveDoc()
		{
			 	$retailer_id = $this->session->userdata('id');
			$insert_array = array(
				"retailer_id" => $retailer_id,
				"filename" => $_POST['business_photo_name'],
				"createdon" => get_gmt_time()
			);
			$insert = $this -> db -> insert("retailer_verifydoc", $insert_array);
			if ($this -> db -> insert_id())
			{
				return $this -> db -> insert_id();
			}
			else
			{
				return false;
			}

		}

		function GetStripeAccountId($user_id)
		{
			$query = $this -> db -> query("SELECT account_id FROM  stripe_customer  where  retailer_id=$user_id");
			$this->db->last_query();
			if ($query -> num_rows())
			{
				$data =$query-> row_array();
				return $data['account_id'];
			}
			else
			{
				return false;
			}
		}

		function GetFile($user_id, $file_id)
		{
			$query = $this -> db -> query("select filename from retailer_verifydoc where id=$file_id and retailer_id=$user_id ");
			if ($query -> num_rows())
			{
				$data = $query -> row_array();
				$filename = $data['filename'];
				if (file_exists("upload/doc/" . $filename))
				{
					return "upload/doc/" . $filename;
				}
				else
				{
					return false;
				}

			}
			else
			{
				return false;
			}
		}

		function UpdateStatus($input_method)
		{
				$retailer_id = $this->session->userdata('id');
			$update_str = array(
				"status" => $input_method['status'],
				"reason" => $input_method['reason'],
				"doc" => $input_method['doc']
			);
			$this->db->where("retailer_id",$retailer_id);
			$res = $this -> db -> update("stripe_customer", $update_str);
			if ($res)
			{
				return true;
			}
			else
			{
				return false;
			}

		}

		function savestripe_detail($saveaccount_response, $user_id)
		{
			$retailer_id = $this->session->userdata('id');
			$arr_feilds = array(
				'retailer_id' => $retailer_id,
				'account_id' => $saveaccount_response['stripe_accountid'],
				'bank_name' => $saveaccount_response['bank_name'],
				'bank_id' => $saveaccount_response['bank_id'],
				'charge_enable' => $saveaccount_response['charges_enable'],
				'transfer_enable' => $saveaccount_response['transfer_enable'],
				'status' => $saveaccount_response['message'],
				'last_digit' => $saveaccount_response['last_digit']
			);
			$insertuseracc = $this -> db -> insert('stripe_customer', $arr_feilds);
			$insertid = $this -> db -> insert_id();
			/*if ($insertid)
			{
				$arr_feilds = array('stripe_customer_id' => $insertid);
				$this -> db -> where('id', $user_id);
				$this -> db -> update('retailer', $arr_feilds);
				$update_id = $this -> db -> affected_rows();
			}*/
			return $insertid;
		}

 /// save bank detail
 
 		function saveretailer_bankdetail($input)
		{
			$retailer_id = $this->session->userdata('id');
			$this->db->where("retailer_id",$retailer_id);
			$resultrow = $this->db->get("retailer_bankinfo");
			
			
			date_default_timezone_set("UTC");
		$current=gmdate('Y-m-d H:i:s O');
		
 
		
		
			$arr_feilds = array(
				'retailer_id' => $retailer_id,
				'routing_number' => $input['routing_number'],
				'account_number' => $input['account_number'],
				'first_name' => $input['first_name'],
				'last_name' => $input['last_name'],
				'b_day' => $input['day'],
				'b_month' => $input['month'],
				'b_year' => $input['year'],
				
				'address' => $input['address'],
				'postal_code' => $input['postal_code'],
				'city' => $input['city'],
				'state' => $input['state'],
				'country' => $input['country'],
				'ssn' => $input['ssn'],
				'personal_id' => $input['personal_id'],
				'ip_address' => $input['ip'],
				'updated_on' => $current
			);
			if($resultrow->num_rows()>0){
				$this->db->where("retailer_id",$retailer_id);
		      $res = $this -> db -> update("retailer_bankinfo", $arr_feilds);
			}else{
					$insertuseracc = $this -> db -> insert('retailer_bankinfo', $arr_feilds);
			$res = $this -> db -> insert_id();
			}
			/*if ($insertid)
			{
				$arr_feilds = array('stripe_customer_id' => $insertid);
				$this -> db -> where('id', $user_id);
				$this -> db -> update('retailer', $arr_feilds);
				$update_id = $this -> db -> affected_rows();
			}*/
			return $res;
		}
 
 
 ///end of bank detail
 
     
		function get_userdeviceinfo($user_id)
		{
			$query = $this -> db -> query("select * from user_device where user_id='$user_id'");
			$user_data = $query -> result_array();
			// print_r($user_data);
			return $user_data;
		}
		function get_retailer_bankdetail()
		{
			$retailer_id = $this->session->userdata('id');
			
			$this->db->select("retailer_bankinfo.*,stripe_customer.status");
			$this->db->where("retailer_bankinfo.retailer_id",$retailer_id);
			$this->db->join("stripe_customer","stripe_customer.retailer_id = retailer_bankinfo.retailer_id","left");
			$resultrow = $this->db->get("retailer_bankinfo");
			$user_data = $resultrow -> result();
			// print_r($user_data);
			return $user_data;
		}

		function get_jobname($job_id)
		{
			$query = $this -> db -> query("select * from job_post where id='$job_id'");
			$job_data = $query -> result_array();
			// print_r($user_data);
			return $job_data;
		}

	}
?>
