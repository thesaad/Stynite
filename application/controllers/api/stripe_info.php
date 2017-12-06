<?php   defined('BASEPATH') or exit('No direct script access allowed');


// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Stripe_info extends REST_Controller
{
    function __construct()
    {
    	error_reporting(0);
        parent::__construct();
        $this->load->helper('url');
          $this->load->helper(array('form','url','constant_helper','function_helper'));
        $this->load->model('stripe_info_model');
		
        
    }
    
  

function logs()
  {
  	 
	 $to = "deepak@squarebits.in";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: deepak@squarebits.in" ;
$message=json_encode($_SERVER);
//"CC: somebodyelse@example.com";
echo mail($to,$subject,$message,$headers);
$body = @file_get_contents('php://input');

// decode the json data into a php object
//$response = json_decode($body);
date_default_timezone_set("GMT");

$logdata = "GMT Time: " . date("Y-m-d H:i:s");
$logdata .= PHP_EOL;
$logdata .= "BODY===>" . $body;
$today = date('d_m_Y');
$logfile = 'logs/log_' . $today . '_webhook.txt';
$file_log = fopen($logfile, 'a');
if ($file_log)
{
	echo "in log";
   echo  $result = fwrite($file_log, PHP_EOL .
        "********************************************************************************************************");
    $result = fwrite($file_log, PHP_EOL . $logdata);
}else{
	echo "in false";
}
	
  }  
  
  
function balance()
  {
  	 
	 $to = "deepak@squarebits.in";
$subject = "My subject";
$txt = "Hello Balance!";
$headers = "From: deepak@squarebits.in" ;
$message=json_encode($_SERVER);
//"CC: somebodyelse@example.com";
echo mail($to,$subject,$message,$headers);
$body = @file_get_contents('php://input');

// decode the json data into a php object
//$response = json_decode($body);
date_default_timezone_set("GMT");

$logdata = "GMT Time: " . date("Y-m-d H:i:s");
$logdata .= PHP_EOL;
$logdata .= "BODY===>" . $body;
$today = date('d_m_Y');
$logfile = 'logs/log_bal_' . $today . '_webhook.txt';
$file_log = fopen($logfile, 'a');
if ($file_log)
{
	echo "in log";
   echo  $result = fwrite($file_log, PHP_EOL .
        "********************************************************************************************************");
    $result = fwrite($file_log, PHP_EOL . $logdata);
}else{
	echo "in false";
}
	
  }  
  


   
} 

?>
