<?php
class Shop_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }
    function getStyniteRetailers()
	{
		$this->db->where("is_admin","0");
		$query = $this->db->get("retailer");
		return $query->result_array();
	}
	function getShopRetailer()
	{
		$this->db->where("imgset","1");
		$query = $this->db->get("shop_brand");
		return $query->result_array();
	}
		function saveCamfindWord($input,$id=0)
	{
		 	$date =gmdate("Y-m-d H:i:s");
	
		 
        $arr_field = array(
                           "image_id"=>$id,
                             "find_keyword" => $input['keyword'], 
                             "createdon" => $date
                            );
          $this->db->insert('camfind_keywords', $arr_field);
            $id = $this->db->insert_id();
           return true;
	}
			function saveCamfindImage($filename)
	{
		 	$date =gmdate("Y-m-d H:i:s");
	
		 
        $arr_field = array(
                           
                             "imagename" => $filename, 
                             "createdon" => $date
                            );
          $this->db->insert('camfind_image', $arr_field);
            $id = $this->db->insert_id();
           return $id;
	}
	
     function getStyniteShopProduct($input)
	{
		$user_id = 0;
		$more=$keyword="";
		$result = array();
		if($input['keyword']!=""){
		$keyword = mysql_real_escape_string($input['keyword']);
		}else if($input['category']!=""){
			//$keyword = $this->db->escape($input['category']);
			$keyword = $input['category'];
		}else{
			$keyword = $input['brand'];
		}
		if(isset($input['user_id'])){
			$user_id = $input['user_id'];
		}
		
		
	  $keyword =  $this->getNewKeywordStynite($keyword); 
	//exit;	
	 $query	= $this->db->query("select products.*,product_images.imagename from products left join product_images
		on product_images.product_id = products.id left join retailer on retailer.id =  products.retailer_id
		where products.title like '%$keyword%' group by products.id ");
		//echo $this->db->last_query();exit;
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row) {
				//print_r($row);
				$this->productAction($row['id'],'SEARCHED',$user_id);
			$product_img =	get_retailerproduct_image($row['imagename']);
				$result[]= array("strProductId"=>$row['id'],
				"strProductName"=>$row['title'],
				"strLink"=>site_url("product/detail")."?id=".md5($row['id'])."&user_id=".md5($user_id) ,
				"strImageURL"=>$product_img,
				"strStorePrice"=>$row['price'],
				"strStoreName"=>"Stynite"
				);
			}
			
		}
		//print_r($result);
		return $result;
	}

