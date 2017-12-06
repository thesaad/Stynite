<?php
class Product_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }
 
   	function productAction($product_id,$action,$user_id=0)
	{
		$this->db->where("recommendation",'2');
		$this->db->where("product_id",$product_id);
		$this->db->where("user_id",$user_id);
	 $chkquery =	$this->db->get("product_analyse");
	 if($chkquery->num_rows()>0){
	 	$recommendation = 2;
	 }else{
	 	$recommendation = 0;
	 }
		 	$date =gmdate("Y-m-d H:i:s");
	
		 
        $arr_field = array(
                            "user_id" => $user_id,
                             "product_id" => $product_id,
                             "action" => $action, 
                             "recommendation"=>$recommendation,
                             "createdon" => $date
                            );
          $this->db->insert('product_analyse', $arr_field);
		  $this->update_product_quantity($product_id);
		  //echo $this->db->last_query(); 
           // exit;
           return true;
	}
	function update_product_quantity($product_id){
		$this->db->query("update products set quantity = (quantity-1) where id = '$product_id'");
		return true;
	}
    function get_products($id="",$action="")
    {
      
        $query = $this->db->query("select products.*,product_images.imagename from products left join product_images
        on product_images.product_id = products.id where md5(products.id)='$id' and products.hide = '0'");
        //echo $this->db->last_query();exit;
        
          if(isset($_REQUEST['user_id'])){
          	 $product= $query->row_array();
        	$user_id = $_REQUEST['user_id'];
			$result = $this->db->query("select id from users where md5(id)='$user_id'");
			if($result->num_rows()>0){
			$row=	$result->row();
				$this->productAction($product['id'],$action,$row->id);
			}
        
		}
        return $query->row_array();
    }
	function getUseridByMd5($user_id)
	{
	 $id = 0;
			$result = $this->db->query("select id from users where md5(id)='$user_id'");
			if($result->num_rows()>0){
			$row=	$result->row();
			$id  =$row->id;
			}
			return $id;	
	}
	
	function save_transaction_info($input_method)
		{

			$update = get_gmt_time();
		 
			$arr_feilds = array(
				'user_id' => $input_method['user_id'],
		//		"application_fee_id" => $input_method['app_fee_id'],
				"amount" => $input_method['amount'],
				//"application_fee" => $input_method['app_fee'],
				"token" => $input_method['token'],
				'balance_ch_id' => $input_method['balance_ch_id'],
				'balance_transaction_id' => $input_method['balance_transaction_id'],
				'product_id' => $input_method['product_id'],
				'payment_response' => $input_method['payment_response'],
				'createdon' => $update
			);
			// print_r($arr_feilds);
			$this -> db -> insert('tb_payment', $arr_feilds);
			$insert = $this -> db -> insert_id();
			if($insert){
				$this->productAction($input_method['product_id'],'BUY',$input_method['user_id']);
				$this->productQuantityUpdate($input_method['product_id']);
			}
			return $insert;
		}
	function productQuantityUpdate($product_id)
	{
		$this->db->query("update  products set quantity = (quantity-1) where id = '$product_id' ");
	}	
    
    function getProductRetailerAccount($product_id)
	{
	$query	= $this->db->query("select account_id from stripe_customer join products on 
		products.retailer_id = stripe_customer.retailer_id where products.id = '$product_id' ");
		if($query->num_rows()>0){
		$row	=$query->row();
			return $row->account_id;
		}else{
			return '';
		}
	}
	 
	
   
}
?>