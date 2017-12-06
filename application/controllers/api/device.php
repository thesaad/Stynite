<?php   defined('BASEPATH') or exit('No direct script access allowed');


// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Device extends REST_Controller
{
    function __construct()
    {
    	error_reporting(0);
        parent::__construct();
        $this->load->helper('url');
          $this->load->helper(array('form','url','constant_helper','function_helper'));
        $this->load->model('device_model');
		
        
    }
    
  

function register()
  {
  	$input_method = $this->webservices_inputs();
	$this->validate_param('device_register',$input_method);		 
  	$isregister=$this->device_model->register($input_method);
	if($isregister)
	{
		$this->response(array('message' => SUCCESS, 'status' => 1 ), 200);   
	}else{
		$this->response(array('message' => FAIL, 'status' => 0), 200);   
	}
	
  }  
  
  function userDevice()
  {
  	$input_method = $this->webservices_inputs();
	$this->validate_param('userDevice',$input_method);		 
  	$isregister=$this->device_model->userDevice($input_method);
	if($isregister)
	{
		$this->response(array('message' => SUCCESS, 'status' => 1 ), 200);   
	}else{
		$this->response(array('message' => FAIL, 'status' => 0), 200);   
	}
  }



   
} 

?>
