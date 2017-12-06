<?php
class Notification_model  extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

  /* notification */
 function save_requested_notice($input)
 {
 	 $gmt_datetime = get_gmt_time();
 	$retailer_id = $this->session->userdata('id');
 	$arr_field = array("retailer_id"=>$retailer_id,
 	"product_id"=>$input['product_id'],
 	"notification"=>$input['message'],
 	"notification_type"=>$input['type'],
 	"createdon"=>$gmt_datetime
	);
	 $this->db->insert('notification_list', $arr_field);
            $notice_id= $this->db->insert_id();
			if($notice_id>0){
				return true;
			}else{
				return false;
			}
 } 
function fetch_device($device_type)
{
    $fetch=$this->db->query("select * from users where device_type='$device_type'");
    $count=$fetch->num_rows();
    if($count)
    {
      return $fetch->result_array();
    }
 
}
function fetch_device_selecteduser($usersids)
{
    $fetch=$this->db->query("select * from users where id IN ($usersids)");
    $count=$fetch->num_rows();
    if($count)
    {
      return $fetch->result_array();
    }
 
}
function fetch_all_device($type="")
{
	$morequery = "";
	$iphone = $andorid=array();
	if($type=="PRICE_DROP"){
		$morequery = " and user_setting.price_drop='1'  ";//and devices.is_debug = '1'
	}
	if($type=="SALE"){
		$morequery = " and user_setting.offer_sale='1'   ";
	}
	
    $fetch=$this->db->query("select devices.* from devices 
    left join user_device on user_device.device_id = devices.id
    left join users on users.id = user_device.user_id
    left join user_setting on user_setting.user_id = users.id
    where device_platfrom='Ios' and users.is_logout='0'  ".$morequery." group by devices.uuid ");
	
    $count=$fetch->num_rows();
    if($count)
    {
      $iphone=$fetch->result_array();
    }
    $fetch_data=$this->db->query("select devices.* from devices
        left join user_device on user_device.device_id = devices.id
    left join users on users.id = user_device.user_id
    left join user_setting on user_setting.user_id = users.id
     where device_platfrom='Android' and users.is_logout='0'  ".$morequery." group by devices.uuid");
    $counts=$fetch_data->num_rows();
    $andorid=array();
    if($counts)
    {
      $andorid=$fetch_data->result_array();
    }
    
    return array("iphone"=>$iphone,"andorid"=>$andorid);
}
	function get_notification_dt()
	{
		       $requestData= $_REQUEST;
$retailer_id = $this->session->userdata('id');

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'notification',
	2 =>'notification_type',
	3=> 'status',
	4 =>'createdon' ,
	5 =>'id' 
);

// getting total number records without any search
$sql = "SELECT id FROM `notification_list`  WHERE retailer_id ='$retailer_id' ";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT notification_list.*  FROM `notification_list`   
 WHERE retailer_id ='$retailer_id' ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( notification LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR notification_type LIKE '%".$requestData['search']['value']."%' )";
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
		   $updatedon =  $row->createdon;
		   
		  
		   
		  
		$status = ''; 
	$nestedData[] = $i;
 $nestedData[] = $row->notification;
 if($row->status=='PENDING'){
 $status=	'<span class="label label-warning">PENDING</span>';
 }else if($row->status=='SENT'){
 $status=	'<span class="label label-success">SENT</span>';
 }else{
 $status=	'<span class="label label-danger">REJECT</span>';	
 }
	$nestedData[] = $row->notification_type;
	$nestedData[] = $status;
	$nestedData[] = $updatedon;
	
	  
	$nestedData[]= '
	<a  style="cursor:pointer;"  onclick="delete_notice('.$id.')" title="Delete"><i class="icon-trash"></i></a>';
	
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
function delete_notice()
{
	$retailer_id = $this->session->userdata('id');
	$id = $_POST['id'];
	$result = $this->db->delete('notification_list', array('retailer_id' => $retailer_id,'id'=>$id));
        return $result;
}
function get_notification($noticeid)
{
	$this->db->where("id",$noticeid);
	$query = $this->db->get('notification_list');
	return $query->result_array();
}
function notice_action()
{
	 
	$id = $_POST['id'];
	$action = $_POST['action'];
	
	if($action==0){
		$status = "REJECT";
	}else{
		$status = "SENT";
	}
	 
	
	$gmt_datetime = get_gmt_time();
		
		
		$id = $_POST['id'];
		 
        $arr_field = array( 
                            "status" => $status,                 
                            "updatedon"=>$gmt_datetime 
                            );
         
            $this->db->where('id', $id);
            $query = $this->db->update('notification_list', $arr_field);
            $product_id= $id;
        return $query;
}

function get_retailernotification_dt()
{
			       $requestData= $_REQUEST;
$retailer_id = $this->session->userdata('id');

$columns = array( 
// datatable column index  => database column name
	0 =>'id', 
	1 => 'email',
	2 => 'notification',
	3 =>'notification_type',
	4=> 'status',
	5 =>'createdon' ,
	6 =>'id' 
);

// getting total number records without any search
$sql = "SELECT id FROM `notification_list`   ";

$query=$this->db->query($sql);
$totalData = $query->num_rows();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT notification_list.*,retailer.business_name, retailer.email FROM `notification_list` 
left join retailer on retailer.id =   notification_list.retailer_id
 WHERE 1 =1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( notification LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR notification_type LIKE '%".$requestData['search']['value']."%' )";
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
		   $updatedon =  $row->createdon;
		   
		  
		   
		  
		$status = ''; 
	$nestedData[] = $i;
	 $nestedData[] = $row->business_name." (".$row->email.")";
 $nestedData[] = $row->notification;
 if($row->status=='PENDING'){
 $status=	'<span class="label label-warning">PENDING</span>';
 }else if($row->status=='SENT'){
 $status=	'<span class="label label-success">SENT</span>';
 }else{
 $status=	'<span class="label label-danger">REJECT</span>';	
 }
	$nestedData[] = $row->notification_type;
	$nestedData[] = $status;
	$nestedData[] = $updatedon;
	
	  
	$nestedData[]= '
	<a  style="cursor:pointer;"  onclick="proceed_notice('.$id.',1)" title="Send"><i class="icon-thumbs-up"></i></a>&nbsp;|
	<a  style="cursor:pointer;"  onclick="proceed_notice('.$id.',0)" title="Reject"><i class=" icon-thumbs-down"></i></a>';
	
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
    
}