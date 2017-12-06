<?php
class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url','constant_helper','function_helper'));
        $this->load->model('admin_model');
        $this->load->model('users_photos_model');
        $this->load->model('notification_model');
        $this->load->model('bankdetail_model');
        $this->load->library('session');
        $this->clear_cache();
        $user = $this->session->userdata('logged_in');
        if (!$user) {
            redirect('login/index');
        }
        if($this->session->userdata('is_admin')!='1'){
            $bank_detail = $this->bankdetail_model->get_retailer_bankdetail();
            //print_r($bank_detail);
            if(count($bank_detail)>0){

            }else{
                redirect('bankdetail/index');
            }

        }
    }

    function index()
    {
    	if($this->session->userdata('is_admin')==1)
		{
			//echo '<script>window.location.href='.site_url('admin/retailers').'</script>';
			redirect('admin/retailers');
		}
        @$products = "";
       $products = $this->admin_model->get_products();
      
        $data['active_page'] = 'product';
	$data['products']=$products;
        $this->load->view('templates/header', $data);
        $this->load->view('products',$data);
    }
	    function product_preview()
    {
    	 
        
        $this->load->view('product_preview');
    }
	   function map()
    {
    	 
        
        $this->load->view('map');
    }
	
	    function dashboard()
    {
    	if($this->session->userdata('is_admin')==1)
		{
			//echo '<script>window.location.href='.site_url('admin/retailers').'</script>';
			redirect('admin/retailers');
		}
        @$products = "";
       $products = $this->admin_model->get_products_dashboard("VIEWED");
	    $searched = $this->admin_model->get_products_dashboard("SEARCHED");
		 $clickbuy = $this->admin_model->get_products_dashboard("CLICK_BUY_LINK");
		  $buy = $this->admin_model->get_products_dashboard("BUY");
      
	  	$data['buy']=$buy;
			$data['clickbuy']=$clickbuy;
				$data['searched']=$searched;
	  
        $data['active_page'] = 'dashboard';
	$data['products']=$products;
        $this->load->view('templates/header', $data);
        $this->load->view('dashboardproducts',$data);
    }
	    function check_email()
   {
    $check = $this->admin_model->check_email();
    if($check)
    {
        echo 0;
    }else{
        echo 1; 
    }
   }
      function check_businessname()
   {
    $check = $this->admin_model->check_businessname();
    if($check)
    {
        echo 0;
    }else{
        echo 1; 
    }
   }
   
   function productAddByFeed()
   {
   	$url = "https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&q=http://www.digg.com/rss/index.xml";
    $productresponse = 	$this->feedLinkResponse($url);
	
	//echo $productresponse;
	$productarr = json_decode($productresponse,true);
	//print_r($productarr);
	
	
	/// decode the json for each product detail and pass each array value like below.
	$productDetail = array("title"=>"Product titel",
	                       "description"=>"Des",
	                       "quantity"=>"2",
	                       "price"=>"1",
	                       "imagelink"=> "http://stynite-products.s3.amazonaws.com/583e9619d16c2.jpeg"
	);
	
	$this->admin_model->save_feed_product($productDetail);
	echo 1;
	 
   }
   
   function feedLinkResponse($url)
   {
    

	//print_r($fields);		
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, false);
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
$result = curl_exec($ch);
if ($result === FALSE) {
	die('FCM Send Error: ' . curl_error($ch));
}
curl_close($ch);
//echo 'endsend';
 $result;
 //print_r($result);
