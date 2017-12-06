<?php   defined('BASEPATH') or exit('No direct script access allowed');


// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Shop_product extends REST_Controller
{
    function __construct()
    {
    	error_reporting(0);
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form','url','constant_helper','function_helper'));
        $this->load->model('users_model');	
		$this->load->model('user_post_model');	
		
		$this->load->model('users_panel_model');		
		$this->load->model('shop_model');
		$this->load->library('amazon_api'); 
		 $this -> load -> library(array('upload','S3_lib'));
        
    }
	
   	function upload_image($file)
		{
			
			if (isset($file['image']))
			{

				if ($file['image']['error'] == 0)
				{

					$file_name = uniqid();				
					$config['allowed_types'] = 'jpg|jpeg|gif|png';
					$config['file_name'] = $file_name;
					$s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
				    $source=$file['image']['tmp_name'];
					$ext = pathinfo($file['image']['name'], PATHINFO_EXTENSION);
					$file_name=$file_name.'.'.$ext;
					$aws_upload = $s3->putObjectFile($source, AWS_BUCKET_PRODUCT,
					$file_name,   S3::ACL_PUBLIC_READ);
					
					if($aws_upload==1){
						return	$file_name;
					}else{
						return	false;
					}	
					
						
					
				}
			}
		}	
function photo_upload()
{
	$input_method = $this->webservices_inputs();
	if(count($_FILES['image'])>0){
		$filename = $this->upload_image($_FILES);
		$id= $this->shop_model->saveCamfindImage($filename);
		 $this->shop_model->saveCamfindWord($input_method,$id);
		$this->response(array('message' => "Image  uploaded","url"=>PRODUCT_IMAGE.$filename,"image_id"=>$id,'status' => 1), 200);
	}else{
			$this->response(array('message' => "Image not upload",'status' => 0), 200);
	}
}
  function getStyniteRetailers()
  {
  	$retailerarr = array();
     $retailer=	$this->shop_model->getStyniteRetailers();
  	 if(count($retailer)>0)
		   {
		   	 foreach ($retailer as $row) {
		   	 	$logo = get_retailer_image($row['logo']);
				 
		   	 	$retailerarr[] = array("logo"=>$logo,"business_name"=>$row['business_name']); 
			 }
		   	 
		   } 
    $shopretailer = $this->shop_model->getShopRetailer();
	  	 if(count($shopretailer)>0)
		   {
		   	 foreach ($shopretailer as $row) {
		   	 	$logo = get_brand_image($row['logo']);
				 
		   	 	$retailerarr[] = array("logo"=>$logo,"business_name"=>$row['name']); 
			 }
		   	 
		   } 
	$this->response(array('message' => "Retailers",'data'=>$retailerarr, 'status' => 1), 200);	   
		   
		   	$this->response(array('message' => "Faile",'status' => 0), 200);
		    
  }
	
  function getCountryCode($lat,$lng)
  {
  	
	$latlng = $lat.",".$lng;
	$url= 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlng.'&sensor=true';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
	$phparr = json_decode($result,true);
	
	if(count($phparr['results'])>0){
		foreach ($phparr['results'][0]['address_components'] as $raw) {
			if (in_array("country", $raw['types']))
			  {
			
			   return $raw['short_name'];
			  }
		}
		
	}
	return false;
  	
  	}
	//////
	function getRecommendation()
	{
		$resultProduct = array();
		$input_method = $this->webservices_inputs();
	$this->validate_param('getRecommendation',$input_method);
	
	$product_ids = $this->users_panel_model->get_stynite_recommendation($input_method['user_id']);
	
	$aff_product_ids = $this->users_panel_model->get_affiliate_recommendation($input_method['user_id']);
	if(count($product_ids)>0){
		$styniteShopProduct = $this->shop_model->getStyniteRecommendationProduct($product_ids);
		
		if(count($styniteShopProduct)>0){
		foreach ($styniteShopProduct as  $value) {
			$resultProduct[] = $value;
		}
		}
	
	}
	
	if(count($aff_product_ids)>0){
		$styniteAffProduct = $this->shop_model->getAffilateRecommendationProduct($aff_product_ids);
		
		if(count($styniteAffProduct)>0){
		foreach ($styniteAffProduct as  $value) {
			$resultProduct[] = $value;
		}
		}
	}
	
	if(count($resultProduct)>0)
	{
		$this->response(array('message' => "Success",'data'=>$resultProduct, 'status' => 1 ), 200);   
	}else{
		$this->response(array('message' => "Fail", 'status' => 0), 200);   
	}
	
	}
	    function shopProductView()
  {
  	$product = false;
  	$input_method = $this->webservices_inputs();
	$this->validate_param('shopProductView',$input_method);	
	$product_id = $this->shop_model->getOnlineProductId($input_method['product_id']);
	if($product_id){
	$product = $this->shop_model->productActionForOnline($product_id,'VIEWED',$input_method['user_id']);
		
	}

	if($product)
	{
		$this->response(array('message' => "Success", 'status' => 1 ), 200);   
	}else{
		$this->response(array('message' => "Fail", 'status' => 0), 200);   
	}
	
  } 
	
	/////

     function productByKeyword()
  {
  	$page = 1;
  	$product=$linkshare =$ebay= array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
		if($input_method['lat']!=""&&$input_method['lng']!=""){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
		}else{
			$lat="55.3781"; $lng = "-3.4360";
		}
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	
	if(isset($input_method['user_id'])){
	$user_id	= $input_method['user_id'];
	}else{
	$user_id = 0;	
	}
	
	$countrycode = $this->getCountryCode($lat,$lng);
	
  	$ebay=$this->ebay_item($input_method['keyword'],$countrycode,$page,$user_id);
	$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode,$page,$user_id);

	if((count($ebay)>0)||(count($linkshare)>0)){
	$product = array("ebay"=>$ebay,"linkshare"=>$linkshare);
	}

	if($product)
	{
		$this->response(array('message' => "Product results", 'status' => 1 , 'data'=>$product), 200);   
	}else{
		$this->response(array('message' => "No result found", 'status' => 0), 200);   
	}
	
  } 
  
  
     function productByKeywordEbay()
  {
  	$page=1;
  $styniteShopProduct=	$product=$linkshare =$ebay= array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
		if($input_method['lat']!=""&&$input_method['lng']!=""){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
		}else{
			$lat="55.3781"; $lng = "-3.4360";
		}
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	
	if(isset($input_method['page'])){
		$page=$input_method['page'];
	}
	if(isset($input_method['user_id'])){
	$user_id	= $input_method['user_id'];
	}else{
	$user_id = 0;	
	}
	if(isset($input_method['is_camfind'])){
		if($input_method['is_camfind']==1){
	 $this->shop_model->saveCamfindWord($input_method);
		}
	}
  	$ebay=$this->ebay_item($input_method['keyword'],$countrycode,$page,$user_id);
	//$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode);
	
	if(count($ebay)>0){
	$product = $ebay;
	}
	/*if($input_method['page']==1){
		$styniteShopProduct = $this->shop_model->getStyniteShopProduct($input_method,$countrycode);
		if(count($styniteShopProduct)>0){
		foreach ($styniteShopProduct as  $value) {
			$resultProduct[] = $value;
		}
		}
	}*/
		if(count($product)>0){ 
		foreach ($product as  $value) {
			$resultProduct[] = $value;
		}
		}
	

	if(count($resultProduct)>0)
	{
		$this->response(array('message' => "Product results", 'status' => 1 , 'data'=>$resultProduct), 200);   
	}else{
		$this->response(array('message' => "No result found", 'status' => 0), 200);   
	}
	
  } 
  
  
     function productByKeywordLinkshare()
  {
  	$page=1;
  	$product=$linkshare =$ebay= $resultProduct=array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
			if($input_method['lat']!=""&&$input_method['lng']!=""){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
		}else{
			$lat="55.3781"; $lng = "-3.4360";
		}
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	if(isset($input_method['page'])){
		$page=$input_method['page'];
	}
	
	if(isset($input_method['is_camfind'])){
		if($input_method['is_camfind']==1){
	 $this->shop_model->saveCamfindWord($input_method);
		}
	}
	$linkkeyword = $input_method['keyword'];
	if($input_method['page']==1){
		if(isset($input_method['web_keyword'])){
			$input_method['keyword'] = $input_method['web_keyword'];
			}
		$styniteShopProduct = $this->shop_model->getStyniteShopProduct($input_method,$countrycode);
		
		if(count($styniteShopProduct)>0){
		foreach ($styniteShopProduct as  $value) {
			$resultProduct[] = $value;
		}
		}
	}
		if(isset($input_method['user_id'])){
	$user_id	= $input_method['user_id'];
	}else{
	$user_id = 0;	
	}
	
  	//$ebay=$this->ebay_item($input_method['keyword'],$countrycode);
	$linkshare=$this->linkshare_item($linkkeyword,$countrycode,$page,$user_id);
	
	if(count($linkshare)>0){ 
		foreach ($linkshare as  $value) {
			$resultProduct[] = $value;
		}
		}
	
	if(count($resultProduct)>0){
	$product = array("linkshare"=>$resultProduct);
	}
	if($product)
	{
		$this->response(array('message' => "Product results", 'status' => 1 , 'data'=>$product), 200);   
	}else{
		$this->response(array('message' => "No result found", 'status' => 0), 200);   
	}
	
  } 

 
  
         function productByKeywordAmazon()
  {
  	$page=1;
  	$product =$amazon= array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
			if($input_method['lat']!=""&&$input_method['lng']!=""){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
		}else{
			$lat="55.3781"; $lng = "-3.4360";
		}
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	if(isset($input_method['page'])){
		$page=$input_method['page'];
	}
  	//$ebay=$this->ebay_item($input_method['keyword'],$countrycode);
	//$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode);
	if(isset($input_method['user_id'])){
	$user_id	= $input_method['user_id'];
	}else{
	$user_id = 0;	
	}
	if(isset($input_method['is_camfind'])){
		if($input_method['is_camfind']==1){
	 $this->shop_model->saveCamfindWord($input_method);
		}
	}
	try{
	$amazon=$this->amazon_product($input_method['keyword'],$countrycode,$page,$user_id);
		} catch (Exception $e) {
			continue;
		}	
		 
	if((count($amazon)>0)){
	$product = array("amazon"=>$amazon);
	}
	if($product)
	{
		$this->response(array('message' => "Product results", 'status' => 1 , 'data'=>$product), 200);   
	}else{
		$this->response(array('message' => "No result found", 'status' => 0), 200);   
	}
	
  } 
  
  
       function productByKeywordCj()
  {
  	$page=1;
  	$product = $cj=array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
			if($input_method['lat']!=""&&$input_method['lng']!=""){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
		}else{
			$lat="55.3781"; $lng = "-3.4360";
		}
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	
  	//$ebay=$this->ebay_item($input_method['keyword'],$countrycode);
	//$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode);
	if(isset($input_method['page'])){
		$page=$input_method['page'];
	}
	if(isset($input_method['is_camfind'])){
		if($input_method['is_camfind']==1){
	 $this->shop_model->saveCamfindWord($input_method);
		}
	}
		if(isset($input_method['user_id'])){
	$user_id	= $input_method['user_id'];
	}else{
	$user_id = 0;	
	}
$input_method['keyword'] =	$this->shop_model->check_adultkeyword($input_method['keyword']);
	$cj=$this->cj_product($input_method['keyword'],$countrycode,$page,$user_id);
	if((count($cj)>0)){
		$product = array("cj"=>$cj);
	//$product = $cj;
	}
	if($product)
	{
		$this->response(array('message' => "Product results", 'status' => 1 , 'data'=>$product), 200);   
	}else{
		$this->response(array('message' => "No result found", 'status' => 0), 200);   
	}
	
  } 
  

  
  
  
  //---------------------------------amazon products-------------------------------------//

