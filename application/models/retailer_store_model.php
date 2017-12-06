<?php
class Retailer_store_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }
   function get_a_retailer($id)
   {
   	$this->db->where("id",$id);
	$query=$this->db->get("retailer");
	
   	return $query->result_array();
   }
   
   function get_keywords($id=0)
   {
  
   	$query=$this->db->get("keywords_tb");
	
   	return $query->result_array();
   }
   
    function get_products($id="")
    {
        
        
        $query = $this->db->query("select products.*,product_images.imagename from products left join product_images
        on product_images.product_id = products.id where products.id='$id' and products.hide = '0'");
        //echo $this->db->last_query();exit;
        return $query->result();
    }
	 function get_shopproducts($id="")
    {
        
        
        $query = $this->db->query("select online_products.* from online_products   where online_products.id='$id' ");
        //echo $this->db->last_query();exit;
        return $query->result();
    }
	function get_shopproducts_stats($id,$action)
	{
		 $query = $this->db->query("select online_product_analyse.*,count(online_product_analyse.id) as countdata,date(createdon) as p_date 
		  from online_product_analyse  
		  where online_product_analyse.product_id='$id' and online_product_analyse.action = '$action' group by date(createdon)");
        //echo $this->db->last_query();exit;
        return $query->result_array();
	}
		function get_shopproducts_stats_total($id,$action)
	{
		 $query = $this->db->query("select online_product_analyse.*,count(online_product_analyse.id) as countdata,date(createdon) as p_date 
		  from online_product_analyse  
		  where online_product_analyse.product_id='$id' and online_product_analyse.action = '$action' group by online_product_analyse.action ");
        //echo $this->db->last_query();exit;
        if($query->num_rows()>0){
       $row =  $query->row_array();
        return $row['countdata'];
        }else{
        	return 0;
        }
	}
	
			function get_products_dashboard($action)
	{
		$retailer_id = $_GET['id'];
		 $query = $this->db->query("select product_analyse.*,products.title,count(product_analyse.id) as countdata,date(product_analyse.createdon) as p_date 
		  from product_analyse  left join products on products.id = product_analyse.product_id
		  where  product_analyse.action = '$action' and products.retailer_id = '$retailer_id' group by product_id order by countdata desc limit 20");
        //echo $this->db->last_query();exit;
        return $query->result_array();
	}	 
	
		function get_onlineproducts_dashboard($action)
	{
		$merchant = $_GET['id'];
		 $query = $this->db->query("select online_product_analyse.*,online_products.product_name as title,count(online_product_analyse.id) as countdata,date(online_product_analyse.createdon) as p_date 
		  from online_product_analyse  left join online_products on online_products.id = online_product_analyse.product_id
		  where  online_product_analyse.action = '$action' and lower(online_products.retailer_name) = lower('$merchant') group by product_id order by countdata desc limit 20");
        //echo $this->db->last_query();exit;
        return $query->result_array();
	}	 
	
	 
	
	 
	
	
	
	
	function get_product_keywords($product_id)
	{
		 
	$query=	$this->db->query("select group_concat(keyword_id) as keyword_ids from product_keywords where product_id = '$product_id' ");
		return $query->result_array();
	}
  function winnersetting($id="")
    {

            $this->db->where('id',1);
        
        $query = $this->db->get("photo_setting");
        //echo $this->db->last_query();exit;
        return $query->result();
    }
    
	function image_detail($id="")
	{
		
        
        $query = $this->db->query(" select  user_photos.* ,users.name,users.email from  user_photos left join users on user_photos.user_id=users.id  where user_photos.id='$id'");
		
      // echo $this->db->last_query();exit;
        return $query->result();
	}
	function user_detail($id="")
	{
		if($id>0)
            $this->db->where('id',$id);
        
        $query = $this->db->get("users");
      // echo $this->db->last_query();exit;
        return $query->result();
	}
	
			function get_shopretailerproducts_dt()
	{
		       $requestData= $_REQUEST;
$merchant = $_REQUEST['merchant'];

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'title',
	2 =>'title',
	3=> 'description',
	4 =>'updatedon',
	5 =>'id',
);

// getting total number records without any search
$sql = "SELECT online_products.id FROM `online_products` where   lower(online_products.retailer_name) = lower('$merchant')";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT online_products.*   FROM `online_products`  
  where lower(online_products.retailer_name) = lower('$merchant') ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( product_name LIKE '%".$requestData['search']['value']."%' ";    
	$sql.="   )";
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
		   $updatedon_timestamp = strtotime($row->updatedon);
		   $updatedon = date('d/m/y h:i:s A', $updatedon_timestamp);
		  
		 
	$nestedData[] = $i;
	$nestedData[] = '<a href="'.$row->product_link.'" ><img src="'.$row->product_image_link.'" style="height:100px;width:100px;"></a>';
$nestedData[] = $row->product_name;
	$nestedData[] = $row->country;
	$nestedData[] = $row->product_price;
	$nestedData[] = $updatedon;
	
	  
	$nestedData[]= ' <a   href="'.site_url('retailer_store/onlineproduct_statistics').'?id='.$id.'" title="Statistics"><i class="icon-signal"></i></a> ';
	 
 
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
	
	
	
		function get_products_dt()
	{
		       $requestData= $_REQUEST;
$retailer_id = $this->session->userdata('id');

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'title',
	2 =>'title',
	3=> 'description',
	4 =>'updatedon',
	5 =>'id',
);

// getting total number records without any search
$sql = "SELECT products.id FROM `products` where products.hide = '0' and products.retailer_id = '$retailer_id'";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT products.*,product_images.imagename  FROM `products` 
left JOIN product_images on products.id=product_images.product_id
  where products.hide = '0' and products.retailer_id = '$retailer_id' ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( title LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR description LIKE '%".$requestData['search']['value']."%' )";
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
		   $updatedon_timestamp = strtotime($row->updatedon);
		   $updatedon = date('d/m/y h:i:s A', $updatedon_timestamp);
		  
		   
		  $img_name= $row->imagename;
		  $temp="'".$id."','".$img_name."'";
		 
	$nestedData[] = $i;
	$nestedData[] = '<a href="'.site_url("product/detail")."?id=".md5($row->id).'" ><img src="'.get_retailerproduct_image($row->imagename).'" style="height:100px;width:100px;"></a>';
$nestedData[] = $row->title;
	$nestedData[] = $row->description;
	$nestedData[] = $updatedon;
	
	 
	$nestedData[]= '<input type="checkbox" style="margin:0px;" value="'.$id.'" name="product_id[]" form="deleteproduct" />&nbsp;&nbsp;|&nbsp;&nbsp;
	<a   href="'.site_url('admin/manage_product').'?id='.$id.'" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  onclick="product_id('.$id.')" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" data-id="'.$id.'"   title="Send Notification"><i class="icon-bell"></i></a>
               &nbsp;&nbsp;|&nbsp;&nbsp;<a   href="'.site_url('admin/product_statistics').'?id='.$id.'" title="Statistics"><i class="icon-signal"></i></a>     <img src="'.base_url().'/images/loaders/loader19.gif" id="image_'.$id.'" style="display: none;"/>';
	
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
//sale

	function get_sales_dt()
	{
		       $requestData= $_REQUEST;
$retailer_id = $_REQUEST['id'];

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'title',
	2 =>'title',
	3=> 'description',
	4 =>'createdon',
	5 =>'id',
	6=>'id'
);

// getting total number records without any search
$sql = "SELECT product_id FROM `tb_payment` group by product_id";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT products.*,product_images.imagename,tb_payment.createdon as saledate,count(tb_payment.product_id)  as salecount,sum(tb_payment.amount) as saleprice FROM `tb_payment`  join products on tb_payment.product_id = products.id
left JOIN product_images on products.id=product_images.product_id
  where products.hide = '0' and products.retailer_id = '$retailer_id' ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( title LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR description LIKE '%".$requestData['search']['value']."%' )";
}
$query=$this->db->query($sql);
$totalFiltered = $query->num_rows(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" GROUP BY  month(tb_payment.createdon),products.id ";
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
		   $updatedon_timestamp = strtotime($row->saledate);
		   $updatedon = date('F', $updatedon_timestamp);
		  
		   
		  $img_name= $row->imagename;
		  $temp="'".$id."','".$img_name."'";
		 
	$nestedData[] = $i;
	$nestedData[] = '<a href="'.site_url("product/detail")."?id=".md5($row->id).'" ><img src="'.get_retailerproduct_image($row->imagename).'" style="height:100px;width:100px;"></a>';
$nestedData[] = $row->title;
	$nestedData[] = $row->description;
	$nestedData[] = $updatedon;
	$nestedData[] = $row->salecount;
	 $nestedData[] = $row->saleprice;
	$nestedData[]= '
	<a   href="'.site_url('retailer_store/manage_product').'?id='.$id.'&retailer_id='.$_REQUEST['id'].'" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  onclick="product_id('.$id.')" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" data-id="'.$id.'"   title="Send Notification"><i class="icon-bell"></i></a>
               &nbsp;&nbsp;|&nbsp;&nbsp;<a   href="'.site_url('admin/product_statistics').'?id='.$id.'" title="Statistics"><i class="icon-signal"></i></a>     <img src="'.base_url().'/images/loaders/loader19.gif" id="image_'.$id.'" style="display: none;"/>';
	
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

function get_totalsales_dt()
	{
		       $requestData= $_REQUEST;
$retailer_id = $_REQUEST['id'];

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'title',
	2 =>'title',
	3=> 'description',
	4 =>'createdon',
	5 =>'id',
	6=>'id'
);

// getting total number records without any search
$sql = "SELECT product_id FROM `tb_payment` group by product_id";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT tb_payment.*,tb_payment.createdon as saledate,count(tb_payment.product_id)  as salecount,sum(tb_payment.amount) as saleprice FROM `tb_payment`  join products on tb_payment.product_id = products.id
  where products.hide = '0' and products.retailer_id = '$retailer_id' ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( title LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR description LIKE '%".$requestData['search']['value']."%' )";
}
$query=$this->db->query($sql);
$totalFiltered = $query->num_rows(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" GROUP BY  month(tb_payment.createdon) ";
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
		   $updatedon_timestamp = strtotime($row->saledate);
		   $updatedon = date('F', $updatedon_timestamp);
		  
		   
		  
		 
	$nestedData[] = $i;
	 
	$nestedData[] = $updatedon;
	//$nestedData[] = $row->salecount;
	 $nestedData[] = $row->saleprice;
	 	
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


//sale end

// retailers
		function get_retailers_dt()
	{
		       $requestData= $_REQUEST;
 

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'firstname',
	2 =>'address',
	3=> 'contact',
	4 =>'email',
	5 =>'id',
	6 =>'id',
);

// getting total number records without any search
$sql = "SELECT  id FROM `retailer` where is_admin = '0' and hide = '0'  ";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT retailer.*   FROM `retailer`  
  where is_admin = '0' and hide = '0'  ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( 	email LIKE '%".$requestData['search']['value']."%' ";    
$sql.=" OR firstname LIKE '%".$requestData['search']['value']."%' ";   
$sql.=" OR lastname LIKE '%".$requestData['search']['value']."%' ";
$sql.=" OR address LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR contact LIKE '%".$requestData['search']['value']."%' )";
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
		//  date_default_timezone_set('UTC');
		 //  $updatedon_timestamp = strtotime($row->updatedon);
		 //  $updatedon = date('d/m/y h:i:s A', $updatedon_timestamp);
		  
		   if($row->status=='ACTIVE'){
		   	$class = 'success';
		   }else{
		   	$class = 'warning';
		   }
		 
	$nestedData[] = $i;
	
$nestedData[] = $row->firstname." ".$row->lastname;
	$nestedData[] = $row->address;
	$nestedData[] = $row->contact;
	$nestedData[] = '<a href="mailto:'.$row->email.'" >'.$row->email.' </a>';
	$nestedData[] = '<a target="blank" href="'.site_url("admin/retailer_products")."?id=".$row->id.'" class="label label-primary">View</a>';
	$nestedData[] =  '<span class="label label-'.$class.'">'.$row->status.'</span>';
	 
	$nestedData[]= '<input type="checkbox" style="margin:0px;" value="'.$id.'" name="retailer_id[]" form="retaileraction" /> 

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


//end

function get_keywords_dt()
{
	$retailer_id = $this->session->userdata('id');
			       $requestData= $_REQUEST;
 

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'keyword',	
	2 =>'id' 
);

// getting total number records without any search
$sql = "SELECT  id FROM `keywords_tb` where retailer_id	 = '$retailer_id'    ";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT keywords_tb.*   FROM `keywords_tb`  
  where retailer_id	 = '$retailer_id'   ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( 	keyword LIKE '%".$requestData['search']['value']."%' ) ";    
 
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

		 
	$nestedData[] = $i;
	
$nestedData[] = $row->keyword;
	 
	$nestedData[]= '
<a   href="'.site_url('admin/manage_keyword').'?id='.$id.'" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<input type="checkbox" style="margin:0px;" value="'.$id.'" name="keyword_id[]" form="keywordaction" /> 
    
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
	
		function delete_products()
		{
			$realeasedate =gmdate("Y-m-d H:i:s");
			if(count($_POST['product_id'])){
			$product_ids = 	implode(",", $_POST['product_id']);
			$this->db->query("update  product_images set hide = '1',updatedon = '$realeasedate' where product_id in (".$product_ids.")");
			$qry = $this->db->query("update products set hide = '1',updatedon = '$realeasedate' where id in (".$product_ids.")");
			if($qry){
				return true;
			}
			}
			return false;
		}
		
   	function retaileraction()
		{
			$qry=false;
			$realeasedate =gmdate("Y-m-d H:i:s");
			if(count($_POST['retailer_id'])){
			$retailer_ids = 	implode(",", $_POST['retailer_id']);
				if($_POST['action']=='delete'){
				$qry=	$this->db->query("update  retailer set hide = '1',updatedon = '$realeasedate' where id in (".$retailer_ids.")");
			
				}else if($_POST['action']=='active')
				{
				$qry=	$this->db->query("update  retailer set status = 'ACTIVE',updatedon = '$realeasedate' where id in (".$retailer_ids.")");
				
				}else if($_POST['action']=='detactive')
				{
				$qry=	$this->db->query("update  retailer set status = 'DEACTIVE',updatedon = '$realeasedate' where id in (".$retailer_ids.")");
				
				}
				if($qry){
				return true;
			}
			}
			return false;
		}
			
   	function keywordaction()
		{
			$retailer_id = $this->session->userdata('id');
			$qry=false;
			$realeasedate =gmdate("Y-m-d H:i:s");
			if(count($_POST['keyword_id'])){
			$keyword_ids = 	implode(",", $_POST['keyword_id']);
				if($_POST['action']=='delete'){
				$qry=	$this->db->query("delete  from keywords_tb where   id in (".$keyword_ids.") and retailer_id = '$retailer_id'");
			
				}
				if($qry){
				return true;
			}
			}
			return false;
		}	

	function user_save($id="")
    {
        $arr_field = array(
                            "bonus_points" => $_POST['bonus_points']
                            
                          
                            );
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->update('users', $arr_field);
            return $id;
        } else {
            //$this->db->insert('group_type', $arr_field);
         //   echo $this->db->last_query();exit;
           // return $this->db->insert_id();
        } 
    }
	
		function save_setting($id="")
    {
    	$timelimit =(60*$_POST['timelimit']);
        $arr_field = array(
                            "photo_time" => $timelimit,
                            "reward" => $_POST['reward']
                          
                            );
       
            $this->db->where('id', $id);
            $query = $this->db->update('photo_setting', $arr_field);
            return $id;
        
    }
	

   function getUser($id="")
    {
        if($id>0)
            $this->db->where('id',$id);
        $query = $this->db->get("users");
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }   
function toggleStatus($userid){
	$query=$this->db->query(" update users set status=1-status where id='$userid'");
	 $this->db->where('id',$userid);
        $userqry = $this->db->get("users");
		$row =$userqry->row();
	return $row->status;
}
    
	function get_userphoto_count($id)
    {
     $query = 	$this->db->query("SELECT count(id) as photo_count FROM `user_photos` where user_id='$id' and type='PHOTO' ");
	 $row=$query->row();
	 return $row->photo_count;	
    }
    function get_uservideo_count($id)
    {
     $query = 	$this->db->query("SELECT count(id) as photo_count FROM `user_photos` where user_id='$id' and type='VIDEO' ");
	 $row=$query->row();
	 return $row->photo_count;	
    }
    function get_userupload_likecount($id)
    {
     $query = 	$this->db->query("SELECT count(photos_like.id) as like_count FROM `photos_like` left join user_photos on user_photos.id = photos_like.photo_id where user_photos.user_id='$id'  ");
	 $row=$query->row();
	 return $row->like_count;	
    }

	    function save_product($id = "0")
    {
    	$realeasedate =gmdate("Y-m-d H:i:s");
		$retailer_id = $_POST['retailer_id'];
		if($_POST['keywords']==''){
			$keywords = '';
		}else{
			$keywords = implode(',', $_POST['keywords']);
		}
		
		 
        $arr_field = array(
                            "title" => $_POST['title'],
                             "description" => $_POST['description'],
                              "quantity" => $_POST['quantity'], 
                                 "price" => $_POST['price'],   
                                 "keywords"=>$keywords, 
                           "admin_updateon"=>$realeasedate,
                            "is_admin_update"=>1,
                            "retailer_id"=>$retailer_id
                            );
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->update('products', $arr_field);
            $product_id= $id;
        } else {
            $this->db->insert('products', $arr_field);
         //   echo $this->db->last_query();exit;
            $product_id= $this->db->insert_id();
        }
		
		$product_id;
		if($product_id){
			$this->save_product_keyword($product_id,$_POST['keywords']);
		  if($_POST['business_photo_name']!=""){
		  		
		  	 $imgarr_field = array(
                            "product_id" => $product_id,
                             "imagename" => $_POST['business_photo_name'], 
                            "updatedon"=>$realeasedate
                            );		
		  		
		  	
		  	$this->db->where("product_id",$product_id);
		  	$imgqry = $this->db->get("product_images");
		  	if($imgqry->num_rows()>0){
		  		$this->db->where("product_id",$product_id);
	            $query = $this->db->update('product_images', $imgarr_field);
	            $product_id= $id;
				 
		  	}else{
		  		 $this->db->insert('product_images', $imgarr_field);
                 $productimg_id= $this->db->insert_id();
                 
		  	}
		  	
		  }
		  return $product_id;
		  }
     return false;

    }

//// product feed

	    function save_feed_product($product)
    {
    	$realeasedate =gmdate("Y-m-d H:i:s");
		$retailer_id = $this->session->userdata('id');
		
		 
		 
        $arr_field = array(
                            "title" => $product['title'],
                             "description" => $product['description'],
                              "quantity" => $product['quantity'], 
                                 "price" => $product['price'],
                            "updatedon"=>$realeasedate,
                            "retailer_id"=>$retailer_id
                            );
    
            $this->db->insert('products', $arr_field);
         //   echo $this->db->last_query();exit;
            $product_id= $this->db->insert_id();
        
		
		$product_id;
		if($product_id){
			//$this->save_product_keyword($product_id,$_POST['keywords']);
		  if($product['imagelink']!=""){
		  		
		  	 $imgarr_field = array(
                            "product_id" => $product_id,
                             "imagename" => $product['imagelink'], 
                             "image_type" => 'G_FEED',
                            "updatedon"=>$realeasedate
                            );		
		  		
		  	
		  	$this->db->where("product_id",$product_id);
		  	$imgqry = $this->db->get("product_images");
		  	if($imgqry->num_rows()>0){
		  		$this->db->where("product_id",$product_id);
	            $query = $this->db->update('product_images', $imgarr_field);
	            $product_id= $id;
				 
		  	}else{
		  		 $this->db->insert('product_images', $imgarr_field);
                 $productimg_id= $this->db->insert_id();
                 
		  	}
		  	
		  }
		  return $product_id;
		  }
     return false;

    }


/// end of product feed





    function save_keyword()
	{
			$realeasedate =gmdate("Y-m-d H:i:s");
		$retailer_id = $this->session->userdata('id');
		
		$id = $_POST['id'];
		 
        $arr_field = array(
                            "keyword" => $_POST['title'],
                             "retailer_id" => $retailer_id,                       
                            "createdon"=>$realeasedate 
                            );
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->update('keywords_tb', $arr_field);
            $product_id= $id;
        } else {
            $this->db->insert('keywords_tb', $arr_field);
         //   echo $this->db->last_query();exit;
            $product_id= $this->db->insert_id();
        }
	}
    function save_product_keyword($product_id,$keywords)
	{
		 $realeasedate =gmdate("Y-m-d H:i:s");
        $result = $this->db->delete('product_keywords', array('product_id' => $product_id));
         
		foreach ($keywords as  $value) {
			 $arr_field = array(
                            "product_id" => $product_id,
                             "keyword_id" => $value, 
                            "createdon"=>$realeasedate
                            );	
			
			$this->db->insert('product_keywords', $arr_field);
                 $productimg_id= $this->db->insert_id();
		}
	}

	function edit_user($id)
    {
     $query = 	$this->db->query("SELECT * FROM `users` where id='$id' ");
	 
	 return $query->result();
    }
    function delete_record($id="0")
    {
      
        $this->db->where('id',$id);
        $result = $this->db->delete('employee', array('id' => $id));
        return $result;
    }
    function delete_group($id="0")
    {
      
        $this->db->where('id',$id);
        $result = $this->db->delete('group_type', array('id' => $id));
        return $result;
    }
    function delete_group_image($id,$image)
	{
		$img_link=base_url()."upload/userphotos/".$image;
		
		  $this->db->where('id',$id);
		        $result = $this->db->delete('user_photos', array('id' => $id));
		        return $result;
		 
		
	}
	function remove_pic_comment($id)
	{
		 $this->db->where('id',$id);
		        $result = $this->db->delete('photos_comment', array('id' => $id));
		        return $result;
	}
	function remove_pic_like($id)
	{
		 $this->db->where('id',$id);
		        $result = $this->db->delete('photos_like', array('id' => $id));
		        return $result;
	}
   function remove_user_and_data($id="0")
    {
    	$this->db->where('user_id',$id);
        $result_photos = $this->db->get('user_photos', array('id' => $id));
		foreach ($result_photos->result() as $photo) {
			$photo_id=$photo->id;
			$this->db->where('id',$photo_id);
             $result = $this->db->delete('photos_like', array('id' => $photo_id));
			 $this->db->where('id',$photo_id);
             $result = $this->db->delete('photos_comment', array('id' => $photo_id));
		}
		$this->db->where('user_id',$id);
         $result = $this->db->delete('photos_like', array('user_id' => $id));
	   $this->db->where('user_id',$id);
        $result = $this->db->delete('photos_comment', array('user_id' => $id));
		
		$this->db->where('user_id',$id);
        $result = $this->db->delete('user_follower', array('user_id' => $id));
		
		$this->db->where('follower_id',$id);
        $result = $this->db->delete('user_follower', array('follower_id' => $id));
		
        $this->db->where('id',$id);
        $result = $this->db->delete('users', array('id' => $id));
        return $result;
    }
    function get_admin()
    {
       $id= $this->session->userdata('id');
         $this->db->where("id",$id);
        $query = $this->db->get("retailer");
        //echo $this->db->last_query();exit;
        return $query->result();
    }
	function check_email()
    {
    	$id= $this->session->userdata('id');
        $email = $_POST['email'];
        $query = $this->db->query("select * from retailer where id!='$id' and email = '$email' ");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
		function check_businessname()
    {
    	$id= $this->session->userdata('id');
     $businessname =  	trim($_POST['business_name']);
        $query = $this->db->query("select * from retailer where id!='$id' and business_name = '$businessname' ");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function save_profile($id="0")
    {
         $realeasedate =gmdate("Y-m-d H:i:s");
            $this->db->select('*');
            $this->db->where("id ='".$_POST['id']."' AND password='".md5($_POST['password'])."' ");
            $query = $this->db->get('retailer');
           
                if ($query->num_rows() > 0)
                {
                    
                 $arr_field = array("email" =>$_POST['email'],
                  "business_name" =>$_POST['business_name'],
                 "address" =>$_POST['address'],
                 "firstname" =>$_POST['firstname'],
                   "lastname" =>$_POST['lastname'],
                            "contact" =>$_POST['contact'],
                               "updatedon" =>$realeasedate 
                
                            );
                 $id=$_POST['id'];           
                $this->db->where('id', $id);
                $query = $this->db->update('retailer', $arr_field);
                
						  if($_POST['business_photo_name']!=""){
		  		
		  	 $imgarr_field = array(
                            
                             "logo" => $_POST['business_photo_name']
                            
                            );		
		  		
	
		  	     $this->db->where('id', $id);
	            $query = $this->db->update('retailer', $imgarr_field);
	            $product_id= $id;
				 
		 
		  	
		  }
				
				
				
                return $query;  
        		} 
				return false;
                
              
    }
function change_password($id="0")
    {
         $realeasedate =gmdate("Y-m-d H:i:s");
            $this->db->select('*');
            $this->db->where("id ='".$_POST['id']."' AND password='".md5($_POST['password'])."' ");
            $query = $this->db->get('retailer');
           
                if ($query->num_rows() > 0)
                {
                    
                 $arr_field = array( 
                               "updatedon" =>$realeasedate,
                                    "password"=>md5($_POST['stynitenewpassword'])
                
                            );
                 $id=$_POST['id'];           
                $this->db->where('id', $id);
                $query = $this->db->update('retailer', $arr_field);
                
                return $query;  
        		} 
				return false;
                
              
    }
    function save_information($input = "")
    {
    	
    	$user_id='';
        $this->db->where('reset_token', $_POST['token']);
            $query = $this->db->get('users');
	//echo $this->db->last_query();exit;
	
           if($query->num_rows()>0)
		   {
		   	$row=$query->row();
			$user_id=$row->id;
		
			
		   	 $arr_field = array(
            "user_id" => $user_id,
            "beneficiary_name" => $_POST['beneficiary_name'],
            "country" => $_POST['country'],
            "address" => $_POST['address'],
            "remeeting_currency" => $_POST['remeeting_currency'],
            "account_no" => $_POST['account_no'],
            "bank" => $_POST['bank'],
            "bankcountry" => $_POST['bankcountry'],
            "bankaddress" => $_POST['bankaddress'],
            "code" => $_POST['code']);
		   }
	
       
        if ($user_id>0) {
           
            $query = $this->db->insert('user_bankinfo', $arr_field);
           	//clear token
			$recordin   =$this->db->insert_id();
			if($recordin>0){
				$arr_usr = array("reset_token" =>''                
                            );
                         
                $this->db->where('id', $user_id);
                $query = $this->db->update('users', $arr_usr);
				return $recordin;
			}
			
			//end
        } else {
           return false;

        }

        return false;
    }
    
   
}
?>