return $result;
	
   }
   
     function affiliate_retailers()
	 {
	 	$cjretailer = $linkshareretailer = array();
	 	 $data['active_page'] = 'affiliate_retailers';
		 $linkshareretailer = $this->linkshare_advertiser();
		//$cjretailer =  $this->cj_retailer();
	$other[]	= array(
        "mid" => @"ebay",
            "merchantname" => @"ebay",  
            "storeName" => "Ebay"
        );
		$other[]	= array(
        "mid" => @"amazon",
            "merchantname" => @"amazon",  
            "storeName" => "Amazon"
        );
		$linkshareretailer =  array_merge($other,$linkshareretailer);
		$merchant =  array_merge($linkshareretailer,$cjretailer);
		 
$data['merchant'] = $merchant;
        $this->load->view('templates/header', $data);
        $this->load->view('affiliate_retailers',$data);
	 }
   
   function linkshare_advertiser(){
   	
  $token =	$this->linkshare_token();
	$content = array();
 
$adverurl = 'https://api.rakutenmarketing.com/advertisersearch/1.0';
 
//echo $apiurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $adverurl);
curl_setopt($ch, CURLOPT_HEADER, FALSE);  //curl_setopt($ch, CURLOPT_HEADER, FALSE);
//curl_setopt($ch,CURLOPT_GET,1);//curl_setopt($ch,CURLOPT_GET,1); error
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
//print_r($response);exit;


  $dom = new DOMDocument;
      $dom->loadXML($response);
     $result = array(); 
        if (!$dom) {
           // echo 'Error while parsing the document';
          //  exit;
        }
       $ItemResponse = simplexml_import_dom($dom);
 
$count=count($ItemResponse->midlist->merchant);
    $content='';
       if($count>0){
       	foreach($ItemResponse->midlist->merchant as $result)
    {
    	//print_r($result);
		$mid=    array( (string)   $result->mid);
        $merchantname=     array( (string)  $result->merchantname);
		 $content[]=array(
        "mid" => @$mid[0],
            "merchantname" => @$merchantname[0],  
            "storeName" => "Linkshare"
        );
	}
	   }
 
return $content;
}   
   function cj_retailer()
{
    $access_content = array();
    //
    // Build REST URI for product search. Refer to 
    // documentation for more request parameters.
    //
$URI = 'https://advertiser-lookup.api.cj.com/v2/advertiser-lookup?advertiser-ids=joined';

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
$res = curl_exec($ch);
//echo "<pre>";
// print_r($response);
	
	$response = new SimpleXMLElement($res);
	//print_r($response); 
    $cjcount=count($response->advertisers->advertiser);
    $content='';

      	//print_r($response->advertisers->advertiser);      
   
if($cjcount>0){
     
          foreach($response->advertisers->advertiser as $cjitem)
          {
          	//print_r($cjitem);
                  $cjitemid = array( (string) $cjitem->{'advertiser-id'} );
               $mid = $cjitemid[0];
               $cjitemname = array( (string) $cjitem->{'advertiser-name'} );
               $merchantname = $cjitemname[0];
               
                         $access_content[]=array(
                                            "mid" => $mid,
                                                "merchantname" => $merchantname,
                                                "storeName" =>"CJ"  
                                            );
               
            
            
          }
          
                   

}

//print_r($access_content);exit;

return $access_content;
  
}
   function linkshare_token(){
	
	
// 	
		// $url = 'http://www.stynite.com/contact-us/';
// 		
			// $data = array('your-email' => $_REQUEST['your-email'], 'your-message' => $_REQUEST['your-message'],
			// 'your-name' => $_REQUEST['your-name'], 'your-subject' => $_REQUEST['your-subject']);
// 
// // You can POST a file by prefixing with an @ (for <input type="file"> fields)
// 
//  
// $handle = curl_init($url);
// curl_setopt($handle, CURLOPT_POST, true);
// curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
// curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
// $res= curl_exec($handle);
// echo $res;
// 	
// 	
	
	////////////////////////////
	$access_content = array();
 
//define('LSURL', 'https://api.rakutenmarketing.com/token');
$url = 'https://api.rakutenmarketing.com/token';
$data = array('grant_type' => 'password', 'username' => 'stynite123',
			'password' => 'faizan123','scope'=>'3321614');
$datastr = json_encode($data);
 
 $headers = array('Content-Type:application/x-www-form-urlencoded',
                "Authorization:Basic aXpJYllvTWZxX1JvMF9JZTZ6UjluQUFpbkZBYTp2MDdKVVZHN0JpZG5WVU9wXzNmV0Q3U2hlQWth");
//echo $apiurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=password&username=stynite123&password=faizan123&scope=3321614');
//curl_setopt($ch, CURLOPT_POSTFIELDS, $datastr);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
//echo $response;
$resultarr = json_decode($response,true);
//print_r($response);
return $resultarr['access_token'];
//exit;


//return $access_content;
}  
   
  ////end of linkshare 
	    function retailers()
    {
        
      
      
        $data['active_page'] = 'retailer';

        $this->load->view('templates/header', $data);
        $this->load->view('retailers',$data);
    }

    function sales()
    {
        
      
      
        $data['active_page'] = 'sales';

        $this->load->view('templates/header', $data);
        $this->load->view('sales',$data);
    }
    
       function balance_history()
    {
        
      
      
        $data['active_page'] = 'balance_history';

        $this->load->view('templates/header', $data);
		if($this->session->userdata('is_admin')=='1')
                {
        $this->load->view('admin_balance_history',$data);         	
				}else{
        $this->load->view('balance_history',$data);
    
				}
	}
    
    
    
     function notification_list()
    {
        
      
      
        $data['active_page'] = 'notification';

        $this->load->view('templates/header', $data);
        $this->load->view('notification_list',$data);
    }

