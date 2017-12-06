<?php   defined('BASEPATH') or exit('No direct script access allowed');


// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class User_post extends REST_Controller
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
		//$this->load->library(array('Upload'));
       $this -> load -> library(array('upload','S3_lib'));
    }
  
  	function uploadpost_image($file)
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
					$aws_upload = $s3->putObjectFile($source, AWS_BUCKET_POST,
					$file_name,   S3::ACL_PUBLIC_READ);
					
					if($aws_upload==1){
						return	$file_name;
					}else{
						return	false;
					}	
					
						
					
				}
			}
		}	
  function test_notice()
  {
  	$message_text = "Hello ";
  	$registration[] = "e17bd74527a1f6bb6dfbfb58e68824c4b0622067c9252be1c0c879a6ae3e3e3a";
	send_push($registration, $message_text);
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
	
	function create_post()
	{
		$input_method = $this->webservices_inputs();
        $this->validate_param('create_post',$input_method);
		
		
			if(count($_FILES['image'])>0){
		/*	
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
		$filename = $this->uploadpost_image($_FILES);
		if(!$filename){
			$this->response(array('message' => "Error occur while file uploading", 'status' => 0), 200);
		}else{
			$input_method['image']=$filename;
		}
 
        }
		
		$post_result =$this->user_post_model->create_post($input_method);
		if($post_result>0){
			$post_data= $this->get_post_data($post_result);
			$this->response(array('message' => POST_CREATED, 'data'=>$post_data,'status' => 1), 200);   
		}else{
			 $this->response(array('message' => POST_CREATE_ERROR, 'status' => 0), 200);
		}
		
	}
	
	function share_post()
	{
		$input_method = $this->webservices_inputs();
        $this->validate_param('share_post',$input_method);
		

		
		$post_result =$this->user_post_model->share_post($input_method);
		if($post_result>0){
			$post_data= $this->get_post_data($post_result,$input_method['user_id']);
			$this->response(array('message' => POST_SHARE, 'data'=>$post_data,'status' => 1), 200);   
		}else{
			 $this->response(array('message' => POST_SHARE_ERROR, 'status' => 0), 200);
		}
		
	}
	
	
	
	function edit_post()
	{
		$input_method = $this->webservices_inputs();
        $this->validate_param('edit_post',$input_method);
		
		
			if(isset($_FILES['image'])){
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
		***/
		  	$filename = $this->uploadpost_image($_FILES);
		if(!$filename){
			$this->response(array('message' => "Error occur while file uploading", 'status' => 0), 200);
		}else{
			$input_method['image']=$filename;
		}
 
        }
		
		$post_result = $this->user_post_model->edit_post($input_method);
		if($post_result>0){
			$post_data= $this->get_post_data($post_result);
			$this->response(array('message' => POST_CREATED, 'data'=>$post_data,'status' => 1), 200);   
		}else{
			 $this->response(array('message' => POST_CREATE_ERROR, 'status' => 0), 200);
		}
		
	}
	
	
	

     function get_post_data($postid,$user_id="")
   {
   	$image="";
   	$post_data = array();
   	 $post = $this->user_post_model->get_post_data($postid);
	 
	
   	 if(count($post)>0){
   	 	foreach ($post as $row) {
   	 		
   	 		$sharecount = $this->user_post_model->get_post_sharecount($row['id']);
	 $likecount = $this->user_post_model->get_post_likecount($row['id']);
	 $commentcount = $this->user_post_model->get_post_commentcount($row['id']);
			
   	 		   if($row['is_image']==1){
   	 		   	$image=get_post_image($row['image']);
   	 		   }
			    $user_data =$this->user_post_model->get_userdata($row['user_id']);
				$is_like = $this->user_post_model->is_postlike($user_id,$postid);
				$userimage= get_user_image($user_data[0]['image']);
				$post_data = array(
				"id"=>$row['id'],
				"user_id"=>$row['user_id'],
				"user_name"=>$user_data[0]['username'],
				"first_name"=>$user_data[0]['firstname'],
				"last_name"=>$user_data[0]['lastname'],
				"email"=>$user_data[0]['email'],
				"userimage"=>$userimage,
				"image"=>$image,
				"is_image"=>$row['is_image'],
				"post_text"=>$row['post_text'],
				"tags"=>$row['tags'],
				"post_date"=>$row['post_date'],
				"share_count"=>$sharecount,
				"like_count"=>$likecount,
				"is_like"=>$is_like,
				"comment_count"=>$commentcount
				);
			}
   	 }
	 
	 

        foreach ($post_data as $key => $value) {
					if($post_data[$key]==""){
						$post_data[$key]="";
					}
						}
		
		return $post_data;
   }
   
   function get_a_post()
   {
   	$user_id="";
     	$input_method = $this->webservices_inputs();
        $this->validate_param('get_a_post',$input_method);
		if(isset($input_method['user_id'])){
			$user_id = $input_method['user_id'];
		}
		$post_data= $this->get_post_data($input_method['post_id'],$user_id);
		$post_comment = $this->getPostComment($input_method['post_id']);
		$post_like = $this->getPostLike($input_method['post_id']);
		if(count($post_data)>0){
			$this->response(array('message' => SUCCESS, 'data'=>$post_data,'comments'=>$post_comment,'likes'=>$post_like,'status' => 1), 200);
		}else{
			$this->response(array('message' => FAIL, 'status' => 0), 200);
		}
		
   }
   
   function get_user_posts()
   {
   	$view_by = "";
   	$postarr=array();
   	   $input_method = $this->webservices_inputs();
        $this->validate_param('get_user_posts',$input_method);
		
		 if(isset($input_method['view_by'])){
	 	$view_by=$input_method['view_by'];
	 }
		
		 $post = $this->user_post_model->get_userposts($input_method,PAGE_DATA_LIMIT);
		   	 if(count($post)>0){
   	 	foreach ($post as $row) {
   	 		   if($row['is_image']==1){
   	 		   	$image=get_post_image($row['image']);
   	 		   }
$is_like = $this->user_post_model->is_postlike($view_by,$row['id']);
			   $sharecount = $this->user_post_model->get_post_sharecount($row['id']);
   	 		    $likecount = $this->user_post_model->get_post_likecount($row['id']);
	 $commentcount = $this->user_post_model->get_post_commentcount($row['id']);
	 $user_datainfo =$this->user_post_model->get_userdata($row['user_id']);
	 $userimage= get_user_image($user_datainfo[0]['image']);
				$post_data = array(
				"id"=>$row['id'],
				"user_id"=>$row['user_id'],
				"user_name"=>$user_datainfo[0]['username'],
				"userimage"=>$userimage,
				"image"=>$image,
				"is_image"=>$row['is_image'],
				"post_text"=>$row['post_text'],
				"tags"=>$row['tags'],
				"post_date"=>$row['post_date'],
				"share_count"=>$sharecount,
				"is_like"=>$is_like,
				"like_count"=>$likecount,
				"comment_count"=>$commentcount
				);
				   foreach ($post_data as $key => $value) {
					if($post_data[$key]==""){
						$post_data[$key]="";
					}
						}
				 $postarr[] = $post_data;
			}
		$userdata = $this->get_user_data($input_method['user_id'],$view_by);
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'user'=>$userdata,'status' => 1), 200);
   	 }else{
   	 	$userdata = $this->get_user_data($input_method['user_id'],$view_by);
   	 	$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'user'=>$userdata,'status' => 0), 200);
  
   	 	//$this->response(array('message' => NO_POST, 'user'=>$userdata,'status' => 0), 200);
   	 }
	 
   }
   
      function getUserLikedPosts()
   {
   	$view_by = "";
   	$postarr=array();
   	   $input_method = $this->webservices_inputs();
        $this->validate_param('getUserLikedPosts',$input_method);
		
		
	 	$view_by=$input_method['view_by'];
	
		 $post = $this->user_post_model->getUserLikedPosts($input_method,PAGE_DATA_LIMIT);
		   	 if(count($post)>0){
   	 	foreach ($post as $row) {
   	 		   if($row['is_image']==1){
   	 		   	$image=get_post_image($row['image']);
   	 		   }
$is_like = $this->user_post_model->is_postlike($view_by,$row['id']);
			   $sharecount = $this->user_post_model->get_post_sharecount($row['id']);
   	 		    $likecount = $this->user_post_model->get_post_likecount($row['id']);
	 $commentcount = $this->user_post_model->get_post_commentcount($row['id']);
	 $user_datainfo =$this->user_post_model->get_userdata($row['user_id']);
	 $userimage= get_user_image($user_datainfo[0]['image']);
				$post_data = array(
				"id"=>$row['id'],
				"user_id"=>$row['user_id'],
				"user_name"=>$user_datainfo[0]['username'],
				"userimage"=>$userimage,
				"image"=>$image,
				"is_image"=>$row['is_image'],
				"post_text"=>$row['post_text'],
				"tags"=>$row['tags'],
				"post_date"=>$row['post_date'],
				"share_count"=>$sharecount,
				"is_like"=>$is_like,
				"like_count"=>$likecount,
				"comment_count"=>$commentcount
				);
				   foreach ($post_data as $key => $value) {
					if($post_data[$key]==""){
						$post_data[$key]="";
					}
						}
				 $postarr[] = $post_data;
			}
		$userdata = $this->get_user_data($input_method['user_id'],$view_by);
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'user'=>$userdata,'status' => 1), 200);
   	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	 
   }
   
   
   
   
   function userPostLike()
  {
  	$input_method = $this->webservices_inputs();
	$this->validate_param('userPostLike',$input_method);	
  	$islike=$this->user_post_model->userPostLike($input_method);
		$post_data= $this->get_post_data($input_method['post_id'],$input_method['user_id']);
		$post_comment = $this->getPostComment($input_method['post_id']);
		$post_like = $this->getPostLike($input_method['post_id']);
	if($islike==1)
	{
		
		$this->response(array('message' => "You  liked this post", 'status' => 1 ,'data'=>$post_data,'comments'=>$post_comment,'likes'=>$post_like, 'islike'=>$islike,'post_id'=>$input_method['post_id']), 200);   
	}else{
		$this->response(array('message' => "You unliked this post", 'status' => 2 ,'data'=>$post_data,'comments'=>$post_comment,'likes'=>$post_like, 'islike'=>$islike,'post_id'=>$input_method['post_id']), 200);   
	
	}
	
  }  
  
     function userPostComment()
  {
  	$input_method = $this->webservices_inputs();
	$this->validate_param('userPostComment',$input_method);	
  	$iscomment=$this->user_post_model->userPostComment($input_method);
	if($iscomment==1)
	{
		$post_data= $this->get_post_data($input_method['post_id'],$input_method['user_id']);
		$post_comment = $this->getPostComment($input_method['post_id']);
		
		$post_data['post_comment']=$post_comment;
				$post_arr = $post_data;
		
		
		$this->response(array('message' => "You  commented on this post", 'status' => 1 , 'data'=>$post_arr), 200);   
	}else{
		$this->response(array('message' => "Please try again", 'status' => 0 ,'post_id'=>$input_method['post_id']), 200);   
	}
	
  } 
  
   function getPostComment($post_id)
   {
   	$postarr = array();
   	    	 $post_comment = $this->user_post_model->get_post_comment($post_id);
   	 if(count($post_comment)>0){
   	 	foreach ($post_comment as $row) {
   	 		$userimage= get_user_image($row['userimage']);
			 $action_time =$this->user_post_model->get_user_action($row['user_id'],$row['post_id'],ACTION_POST);
				$post_data  = array(
				"id"=>$row['id'],
				"user_id"=>$row['user_id'],				
				"post_id"=>$row['post_id'],
				"comment"=>$row['comment'],
				"username"=>$row['username'],
				"actiontime"=>$action_time,
				"userimage"=>$userimage,
				);
				
				foreach ($post_data as $key => $value) {
					if($post_data[$key]==""){
						$post_data[$key]="";
					}
						}
				 $postarr[] = $post_data;
			}
   	 }
	 return $postarr;
   }
      function getPostLike($post_id)
   {
   	$postarr = array();
   	    	 $post_comment = $this->user_post_model->get_post_like($post_id);
   	 if(count($post_comment)>0){
   	 	foreach ($post_comment as $row) {
   	 		$action_time =$this->user_post_model->get_user_action($row['user_id'],$row['post_id'],ACTION_LIKE);
   	 		$userimage= get_user_image($row['userimage']);
				$post_data  = array(
				"id"=>$row['id'],
				"user_id"=>$row['user_id'],				
				"post_id"=>$row['post_id'],				
				"username"=>$row['username'],
				"userimage"=>$userimage,
				"actiontime"=>$action_time
				);
				
				foreach ($post_data as $key => $value) {
					if($post_data[$key]==""){
						$post_data[$key]="";
					}
						}
				 $postarr[] = $post_data;
			}
   	 }
	 return $postarr;
   }
 
      function getGenFeeds()
   {
   	$postarr = array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getUsersFeeds',$input_method);	
	$post = $this->user_post_model->getGenFeeds($input_method,PAGE_DATA_LIMIT);
		   	    	 if(count($post)>0){
   	 	foreach ($post as $row) {
   	 		$post_data= $this->get_post_data($row['post_id'],$input_method['user_id']);
		//$post_comment = $this->getPostComment($row['post_id']);
		if(count($post_data)>0){
			/*
			 $actor_name =$this->user_post_model->get_username($row['user_id']);
			
			$post_data['actor_id']=$row['user_id'];
			
			$post_data['actor_name']=$actor_name;
			$post_data['action']=$row['action'];
			$post_data['action_time']=$row['action_time'];
			$post_data['post_comment']=$post_comment;
			*/
				$postarr[] = $post_data;
			
		}

					
						}
						
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'status' => 1), 200);
   	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }
  
   function checkInArray($value,$array){
   
   	$count = 0;
   	foreach ($array as $raw) {
   		//echo "array:".$raw['id'];
	//	echo "value:".$value;
   		if($raw['id']==$value){
   			return $count;
   		}
		   $count++;
	   }
	
	return -1;
   	
  }


