<?php
class Users_panel_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }
	function get_users_dt()
	{
				       $requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'username',
	2 =>'firstname',
	3=> 'lastname',
	4 =>'email',
	5 =>'gender',
	6 =>'contact',
	7 =>'createdon',
	8 =>'id',
	9 =>'id',
);

// getting total number records without any search
$sql = "SELECT * FROM `users`  ";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT * FROM `users` 
  where 1=1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( username LIKE '%".$requestData['search']['value']."%' ";  
$sql.=" OR firstname LIKE '%".$requestData['search']['value']."%' ";  

$sql.=" OR lastname LIKE '%".$requestData['search']['value']."%' ";  
$sql.=" OR email LIKE '%".$requestData['search']['value']."%' ";  
$sql.=" OR gender LIKE '%".$requestData['search']['value']."%' ";  
$sql.=" OR contact LIKE '%".$requestData['search']['value']."%' ";  
	$sql.=" OR createdon LIKE '%".$requestData['search']['value']."%' )";
}
$query=$this->db->query($sql);
$totalFiltered = $query->num_rows(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql;
//exit;
$query=$this->db->query($sql);

$data = array();

 // preparing an array
 $i=($requestData['start']+1);
foreach ($query->result() as $row){
	$nestedData=array(); 
	     
           $id=$row->id;
		  date_default_timezone_set('UTC');
		   $updatedon_timestamp = strtotime($row->createdon);
		   $updatedon = date('d/m/y h:i A', $updatedon_timestamp);
		  if($row->status==0){
		  	$status='<a title="Deactive" onclick="delete_user('.$row->id.')" class="label label-danger" style="background-color:#ff9999;"><i class="icon-thumbs-down"></i></a> 
	   ';
		  }else{
		  		$status='<a title="Active" onclick="delete_user('.$row->id.')" class="label label-danger" style="background-color:#99ff99;"><i class="icon-thumbs-up"></i></a> 
	   ';
		  }
		    
	$nestedData[] = $i;
 $nestedData[] = $row->username;
	$nestedData[] = $row->firstname;
	$nestedData[] = $row->lastname;
	$nestedData[] = '<a href="mailto:'.$row->email.'" >'.$row->email.'</a>';
	 $nestedData[] = $row->contact;
	 $nestedData[] = $row->gender;
	 
		$nestedData[] = '<a href="'.get_user_image($row->image).'" ><img src="'.get_user_image($row->image).'" style="height:50px;width:50px;"></a>';
	  
	   $nestedData[] = $updatedon;
	   $nestedData[] = $status;
	   
$nestedData[]= '<a title="Edit" href="'.site_url("user/editprofile")."?id=".$row->id.'" class="label label-warning" style="background-color:cyan;"><i class="icon-edit"></i></a>&nbsp;|&nbsp;&nbsp;

	<a title="Stynite Products" href="'.site_url("user/products")."?id=".$row->id.'" class="label label-warning"><i class="icon-shopping-cart"></i></a>&nbsp;|&nbsp;&nbsp;
	<a title="Affilate Products" href="'.site_url("user/affilateproducts")."?id=".$row->id.'" class="label label-success"><i class="icon-shopping-cart"></i></a>&nbsp;|&nbsp;&nbsp;
		<a title="Posts activity" href="'.site_url("user/postsactivity")."?id=".$row->id.'" class="label label-primary"><i class="icon-zoom-in"></i></a> |&nbsp;   
		<a  onclick="user_recommendation('.$id.')" data-toggle="modal" data-target="#myModal" style="cursor:pointer; background-color:#ff6699;"  class="label label-primary"  data-id="'.$id.'"   title="Recommendation Settings"><i class="icon-bullhorn"></i></a>
		';
	
 
	$data[] = $nestedData;
	$i++;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);
//print_r($json_data);
return $json_data;
//echo json_encode($json_data);  // send data as json format
	  
	}
	
	   function get_a_user($id)
   {
   	$this->db->where("id",$id);
	$query=$this->db->get("users");
	
   	return $query->result_array();
   }
   function user_recommendation_keywords($user_id)
   {
   		$this->db->where("user_id",$user_id);
	$query=$this->db->get("keywords_recommendation");
	
   	return $query->result_array();
   }
   
   		
   	function recommendation_action()
		{
			$qry=false;
			$realeasedate =gmdate("Y-m-d H:i:s");
			 $action = $_POST['action'];
			 $user_id = $_POST['user_id'];
			if(count($_POST['product_id'])){
			$product_ids = 	implode(",", $_POST['product_id']);
				if($_POST['action']=='2'){
				$qry=	$this->db->query("update  product_analyse set 	recommendation = '$action',updatedon = '$realeasedate' where product_id in (".$product_ids.") and user_id = '$user_id'");
			
				}else if($_POST['action']=='1')
				{
				$qry=	$this->db->query("update  product_analyse set 	recommendation = '$action',updatedon = '$realeasedate' where product_id in (".$product_ids.") and user_id = '$user_id'");
				
				} 
				if($qry){
				return true;
			}
			}
			return false;
		}

      	function affilate_recommendation_action()
		{
			$qry=false;
			$realeasedate =gmdate("Y-m-d H:i:s");
			 $action = $_POST['action'];
			 $user_id = $_POST['user_id'];
			if(count($_POST['product_id'])){
			$product_ids = 	implode(",", $_POST['product_id']);
				if($_POST['action']=='2'){
				$qry=	$this->db->query("update  online_product_analyse set 	recommendation = '$action',updatedon = '$realeasedate' where product_id in (".$product_ids.") and user_id = '$user_id'");
			
				}else if($_POST['action']=='1')
				{
				$qry=	$this->db->query("update  online_product_analyse set 	recommendation = '$action',updatedon = '$realeasedate' where product_id in (".$product_ids.") and user_id = '$user_id'");
				
				} 
				if($qry){
				return true;
			}
			}
			return false;
		}
		
		
	function get_stynite_recommendation($user_id)
	{
		$adminrecommendation = 0;
		$product_ids = array();
		 
		$query = "SELECT product_analyse.* FROM `product_analyse`  join products on products.id = product_analyse.product_id WHERE user_id='$user_id' and recommendation = '1' group by product_id";
		$result = $this->db->query($query);
		if($result->num_rows()>0){
		 $adminrecommendation	= $result->num_rows();
			foreach ($result->result_array() as $raw) {
				$product_ids[]=$raw['product_id'];
				
			}
			
		}
		if($adminrecommendation<10){
       $limit = (10 - $adminrecommendation);
	    	$query = "SELECT product_analyse.* FROM `product_analyse`  join products on products.id = product_analyse.product_id WHERE user_id='$user_id' and recommendation='0' group by product_id  order by product_analyse.id desc limit ".$limit;
		$result = $this->db->query($query);
		if($result->num_rows()>0){
		 $adminrecommendation	= $result->num_rows();
			foreach ($result->result_array() as $raw) {
				$product_ids[]=$raw['product_id'];
				
			}
			 
		}
        }
		if(count($product_ids)>0){
        $ids = implode(",", $product_ids);
        $this->db->query("update product_analyse set temp_recommendation='0' where user_id='$user_id'");
		$this->db->query("update product_analyse set temp_recommendation='1' where user_id='$user_id' and product_id in(".$ids.")");
		}
		return $product_ids;
	}	