function amazon_product($keyword,$countrycode,$page=1,$user_id){
	$keyword =	$this->shop_model->check_adultkeyword($keyword);
	if($countrycode=="GB"){
		$region = "co.uk";
	}else{
		$region = "com";
	}
	 
	try{
	 $response = $this->amazon_api->getItemByKeyword($keyword, "All",$page,$region);
	  
	  	} catch (Exception $e) {
	  		}	
		
	// $amazon_val="mobile";
  // $keyword = urlencode($amazon_val);
  // $public = 'AKIAJ37T4QOQSMXKDW6A';//'AKIAJRXOEQG7IVQEVZ4A'; //amazon public key here Access Key ID:
// $private = 'X/iiwQXk2AeE8qc/MVymLBaJ5jaUNBdxhkRpCGSn';//'hLH+T/mBvkHZin+UP5SmL1IOkg10xuz8CH5NhG8X'; //amazon private/secret key here
// $site = 'com'; //amazon region
// $affiliate_id = 'what09e1-20';//swagmedi-20 //amazon affiliate id
// $amazon = $amazon = new AmazonProductAPI($public, $private, $site, $affiliate_id);
// $retailerlogo = AMAZON_LOGO;
// $content =array();
// $count_result=0;
// for($i=1;$i<2;$i++){
     // $result =	$amazon->getItemByKeyword($keyword,"All",$i);
// 
      // $dom = new DOMDocument;
      // $dom->loadXML($result);
    // // $response = array(); 
        // if (!$dom) {
            // echo 'Error while parsing the document';
            // exit;
        // }
       $ItemResponse =$response;
	   //print_r($ItemResponse);
   $count=count($ItemResponse->Items->Item);
    if($count>0){
        
       foreach($ItemResponse->Items->Item as $itemdata)
       {
       	sleep(1) ;
       	//echo "<pre>";
     try{
     $Item_info =	$this->amazon_api->getItemByAsin($itemdata->ASIN,$region);
		} catch (Exception $e) {
			continue;
		}	
      
         $itemid = array( (string) $Item_info->Items->Item->{'ASIN'} );
        $itemid = $itemid[0];
        $itemtitle = array( (string) $Item_info->Items->Item->ItemAttributes->{'Title'} );
        $itemtitle = $itemtitle[0];
        $item_url = array( (string) $Item_info->Items->Item->{'DetailPageURL'} );
        $item_url = $item_url[0];
        $item_image = array( (string) $Item_info->Items->Item->LargeImage->{'URL'} );
        $item_image = $item_image[0];
     $item_price = array( (string) $Item_info->Items->Item->OfferSummary->LowestNewPrice->{'FormattedPrice'} );
        $item_price = $item_price[0];
        $itemdescription = array( (string) $Item_info->Items->Item->EditorialReviews->EditorialReview->{'Content'} );
        $itemdescription = $itemdescription[0];
    $itemcat = array( (string) $Item_info->Items->Item->ItemAttributes->{'ProductTypeName'} );
     $itemcat = $itemcat[0];
if($page==1){
	$itemcat= str_replace("_"," ",$itemcat);
	$this->shop_model->savekeyword_forrecommendation($itemcat,$user_id);
}

 
   $access_content[]=$shop_product=array(
        "strProductId" => @$itemid,
            "strProductName" => @$itemtitle,
            "strLink" =>$item_url ,
            "strImageURL" => @$item_image,
            "strStorePrice" => $item_price,
           // "strStoreCat" => @$category,
            "strStoreName" => 'amazon'
        );
$this->shop_model->save_product_from_online($shop_product,"Amazon",$countrycode,$user_id);
		//print_r($access_content);exit;

	   }
 
	}
 
return $access_content;
}
  