function retailer_notification_list()
    {
        
      
      
        $data['active_page'] = 'notification';

        $this->load->view('templates/header', $data);
        $this->load->view('retailer_notification_list',$data);
    }
    
        function totalsales()
    {
        
      
      
        $data['active_page'] = 'totalsales';

        $this->load->view('templates/header', $data);
        $this->load->view('totalsales',$data);
    }
	
	  function retailer_products()
    {
        $retailer = $this->admin_model->get_a_retailer($_GET['id']);
        $data['active_page'] = 'retailer';
	  $data['retailer'] = $retailer;
        $this->load->view('templates/header', $data);
        $this->load->view('retailer_products',$data);
    }
	
 function manage_product()
    {
        @$product = "";
		$product_keyword_ids = array();
        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
            $product = $this->admin_model->get_products($id);
             $product_keyword_ids  = $this->admin_model->get_product_keywords($id);
        }
    $keywords = $this->admin_model->get_keywords();    
  $data['keywords'] = $keywords;
  $data['product'] = $product;
  $data['product_keyword_ids'] = $product_keyword_ids;
           $data['active_page'] = 'manage_product';
        $this->load->view('templates/header', $data);
        $this->load->view('manage_product');
    }

 function  product_statistics()
    {
        @$product = "";
		$product_keyword_ids = array();
        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
            $product = $this->admin_model->get_products($id);
             
        }
    $product_view = $this->admin_model->get_products_stats($id,"VIEWED");
	$product_search = $this->admin_model->get_products_stats($id,"SEARCHED");
	$product_buylink_click = $this->admin_model->get_products_stats($id,"CLICK_BUY_LINK");
	$product_buy  = $this->admin_model->get_products_stats($id,"BUY");
	 
	$product_view_count = $this->admin_model->get_products_stats_total($id,"VIEWED");
	$product_search_count = $this->admin_model->get_products_stats_total($id,"SEARCHED");
	$product_buylink_click_count = $this->admin_model->get_products_stats_total($id,"CLICK_BUY_LINK");
	$product_buy_count  = $this->admin_model->get_products_stats_total($id,"BUY");
	 
	  $data['product_view_count'] = $product_view_count;
	  $data['product_search_count'] = $product_search_count;
	   $data['product_buylink_click_count'] = $product_buylink_click_count;
	   $data['product_buy_count'] = $product_buy_count;
	 
	 
	 $data['product_view'] = $product_view;
	  $data['product_search'] = $product_search;
	   $data['product_buylink_click'] = $product_buylink_click;
	   $data['product_buy'] = $product_buy;
	
	
	
  $data['product'] = $product;
 
           $data['active_page'] = 'manage_product';
        $this->load->view('templates/header', $data);
        $this->load->view('product_statistics');
    }
	
	 function manage_keyword()
    {
        @$keyword = $product_keyword_ids="";
        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
            $keyword = $this->admin_model->get_keywords($id);
             
        }
 $data['keyword'] = $keyword;
           $data['active_page'] = 'keyword';
        $this->load->view('templates/header', $data);
        $this->load->view('manage_keyword');
    }

	 function manage_adultkeyword()
    {
        @$keyword = $product_keyword_ids="";
        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
            $keyword = $this->admin_model->get_adultkeywords($id);
             
        }
 $data['keyword'] = $keyword;
           $data['active_page'] = 'adultkeywords';
        $this->load->view('templates/header', $data);
        $this->load->view('manage_adultkeyword');
    }

		function save_product()
	{
		$id=0;
		if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		    $res = $this->admin_model->save_product($id);
        if ($res) {
            redirect('admin/index');
        } else {
            // set the message that show the data is not save;
            redirect('admin/index');
        }
	}
		function save_camkeyword()
	{
		$id=0;
		if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		    $res = $this->admin_model->save_camkeyword($id);
        if ($res) {
             echo 1;
        } else {
            // set the message that show the data is not save;
           echo 0;
        }
	}
		function save_keyword()
	{
		$id=0;
		if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		    $res = $this->admin_model->save_keyword($id);
        if ($res) {
             redirect('admin/keywords');
        } else {
            // set the message that show the data is not save;
           redirect('admin/keywords');
        }
	}
			function save_adultkeyword()
	{
		$id=0;
		if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		    $res = $this->admin_model->save_adultkeyword($id);
        if ($res) {
             redirect('admin/adultkeywords');
        } else {
            // set the message that show the data is not save;
           redirect('admin/adultkeywords');
        }
	}
	
	
		  function keywords()
    {
   
        $data['active_page'] = 'keywords';
	  
        $this->load->view('templates/header', $data);
        $this->load->view('keywords',$data);
    }
	
		function get_keywords_dt()
	{
		 
		$user = $this->admin_model->get_keywords_dt();
		echo json_encode($user); 
	}
	
			  function adultkeywords()
    {
   
        $data['active_page'] = 'adultkeywords';
	  
        $this->load->view('templates/header', $data);
        $this->load->view('adultkeywords',$data);
    }
	
		function get_adultkeywords_dt()
	{
		 
		$user = $this->admin_model->get_adultkeywords_dt();
		echo json_encode($user); 
	}
			  function camfindkeywords()
    {
   
        $data['active_page'] = 'camfindkeywords';
	  
        $this->load->view('templates/header', $data);
        $this->load->view('camfindkeywords',$data);
    }
	
		function get_camfindkeywords_dt()
	{
		 
		$user = $this->admin_model->get_camfindkeywords_dt();
		echo json_encode($user); 
	}
	
		 function camfindkeywords_manage_keyword()
    {
        @$keyword = $product_keyword_ids="";
        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
            $keyword = $this->admin_model->get_camkeywords($id);
             
        }
 $data['keyword'] = $keyword;
           $data['active_page'] = 'camfindkeywords';
        $this->load->view('templates/header', $data);
        $this->load->view('camfind_manage_keyword');
    }
	
		function get_retailerproducts_dt()
	{
		 
		$user = $this->admin_model->get_retailerproducts_dt();
		echo json_encode($user); 
	}
	function get_products_dt()
	{
		$group = $this->uri->segment(3);
		$user = $this->admin_model->get_products_dt();
		echo json_encode($user); 
	}
		function get_retailers_dt()
	{
		 
		$retailer = $this->admin_model->get_retailers_dt();
		echo json_encode($retailer); 
	}
		function get_sales_dt()
	{
		 
		$retailer = $this->admin_model->get_sales_dt();
		echo json_encode($retailer); 
	}
	
	function get_balancehistory_dt()
	{
		 
		$retailer = $this->admin_model->get_balancehistory_dt();
		echo json_encode($retailer); 
	}
	function get_admin_balancehistory_dt()
	{
		 
		$retailer = $this->admin_model->get_admin_balancehistory_dt();
		echo json_encode($retailer); 
	}
	
	
	
	
	function get_notification_dt()
	{
		 
		$retailer = $this->notification_model->get_notification_dt();
		echo json_encode($retailer); 
	}
	
	function get_retailernotification_dt()
	{
		 
		$retailer = $this->notification_model->get_retailernotification_dt();
		echo json_encode($retailer); 
	}
	
		function get_totalsales_dt()
	{
		 
		$retailer = $this->admin_model->get_totalsales_dt();
		echo json_encode($retailer); 
	}
	function delete_products()
	{
		$res = $this->admin_model->delete_products();
		echo $res;
	}
	function notice_action()
	{
		$res = $this->notification_model->notice_action();
		echo $res;
	}
	function delete_notice()
	{
		$res = $this->notification_model->delete_notice();
		echo $res;
	}
	function merge_keywords()
	{
		$res = $this->admin_model->merge_keywords();
		echo $res;
	}
	
	
	function retaileraction()
	{
		$res = $this->admin_model->retaileraction();
		echo $res;
		
	}
	
	function keywordaction()
	{
		$res = $this->admin_model->keywordaction();
		echo $res;
		
	}
	
	function adultkeywordaction()
	{
		$res = $this->admin_model->adultkeywordaction();
		echo $res;
		
	}
	
	
	function camkeywordaction()
	{
		$res = $this->admin_model->camkeywordaction();
		echo $res;
		
	}
	
    function users()
    {
        $user = $this->admin_model->all_users();
        $data['active_page'] = 'user';
        $data['user'] = $user;
        $this->load->view('templates/header', $data);
        $this->load->view('users');
    }
	function get_all_user_dt()
	{
		$user = $this->admin_model->get_all_user_dt();
		echo json_encode($user); 
	}
	
	function userImages(){
		 @$pics = "";
	      @$userdetail = "";
	        if ($this->uri->segment(3) === false) {
	            $user = 0;
	        } else {
	            $user = $this->uri->segment(3);
	           $userdetail = $this->admin_model->getUser($user);
	            
	        }
        
      
        $data['active_page'] = 'image';
		$data['userdetail'] = $userdetail;
	 
        $this->load->view('templates/header', $data);
        $this->load->view('userImages',$data);
	}
	function getUserImagesDt()
	{
		
		$userid = $this->uri->segment(3);
		$user = $this->admin_model->getUserImagesDt($userid);
		echo json_encode($user); 
	}
	
	
	
	function userlike()
    {
    	@$pics = "";
        if ($this->uri->segment(3) === false) {
            $group = 0;
        } else {
            $image = $this->uri->segment(3);
           $image_detail = $this->admin_model->image_detail($image);
            
        }
        
        $data['active_page'] = 'pics';
		$data['image']=$image;
		$data['image_detail']=$image_detail;
        $data['user'] = $pics;
        $this->load->view('templates/header', $data);
        $this->load->view('user_like');
    }
	
	function winner()
	{
		  @$winnerdata = array();
       
      
        $data['active_page'] = 'winner';

	$data['winnerdata']=$winnerdata;
        $this->load->view('templates/header', $data);
        $this->load->view('winner',$data);
	}
	function winnerRecords()
	{
		$group = $this->uri->segment(3);
		$winnerdata = $this->admin_model->get_winner_dt();
		  $data['active_page'] = '$winnerdata';
	$data['winnerdata']=$winnerdata;
        $this->load->view('templates/header', $data);
        $this->load->view('winner',$data);
		
	}
	
	function get_all_userlike_dt()
	{
		$group = $this->uri->segment(3);
		$user = $this->admin_model->get_all_userlike_dt($group);
		echo json_encode($user); 
	}
	
	
	
	function submitNotification()
	{
		echo 1;
	}




    function remove_group_image()
    {
        $id = $_POST['id'];
		$image = $_POST['image'];
        $res = $rcp = $this->admin_model->delete_group_image($id,$image);
        echo $res;

    }
	function toggleStatus()
	{
		$user = $this->uri->segment(3);
		
        $res = $this->admin_model->toggleStatus($user);
        echo $res;
	}
	function remove_pic_like()
	{
		$id = $_POST['id'];
		
        $res = $rcp = $this->admin_model->remove_pic_like($id);
        echo $res;
	}
    function remove_group()
    {
        $id = $_POST['id'];
        $res = $rcp = $this->admin_model->delete_group($id);
        echo $res;

    }
	function remove_user_and_data()
    {
        $id = $_POST['id'];
        $res = $rcp = $this->admin_model->remove_user_and_data($id);
        echo $res;

    }
     function save_group()
    {
        $id = $_POST['id'];
        $res = $this->admin_model->group_save($id);
        if ($res) {
           redirect('admin/index');
        } else {
            // set the message that show the data is not save;
          redirect('admin/index');
        }
              
    }
	 function save_user()
    {
        $id = $_POST['id'];
        $res = $this->admin_model->user_save($id);
        if ($res) {
           redirect('admin/all_users');
        } else {
            // set the message that show the data is not save;
          redirect('admin/all_users');
        }
              
    }
    
    function editprofile()
    {
        @$admin[0]->username = $admin[0]->email = $admin[0]->password = "";

        $admin = $this->admin_model->get_admin();
        $data['admin'] = $admin;
        $data['active_page'] = 'editprofile';
        $this->load->view('templates/header', $data);
        $this->load->view('editprofile');

    }
    function update_profile()
    {
        $admin = $this->admin_model->save_profile();
        if($admin) {
        		 $data = array("IsValid" => 1);
        	}else {
            $data = array("IsValid" => 0);
        }
        echo json_encode($data);

    }
	function change_password()
	{
		$admin = $this->admin_model->change_password();
        if($admin) {
        		 $data = array("IsValid" => 1);
        	}else {
            $data = array("IsValid" => 0);
        }
        echo json_encode($data);
	}
      function send_notification()
    {
      
        $data['active_page'] = 'notification';
        $this->load->view('templates/header', $data);
		if($this->session->userdata('is_admin')!='1'){
        $this->load->view('notification');
		}else{
		$this->load->view('admin_notification');
		}
    }
	 function winnersetting()
    {
      $users = $this->admin_model->winnersetting();
	  $data['users']= $users;
        $data['active_page'] = 'winnersetting';
        $this->load->view('templates/header', $data);
        $this->load->view('winnersetting');
    }
     function save_setting()
    {
        $id = $_POST['id'];
        $res = $this->admin_model->save_setting($id);
        if ($res) {
          echo 1;
        } else {
            // set the message that show the data is not save;
          echo 0;
        }
              
    }

    function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
}
?>