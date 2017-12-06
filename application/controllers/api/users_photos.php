<?php   defined('BASEPATH') or exit('No direct script access allowed');


// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Users_photos extends REST_Controller
{
    function __construct()
    {
    	error_reporting(0);
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form','url','constant_helper'));
        $this->load->model('users_model');
		$this->load->model('users_photos_model');
		//$this->load->library(array('Upload'));
			$this->load->library('S3');
        
    }
    
   
    
	
   function userPhotoUpload()
    {
        $input_method = $this->webservices_inputs();
        $this->validate_param('user_photoupload',$input_method);
	     $auth =getallheaders()['Auth'];

	
	 $this->Auth_Validate($auth,$input_method['user_id']);
		if(!(isset($_FILES['image']))){
			$this->response(array('message' => NO_FILE_ERROR, 'status' => 0), 200);
		}
        //----------------------------------------------------------------
        $num = rand();
	     $file_name = substr(str_shuffle("0123456789a".$num."bcdefghijklm".$num."nopqrstuvwxyzABCDEFGH".$num."IJKLMNOPQRSTUVWXYZ"),
                    0, 30);
	$ext =pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);	
      $upload_result='';
		//$this->load->library('upload', $config);]
		$imagename=$file_name.'.'.$ext;
		$uri="user_".$input_method['user_id']."/".$file_name.'.'.$ext;
		  if (S3::putObject(S3::inputFile($_FILES['image']['tmp_name']), BUCKET_NAME, $uri, S3::ACL_PUBLIC_READ)) {
        //echo "File uploaded.".$uri;
			 $upload_result='1';
    } else {
       // echo "Failed to upload file.";
		 $upload_result='0';
    }
		/*
		$config['upload_path'] = IMAGE_UPLOAD_LINK;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['file_name'] = '"'.$file_name.'"';	

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('image'))
		{
	 $this -> response(array(
						'message' => $this -> upload -> display_errors(),
						'status' => 0
					), 200);
			//$this->load->view('upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
           // print_r($data);
			//$this->load->view('upload_success', $data);
		}
		*/
        ///----------------------------------------------------------------------------
       
         if($upload_result=='1')
        {
         $input_method['image_name']=$imagename; 
        }else{
         $this->response(array('message' => NO_FILE_ERROR, 'status' => 0), 200);
		 
        }
         
        $user_register =$this->users_photos_model->user_photo_upload($input_method);
		$last_uploaded =$this->users_photos_model->last_photoupload($input_method['user_id']);
		$photo_setting =$this->users_photos_model->photo_setting();
      if($user_register)
      {
       
         $this->response(array('message' => PHOTO_UPLOAD_SUCCESS,'last_upload'=>$last_uploaded,
         'limit_time'=>$photo_setting,'status' => 1), 200);   
        
         
      }else{
         $this->response(array('message' => FILE_UPLOAD_ERROR, 'status' => 0), 200);
      }
    }	
    
  function get_photos()
  {   $userid='';
  $win_data=array();
  $result_data=array();
  	 $input_method = $this->webservices_inputs();
	 $this->validate_param('get_photos',$input_method);
	 
	 $auth =getallheaders()['Auth'];
	 $this->Auth_Validate($auth,$input_method['user_id']);
	 
	 if(isset($input_method['user_id'])){
	 	$userid=$input_method['user_id'];
	 }
  	$user_photos=$this->users_photos_model->get_photos($input_method,$userid);
	foreach ($user_photos as $row) {
		//check follow status
					$is_like=$this->users_photos_model->check_userlike($input_method['user_id'],$row['id']);
					//end 
		$result_data[]=array( "id"=>$row['id'],
		                     "user_id"=>$row['user_id'],		                    
		                     "image"=>IMAGE_LINK."user_".$row['user_id']."/".$row['image'],		                     
		                       "caption"=>($row['caption'])?$row['caption']:"",		         
		                         "photo_date"=>$row['photo_date'],
		                         "is_like"=>$is_like,
		                         "like_count"=>$row['like_count'],
		                     "createdon"=>$row['createdon']
						);
	}
	//winner photo
	  	$win_photos=$this->users_photos_model->get_win_photos();
	foreach ($win_photos as $row) {
		//check follow status
					$is_like=$this->users_photos_model->check_userlike($input_method['user_id'],$row['id']);
					//end 
		$win_data=array( "id"=>$row['id'],
		                     "user_id"=>$row['user_id'],
		                      "user_name"=>$row['name'],		                    
		                  "image"=>IMAGE_LINK."user_".$row['user_id']."/".$row['image'],		                     
		                       "caption"=>($row['caption'])?$row['caption']:"",		         
		                         "photo_date"=>$row['photo_date'],
		                         "is_like"=>$is_like,
		                         "like_count"=>$row['like_count'],
		                     "createdon"=>$row['createdon']
						);
	}
	//end
	 if(count($result_data))
      {
       
         $this->response(array('message' => SUCCESS, 'status' => 1 , 'data'=>$result_data , 'winner'=>$win_data), 200);   
      
         
      }else{
         $this->response(array('message' =>NO_PHOTOS_ERROR , 'status' => 0), 200);
      }
  } 	



function user_photolike()
  {
  	$input_method = $this->webservices_inputs();
	$this->validate_param('user_photolike',$input_method);	
	 $auth =getallheaders()['Auth'];
	 $this->Auth_Validate($auth,$input_method['user_id']);
  	$isfollow=$this->users_photos_model->user_photolike($input_method);
	if($isfollow==1)
	{
		$this->response(array('message' => SUCCESS, 'status' => 1 , 'islike'=>$isfollow), 200);   
	}else{
		$this->response(array('message' => FAIL, 'status' => 0 , 'islike'=>$isfollow), 200);   
	}
	
  }  




  function imageupload()
  {
  
   $this->do_upload($_FILES);
   
  }

  function auth_create($email)
  {
  	$rand=rand(500000, 1500000);
  	  $today = date('y m d h:m:s');
	     $auth = substr(str_shuffle($email."12".$rand."abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                    0, 50);
					return $auth;
  }
  
  
  
  
function do_upload($image)
	{
	  $rand = rand(500000, 1500000);
	     $file_name = substr(str_shuffle("0123456789abcdefghijklm".$rand."nopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                    0, 30);
		$config['upload_path'] = './upload/';
		$config['allowed_types'] = 'gif|jpg|png';
	    $config['file_name'] = $file_name;
	

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($image))
		{
			$error = array('error' => $this->upload->display_errors());

			//$this->load->view('upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
           
			//$this->load->view('upload_success', $data);
		}
	}
   
   
} 

?>