//-----------------------------------end of amazon---------------------------------///
  
/// -------------------------advance  search---------------------------------/// 
  function productSearchAdvance()
  {
  	
  	$product = array();
	$resultProduct = $styniteShopProduct = array();
	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
		if(isset($input_method['lat'])&&isset($input_method['lng'])){
			if($input_method['lat']!=""&&$input_method['lng']!=""){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
		}else{
			$lat="55.3781"; $lng = "-3.4360";
		}
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
			if(isset($input_method['user_id'])){
	$user_id	= $input_method['user_id'];
	}else{
	$user_id = 0;	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	$val= $input_method['color']." ".$input_method['brand']." ".$input_method['keyword']." ".$input_method['gender']." ".$input_method['category'];
    $keyword=urlencode($val);
	
	/*
  	$input_method = array("keyword"=>"shirt","category"=>"electroinics","gender"=>"all","brand"=>"micromax",
	"color"=>"black","maxprice"=>"100000","minprice"=>"111","store"=>"amazone","page"=>"3");*/
	if($input_method['store']=="ebay"){
	$product=	$this->ebayAdvance($input_method,$countrycode,$user_id);
	}else if($input_method['store']=="amazon")
	{
   try{
	$product=	$this->amazonAdvance($input_method,$countrycode,$user_id);	
	} catch (Exception $e) {		
		 
	}
	}else if($input_method['store']=="linkshare")
	{
		
	$product =$this->linkshare_item($keyword,$countrycode,$page,$user_id);
	
	}else if($input_method['store']=="cj"){
		$product =$this->cj_product($keyword,$countrycode,$page,$user_id);
	}

		 
	if(($input_method['page']==1)&&($input_method['store']=="linkshare")){
	$resultProduct = $this->shop_model->getStyniteShopProduct($input_method,$countrycode);
		if(count($resultProduct)>0){
		foreach ($styniteShopProduct as  $value) {
			$resultProduct[] = $value;
		}
		}
	}
	
		if(count($product)>0){
			
		foreach ($product as  $value) {
			$resultProduct[] = $value;
		}
		}
	if(count($resultProduct)>0)
	{
		$this->response(array('message' => "Product results", 'status' => 1 , 'data'=>$resultProduct), 200);   
	}else{
		$this->response(array('message' => "No result found", 'status' => 0), 200);   
	}
	
  } 
function ebayAdvance($input,$countrycode,$user_id)
{
	 $accessflag=0;
	$val= $input['color']." ".$input['brand']." ".$input['keyword']." ".$input['gender']." ".$input['category'];
   $keyword =	$this->shop_model->check_adultkeyword($keyword);
    $keyword=urlencode($val);
	
  $url= 'http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsByKeywords&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=StyniteS-Stybnite-PRD-d14016a09-6bfc2b7b&GLOBAL-ID=EBAY-'.$countrycode.'&RESPONSE-DATA-FORMAT=JSON&callback=_cb_findItemsByKeywords&REST-PAYLOAD&keywords='.$keyword.'&descriptionSearch=true&paginationInput.pageNumber='.$input['page'].'&paginationInput.entriesPerPage=20&affiliate.trackingId=1234567890&affiliate.networkId=9&affiliate.customId=456';

  $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 $result = curl_exec($ch);

 $json=substr($result,28,-1);
 
// echo $json;
// exit;
 $json=json_decode($json,true);
 $count=count($json['findItemsByKeywordsResponse']['0']['searchResult']['0']['item']);

$access_content=array();
if($count>0){
 
 foreach(@$json['findItemsByKeywordsResponse']['0']['searchResult']['0']['item'] as $data){

    $itemid=$data['itemId']['0'];

    $title=$data['title']['0'];
if(!$data['title']['0'])
{
    $title='';
}
    $subtitle=$data['subtitle']['0'];
if(!$data['subtitle']['0'])
{
    $subtitle='';
}
    $viewItemURL=$data['viewItemURL']['0'];
if(!$data['viewItemURL']['0'])
{
    $viewItemURL='';
}

	$galleryURL="http://galleryplus.ebayimg.com/ws/web/".$itemid."_1_0_1.jpg";
if(!$data['galleryURL']['0'])
{
    $galleryURL='';
}
$price=$data['sellingStatus']['0']['currentPrice']['0']['__value__'];
$category = $data['primaryCategory']['0']['categoryName']['0'];
if($page==1){
	$this->shop_model->savekeyword_forrecommendation($category,$user_id);
}
$producturlwork = $this->check_ebayproduct($viewItemURL);
  if($producturlwork){
  
     $access_content[]=$shop_product=array(
        "strProductId" => @$itemid,
            "strProductName" => @$title,
            "strLink" =>$viewItemURL ,
            "strImageURL" => @$galleryURL,
            "strStorePrice" => $price,
            "strStoreName" => 'ebay'
        );
		$this->shop_model->save_product_from_online($shop_product,"Ebay",$countrycode,$user_id);
		}

  } 
  
    return $access_content;
}



 return $access_content;
}  
  
  
  
 function amazonAdvance($input,$countrycode,$user_id){
 	
	$access_content = array();
 	//price is 3241 is $32.41.
 	if($countrycode=="GB"){
 		$region = 'co.uk';
 	}else{
 		$region = 'com';
 	}
	
 	//$keyword= $input['color']." ".$input['brand']." ".$input['keyword']." ".$input['gender'];
	if($input['keyword']!=""){
		$keyword= $input['keyword'] ;
		$keyword =	$this->shop_model->check_adultkeyword($keyword);
	}else if($input['category']!=""){
		$keyword= $input['category'] ;
		$keyword =	$this->shop_model->check_adultkeyword($keyword);
	}else{
		$keyword= $input['brand'] ;
	}
	
	$parameters = array("Operation"     => "ItemSearch",                                             
                                                    "SearchIndex"   => "All",
                                                   "Keywords"    => $keyword,
                                                    "MaximumPrice"   => $input['maxprice'],
                                                "MinimumPrice"    =>$input['minprice'],
                                                "ItemPage" =>$input['page']
                                                
                                                 // "Brand"   => "nokia"
                                                  //   "MaximumPrice"   => $input['maxprice'],
                                                  // "MinimumPrice"    => $input['minprice'],
                                                  // "ItemPage" =>$input['page'], "ResponseGroup" => "Medium"
                                                   );
	

	try{
	 $response = $this->amazon_api->searchProductsAdvance($parameters,$region);
	 //$response = $this->amazon_api->getItemByKeyword($keyword, "All",$page,$region);
	 	} catch (Exception $e) {
		}	
	//print_r($response);exit;
       $ItemResponse =$response;
   $count=count($ItemResponse->Items->Item);
    if($count>0){
        
       foreach($ItemResponse->Items->Item as $itemdata)
       {
       	sleep(1) ;
       	//echo "<pre>";
     try{
     $Item_info =	$this->amazon_api->getItemByAsin($itemdata->ASIN,$region);
		} catch (Exception $e) {
			continue;
		}	
       
      
         $itemid = array( (string) $Item_info->Items->Item->{'ASIN'} );
        $itemid = $itemid[0];
        $itemtitle = array( (string) $Item_info->Items->Item->ItemAttributes->{'Title'} );
        $itemtitle = $itemtitle[0];
        $item_url = array( (string) $Item_info->Items->Item->{'DetailPageURL'} );
        $item_url = $item_url[0];
        $item_image = array( (string) $Item_info->Items->Item->LargeImage->{'URL'} );
        $item_image = $item_image[0];
     $item_price = array( (string) $Item_info->Items->Item->OfferSummary->LowestNewPrice->{'FormattedPrice'} );
        $item_price = $item_price[0];
        $itemdescription = array( (string) $Item_info->Items->Item->EditorialReviews->EditorialReview->{'Content'} );
        $itemdescription = $itemdescription[0];
    $itemcat = array( (string) $Item_info->Items->Item->ItemAttributes->{'ProductTypeName'} );
     $itemcat = $itemcat[0];

if($page==1){
	$itemcat= str_replace("_"," ",$itemcat);
	$this->shop_model->savekeyword_forrecommendation($itemcat,$user_id);
}
 
   $access_content[]=$shop_product=array(
        "strProductId" => @$itemid,
            "strProductName" => @$itemtitle,
            "strLink" =>$item_url ,
            "strImageURL" => @$item_image,
            "strStorePrice" => $item_price,
           // "strStoreCat" => @$category,
            "strStoreName" => 'amazon'
        );
$this->shop_model->save_product_from_online($shop_product,"Amazon",$countrycode,$user_id);
		//print_r($access_content);exit;

	   }

	}

return $access_content;
} 
  
//-------------end of advance--------------------------------//  
  
function ebay_item($val,$countrycode,$page=1,$user_id)
{
	 /*
	 &affiliate.trackingId=[yourcampaignid]
&affiliate.networkId=9
&affiliate.customId=[customid]
*/
    $accessflag=0;
	$keyword =	$this->shop_model->check_adultkeyword($val);
    $keyword=urlencode($val);
	
  $url= 'http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsByKeywords&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=StyniteS-Stybnite-PRD-d14016a09-6bfc2b7b&GLOBAL-ID=EBAY-'.$countrycode.'&RESPONSE-DATA-FORMAT=JSON&callback=_cb_findItemsByKeywords&REST-PAYLOAD&keywords='.$keyword.'&paginationInput.pageNumber='.$page.'&paginationInput.entriesPerPage=20&affiliate.trackingId=1234567890&affiliate.networkId=9&affiliate.customId=456';

  $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 $result = curl_exec($ch);

 $json=substr($result,28,-1);
 $json=json_decode($json,true);

//print_r($json);

$count=count($json['findItemsByKeywordsResponse']['0']['searchResult']['0']['item']);

$access_content=array();
if($count>0){
 
 foreach(@$json['findItemsByKeywordsResponse']['0']['searchResult']['0']['item'] as $data){

    $itemid=$data['itemId']['0'];

    $title=$data['title']['0'];
if(!$data['title']['0'])
{
    $title='';
}
    $subtitle=$data['subtitle']['0'];
if(!$data['subtitle']['0'])
{
    $subtitle='';
}
    $viewItemURL=$data['viewItemURL']['0'];
if(!$data['viewItemURL']['0'])
{
    $viewItemURL='';
}

	$galleryURL="http://galleryplus.ebayimg.com/ws/web/".$itemid."_1_0_1.jpg";
if(!$data['galleryURL']['0'])
{
    $galleryURL='';
}
$price=$data['sellingStatus']['0']['currentPrice']['0']['__value__'];
$category = $data['primaryCategory']['0']['categoryName']['0'];
if($page==1){
	$this->shop_model->savekeyword_forrecommendation($category,$user_id);
}
$producturlwork = $this->check_ebayproduct($viewItemURL);
  if($producturlwork){
     $access_content[]=$shop_product=array(
        "strProductId" => @$itemid,
            "strProductName" => @$title,
            "strLink" =>$viewItemURL ,
            "strImageURL" => @$galleryURL,
            "strStorePrice" => $price,
            "strStoreName" => 'ebay'
        );
		$this->shop_model->save_product_from_online($shop_product,"Ebay",$countrycode,$user_id);
		}

  } 
  
    return $access_content;
}



 return $access_content;
}
 
function check_ebayproduct($itemurl)
{
	$mystring = 'abc';
$findme   = 'item=0';
$pos = strpos($itemurl, $findme);

// Note our use of ===.  Simply == would not work as expected
// because the position of 'a' was the 0th (first) character.
if ($pos === false) {
    return true;
} else {
   // echo "The string '$findme' was found in the string '$mystring'";
   
   // this link will not open so we will skip
    return false;
}
} 
 
 
function linkshare_item($lval,$countrycode,$page=1,$user_id){
	$lval =	$this->shop_model->check_adultkeyword($lval);
	$access_content = array();
  define('DEVELOPERKEY','96453819c1685473c73df8bb2ff7e0c99294dd026f2c4f8eb201a8bed1e98e0d');
define('LSURL', 'http://productsearch.linksynergy.com/productsearch?');
$params="";

    $params.="&pagenumber=".$page."&keyword=".urlencode($lval);
	 //$params.="&lang=".urlencode($countrycode);


$apiurl=LSURL."token=".DEVELOPERKEY.$params;
//echo $apiurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiurl);
curl_setopt($ch, CURLOPT_HEADER, FALSE);  //curl_setopt($ch, CURLOPT_HEADER, FALSE);
//curl_setopt($ch,CURLOPT_GET,1);//curl_setopt($ch,CURLOPT_GET,1); error
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: ".DEVELOPERKEY)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//if($httpCode==200)
//{
     $dom = new DOMDocument;
      $dom->loadXML($response);
     $result = array(); 
        if (!$dom) {
            echo 'Error while parsing the document';
            exit;
        }
       $ItemResponse = simplexml_import_dom($dom);
    $count=count($ItemResponse->item);
    $content='';
       if($count>0){
        
    $accessflag=0;      

    foreach($ItemResponse->item as $result)
    {
         
  $itemlistid=    array( (string)   $result->linkid);
  $itemtitle=     array( (string)  $result->productname);
   $itemurl=    array( (string)  $result->linkurl);
  $itemimg=     array( (string)  $result->imageurl);
   $itemprice=  array( (string)    $result->price);
   $itemcat=    array( (string)  $result->category->primary);
    $retailer = array( (string)  $result->merchantname);     
   if(!$result->productname){
    $itemtitle='';
   }
    if(!$result->linkurl){
    $itemurl='';
   }
    if(!$result->imageurl){
    $itemimg='';
   }
   if(!$result->price){
    $itemprice='';
   }
if($page==1){
	$this->shop_model->savekeyword_forrecommendation($itemcat[0],$user_id);
}

//      $access_content.="<accessories>";
//     $access_content.="<strProductId>".$itemlistid."</strProductId>";
//        $access_content.="<strProductName><![CDATA[" .$itemtitle. "]]></strProductName>";
//        $access_content.="<strLink><![CDATA[" .$itemurl. "]]></strLink>";
//        $access_content.="<strImageURL><![CDATA[" .$itemimg. "]]></strImageURL>";
//                  $access_content.="<strStorePrice><![CDATA[" .$itemprice. "]]></strStorePrice>";
//                  $access_content.="<strStoreName>linkshare</strStoreName>";
//   $access_content.="</accessories>";
   $access_content[]=$shop_product=array(
        "strProductId" => @$itemlistid[0],
            "strProductName" => @$itemtitle[0],
            "strLink" =>$itemurl[0] ,
            "strImageURL" => @$itemimg[0],
            "strStorePrice" => $itemprice[0],
           // "strStoreCat" => @$category,
            "strStoreName" => $retailer[0]
        );
$this->shop_model->save_product_from_online($shop_product,"Linkshare",$countrycode,$user_id);

       
    } 
  
   }
  

return $access_content;
}   

//------ advertiser-------//

function linkshare_advertiser(){
	$access_content = array();
  define('DEVELOPERKEY','96453819c1685473c73df8bb2ff7e0c99294dd026f2c4f8eb201a8bed1e98e0d');
define('LSURL', 'https://api.rakutenmarketing.com/productsearch/1.0');
$params="";

    $params.="&pagenumber=".$page;
	 //$params.="&lang=".urlencode($countrycode);


$apiurl=LSURL."token=".DEVELOPERKEY.$params;
//echo $apiurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, LSURL);
curl_setopt($ch, CURLOPT_HEADER, FALSE);  //curl_setopt($ch, CURLOPT_HEADER, FALSE);
//curl_setopt($ch,CURLOPT_GET,1);//curl_setopt($ch,CURLOPT_GET,1); error
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: ".DEVELOPERKEY)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
print_r($response);exit;


$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//if($httpCode==200)
//{
     $dom = new DOMDocument;
      $dom->loadXML($response);
     $result = array(); 
        if (!$dom) {
            echo 'Error while parsing the document';
            exit;
        }
       $ItemResponse = simplexml_import_dom($dom);
    $count=count($ItemResponse->item);
    $content='';
       if($count>0){
        
    $accessflag=0;      

    foreach($ItemResponse->item as $result)
    {
         
  $itemlistid=    array( (string)   $result->linkid);
  $itemtitle=     array( (string)  $result->productname);
   $itemurl=    array( (string)  $result->linkurl);
  $itemimg=     array( (string)  $result->imageurl);
   $itemprice=  array( (string)    $result->price);
   $itemcat=    array( (string)  $result->category->primary);
    $retailer = array( (string)  $result->merchantname);     
   if(!$result->productname){
    $itemtitle='';
   }
    if(!$result->linkurl){
    $itemurl='';
   }
    if(!$result->imageurl){
    $itemimg='';
   }
   if(!$result->price){
    $itemprice='';
   }


//      $access_content.="<accessories>";
//     $access_content.="<strProductId>".$itemlistid."</strProductId>";
//        $access_content.="<strProductName><![CDATA[" .$itemtitle. "]]></strProductName>";
//        $access_content.="<strLink><![CDATA[" .$itemurl. "]]></strLink>";
//        $access_content.="<strImageURL><![CDATA[" .$itemimg. "]]></strImageURL>";
//                  $access_content.="<strStorePrice><![CDATA[" .$itemprice. "]]></strStorePrice>";
//                  $access_content.="<strStoreName>linkshare</strStoreName>";
//   $access_content.="</accessories>";
   $access_content[]=array(
        "strProductId" => @$itemlistid[0],
            "strProductName" => @$itemtitle[0],
            "strLink" =>$itemurl[0] ,
            "strImageURL" => @$itemimg[0],
            "strStorePrice" => $itemprice[0],
           // "strStoreCat" => @$category,
            "strStoreName" => $retailer[0]
        );

       
    } 
  
   }
  

return $access_content;
}   




//-----------end of advertiser----///

//------------------------------------------------------cj product-------------------------------------------///
function cj_product($cj_val,$countrycode,$page=1,$user_id)
{
	$cj_val =	$this->shop_model->check_adultkeyword($cj_val);
    $access_content = array();
    //
    // Build REST URI for product search. Refer to 
    // documentation for more request parameters.
    //
$URI = 'https://product-search.api.cj.com/v2/product-search?'.
        'website-id=8059028'.   // USE YOUR OWN.
       '&advertiser-ids=joined'.
        '&low-price=1&page-number='.$page.
        '&records-per-page=20'.
        '&sort-order=asc'. 
        '&sort-by=name'. 
        '&keywords='. rawurlencode($cj_val);

    // $context = stream_context_create(
    // array(
    // 'http' => array(
        // 'method' => 'GET',
        // 'header' => 'Authorization: ' . // USE YOUR OWN.
            // '00a281966068f26b7c6275d3faec6416cd812384c685b5b45e152947f4cd3f843eb7b626e86eb5e31901b0e8bffc7356693b6a1bafd16a2d275b64e7f65a9a380d'
        // )
    // ));
// 	
// 	
// 
    // $response = new SimpleXMLElement(file_get_contents($URI, false, $context));
// 	
	//echo $apiurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $URI);
curl_setopt($ch, CURLOPT_HEADER, FALSE);  //curl_setopt($ch, CURLOPT_HEADER, FALSE);
//curl_setopt($ch,CURLOPT_GET,1);//curl_setopt($ch,CURLOPT_GET,1); error
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'method' => 'GET',
        'header' => 'Authorization: ' . // USE YOUR OWN.
            '008e0e556d4414b754ee430a2e987723fc94b06651d4a346fba284fcad73f2eb31f6c1924b1288925d071d407ac6b8960bc4137d14378f5d714d10a84480646d61/463aafe1251758cb943f707938c72df3614f1446c069385e8f2a500130a197c48cf6a8b1dc813b27807eb790374fa0b4b9c1036ac78aa74991989f50f3012c01'
      )); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
	//print_r($response);exit;
	
	$response = new SimpleXMLElement($response);

    $cjcount=count($response->products);
    $content='';
            
if($cjcount>0){
     
          foreach($response->products->product as $cjitem)
          {
                  $cjitemcat = array( (string) $cjitem->{'advertiser-category'} );
               $itemcat = $cjitemcat[0];
               $cjitemid = array( (string) $cjitem->{'ad-id'} );
               $itemlistid = $cjitemid[0];
               $cjitemname = array( (string) $cjitem->{'name'} );
                $itemtitle = $cjitemname[0];
                if(!$cjitem->{'name'}){
                  $itemtitle = '';  
                }
                $cjitemurl = array( (string) $cjitem->{'buy-url'} );
                $itemurl = $cjitemurl[0] ;
                if(!$cjitem->{'buy-url'})
                {
                  $itemurl = '';  
                }
                $cjitemimg = array( (string) $cjitem->{'image-url'} );
                $itemimg = $cjitemimg[0];
                if(!$cjitem->{'image-url'})
                {
                  $itemimg = '';  
                }
                $cjitemprice = array( (string) $cjitem->{'price'} );
               $itemprice = $cjitemprice[0];
               if(!$cjitem->{'price'})
                {
                  $itemprice = '';  
                }
				
                $cjretailer = array( (string) $cjitem->{'advertiser-name'} );
                $retailer = $cjretailer[0];
             
                         $access_content[]= $shop_product =array(
                                            "strProductId" => @$itemlistid,
                                                "strProductName" => @$itemtitle,
                                                "strLink" =>$itemurl ,
                                                "strImageURL" => @$itemimg,
                                                "strStorePrice" => $itemprice,
                                               // "strStoreCat" => @$category,
                                                "strStoreName" => $retailer
                                            );
               $this->shop_model->save_product_from_online($shop_product,"CJ",$countrycode,$user_id);
            
            
          }
          
                   

}

   


return $access_content;
  
}
function cj_advertiser()
{
    $access_content = array();
    //
    // Build REST URI for product search. Refer to 
    // documentation for more request parameters.
    //
$URI = 'https://advertisers.api.cj.com/v2/advertisers';

    // $context = stream_context_create(
    // array(
    // 'http' => array(
        // 'method' => 'GET',
        // 'header' => 'Authorization: ' . // USE YOUR OWN.
            // '00a281966068f26b7c6275d3faec6416cd812384c685b5b45e152947f4cd3f843eb7b626e86eb5e31901b0e8bffc7356693b6a1bafd16a2d275b64e7f65a9a380d'
        // )
    // ));
// 	
// 	
// 
    // $response = new SimpleXMLElement(file_get_contents($URI, false, $context));
// 	
	//echo $apiurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $URI);
curl_setopt($ch, CURLOPT_HEADER, FALSE);  //curl_setopt($ch, CURLOPT_HEADER, FALSE);
//curl_setopt($ch,CURLOPT_GET,1);//curl_setopt($ch,CURLOPT_GET,1); error
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'method' => 'GET',
        'header' => 'Authorization: ' . // USE YOUR OWN.
            '008e0e556d4414b754ee430a2e987723fc94b06651d4a346fba284fcad73f2eb31f6c1924b1288925d071d407ac6b8960bc4137d14378f5d714d10a84480646d61/463aafe1251758cb943f707938c72df3614f1446c069385e8f2a500130a197c48cf6a8b1dc813b27807eb790374fa0b4b9c1036ac78aa74991989f50f3012c01'
      )); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
	print_r($response);exit;
	
	$response = new SimpleXMLElement($response);

    $cjcount=count($response->products);
    $content='';
            