function get_affiliate_recommendation($user_id)
{
			$adminrecommendation = 0;
		$product_ids = array();
		 
		$query = "SELECT online_product_analyse.* FROM `online_product_analyse`  join online_products on online_products.id = online_product_analyse.product_id WHERE user_id='$user_id' and recommendation = '1' group by product_id";
		$result = $this->db->query($query);
		if($result->num_rows()>0){
		 $adminrecommendation	= $result->num_rows();
			foreach ($result->result_array() as $raw) {
				$product_ids[]=$raw['product_id'];
				
			}
			
		}
		if($adminrecommendation<10){
       $limit = (10 - $adminrecommendation);
	    	$query = "SELECT online_product_analyse.* FROM `online_product_analyse`  join online_products on online_products.id = online_product_analyse.product_id WHERE user_id='$user_id' and recommendation='0' group by product_id  order by online_product_analyse.id desc limit ".$limit;
		$result = $this->db->query($query);
		if($result->num_rows()>0){
		 $adminrecommendation	= $result->num_rows();
			foreach ($result->result_array() as $raw) {
				$product_ids[]=$raw['product_id'];
				
			}
			 
		}
        }
		if(count($product_ids)>0){
        $ids = implode(",", $product_ids);
        $this->db->query("update online_product_analyse set temp_recommendation='0' where user_id='$user_id'");
		$this->db->query("update online_product_analyse set temp_recommendation='1' where user_id='$user_id' and product_id in(".$ids.")");
		}
		return $product_ids;
}	
   
   		function get_userproducts_dt()
	{
		       $requestData= $_REQUEST;
$user_id = $_REQUEST['id'];
$recommendationarr = $this->get_stynite_recommendation($user_id);

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'title',
	2 =>'title',
	3=> 'description',
	4 =>'action_count',
	5 =>'updatedon',
	6 =>'id',
	7 =>'temp_recommendation',
	8 =>'id'
);

