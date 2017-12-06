<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
define("BRANDIMAGE_URL","http://52.36.18.106/app/upload/logo/");
define("IMAGE_URL","http://52.36.18.106/app/upload/images/");
define("NO_IMAGE_URL","http://52.36.18.106/app/upload/images/noimage.jpg");
define("POST_IMAGE_URL","http://userpost-image.s3.amazonaws.com/");
define("USER_IMAGE_URL","http://stynite-profile-image.s3.amazonaws.com/");
define("RETAILER_IMAGE_URL","http://stynite-products.s3.amazonaws.com/");
	function get_user_image($image)
	{
		$img_url="";
		if($image!=""){
					$checkurl=strpos($image,"http://");
						$checkurls=strpos($image,"https://");
					if($checkurl!==false || $checkurls!==false){
							$img_url=$image;
						}else{
							
							$img_url=USER_IMAGE_URL.$image;
						}
		}else{
		$img_url=	NO_IMAGE_URL;
		}
						return $img_url;
	}
	
		function get_brand_image($image)
	{
		$img_url="";
		if($image!=""){
				 	
							$img_url=BRANDIMAGE_URL.$image;
					 
		}else{
		$img_url=	NO_IMAGE_URL;
		}
						return $img_url;
	}
			function get_retailer_image($image)
	{
		$img_url="";
		if($image!=""){
				 	
							$img_url=RETAILER_IMAGE_URL.$image;
					 
		}else{
		$img_url=	NO_IMAGE_URL;
		}
						return $img_url;
	}
	
			function get_retailerproduct_image($image)
	{
		$img_url="";
		if($image!=""){
			$checkurl=strpos($image,"http://");
						$checkurls=strpos($image,"https://");
					if($checkurl!==false || $checkurls!==false){
							$img_url=$image;
						}else{
				 	
							$img_url=RETAILER_IMAGE_URL.$image;
						}
					 
		}else{
		$img_url=	NO_IMAGE_URL;
		}
						return $img_url;
	}
	

	function category_image($image)
	{
		$img_url="";
		if($image!=""){
					$checkurl=strpos($image,"http://");
						$checkurls=strpos($image,"https://");
					if($checkurl!==false || $checkurls!==false){
							$img_url=$image;
						}else{
							
							$img_url=IMAGE_URL.$image;
						}
		}
						return $img_url;
	}

function get_post_image($image)
{
	$img_url="";
		if($image!=""){
					$checkurl=strpos($image,"http://");
						$checkurls=strpos($image,"https://");
					if($checkurl!==false || $checkurls!==false){
							$img_url=$image;
						}else{
							
							$img_url=POST_IMAGE_URL.$image;
						}
		}else{
		$img_url=	NO_IMAGE_URL;
		}
		return $img_url;
}
	function get_gmt_time()
{
    return gmdate("Y-m-d H:i:s");
}

function get_bing_image($keyword)
{
    //** change by deepak //

$googleimage_name =array();
$acctKey = 'nyv/EPLwCJa5QD4BR7bYi3C/uUIpWD8MrxLK1oU4gVs';
$rootUri = 'https://api.datamarket.azure.com/Bing/Search';

// Read the contents of the .html file into a string.



if ($keyword)

{

// Here is where you'll process the query.

// The rest of the code samples in this tutorial are inside this conditional block.

// Encode the query and the single quotes that must surround it.

$query = urlencode("'{$keyword}'");

// Get the selected service operation (Web or Image).

$serviceOp = 'Image';

// Construct the full URI for the query.

$requestUri = "$rootUri/$serviceOp?\$format=json&\$top=1&Query=$query";

// Encode the credentials and create the stream context.

$auth = base64_encode("$acctKey:$acctKey");

$data = array(

'http' => array(

'request_fulluri' => true,

// ignore_errors can help debug  remove for production. This option added in PHP 5.2.10

'ignore_errors' => true,

'header' => "Authorization: Basic $auth")

);

$context = stream_context_create($data);

// Get the response from Bing.

 $response = file_get_contents($requestUri, 0, $context);

// Decode the response. 
$jsonObj = json_decode($response,true); $resultStr = ''; 



$googleimage_name_img=$jsonObj['d']['results'][0]['MediaUrl'];
$googleimage_name_thumb =$jsonObj['d']['results'][0]['Thumbnail']['MediaUrl'];
$googleimage_name = array('image'=>$googleimage_name_img,
						  'image_thumb'=>$googleimage_name_thumb
						  );
}





return $googleimage_name;
//end of the change
}


function get_retailer_balance($amount)
{
	$usd_price = $amount;
	$appfee = (($usd_price * OWNER_AMOUNT) / 100);
	$totalbal = $amount - $appfee;
	return $totalbal;
}

function get_admin_balance($amount)
{
	$usd_price = $amount;
	$appfee = (($usd_price * OWNER_AMOUNT) / 100);
 
	return $appfee;
}


function getbalance()
{
	$ci = &get_instance(); 
    $ci->load->database();
	$retailer_id = $ci->session->userdata('id');
 if($ci->session->userdata('is_admin')!='1'){
$query=$ci->db->query("select sum(tb_payment.amount) as total_balance from tb_payment join products on products.id = 
tb_payment.product_id where products.retailer_id =  '$retailer_id' ");

$row = $query->row();
 $amount    = $row->total_balance;
	$total_balance = get_retailer_balance($amount);
    return "$".$total_balance;
 }else{
 	$query=$ci->db->query("select sum(tb_payment.amount) as total_balance from tb_payment join products on products.id = 
tb_payment.product_id   ");

$row = $query->row();
 $amount    = $row->total_balance;
	$total_balance = get_admin_balance($amount);
    return "$".$total_balance;
 }
}
?>