function sksort(&$array, $subkey="id", $sort_ascending=false) {

    if (count($array))
        $temp_array[key($array)] = array_shift($array);

    foreach($array as $key => $val){
        $offset = 0;
        $found = false;
        foreach($temp_array as $tmp_key => $tmp_val)
        {
            if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
            {
                $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                                            array($key => $val),
                                            array_slice($temp_array,$offset)
                                          );
                $found = true;
            }
            $offset++;
        }
        if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
    }

    if ($sort_ascending) $array = array_reverse($temp_array);

    else $array = $temp_array;
}
         function getShopFeeds()
   {
   	$postarr =$post_array=$op_array= array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getShopFeeds',$input_method);
	$arr_keyword = explode(" ",$input_method['keyword']);
	foreach ($arr_keyword as $key) {
		$post = $this->user_post_model->getPostByKeyword($key);
		if(count($post)>0){
			$post_array[]=$post;
		}
	}	
	
	
	$count=0;
	foreach ($post_array as $raw) {
		if($count==0){
			foreach ($raw as $val) {
				$op_array[]=array("id"=>$val['id'],"match_count"=>1);
			}
			
		}else{
		foreach ($raw as $val) {	
		$checkmatch	= $this->checkInArray($val['id'],$op_array);
		if ($checkmatch >= 0)
		  {
		  	$op_array[$checkmatch]['match_count']=$op_array[$checkmatch]['match_count']+1;
		//  echo "Match found".$checkmatch;
		  }else
  {
     $op_array[]=array("id"=>$val['id'],"match_count"=>1);
  }
		}
		}
		  $count++;
	}

		   	    	 if(count($post)>0){
   	 	foreach ($op_array as $row) {
   	 		$post_data= $this->get_post_data($row['id'],$input_method['user_id']);
		//$post_comment = $this->getPostComment($row['post_id']);
		if(count($post_data)>0){
			/*
			 $actor_name =$this->user_post_model->get_username($row['user_id']);
			
			$post_data['actor_id']=$row['user_id'];
			
			$post_data['actor_name']=$actor_name;
			$post_data['action']=$row['action'];
			$post_data['action_time']=$row['action_time'];
			$post_data['post_comment']=$post_comment;
			*/
				$postarr[] = $post_data;
			
		}

					
						}
		if(count($postarr)>0){
			$this->response(array('message' => SUCCESS, 'data'=>$postarr,'status' => 1), 200);
		}else{
			$this->response(array('message' => NO_POST, 'status' => 0), 200);
		}				
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'status' => 1), 200);
   	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }
   
   



   
   function getUsersFeeds()
   {
   	$postarr = array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getUsersFeeds',$input_method);	
	$post = $this->user_post_model->getUsersFeeds($input_method,PAGE_DATA_LIMIT);
		   	    	 if(count($post)>0){
   	 	foreach ($post as $row) {
   	 		$post_data= $this->get_post_data($row['post_id']);
		$post_comment = $this->getPostComment($row['post_id']);
		if(count($post_data)>0){
			 $actor_name =$this->user_post_model->get_username($row['user_id']);
			
			$post_data['actor_id']=$row['user_id'];
			
			$post_data['actor_name']=$actor_name;
			$post_data['action']=$row['action'];
			$post_data['action_time']=$row['action_time'];
			$post_data['post_comment']=$post_comment;
				$postarr[] = $post_data;
			
		}

					
						}
						
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'status' => 1), 200);
   	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }
   
   
      function getUserOwnFeeds()
   {
   	$postarr = array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getUsersFeeds',$input_method);	
	$post = $this->user_post_model->getUserOwnFeeds($input_method,PAGE_DATA_LIMIT);
		   	    	 if(count($post)>0){
   	 	foreach ($post as $row) {
   	 		$post_data= $this->get_post_data($row['post_id']);
		$post_comment = $this->getPostComment($row['post_id']);
		if(count($post_comment)>0){
			$comment=$post_comment;
		}else{
			$comment = array();
		}
		if(count($post_data)>0){
			 $actor_name =$this->user_post_model->get_username($row['user_id']);
			
			$post_data['actor_id']=$row['user_id'];
			
			$post_data['actor_name']=$actor_name;
			$post_data['action']=$row['action'];
			$post_data['action_time']=$row['action_time'];
			$post_data['post_comment']=$comment;
				$postarr[] = $post_data;
			
		}

					
						}
						
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'status' => 1), 200);
   	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }

     function getGenFeeds_v2()
   {
   	date_default_timezone_set("UTC");
   	$postarr = array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getGenFeeds_v2',$input_method);
	
	$post = $this->user_post_model->getGenFeeds_v2($input_method);
		   	    	 if(count($post)>0){
		   	    	 	$lastactor='';$lastaction=$lastdatetime="";
						 $i=0;
   	 	foreach ($post as $row) {
   	 		$post_data= $this->get_post_data($row['post_id'],$row['user_id']);
		
		if(count($post_data)>0){
			
			 $user_datainfo =$this->user_post_model->get_userdata($row['user_id']);
	 $actorimage= get_user_image($user_datainfo[0]['image']);
	  $actor_name =$user_datainfo[0]['username'];
			// $post_data['actor_id']=$row['user_id'];
// 			
			// $post_data['actor_name']=$actor_name;
			// $post_data['action']=$row['action'];
			// $post_data['action_time']=$row['action_time'];
			// $post_data['post_comment']=$post_comment;
				// $postarr[] = $post_data;
				
				if(($lastactor==$row['user_id'])&&($lastaction==$row['action'])){
								$previousdatetime = strtotime($lastdatetime);	
					$your_date = strtotime($row['action_time']);
$datediff = $previousdatetime - $your_date;

$diffresult= floor($datediff / (60 * 60 * 24));
if($diffresult>=1){
	$i++;
	$post_arr = array();
}
					$post_data['actor_id']=$row['user_id'];
					$post_data['action']=$row['action'];
					$post_data['action_time']=$row['action_time'];
					
					$post_arr[]=$post_data;
					

					
				$postarr[$i-1] = array("record_id"=>$row['id'],"actor_id"=>$row['user_id'],"actor_name"=>$actor_name,"actor_image"=>$actorimage,
				"action"=>$row['action'],"postdata"=>$post_arr);	
					
				}else{
					$post_arr = array();
				
					$post_data['actor_id']=$row['user_id'];
					$post_data['action']=$row['action'];
					$post_data['action_time']=$row['action_time'];
					
					$post_arr[] = $post_data;
					$i++;
					$postarr[$i-1] = array("record_id"=>$row['id'],"actor_id"=>$row['user_id'],"actor_name"=>$actor_name,"actor_image"=>$actorimage,
				"action"=>$row['action'],"postdata"=>$post_arr);	
				
				$lastdatetime=$row['action_time'];
				}
				
				
				
			$lastactor=$row['user_id'];
			$lastaction= $row['action'];
		}
                     if(count($postarr)>19){
                     	break;
                     }
					
						}
					
		if(count($postarr)>0){				
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'status' => 1), 200);
		}else{
			$this->response(array('message' => NO_POST, 'status' => 0), 200);
		}
	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }

      function getUsersFeeds_v2()
   {
   	date_default_timezone_set("UTC");
   	$postarr = array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getUsersFeeds_v2',$input_method);
	
	$post = $this->user_post_model->getUsersFeeds_v2($input_method);
		   	    	 if(count($post)>0){
		   	    	 	$lastactor='';$lastaction=$lastdatetime="";
						 $i=0;
   	 	foreach ($post as $row) {
   	 		$post_data= $this->get_post_data($row['post_id'],$input_method['user_id']);
		
		if(count($post_data)>0){
			
			 $user_datainfo =$this->user_post_model->get_userdata($row['user_id']);
	 $actorimage= get_user_image($user_datainfo[0]['image']);
	  $actor_name =$user_datainfo[0]['username'];
			// $post_data['actor_id']=$row['user_id'];
// 			
			// $post_data['actor_name']=$actor_name;
			// $post_data['action']=$row['action'];
			// $post_data['action_time']=$row['action_time'];
			// $post_data['post_comment']=$post_comment;
				// $postarr[] = $post_data;
				
				if(($lastactor==$row['user_id'])&&($lastaction==$row['action'])){
								$previousdatetime = strtotime($lastdatetime);	
					$your_date = strtotime($row['action_time']);
$datediff = $previousdatetime - $your_date;

$diffresult= floor($datediff / (60 * 60 * 24));
if($diffresult>=1){
	$i++;
	$post_arr = array();
}
					$post_data['actor_id']=$row['user_id'];
					$post_data['action']=$row['action'];
					$post_data['action_time']=$row['action_time'];
					
					$post_arr[]=$post_data;
					

					
				$postarr[$i-1] = array("record_id"=>$row['id'],"actor_id"=>$row['user_id'],"actor_name"=>$actor_name,"actor_image"=>$actorimage,
				"action"=>$row['action'],"postdata"=>$post_arr);	
					
				}else{
					$post_arr = array();
				
					$post_data['actor_id']=$row['user_id'];
					$post_data['action']=$row['action'];
					$post_data['action_time']=$row['action_time'];
					
					$post_arr[] = $post_data;
					$i++;
					$postarr[$i-1] = array("record_id"=>$row['id'],"actor_id"=>$row['user_id'],"actor_name"=>$actor_name,"actor_image"=>$actorimage,
				"action"=>$row['action'],"postdata"=>$post_arr);	
				
				$lastdatetime=$row['action_time'];
				}
				
				
				
			$lastactor=$row['user_id'];
			$lastaction= $row['action'];
		}
                     if(count($postarr)>19){
                     	break;
                     }
					
						}
					
			if(count($postarr)>0){				
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'status' => 1), 200);
			}else{
				$this->response(array('message' => NO_POST, 'status' => 0), 200);
			}
	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }
   
      function getUserOwnFeeds_v2()
   {
   	date_default_timezone_set("UTC");
   	$postarr = array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getUsersFeeds_v2',$input_method);
	
	$post = $this->user_post_model->getUserOwnFeeds_v2($input_method);
		   	    	 if(count($post)>0){
		   	    	 	$lastactor='';$lastaction=$lastdatetime="";
						 $i=0;
   	 	foreach ($post as $row) {
   	 		$post_data= $this->get_post_data($row['post_id'],$input_method['user_id']);
		
		if(count($post_data)>0){
			
			 $user_datainfo =$this->user_post_model->get_userdata($row['user_id']);
	 $actorimage= get_user_image($user_datainfo[0]['image']);
	  $actor_name =$user_datainfo[0]['username'];
			// $post_data['actor_id']=$row['user_id'];
// 			
			// $post_data['actor_name']=$actor_name;
			// $post_data['action']=$row['action'];
			// $post_data['action_time']=$row['action_time'];
			// $post_data['post_comment']=$post_comment;
				// $postarr[] = $post_data;
				
				if(($lastactor==$row['user_id'])&&($lastaction==$row['action'])){
								$previousdatetime = strtotime($lastdatetime);	
					$your_date = strtotime($row['action_time']);
$datediff = $previousdatetime - $your_date;

$diffresult= floor($datediff / (60 * 60 * 24));
if($diffresult>=1){
	$i++;
	$post_arr = array();
}
					$post_data['actor_id']=$row['user_id'];
					$post_data['action']=$row['action'];
					$post_data['action_time']=$row['action_time'];
					
					$post_arr[]=$post_data;
					

					
				$postarr[$i-1] = array("record_id"=>$row['id'],"actor_id"=>$row['user_id'],"actor_name"=>$actor_name,"actor_image"=>$actorimage,
				"action"=>$row['action'],"postdata"=>$post_arr);	
					
				}else{
					$post_arr = array();
				
					$post_data['actor_id']=$row['user_id'];
					$post_data['action']=$row['action'];
					$post_data['action_time']=$row['action_time'];
					
					$post_arr[] = $post_data;
					$i++;
					$postarr[$i-1] = array("record_id"=>$row['id'],"actor_id"=>$row['user_id'],"actor_name"=>$actor_name,"actor_image"=>$actorimage,
				"action"=>$row['action'],"postdata"=>$post_arr);	
				
				$lastdatetime=$row['action_time'];
				}
				
				
				
			$lastactor=$row['user_id'];
			$lastaction= $row['action'];
		}
                     if(count($postarr)>19){
                     	break;
                     }
					
						}
					
		if(count($postarr)>0){						
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>PAGE_DATA_LIMIT,'status' => 1), 200);
		}else{
		$this->response(array('message' => NO_POST, 'status' => 0), 200);	
		}
	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }


         function getTrends()
   {
   	date_default_timezone_set("UTC");
   	$postarr = array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getTrends',$input_method);
	
	$post = $this->user_post_model->get_all_post_data($input_method,30);
		   	    	 if(count($post)>0){
		   	    	 	$lastactor='';$lastaction=$lastdatetime="";
						 $i=0;
   	 	foreach ($post as $row) {
   	 		$post_data= $this->get_post_data($row['id'],$input_method['user_id']);
		
		if(count($post_data)>0){
			$postarr[]=$post_data;
		}
					
		}
					
		if(count($postarr)>0){						
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>30,'status' => 1), 200);
		}else{
		$this->response(array('message' => NO_POST, 'status' => 0), 200);	
		}
	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }

           function getTrendByKeyword()
   {
   	date_default_timezone_set("UTC");
   	$postarr = array();
   	$input_method = $this->webservices_inputs();
	$this->validate_param('getTrendByKeyword',$input_method);
	
	$post = $this->user_post_model->get_all_post_data_trendKeyword($input_method,30);
		   	    	 if(count($post)>0){
		   	    	 	$lastactor='';$lastaction=$lastdatetime="";
						 $i=0;
   	 	foreach ($post as $row) {
   	 		$post_data= $this->get_post_data($row['id'],$input_method['user_id']);
		
		if(count($post_data)>0){
			$postarr[]=$post_data;
		}
					
		}
					
		if(count($postarr)>0){						
		$this->response(array('message' => SUCCESS, 'data'=>$postarr,'limit'=>30,'status' => 1), 200);
		}else{
		$this->response(array('message' => NO_POST, 'status' => 0), 200);	
		}
	 }else{
   	 	$this->response(array('message' => NO_POST, 'status' => 0), 200);
   	 }
	
   }
    
} 

?>
