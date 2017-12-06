<?php   defined('BASEPATH') or exit('No direct script access allowed');


// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class Users extends REST_Controller
{
    function __construct()
    {
    	error_reporting(0);
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form','url','constant_helper','function_helper','notification_helper'));
        $this->load->model('users_model');	
		 $this->load->model('user_post_model');	
		$this->load->model('admin_model');
		$this -> load -> library(array('upload','S3_lib'));
		//$this->load->library(array('Upload'));
        
    }
    //amazon bucket fun start
  	function uploaduser_image($file)
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
					$aws_upload = $s3->putObjectFile($source, AWS_BUCKET_PROFILE,
					$file_name,   S3::ACL_PUBLIC_READ);
					
					if($aws_upload==1){
						return	$file_name;
					}else{
						return	false;
					}	
					
						
					
				}
			}
		}	
	
	// amazon bucket fun end
    function userLogin()
    {
    	$userdata=array();
            $input_method = $this->webservices_inputs();
            $this->validate_param('login',$input_method);
            $user=$this->users_model->user_login($input_method);
           	   $user_check =$this->users_model->check_verification($input_method);
		   if($user_check)
		   {
		   	 $this->response(array('message' => $user_check, 'status' => 0), 200);
		   }
            if(!$user)
            {
               $this->response(array('message' => "Wrong username or password", 'status' => 0), 200);
            }else{
   $userdata = $this->get_user_data($user);
				if($userdata["status"]==0){
					  $this->response(array('message' => "Your account is temporarily blocked", 'status' => 0), 200);
				}
                 $this->response(array('message' => "Login successfull",'data' => $userdata, 'status' => 1), 200);
            }
           
    }
    function checkUsername()
	{
		$input_method = $this->webservices_inputs();
		$this->validate_param('check_username',$input_method);
		$user_username =$this->users_model->check_username($input_method);
		
		
		   if($user_username)
		   {
		   	 $this->response(array('message' => $user_username, 'status' => 0), 200);
		   }else{
		   	$this->response(array('message' => SUCCESS, 'status' => 1), 200);
		   }
	}
	    function userLogout()
	{
		$input_method = $this->webservices_inputs();
		$this->validate_param('userLogout',$input_method);
		$user_username =$this->users_model->userLogout($input_method);
		
		
		   if($user_username)
		   { $this->response(array('message' => SUCCESS, 'status' => 1), 200);
		   	 
		   }else{
		   	$this->response(array('message' => FAIL, 'status' => 0), 200);
		   }
	}
    function userRegister()
    {
        $input_method = $this->webservices_inputs();
        $this->validate_param('user_register',$input_method);
      
       $auth_token=$this->auth_create($input_method['email']);
		
		  $input_method['auth_token']=$auth_token;  
		   $user_check =$this->users_model->check_email($input_method);
		   if($user_check)
		   {
		   	 $this->response(array('message' => $user_check, 'status' => 0), 200);
		   }
		   
		    $user_username =$this->users_model->check_username($input_method);
		   if($user_username)
		   {
		   	 $this->response(array('message' => $user_username, 'status' => 0), 200);
		   }
		//user image upload
		
		
	if(count($_FILES['image'])>0){
		/**	
        //----------------------------------------------------------------
        $num = rand(10000,10000000000);
	     $file_name = substr(str_shuffle("0123456789a".$num."bcdefghijklm".$num."nopqrstuvwxyzABCDEFGH".$num."IJKLMNOPQRSTUVWXYZ"),
                    0, 30);
	$ext =pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);	
      $upload_result='';
	
		
		$config['upload_path'] = IMAGE_UPLOAD_LINK;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['file_name'] = '"'.$file_name.'"';	
         $imagename=$file_name.'.'.$ext;
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('image'))
		{
			
	 $this -> response(array(
						'message' => $this -> upload -> display_errors(),
						'status' => 0
					), 200);
			
		}
		else
		{
			$input_method['image']=$imagename; 
			
          
		}
		
		**/
		$filename = $this->uploaduser_image($_FILES);
		if(!$filename){
			$this->response(array('message' => "Error occur while file uploading", 'status' => 0), 200);
		}else{
			$input_method['image']=$filename;
		}
 
        }
		
		
		//end of user image   
		   
        $user_register =$this->users_model->user_register($input_method);

      if($user_register)
      {
        
         $this->response(array('message' => PROFILE_CREATED, 'status' => 1), 200);   
        
         
      }else{
         $this->response(array('message' => PROFILE_CREATE_ERROR, 'status' => 0), 200);
      }
    }
     
	function getCountry()
	{
		$user_data =$this->users_model->get_country();
        if(count($user_data)){
        	$this->response(array('message' => "Country List ",'date'=>$user_data, 'status' => 1), 200);   
        }else{
        	$this->response(array('message' => "No country", 'status' => 0), 200);
        }
	}
	 
    function userProfileUpdate()
    {
        $input_method = $this->webservices_inputs();
        $this->validate_param('user_update',$input_method);
   	//$auth =getallheaders()['auth'];
	 //$this->auth_Validate($auth,$input_method['user_id']);
         
        $user_register =$this->users_model->user_update($input_method);
       
   $userdata = $this->get_user_data($input_method['user_id']);
	
      if($user_register)
      {
      	
		
        if($user_register!=true){
        		
          $this->response(array('message' => "Please, Use different email or username ", 'status' => 0), 200);   
        }else{
         $this->response(array('message' => "Profile Updated successfully", 'data'=>$userdata, 'status' => 1), 200);   
        }
         
      }else{
         $this->response(array('message' => "Error occur while signup", 'status' => 0), 200);
      }
    }



 function userAddressUpdate()
    {
        $input_method = $this->webservices_inputs();
       
   	//$auth =getallheaders()['auth'];
	 //$this->auth_Validate($auth,$input_method['user_id']);
         
        $user_register =$this->users_model->user_update_address($input_method);
         $userdata = $this->get_user_data($input_method['user_id']);
	
      if($user_register)
      {
      	
		
        if($user_register!=true){
        		
          $this->response(array('message' => "Please, Use different email or username ", 'status' => 0), 200);   
        }else{
         $this->response(array('message' => "Profile Updated successfully", 'data'=>$userdata, 'status' => 1), 200);   
        }
         
      }else{
         $this->response(array('message' => "Error occur while signup", 'status' => 0), 200);
      }
    }



	
   function userPhotoUpdate()
    {
        $input_method = $this->webservices_inputs();
        $this->validate_param('user_photo_update',$input_method);
	   
		if(!(isset($_FILES['image']))){
			$this->response(array('message' => NO_FILE_ERROR, 'status' => 0), 200);
		}
		/***
        //----------------------------------------------------------------
        $num = rand(10000000,10000000000);
	     $file_name = substr(str_shuffle("0123456789a".$num."bcdefghijklm".$num."nopqrstuvwxyzABCDEFGH".$num."IJKLMNOPQRSTUVWXYZ"),
                    0, 30);
	$ext =pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);	
      $upload_result='';

	
		$config['upload_path'] = IMAGE_UPLOAD_LINK;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['file_name'] = '"'.$file_name.'"';	
$imagename=$file_name.'.'.$ext;
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
			 $input_method['image']=$imagename; 
			$data = array('upload_data' => $this->upload->data());
           // print_r($data);
			//$this->load->view('upload_success', $data);
		}
		 **/
        $filename = $this->uploaduser_image($_FILES);
		if(!$filename){
			$this->response(array('message' => "Error occur while file uploading", 'status' => 0), 200);
		}else{
			$input_method['image']=$filename;
		}
         
        $user_register =$this->users_model->user_photo_update($input_method);
		
      if($user_register)
      {
         $userdata = $this->get_user_data($input_method['user_id']);
         $this->response(array('message' => PHOTO_UPDATE_SUCCESS,'status' => 1,'data'=>$userdata), 200);   
        
         
      }else{
         $this->response(array('message' => PHOTO_UPDATE_FAIL, 'status' => 0), 200);
      }
    }	


 function userSettingUpdate()
    {
        $input_method = $this->webservices_inputs();
       
   	//$auth =getallheaders()['auth'];
	 //$this->auth_Validate($auth,$input_method['user_id']);
         
        $user_register =$this->users_model->user_update_setting($input_method);
  $userdata = $this->get_user_data($input_method['user_id']);
	
      if($user_register)
      {
      	
		
        if($user_register!=true){
        		
          $this->response(array('message' => "Please, Use different email or username ", 'status' => 0), 200);   
        }else{
         $this->response(array('message' => "Setting Updated successfully", 'data'=>$userdata, 'status' => 1), 200);   
        }
         
      }else{
         $this->response(array('message' => "Error occur while Setting Update", 'status' => 0), 200);
      }
    }

  function get_user_data($userid,$view_by="")
  {
  	$userdata = array();
  	$input_method=array("user_id"=>$userid);
  	$users=$this->users_model->get_user_profile($input_method);
  	
    foreach ($users as $row) {
		 	
            
             	$userimage= get_user_image($row['image']);
             
         	$follower_count=$this->users_model->get_follower_count($row['id']);
			$follow_count=$this->users_model->get_follow_count($row['id']);
			$post_count=$this->user_post_model->get_post_count($row['id']);
			$is_follow =$this->users_model->check_userfollow($view_by,$userid);
				$userdata = array("id"=>$row['id'],"username"=>$row['username'],"firstname"=>$row['firstname'],
				"lastname"=>$row['lastname'],"dob"=>$row['dob'],"email"=>$row['email'],
				"gender"=>$row['gender'],"contact"=>$row['contact'],"bio"=>$row['bio'],
				"user_type"=>$row['user_type'],"social_login_type"=>$row['social_login_type']
				,"social_login_id"=>$row['social_login_id'],
					"house"=>$row['house'],
					"street"=>$row['street'],
					"city"=>$row['city'],
					"postcode"=>$row['postcode'],
					"country"=>$row['country'],
					"likes"=>$row['likes'],
					"price_drop"=>$row['price_drop'],
					"offer_sale"=>$row['offer_sale'],
					"follow"=>$is_follow,
					"follower_count"=>$follower_count,"following_count"=>$follow_count,
					"post_count"=>$post_count,
				"image"=>$userimage,"auth_token"=>$row['auth_token'],"status"=>$row['status']
				);	
						foreach ($userdata as $key => $value) {
					if($userdata[$key]==""){
						$userdata[$key]="";
					}
						}
				
				}

     return $userdata;
  } 
    
    
	
   function get_profile()
  {   $userid='';
  $view_by = "";
  $result_data=array();
  	 $input_method = $this->webservices_inputs();
	 $this->validate_param('user_profile',$input_method);
	 if(isset($input_method['view_by'])){
	 	$view_by=$input_method['view_by'];
	 }
	 	//$auth =getallheaders()['auth'];
	 //$this->auth_Validate($auth,$input_method['user_id']);
$userdata = $this->get_user_data($input_method['user_id'],$view_by);
	 if(count($userdata))
      {
       
         $this->response(array('message' => SUCCESS, 'status' => 1 , 'data'=>$userdata), 200);   
      
         
      }else{
         $this->response(array('message' => FAIL, 'status' => 0), 200);
      }
  } 





  function userSocialRegister()
    {
    	
        $input_method = $this->webservices_inputs();
        $this->validate_param('social_register',$input_method);
        $auth_token=$this->auth_create($input_method['email']);		
		 $input_method['auth_token']=$auth_token; 
		 //print_r($input_method);exit; 
		  
        $user_register =$this->users_model->user_social_register($input_method);
$userdata = $this->get_user_data($user_register);
		
		//end
      if($user_register)
      {
       if($userdata["status"]==0){
					  $this->response(array('message' => "Your account is temporarily blocked", 'status' => 0), 200);
				}
         $this->response(array('message' => SUCCESS, 'status' => 1 , 'data'=>$userdata), 200);   
       
         
      }else{
         $this->response(array('message' => PROFILE_CREATE_ERROR, 'status' => 0), 200);
      }
    }
  
    function get_users()
  {   $userid='';
  $result_data=array();
  	 $input_method = $this->webservices_inputs();
	 if(isset($input_method['user_id'])){
	 	$userid=$input_method['user_id'];
	 }
  	$users=$this->sunmate_model->get_users($input_method,$userid);
    foreach ($users as $row) {
					if($row['user_type']=='N'){
					if($row['image']!='')
					{
						$img_url=base_url().'upload/images/'.$row['image'];
					}else{
						$img_url=base_url().'upload/images/'.'no_image.jpg';
					}
					}else{
						$img_url=$row['image'];
					}
					if($row['bonus_points']=='')
					{
						$row['bonus_points']='';
					}
			$id=$row['id'];
					// count score point
			$follower_count=$this->admin_model->get_follower_count($id);
			$follow_count=$this->admin_model->get_follow_count($id);
			$user_photo_count=$this->admin_model->get_userphoto_count($id);
			$user_video_count=$this->admin_model->get_uservideo_count($id);
			$user_upload_likecount=$this->admin_model->get_userupload_likecount($id);
			$total_point=($row['bonus_points'])+($user_upload_likecount)+($user_photo_count*5);					
					
					//end
					$userdata[] = array("id"=>$row['id'],"name"=>$row['name'],"email"=>$row['email'],
					"user_type"=>$row['user_type'],"social_login_type"=>$row['social_login_type'],
					"social_login_id"=>$row['social_login_id'],"lat"=>$row['lat'],"lng"=>$row['lng'],
						"total_likes"=>$user_upload_likecount,"total_photos"=>$user_photo_count,
					"image"=>$img_url,"follower"=>$follower_count,"following"=>$follow_count,"score_points"=>$row['bonus_points']
					);
				}
	 if(count($userdata))
      {
       
         $this->response(array('message' => "Users List", 'status' => 1 , 'data'=>$userdata), 200);   
      
         
      }else{
         $this->response(array('message' => "No Users available", 'status' => 0), 200);
      }
  } 	

  function user_follow()
  {
  	$input_method = $this->webservices_inputs();
	$this->validate_param('user_follow',$input_method);	
  	$isfollow=$this->users_model->user_follow($input_method);
	$userdata = $this->get_user_data($input_method['follow_id'],$input_method['user_id']);
	if($isfollow==1)
	{
		
		$this->response(array('message' => "You are following this user", 'status' => 1 , 'is_follow'=>$isfollow,'follow_id'=>$input_method['follow_id'], 'data'=>$userdata), 200);   
	}else{
		$this->response(array('message' => "You unfollowed this user", 'status' => 2 , 'is_follow'=>$isfollow,'follow_id'=>$input_method['follow_id'], 'data'=>$userdata), 200);   
	}
	
  }  
 
 
    function get_users_of_user()
  {   $userid='';
  $result_data=array();
  $userdata=array();
  $userdatalist =array();
  	 $input_method = $this->webservices_inputs();
	  $this->validate_param('users_of_user',$input_method);
	  	if($input_method['page']==''){
		
		$input_method['page']=1;
	}
	 if(!is_numeric($input_method['page'])){
			$input_method['page']=1;
		}
	
  	$users=$this->users_model->get_users_of_user($input_method,PAGE_DATA_LIMIT);
	$view_by=$input_method['view_by'];
	$userid=$input_method['user_id'];
    foreach ($users as $row) {
    	if(!$row['id']){
    		continue;
    	}
				$userimage= get_user_image($row['image']);
				
			$id=$row['id'];
					// count score point
			$follower_count=$this->users_model->get_follower_count($id);
			$follow_count=$this->users_model->get_follow_count($id);
				
					
					//end
					//check follow status
					$is_follow=$this->users_model->check_userfollow($view_by,$userid);
					//end 
						$userdata = array("id"=>$row['id'],"username"=>$row['username'],"firstname"=>$row['firstname'],
				"lastname"=>$row['lastname'],"dob"=>$row['dob'],"email"=>$row['email'],
				"gender"=>$row['gender'],"contact"=>$row['contact'],"bio"=>$row['bio'],
				"user_type"=>$row['user_type'],"social_login_type"=>$row['social_login_type']
				,"social_login_id"=>$row['social_login_id'],
					"house"=>$row['house'],
					"street"=>$row['street'],
					"city"=>$row['city'],
					"country"=>$row['country'],
					"likes"=>$row['likes'],
					"price_drop"=>$row['price_drop'],
					"offer_sale"=>$row['offer_sale'],
					"follow"=>$row['follow'],"is_follow"=>$is_follow,
					"follower_count"=>$follower_count,"following_count"=>$follow_count,
				"image"=>$userimage,"auth_token"=>$row['auth_token'],"status"=>$row['status']
				);	
					foreach ($userdata as $key => $value) {
					if($userdata[$key]==""){
						$userdata[$key]="";
					}
						}
					$userdatalist[]=$userdata;
				}
	 if(count($userdata))
      {
      		
       
         $this->response(array('message' => "Users List", 'status' => 1 ,'limit'=>PAGE_DATA_LIMIT, 'data'=>$userdatalist), 200);   
      
         
      }else{
         $this->response(array('message' => "No data available", 'status' => 0), 200);
      }
  } 
 
 
   function user_bio_update()
  {
  	$input_method = $this->webservices_inputs();
	$this->validate_param('user_bio',$input_method);	
  	$boiupdate=$this->users_model->user_bio_update($input_method);
	if($boiupdate)
	{
		$userdata = $this->get_user_data($input_method['user_id']);
		$this->response(array('message' => SUCCESS,'data'=>$userdata, 'status' => 1 ), 200);   
	}else{
		$this->response(array('message' => FAIL, 'status' => 0 ), 200);   
	}
	
  } 
 
     function forget_password()
  {  
     $result_data=array();
  	 $input_method = $this->webservices_inputs();
	  $this->validate_param('forget_password',$input_method);
	$username=$input_method['email'];
  	$users=$this->users_model->forget_password($username);

	 if($users>0)
      {       
         $this->response(array('message' => FORGET_PASSWORD_SUCCESS, 'status' => 1 ), 200);   
     }else{
         $this->response(array('message' => USER_NOT_EXITS, 'status' => 0), 200);
      }
  } 
  function user_device()
    {
        $input_method = $this->webservices_inputs();
        $this->validate_param('user_device',$input_method);
        
		 	//$auth =getallheaders()['auth'];
	 //$this->auth_Validate($auth,$input_method['user_id']);
		
         
        $user_register =$this->users_model->user_device($input_method);
  
      if($user_register)
      {
      
         $this->response(array('message' => "SUCCESS", 'status' => 1), 200);   
        
      }else{
         $this->response(array('message' => "ERROR", 'status' => 0), 200);
      }
    }


  function auth_create($email)
  {
  	$rand=rand(500000, 1500000);
  	  $today = date('y m d h:m:s');
	     $auth_token = substr(str_shuffle($email."12".$rand."abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                    0, 50);
					return $auth_token;
  }
  
  
  

   
   
} 

?>