// getting total number records without any search
$sql = "SELECT products.id FROM `products`
left join product_analyse on product_analyse.product_id = products.id
 where products.hide = '0' and product_analyse.user_id = '$user_id' group by products.id";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT products.*,count(products.id) as action_count ,product_images.imagename,product_analyse.recommendation  FROM `products` 
left join product_analyse on product_analyse.product_id = products.id
left JOIN product_images on products.id=product_images.product_id
  where products.hide = '0' and product_analyse.user_id = '$user_id' ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( title LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR description LIKE '%".$requestData['search']['value']."%' )";
}
$sql.=" group by products.id ";
$query=$this->db->query($sql);
$totalFiltered = $query->num_rows(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql;
//exit;
$query=$this->db->query($sql);

$data = array();

 // preparing an array
 $i=($requestData['start']+1);
foreach ($query->result() as $row){
	$nestedData=array(); 
	     
           $id=$row->id;
		  date_default_timezone_set('UTC');
		   $updatedon_timestamp = strtotime($row->updatedon);
		   $updatedon = date('d/m/y h:i:s A', $updatedon_timestamp);
		$retailer_id  =$row->retailer_id;
		   
		  $img_name= $row->imagename;
		  $temp="'".$id."','".$img_name."'";
		  $actionby ="";
		   if($row->recommendation=='1'){
		   	 
			$actionby = " <span style='color:red;'>-By Admin</span>";	
		   }else if($row->recommendation=='0'){
		   $actionby = "  <span style='color:blue;'>-By Default</span>";	
		   }else if($row->recommendation=='2'){
		   $actionby = "  <span style='color:red;'>-By Admin</span>";	
		   }else{
		   	$actionby = "  ";
		   }
		  
		  if (in_array($id, $recommendationarr))
  {
  		$class = 'default';
			$label = "On".$actionby;	
  }else{
  	 
		   $label="Off".$actionby;;
		   	$class = 'warning';
			
		   }
  /*
		    if($row->recommendation=='1'){
		   	$class = 'success';
			$label = "On";	
		   }else{
		   	$class = 'warning';
			$label="Off";
		   }
		   */
	$nestedData[] = $i;
	$nestedData[] = '<a href="'.site_url("product/detail")."?id=".md5($row->id).'" ><img src="'.get_retailerproduct_image($row->imagename).'" style="height:100px;width:100px;"></a>';
$nestedData[] = $row->title;
	$nestedData[] = $row->description;
	$nestedData[] = "<label  style='color:#066;font-size:14pt;'>".$row->action_count."</label>";
	$nestedData[] = $updatedon;
	
	  
	$nestedData[]= '<a   href="'.site_url('retailer_store/manage_product').'?id='.$id.'&retailer_id='.$retailer_id.'" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  onclick="product_id('.$id.')" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" data-id="'.$id.'"   title="Send Notification"><i class="icon-bell"></i></a>
               &nbsp;&nbsp;|&nbsp;&nbsp;<a   href="'.site_url('admin/product_statistics').'?id='.$id.'" title="Statistics"><i class="icon-signal"></i></a>     <img src="'.base_url().'/images/loaders/loader19.gif" id="image_'.$id.'" style="display: none;"/>';
	$nestedData[] =  '<span class="label label-'.$class.'">'.$label.'</span>';
	 $nestedData[]= '<input type="checkbox" style="margin:0px;" value="'.$id.'" name="product_id[]" form="recommendationproduct" />';
	
 
	$data[] = $nestedData;
	$i++;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);
//print_r($json_data);
return $json_data;
//echo json_encode($json_data);  // send data as json format
	   
	}
	
	   		function get_useraffilateproducts_dt()
	{
		       $requestData= $_REQUEST;
$user_id = $_REQUEST['id'];

	$recommendationarr = $this->get_affiliate_recommendation($user_id);
		      

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'retailer_name',
	2 =>'store_name',
	3=> 'id',
	4 =>'product_name',
	5 =>'country',
	6 =>'product_price',
	7 =>'actioncount',
	8 =>'updatedon',
	9 =>'id',
	10 =>'temp_recommendation',
	11 =>'id'

);