if($cjcount>0){
     
          foreach($response->products->product as $cjitem)
          {
                  $cjitemcat = array( (string) $cjitem->{'advertiser-category'} );
               $itemcat = $cjitemcat[0];
               $cjitemid = array( (string) $cjitem->{'ad-id'} );
               $itemlistid = $cjitemid[0];
               $cjitemname = array( (string) $cjitem->{'name'} );
                $itemtitle = $cjitemname[0];
                if(!$cjitem->{'name'}){
                  $itemtitle = '';  
                }
                $cjitemurl = array( (string) $cjitem->{'buy-url'} );
                $itemurl = $cjitemurl[0] ;
                if(!$cjitem->{'buy-url'})
                {
                  $itemurl = '';  
                }
                $cjitemimg = array( (string) $cjitem->{'image-url'} );
                $itemimg = $cjitemimg[0];
                if(!$cjitem->{'image-url'})
                {
                  $itemimg = '';  
                }
                $cjitemprice = array( (string) $cjitem->{'price'} );
               $itemprice = $cjitemprice[0];
               if(!$cjitem->{'price'})
                {
                  $itemprice = '';  
                }
				
                $cjretailer = array( (string) $cjitem->{'advertiser-name'} );
                $retailer = $cjretailer[0];
             
                         $access_content[]=array(
                                            "strProductId" => @$itemlistid,
                                                "strProductName" => @$itemtitle,
                                                "strLink" =>$itemurl ,
                                                "strImageURL" => @$itemimg,
                                                "strStorePrice" => $itemprice,
                                               // "strStoreCat" => @$category,
                                                "strStoreName" => $retailer
                                            );
               
            
            
          }
          
                   

}

   