function check_adultkeyword($keyword)
{
      $keyword = strtolower($keyword); 
	$keyword =  $this->getNewKeyword($keyword); 
    //check for adult content
     $adult_content = 0;
    $adquery="select * from adult_keywords";
    $adresponse=$this->db->query($adquery);
   foreach ($adresponse->result_array() as $adinfo ) {
     
        /*echo $color_word=$info['color'];
        echo "-";
        if(strpos($keyword,$color_word)>=0){
            echo $complete_word=strstr($keyword,$color_word);
            echo "<br />";
            if(strlen($complete_word)==strlen($color_word)){    //means that thare are no other characters
                $keyword=str_replace($color_word." ",'',$keyword);
            }
        }*/
        $adult_word=trim($adinfo['keyword']);
        $keyword_array=explode(" ",$keyword);
        foreach($keyword_array as $key=>$value){
            if($value){
                if($value==$adult_word){
                    $adult_content = 1;
                    
                   $keyword=str_replace($value,'',$keyword);         
                }
            }
        }
        
       
        
    }
    //echo $keyword;exit;
    return $keyword;
    //end of check content
    
}
function getNewKeywordStynite($keyword)
{
	$query =$this->db->query("SELECT * FROM `camfind_keywords`  WHERE 	custom_keywords like '%".$keyword."%'
	or selected_custum_keywords like '%".$keyword."%'
	");
	 
	if($query->num_rows()>0){
		 $row = $query->row_array();
		return $row['stynite_keyword'];
	}
	return $keyword;
}


function getNewKeyword($keyword)
{
	$query =$this->db->query("SELECT * FROM `camfind_keywords`  WHERE 	custom_keywords like '%".$keyword."%'
	or selected_custum_keywords like '%".$keyword."%'
	");
	 
	if($query->num_rows()>0){
		 $row = $query->row_array();
		return $row['affiliate_keyword'];
	}
	return $keyword;
}
  function savekeyword_forrecommendation($keyword,$user_id=0)
  {
  	$id = 0;
  	$date =gmdate("Y-m-d H:i:s");
  	$arr_field = array(
                            "keyword" => $keyword,
                             "user_id" => $user_id,
                              "action_by" => 'DEFAULT',
                            "updatedon"=>$date 
                            );
	$this->db->order_by("updatedon","asc");						
  	$this->db->where("user_id",$user_id);
	$chkqry = $this->db->get("keywords_recommendation");
	if($chkqry->num_rows()<3){
		$this->db->insert('keywords_recommendation', $arr_field);
           $this->db->insert_id();
	}else{
		 foreach ($chkqry->result() as $row) {
			 if($row->action_by=="DEFAULT"){
			 	if($row->keyword==$keyword){
			 		return true;
			 	}
			 	$id=$row->id;
			 	break;
			 }
		 }
		 if($id>0){
		 $this->db->limit(1);
		 $this->db->where('id', $id);
		  $this->db->where('action_by', 'DEFAULT');
		 $this->db->update('keywords_recommendation', $arr_field);
		 }
	}
  }
	
	function getStyniteRecommendationProduct($productarr)
	{
		// need changes 
	 $result = array();
	 $ids = implode(",", $productarr);
		
	 $query	= $this->db->query("select products.*,product_images.imagename from products left join product_images
		on product_images.product_id = products.id left join retailer on retailer.id =  products.retailer_id
		where products.id in (".$ids.") group by products.id ");
	//	echo $this->db->last_query();
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row) {
				//print_r($row);
				
			$product_img =	get_retailerproduct_image($row['imagename']);
				$result[]= array("strProductId"=>$row['id'],
				"strProductName"=>$row['title'],
				"strLink"=>site_url("product/detail")."?id=".md5($row['id'])."&user_id=".md5($user_id) ,
				"strImageURL"=>$product_img,
				"strStorePrice"=>$row['price'],
				"strStoreName"=>"Stynite"
				);
			}
			
		}
		//print_r($result);
		return $result;
	}
	function getAffilateRecommendationProduct($productarr)
	{
		$result = array();
		 $ids = implode(",", $productarr);
		
			 $query	= $this->db->query("SELECT online_products.* FROM `online_products`
		where online_products.id in (".$ids.") group by online_products.id ");
		//echo $this->db->last_query();exit;
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row) {
			 
				$result[]= array("strProductId"=>$row['product_id'],
				"strProductName"=>$row['product_name'],
				"strLink"=> $row['product_link'],
				"strImageURL"=>$row['product_image_link'],
				"strStorePrice"=>$row['product_price'],
				"strStoreName"=>$row['retailer_name'],
				);
			}
			
		}
		//print_r($result);
		return $result;
	}
	
	function productAction($product_id,$action,$user_id=0)
	{
		 	$date =gmdate("Y-m-d H:i:s");
		$this->db->where("recommendation",'2');
		$this->db->where("product_id",$product_id);
		$this->db->where("user_id",$user_id);
	 $chkquery =	$this->db->get("product_analyse");
	 if($chkquery->num_rows()>0){
	 	$recommendation = 2;
	 }else{
	 	$recommendation = 0;
	 }
		 
        $arr_field = array(
                            "user_id" => $user_id,
                             "product_id" => $product_id,
                             "action" => $action, 
                              "recommendation"=>$recommendation,
                             "createdon" => $date
                            );
          $this->db->insert('product_analyse', $arr_field);
            $id = $this->db->insert_id();
           return true;
	}
	
		function productActionForOnline($product_id,$action,$user_id=0)
	{
		 	$date =gmdate("Y-m-d H:i:s");
	$this->db->where("recommendation",'2');
		$this->db->where("product_id",$product_id);
		$this->db->where("user_id",$user_id);
	 $chkquery =	$this->db->get("online_product_analyse");
	 if($chkquery->num_rows()>0){
	 	$recommendation = 2;
	 }else{
	 	$recommendation = 0;
	 }
		 
        $arr_field = array(
                            "user_id" => $user_id,
                             "product_id" => $product_id,
                             "action" => $action, 
                              "recommendation"=>$recommendation,
                             "createdon" => $date
                            );
          $this->db->insert('online_product_analyse', $arr_field);
            $id = $this->db->insert_id();
           return true;
	}
	function getOnlineProductId($product_id)
	{
		  $this->db->where("product_id",$product_id);
		  	$qry = $this->db->get("online_products");
		  	 if($qry->num_rows()>0){
		  	 		$result = $qry->row_array();
				 $product_id= $result['id'];
				  return $product_id;
			 }else{
			 	return false;
			 }
			
	}
	/// save shop product
	
		    function save_product_from_online($input,$storename,$country,$user_id)
    {
    	
    	$curdate =gmdate("Y-m-d H:i:s");
		 
		 
        $arr_field = array(
                            "product_id" => $input['strProductId'],
                             "store_name" => $storename,
                             "retailer_name" => $input['strStoreName'], 
                             "product_name" => $input['strProductName'],   
                             "product_link"=>$input['strLink'], 
                            "product_image_link"=>$input['strImageURL'],
                            "product_price"=>$input['strStorePrice'],
                            "country"=>$country,
                            "updatedon"=>$curdate 
                            );
        $this->db->where("product_id",$input['strProductId']);
		  	$qry = $this->db->get("online_products");
       if($qry->num_rows()>0){
       	$result = $qry->row_array();
            $this->db->where('product_id', $input['strProductId']);
            $query = $this->db->update('online_products', $arr_field);
            $product_id= $result['id'];
        } else {
            $this->db->insert('online_products', $arr_field);
         //   echo $this->db->last_query();exit;
            $product_id= $this->db->insert_id();
        }
		$this->productActionForOnline($product_id,'SEARCHED',$user_id);
     return true;

    }
	
	///end of shop product

}
?>