// getting total number records without any search
$sql = "SELECT online_products.id FROM `online_products`
left join online_product_analyse on online_product_analyse.product_id = online_products.id
where 
online_product_analyse.user_id = '$user_id'
 group by online_products.id";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT online_products.* ,count(online_products.id) as actioncount,online_product_analyse.recommendation  FROM `online_products`  
left join online_product_analyse on online_product_analyse.product_id = online_products.id

  where online_product_analyse.user_id = '$user_id' ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( product_name LIKE '%".$requestData['search']['value']."%' ";    
	$sql.="   )";
}
$sql.=" group by online_products.id ";
$query=$this->db->query($sql);
$totalFiltered = $query->num_rows(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql;

$query=$this->db->query($sql);

$data = array();

 // preparing an array
 $i=($requestData['start']+1);
foreach ($query->result() as $row){
	$nestedData=array(); 
	     
           $id=$row->id;
		  date_default_timezone_set('UTC');
		   $updatedon_timestamp = strtotime($row->updatedon);
		   $updatedon = date('d/m/y h:i:s A', $updatedon_timestamp);
		  
		 
	$nestedData[] = $i;
	$nestedData[] = ucfirst($row->retailer_name);
	$nestedData[] = ucfirst($row->store_name);
	$nestedData[] = '<a href="'.$row->product_link.'" ><img src="'.$row->product_image_link.'" style="height:100px;width:100px;"></a>';
$nestedData[] = $row->product_name;
	$nestedData[] = $row->country;
	$nestedData[] = $row->product_price;
 
		$nestedData[] = "<label  style='color:#066;font-size:14pt;'>".$row->actioncount."</label>";
	$nestedData[] = $updatedon;
	
			   if($row->recommendation=='1'){
		   	 
			$actionby = " <span style='color:red;'>-By Admin</span>";	
		   }else if($row->recommendation=='0'){
		   $actionby = "  <span style='color:blue;'>-By Default</span>";	
		   }else if($row->recommendation=='2'){
		   $actionby = "  <span style='color:red;'>-By Admin</span>";	
		   }else{
		   	$actionby = "  ";
		   }
		  
		  if (in_array($id, $recommendationarr))
  {
  		$class = 'default';
			$label = "On".$actionby;	
  }else{
  	 
		   $label="Off".$actionby;;
		   	$class = 'warning';
			
		   }
	  
	$nestedData[]= ' <a   href="'.site_url('retailer_store/onlineproduct_statistics').'?id='.$id.'" title="Statistics"><i class="icon-signal"></i></a> ';
	 $nestedData[] =  '<span class="label label-'.$class.'">'.$label.'</span>';
	 $nestedData[]= '<input type="checkbox" style="margin:0px;" value="'.$id.'" name="product_id[]" form="recommendationproduct" />';
	
 
	$data[] = $nestedData;
	$i++;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);
//print_r($json_data);
return $json_data;
//echo json_encode($json_data);  // send data as json format
	 
	}
	function get_postsactivity_dt()
	{
				       $requestData= $_REQUEST;
$user_id = $_REQUEST['id'];

		      

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'firstname',
	2 =>'id',
	3=> 'post_text',
	4 =>'action',
	5 =>'action_time',
);