return $access_content;
  
}

//----------------test cj----//
function cj_product_check()
{
	$page = $_REQUEST['page'];
    $access_content = array();
    //
    // Build REST URI for product search. Refer to 
    // documentation for more request parameters.
    //
$URI = 'https://product-search.api.cj.com/v2/product-search?'.
        'website-id=8059028'.   // USE YOUR OWN.
      // '&advertiser-ids=joined'.
        '&low-price=1&page-number='.$page.
        '&records-per-page=20'.
        '&sort-order=asc'. 
        '&sort-by=name'. 
        '&keywords='. rawurlencode($_REQUEST['keyword']);

    // $context = stream_context_create(
    // array(
    // 'http' => array(
        // 'method' => 'GET',
        // 'header' => 'Authorization: ' . // USE YOUR OWN.
            // '00a281966068f26b7c6275d3faec6416cd812384c685b5b45e152947f4cd3f843eb7b626e86eb5e31901b0e8bffc7356693b6a1bafd16a2d275b64e7f65a9a380d'
        // )
    // ));
// 	
// 	
// 
    // $response = new SimpleXMLElement(file_get_contents($URI, false, $context));
// 	
	//echo $apiurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $URI);
curl_setopt($ch, CURLOPT_HEADER, FALSE);  //curl_setopt($ch, CURLOPT_HEADER, FALSE);
//curl_setopt($ch,CURLOPT_GET,1);//curl_setopt($ch,CURLOPT_GET,1); error
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'method' => 'GET',
        'header' => 'Authorization: ' . // USE YOUR OWN.
            '008e0e556d4414b754ee430a2e987723fc94b06651d4a346fba284fcad73f2eb31f6c1924b1288925d071d407ac6b8960bc4137d14378f5d714d10a84480646d61/463aafe1251758cb943f707938c72df3614f1446c069385e8f2a500130a197c48cf6a8b1dc813b27807eb790374fa0b4b9c1036ac78aa74991989f50f3012c01'
      )); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
	print_r($response);exit;
	
	$response = new SimpleXMLElement($response);

    $cjcount=count($response->products);
    $content='';
            
