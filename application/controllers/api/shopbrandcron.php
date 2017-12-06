<?php   defined('BASEPATH') or exit('No direct script access allowed');


// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Shopbrandcron extends REST_Controller
{
    function __construct()
    {
    	//error_reporting(0);
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form','url','constant_helper','function_helper','notification_helper'));
        $this->load->model('shopbrandcron_model');	
		 $this->load->model('user_post_model');	
		$this->load->model('admin_model');
		$this -> load -> library(array('upload','S3_lib'));
		 
        
    }
	
	function curlfunction($URL)
	{
		 

$ch = curl_init ($URL);
    curl_setopt($ch, CURLOPT_HEADER, 0);  
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER , false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
  $row=curl_exec($ch);
return $row;
	}
	
	function brands()
	{
		$leagueteams = array();
	 $result	= $this->curlfunction("https://api.import.io/store/connector/7a1dd333-96e3-43f1-8cd1-5261ce5dc387/_query?input=webpage/url:http%3A%2F%2Fwww.ebay.com%2Ffashion%2Fpopular-brands&&_apikey=ecd58ce34af5431f9af9ba8844abc9bbc6f186ab83ce60d1ccba9de16d27d2a3a15c0c0c5fcef737a9e92cb0b5f5f6cc1115fa820a4204fd6600491306ef6daba7ab7c200f023de71482e6bc131fc07b");
	 $resultarr  =   json_decode($result,true);
	 $this->shopbrandcron_model->save_brand($resultarr);
	 echo "<pre>";
	 print_r($resultarr);
	  exit;
    }
	
 
    
    function brandimageCron()
	{
		$this->shopbrandcron_model->brandimageCron();
		echo "done";
	}

}

?>
