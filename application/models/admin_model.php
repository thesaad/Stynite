<?php
class Admin_model extends CI_Model
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
   
    function get_balance_retailer()
   {
   		$retailer_id = $this->session->userdata('id');
 
$query=$this->db->query("select sum(tb_payment.amount) as total_balance from tb_payment join products on products.id = 
tb_payment.product_id where products.retailer_id =  '$retailer_id' ");

$row = $query->row();
 $amount    = $row->total_balance;
	$total_balance = get_retailer_balance($amount);
    return $total_balance;
   }
    function get_admin_balance()
   {
   		$admin_id = $this->session->userdata('id');
   
	$query=$this->db->query(" select tb_payment.* from tb_payment ");
	
   	return $query->result_array();
   }
   
   
   function get_keywords($id=0)
   {
   	$retailer_id = $this->session->userdata('id');
   	if($id>0){
   		$this->db->where("id",$id);
   	}
	if(!($this->session->userdata('is_admin')==1)){
		$this->db->where("retailer_id",$retailer_id);
	}
   	$query=$this->db->get("keywords_tb");
	
   	return $query->result_array();
   }
      function get_adultkeywords($id=0)
   {
   	$retailer_id = $this->session->userdata('id');
   	if($id>0){
   		$this->db->where("id",$id);
   	}
	 
   	$query=$this->db->get("adult_keywords");
	
   	return $query->result_array();
   }
   
   function merge_keywords()
   {
   	$word_id = $_POST['word_id'];
   	 $ids = implode(',', $_POST['keyword_id']);
   	     $query = $this->db->query("select  group_concat(find_keyword) as new_customwords,group_concat(custom_keywords) as new_custom_keywords from camfind_keywords  where  id in (".$ids.")  ");
        //echo $this->db->last_query();
        $row = $query->row_array();
		$wordarray = explode(',', $row['new_customwords']);
		$unique_word_array = array_unique($wordarray);
		$newcustom_words =  implode(',', $unique_word_array);
		///
		
			$wordarray2 = explode(',', $row['new_custom_keywords']);
		$unique_word_array2 = array_unique($wordarray2);
		$newcustom_words2 =  implode(',', $unique_word_array2);
		
		$newcustom_words = $newcustom_words.','.$newcustom_words2;
		////
		
		
		 $query2 = $this->db->query("select  group_concat(find_keyword) as new_customwords,group_concat(custom_keywords) as new_custom_keywords from camfind_keywords  where  id in (".$ids.")
		 and id != '$word_id'  ");
        //echo $this->db->last_query();
        $row2 = $query2->row_array();
		$wordarray2 = explode(',', $row2['new_customwords']);
		$unique_word_array2 = array_unique($wordarray2);
		$newcustom_words2 =  implode(',', $unique_word_array2);
		$selected_custum_keywords = $newcustom_words2;
		////
		
	    $date =gmdate("Y-m-d H:i:s");
		
		       $arr_field = array(
		                   "selected_custum_keywords"=>$selected_custum_keywords,
                            "custom_keywords" => $newcustom_words,
                            "updatedon" => $date
                          
                            );
       
            $this->db->where('id', $word_id);
            $resquery = $this->db->update('camfind_keywords', $arr_field);
			
			foreach($_POST['keyword_id'] as $key=>$value){
    if($value==$word_id){
        unset($_POST['keyword_id'][$key]);
    }
}
  
 
 foreach($_POST['keyword_id'] as $key=>$value){
			$delete="delete from camfind_keywords   where id = '$value' ";               
			$this->db->query($delete);
			}
		return $resquery;
		//exit;
    }
   
      function get_camkeywords($id=0)
   {
   	 
   	if($id>0){
   		$this->db->where("id",$id);
   	}
	 
   	$query=$this->db->get("camfind_keywords");
	
   	return $query->result_array();
   }
   
    function get_products($id="")
    {
        
        
        $query = $this->db->query("select products.*,product_images.imagename from products left join product_images
        on product_images.product_id = products.id where products.id='$id' and products.hide = '0'");
        //echo $this->db->last_query();exit;
        return $query->result();
    }
	function get_products_stats($id,$action)
	{
		 $query = $this->db->query("select product_analyse.*,count(product_analyse.id) as countdata,date(createdon) as p_date 
		  from product_analyse  
		  where product_analyse.product_id='$id' and product_analyse.action = '$action' group by date(createdon)");
        //echo $this->db->last_query();exit;
        return $query->result_array();
	}
		function get_products_stats_total($id,$action)
	{
		 $query = $this->db->query("select product_analyse.*,count(product_analyse.id) as countdata,date(createdon) as p_date 
		  from product_analyse  
		  where product_analyse.product_id='$id' and product_analyse.action = '$action' group by product_analyse.action ");
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
		$retailer_id = $this->session->userdata('id');
		 $query = $this->db->query("select product_analyse.*,products.title,count(product_analyse.id) as countdata,date(product_analyse.createdon) as p_date 
		  from product_analyse  left join products on products.id = product_analyse.product_id
		  where  product_analyse.action = '$action' and products.retailer_id = '$retailer_id' group by product_id order by countdata desc limit 20");
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
	
			function get_retailerproducts_dt()
	{
		       $requestData= $_REQUEST;
$retailer_id = $_REQUEST['id'];

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
	
	  
	$nestedData[]= '<a   href="'.site_url('retailer_store/manage_product').'?id='.$id.'&retailer_id='.$retailer_id.'" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  onclick="product_id('.$id.')" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" data-id="'.$id.'"   title="Send Notification"><i class="icon-bell"></i></a>
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
$retailer_id = $this->session->userdata('id');

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

	
	//// balance history
			function get_admin_balancehistory_dt()
	{
		       $requestData= $_REQUEST;
$retailer_id = $this->session->userdata('id');

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'title',
	2 =>'title',
	3=> 'firstname',
	4 =>'tb_payment.amount',
	5 =>'tb_payment.amount',
	6 =>'tb_payment.amount',
	7=>'createdon'
);

// getting total number records without any search
$sql = "select  tb_payment.amount,tb_payment.createdon as saledate,products.*   from tb_payment join products on products.id = 
tb_payment.product_id   ";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = " select  tb_payment.amount,retailer.firstname,retailer.lastname,product_images.imagename,tb_payment.createdon as saledate,products.*  from tb_payment join products on products.id = 
tb_payment.product_id 
left JOIN product_images on products.id=product_images.product_id 
left JOIN retailer on products.retailer_id=retailer.id 
where 1=1
 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( title LIKE '%".$requestData['search']['value']."%' ";  
$sql.=" OR firstname LIKE '%".$requestData['search']['value']."%' ";  
$sql.=" OR lastname LIKE '%".$requestData['search']['value']."%' "; 
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
		   $updatedon_timestamp = strtotime($row->saledate);
		   $updatedon = date('Y-m-d', $updatedon_timestamp);
		  
		   
		  $img_name= $row->imagename;
		  $temp="'".$id."','".$img_name."'";
		 
	$nestedData[] = $i;
	$nestedData[] = '<a href="'.site_url("product/detail")."?id=".md5($row->id).'" ><img src="'.get_retailerproduct_image($row->imagename).'" style="height:100px;width:100px;"></a>';
$nestedData[] = $row->title;
$nestedData[] = $row->firstname." ".$row->lastname;

		$nestedData[] = $row->amount;
		$nestedData[] = get_retailer_balance($row->amount);
	$nestedData[] = get_admin_balance($row->amount);
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
	
	
		function get_balancehistory_dt()
	{
		       $requestData= $_REQUEST;
$retailer_id = $this->session->userdata('id');

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'title',
	2 =>'title',
	3=> 'tb_payment.amount',
	4 =>'tb_payment.amount',
	5 =>'tb_payment.amount',
	6=>'tb_payment.createdon'
);

// getting total number records without any search
$sql = "select  tb_payment.amount,tb_payment.createdon as saledate,products.*   from tb_payment join products on products.id = 
tb_payment.product_id where products.retailer_id = '$retailer_id' ";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = " select  tb_payment.amount,product_images.imagename,tb_payment.createdon as saledate,products.*  from tb_payment join products on products.id = 
tb_payment.product_id 
left JOIN product_images on products.id=product_images.product_id 
where products.retailer_id = '$retailer_id'
 ";
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
		   $updatedon_timestamp = strtotime($row->saledate);
		   $updatedon = date('Y-m-d', $updatedon_timestamp);
		  
		   
		  $img_name= $row->imagename;
		  $temp="'".$id."','".$img_name."'";
		 
	$nestedData[] = $i;
	$nestedData[] = '<a href="'.site_url("product/detail")."?id=".md5($row->id).'" ><img src="'.get_retailerproduct_image($row->imagename).'" style="height:100px;width:100px;"></a>';
$nestedData[] = $row->title;

		$nestedData[] = $row->amount;
		$nestedData[] = get_retailer_balance($row->amount);
	$nestedData[] = get_admin_balance($row->amount);
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
	
	
	
	
	
	/////// end of balance history
	
	
	
	
function get_totalsales_dt()
	{
		       $requestData= $_REQUEST;
$retailer_id = $this->session->userdata('id');

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
	7 =>'id',
	8 =>'id'
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
	$nestedData[] = '<a target="blank" href="'.site_url("retailer_store/retailer_statistics")."?id=".$row->id.'" class="label label-warning"><i class="icon-shopping-cart"></i></a>';
	$nestedData[] = '<a title="Monthly Sales" target="blank" href="'.site_url("retailer_store/sales")."?id=".$row->id.'" class="label label-warning"><i  class="icon-calendar"></i></a>
	&nbsp;|&nbsp;<a title="Total Sales" target="blank" href="'.site_url("retailer_store/totalsales")."?id=".$row->id.'" class="label label-warning"><i  class="icon-plus"></i></a>
	';
	
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
 
if($this->session->userdata('is_admin')==1){
	$where = " where 1	 = 1 ";
}else{
	$where = " where retailer_id	 = '$retailer_id' ";
}
$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'keyword',	
	2 =>'id' 
);

// getting total number records without any search
$sql = "SELECT  id FROM `keywords_tb`    ".$where;

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT keywords_tb.*   FROM `keywords_tb`  ".$where;
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


function get_adultkeywords_dt()
{
	$retailer_id = $this->session->userdata('id');
			       $requestData= $_REQUEST;
 
 
	$where = " where 1	 = 1 ";
 
$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'keyword',	
	2 =>'id' 
);

// getting total number records without any search
$sql = "SELECT  id FROM `adult_keywords`    ".$where;

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT adult_keywords.*   FROM `adult_keywords`  ".$where;
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
<a   href="'.site_url('admin/manage_adultkeyword').'?id='.$id.'" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;
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


function get_camfindkeywords_dt()
{
	$retailer_id = $this->session->userdata('id');
			       $requestData= $_REQUEST;
 
if($this->session->userdata('is_admin')==1){
	$where = " where 1	 = 1 ";
}else{
	$where = " where retailer_id	 = '$retailer_id' ";
}
$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'find_keyword',
	2 => 'custom_keywords',
	3 => 'affiliate_keyword',
	4 => 'stynite_keyword',	
	5 =>'id', 
	6 =>'id', 
	7 =>'id' 
	
);

// getting total number records without any search
$sql = "SELECT  id FROM `camfind_keywords`    ".$where;

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT camfind_keywords.*,camfind_image.imagename   FROM `camfind_keywords` 
left join camfind_image on camfind_image.id = camfind_keywords.image_id
 ".$where;
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( 	find_keyword LIKE '%".$requestData['search']['value']."%'  ";    
 $sql.=" OR  custom_keywords LIKE '%".$requestData['search']['value']."%' "; 
 $sql.=" OR  stynite_keyword LIKE '%".$requestData['search']['value']."%' "; 
 $sql.=" OR	affiliate_keyword LIKE '%".$requestData['search']['value']."%' ) "; 
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

		 
	$nestedData[]= '
 <input type="checkbox" style="margin:0px;" value="'.$id.'" name="keyword_id[]" form="keywordaction" /> 
    
                    ';
	
$nestedData[] = $row->find_keyword;
$nestedData[] = $row->custom_keywords;
$nestedData[] = $row->affiliate_keyword;
$nestedData[] = $row->stynite_keyword;
$nestedData[]= ' <img style="height:50px;" src="'.PRODUCT_IMAGE.$row->imagename.'"/>';	 
	$nestedData[]= '
<a   href="'.site_url('admin/camfindkeywords_manage_keyword').'?id='.$id.'" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a  onclick="delete_keyword('.$id.')" title="Delete" ><i class="icon-trash"></i> </a>
    
                    ';
						$nestedData[]= '
 <input type="radio" style="margin:0px;" value="'.$id.'" name="word_id" form="keywordaction" /> 
    
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
		
		   	function adultkeywordaction()
		{
			 
			$qry=false;
			$realeasedate =gmdate("Y-m-d H:i:s");
			if(count($_POST['keyword_id'])){
			$keyword_ids = 	implode(",", $_POST['keyword_id']);
				if($_POST['action']=='delete'){
				$qry=	$this->db->query("delete  from adult_keywords where   id in (".$keyword_ids.")");
			
				}
				if($qry){
				return true;
			}
			}
			return false;
		}	
		
	   	function camkeywordaction()
		{
			 
			$qry=false;
			$realeasedate =gmdate("Y-m-d H:i:s");
			if(isset($_POST['id'])){
			$keyword_ids = 	$_POST['id'];
				 
				$qry=	$this->db->query("delete  from camfind_keywords where   id in (".$keyword_ids.") ");
			
				 
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
		$retailer_id = $this->session->userdata('id');
		
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
                            "updatedon"=>$realeasedate,
                            "is_admin_update"=>0,
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


    function save_adultkeyword()
	{
			$realeasedate =gmdate("Y-m-d H:i:s");
		$retailer_id = $this->session->userdata('id');
		
		$id = $_POST['id'];
		 
        $arr_field = array(
                            "keyword" => $_POST['title'],
                             "retailer_id" => $retailer_id,                       
                            "updatedon"=>$realeasedate 
                            );
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->update('adult_keywords', $arr_field);
            $product_id= $id;
        } else {
            $this->db->insert('adult_keywords', $arr_field);
         //   echo $this->db->last_query();exit;
            $product_id= $this->db->insert_id();
        }
	}


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
	
	   function save_camkeyword()
	{
			$realeasedate =gmdate("Y-m-d H:i:s");
		
		
		$id = $_POST['id'];
		 
        $arr_field = array(
                            "find_keyword" => $_POST['f_keyword'],
                            "selected_custum_keywords" => $_POST['sc_keyword'],
                            "custom_keywords" => $_POST['c_keyword'],
                            "affiliate_keyword" => $_POST['a_keyword'],
                            "stynite_keyword" => $_POST['s_keyword'],                 
                            "updatedon"=>$realeasedate 
                            );
        if ($id > 0) {
            $this->db->where('id', $id);
            $query = $this->db->update('camfind_keywords', $arr_field);
            $product_id= $id;
        } else {
            $this->db->insert('camfind_keywords', $arr_field);
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