if($cjcount>0){
     
          foreach($response->products->product as $cjitem)
          {
                  $cjitemcat = array( (string) $cjitem->{'advertiser-category'} );
               $itemcat = $cjitemcat[0];
               $cjitemid = array( (string) $cjitem->{'ad-id'} );
               $itemlistid = $cjitemid[0];
               $cjitemname = array( (string) $cjitem->{'name'} );
                $itemtitle = $cjitemname[0];
                if(!$cjitem->{'name'}){
                  $itemtitle = '';  
                }
                $cjitemurl = array( (string) $cjitem->{'buy-url'} );
                $itemurl = $cjitemurl[0] ;
                if(!$cjitem->{'buy-url'})
                {
                  $itemurl = '';  
                }
                $cjitemimg = array( (string) $cjitem->{'image-url'} );
                $itemimg = $cjitemimg[0];
                if(!$cjitem->{'image-url'})
                {
                  $itemimg = '';  
                }
                $cjitemprice = array( (string) $cjitem->{'price'} );
               $itemprice = $cjitemprice[0];
               if(!$cjitem->{'price'})
                {
                  $itemprice = '';  
                }
				
                
             
                         $access_content[]=array(
                                            "strProductId" => @$itemlistid,
                                                "strProductName" => @$itemtitle,
                                                "strLink" =>$itemurl ,
                                                "strImageURL" => @$itemimg,
                                                "strStorePrice" => $itemprice,
                                               // "strStoreCat" => @$category,
                                                "strStoreName" => 'cj'
                                            );
               
            
            
          }
          
                   

}

   


return $access_content;
  
}


//---------------------------------------------------end of cj product--------------------------------------///

    
} 

?>