// getting total number records without any search
$sql = "SELECT user_action.id FROM `user_action`
where 
user_action.user_id = '$user_id'
";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT user_action.*,user_post.image,user_post.post_text,user_post.post_date,users.firstname,
users.lastname  FROM `user_action`  
left join user_post on user_post.id = user_action.post_id
left join users on users.id = user_post.user_id
  where user_action.user_id = '$user_id' ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( firstname LIKE '%".$requestData['search']['value']."%' ";   
$sql.="OR action LIKE '%".$requestData['search']['value']."%'  "; 
	$sql.="OR post_text LIKE '%".$requestData['search']['value']."%'    )";
}

$query=$this->db->query($sql);
$totalFiltered = $query->num_rows(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql;

$query=$this->db->query($sql);

$data = array();

 // preparing an array
 $i=($requestData['start']+1);
foreach ($query->result() as $row){
	$nestedData=array(); 
	     
           $id=$row->id;
		  date_default_timezone_set('UTC');
		   $updatedon_timestamp = strtotime($row->action_time);
		   $updatedon = date('d/m/y h:i A', $updatedon_timestamp);
		  
		 
	$nestedData[] = $i;
	$nestedData[] =  $row->firstname." ".$row->lastname;
	$nestedData[] = '<a href="'.get_post_image($row->image).'" ><img src="'.get_post_image($row->image).'" style="height:50px;width:50px;"></a>';
$nestedData[] = $row->post_text;
	 
 
		$nestedData[] = "<label  style='color:#066;font-size:10pt;'>".$row->action."</label>";
	$nestedData[] = $updatedon;
	
	 
	$data[] = $nestedData;
	$i++;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);
//print_r($json_data);
return $json_data;
//echo json_encode($json_data);  // send data as json format
	 
	}

  function delete_user()
  {
  	$date =gmdate("Y-m-d H:i:s");
  	$qry = $this->db->query("update users set status = (1-status),updatedon = '$date' where id = (".$_POST['id'].")");
			if($qry){
				return true;
			}
  }
  
  function save_user()
  {
  	 
  	$date =gmdate("Y-m-d H:i:s");
		 
		$dob="0000-00-00";
	$id=  $_POST['id'];
		 if( !empty($_POST['dob']) ) {
				$start	= explode('/', $_POST['dob']);
	if(isset($start[1])){
	$dob = $start[2]."-".$start[0]."-".$start[1];	
	}
		 }
		 if($_POST['email']!=""){
		$this->db->where("id !=$id");
			 $this->db->where("email",$_POST['email']);
		  	$imgqry = $this->db->get("users");
		  	if($imgqry->num_rows()>0){
		  			return false;
		  		}
		 }
        $arr_field = array(
                            "username" => $_POST['username'],
                             "firstname" => $_POST['firstname'],
                              "lastname" => $_POST['lastname'], 
                                 "email" => $_POST['email'],   
                               "contact" => $_POST['contact'],
                             "dob" => $dob,
                              "gender" => $_POST['gender'],
                              "updatedon"=>  $date
                            );
							 
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->update('users', $arr_field);
             
        }  
		
		 
		if($id>0){
			
		  if($_POST['business_photo_name']!=""){
		  		
		  	 $imgarr_field = array(                          
                             "image" => $_POST['business_photo_name']
                            );		
		  		
		  	
		  	$this->db->where("id",$id);
		  	$imgqry = $this->db->get("users");
		  	if($imgqry->num_rows()>0){
		  		$this->db->where("id",$id);
	            $query = $this->db->update('users', $imgarr_field);
	            
				 
		  	} 
		  	
		  }
		  return $id;
		  }
     return false;
	
  }

  function submit_recommendation_keys()
  {
  	$id = $_POST['id'];
  	$date =gmdate("Y-m-d H:i:s");
  	$action_by = "DEFAULT";
  	if($_POST['admin']=='1'){
  		$action_by = "ADMIN";
  	}
	 
  	 $arr_field = array(
                            "keyword" => $_POST['keyword'],
                             "user_id" => $_POST['user_id'],
                              "action_by" => $action_by, 
                             "updatedon" => $date 
                            );
	if($id>0){
		  		$this->db->where("id",$id);
	            $query = $this->db->update('keywords_recommendation', $arr_field);
	            
				 
		  	}else{  $query = $this->db->insert('keywords_recommendation', $arr_field);
           	//clear token
			 
			}
			if($query){
				return true;
			}else{
				return false;
			}
	
  }
	
}
?>