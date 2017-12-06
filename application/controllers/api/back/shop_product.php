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
		$this->load->model('admin_model');
		$this->load->library('amazon_api'); 
        
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
	

     function productByKeyword()
  {
  	
  	$product=$linkshare =$ebay= array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	
  	$ebay=$this->ebay_item($input_method['keyword'],$countrycode);
	$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode);
	
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
  	$product=$linkshare =$ebay= array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	
	if(isset($input_method['page'])){
		$page=$input_method['page'];
	}
  	$ebay=$this->ebay_item($input_method['keyword'],$countrycode,$page);
	//$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode);
	
	if(count($ebay)>0){
	$product = $ebay;
	}
	if($product)
	{
		$this->response(array('message' => "Product results", 'status' => 1 , 'data'=>$product), 200);   
	}else{
		$this->response(array('message' => "No result found", 'status' => 0), 200);   
	}
	
  } 
  
  
     function productByKeywordLinkshare()
  {
  	$page=1;
  	$product=$linkshare =$ebay= array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	if(isset($input_method['page'])){
		$page=$input_method['page'];
	}
  	//$ebay=$this->ebay_item($input_method['keyword'],$countrycode);
	$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode,$page);
	
	if(count(count($linkshare)>0)){
	$product = array("linkshare"=>$linkshare);
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
  	
  	$product =$amazon= array();
  	$input_method = $this->webservices_inputs();
	$this->validate_param('product_by_keyword',$input_method);	
	
	if(isset($input_method['lat'])&&isset($input_method['lng'])){
		$lat=$input_method['lat']; $lng=$input_method['lng'];
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	
  	//$ebay=$this->ebay_item($input_method['keyword'],$countrycode);
	//$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode);
	$amazon=$this->amazon_product($input_method['keyword'],$countrycode);
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
		$lat=$input_method['lat']; $lng=$input_method['lng'];
	}else{
	$lat="55.3781"; $lng = "-3.4360";	
	}
	$countrycode = $this->getCountryCode($lat,$lng);
	
  	//$ebay=$this->ebay_item($input_method['keyword'],$countrycode);
	//$linkshare=$this->linkshare_item($input_method['keyword'],$countrycode);
	if(isset($input_method['page'])){
		$page=$input_method['page'];
	}
	$cj=$this->cj_product($input_method['keyword'],$countrycode,$page);
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

function amazon_product($keyword){
	
	
	 $response = $this->amazon_api->getItemByKeyword($keyword, "All");
	 
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
   $count=count($ItemResponse->Items->Item);
    if($count>0){
        
       foreach($ItemResponse->Items->Item as $itemdata)
       {
       	//echo "<pre>";
     
     $Item_info =	$this->amazon_api->getItemByAsin($itemdata->ASIN);
	
       
      
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


 
   $access_content[]=array(
        "strProductId" => @$itemid,
            "strProductName" => @$itemtitle,
            "strLink" =>$item_url ,
            "strImageURL" => @$item_image,
            "strStorePrice" => $item_price,
           // "strStoreCat" => @$category,
            "strStoreName" => 'amazon'
        );

		//print_r($access_content);exit;

	   }

	}

return $access_content;
}
  




//-----------------------------------end of amazon---------------------------------///
  
  
  
  
  
  
  
  
  
  
function ebay_item($val,$countrycode,$page=1)
{
	 /*
	 &affiliate.trackingId=[yourcampaignid]
&affiliate.networkId=9
&affiliate.customId=[customid]
*/
    $accessflag=0;
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


  
     $access_content[]=array(
        "strProductId" => @$itemid,
            "strProductName" => @$title,
            "strLink" =>$viewItemURL ,
            "strImageURL" => @$galleryURL,
            "strStorePrice" => $price,
            "strStoreName" => 'ebay'
        );
		


  } 
  
    return $access_content;
}



 return $access_content;
}
 
function linkshare_item($lval,$countrycode,$page=1){
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
            "strStoreName" => 'linkshare'
        );

       
    } 
  
   }
  

return $access_content;
}   


//------------------------------------------------------cj product-------------------------------------------///
function cj_product($cj_val,$countrycode,$page=1)
{
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
            '00a281966068f26b7c6275d3faec6416cd812384c685b5b45e152947f4cd3f843eb7b626e86eb5e31901b0e8bffc7356693b6a1bafd16a2d275b64e7f65a9a380d/00853d46f48957aa3573ed33ff1a6efe1b44f4c05a9d2adc55ff77b75dca2377e33d4045cef68b9b427471c7c764dde448b5dd0d83deca21d7bd17a30af80c2ba9'
      )); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
	
	
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
