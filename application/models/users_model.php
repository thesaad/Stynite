<?php
class Users_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }
    function user_login($input = "")
    {
        $username = $input['username'];
        $password = md5($input['password']);
		$this->db->select("users.*");	
        $this->db->where("(`email`='$username' or username = '$username'  )");
        $this->db->where('password', $password);		
        $query = $this->db->get('users');
		if($query->num_rows()>0){
			$row=$query->row();
			$this->userLoginActivity($row->id);
			return $row->id;
		}else{
			return false;
		}
        

    }
	
	function userLoginActivity($user_id)
	{
		 
		 $arr_field = array(
            "is_logout" => 0);
		$this->db->where('id', $user_id);
        $query = $this->db->update('users', $arr_field);
		if($query){
		
			return true;
		}else{
			return false;
		}
	}
	
	function userLogout($input = "")
	{
		$user_id = $input['user_id'];
		 $arr_field = array(
            "is_logout" => 1);
		$this->db->where('id', $user_id);
        $query = $this->db->update('users', $arr_field);
		if($query){
		
			return true;
		}else{
			return false;
		}
	}
		function get_user_profile($input)
	{
		$user_id = $input['user_id'];

	
		if($user_id>0)
		 $this->db->where('users.id', $user_id);
		
		
		
		
		
		  	$this->db->select("users.*,user_address.house,
    	user_address.street,user_address.city,user_address.postcode as postcode,user_address.country,user_setting.likes,
    	user_setting.price_drop,user_setting.offer_sale,user_setting.follow");
       
		$this->db->join('user_setting','users.id=user_setting.user_id','left');
		$this->db->join('user_address','users.id=user_address.user_id','left');
            $query = $this->db->get('users');
           // echo $this->db->last_query();exit;
	return $query->result_array();
		
	}
    
    function user_register($input = "")
    {
    	$image=$contact='';
     if(isset($input['image'])){
     	$image = $input['image'];
     }
	 if(isset($input['contact'])){
	 	$contact = $input['contact'];
	 }
	 
        $arr_field = array(
            "firstname" => $input['firstname'],
            "lastname" => $input['lastname'],
            "username" => $input['username'],
            "dob" => $input['dob'],
            "gender" => $input['gender'],
            "image" => $image,
            "email" => $input['email'],
            "contact"=>$contact,
            "password" => md5($input['password']),
            "auth_token" => $input['auth_token'],
            "user_type" => 'N');
      
            $this->db->insert('users', $arr_field);
            // echo $this->db->last_query();exit;
            $jobid = $this->db->insert_id();

       

        if ($jobid) {
        	$this->verify_account_mail($input['email']);
            return $jobid;
        }
        return false;
    }
	    function user_update($input = "")
    {
    	//$this->db->where('is_verify', '1');
        $this->db->where('email', $input['email']);
            $query = $this->db->get('users');	
           if($query->num_rows()>0)
		   {
		   	$row=$query->row();
			if($row->id!=$input['user_id']){
			
				return "email_fail";
			}
		   	
		   }
	 if(isset($input['contact'])){
	 	$contact = $input['contact'];
	 }
	 
	 if(isset($input['password']))
	 {
	 	if($input['password']!=''){
	 		$this->update_password($input['user_id'],$input['password']);
	 	}
	 }
        $arr_field = array(
            "firstname" => $input['firstname'],
            "lastname" => $input['lastname'],
            "username" => $input['username'],
            "dob" => $input['dob'],
            "gender" => $input['gender'],            
            "email" => $input['email'],
            "contact"=>$contact, 
            "user_type" => 'N');
	
       
        if (isset($input['user_id'])) {
            $id = $input['user_id'];
            $this->db->where('id', $id);
            $query = $this->db->update('users', $arr_field);
            $jobid = $id;
        }

        if ($jobid) {
            return true;
        }
        return false;
    }
	function update_password($user_id,$password)
	{
		$arr_field = array(
            "password" =>md5($password));
		 $this->db->where('id', $user_id);
            $query = $this->db->update('users', $arr_field);
			return $query;
	}
	function user_photo_update($input = "")
	{
		$id = $input['user_id'];
		$image = $input['image'];
		 	 $arr_field = array(
            "image" => $image);
		 $this->db->where('id', $id);
            $query = $this->db->update('users', $arr_field);
			if($query){
				return true;
			}
			return false;
	}
		function user_bio_update($input = "")
	{
		$id = $input['user_id'];
		
		 	 $arr_field = array(
            "bio" => $input['bio']);
		 $this->db->where('id', $id);
            $query = $this->db->update('users', $arr_field);
			if($query){
				return true;
			}
			return false;
	}
	
		function get_follower_count($id)
    {
     $query = 	$this->db->query("SELECT count(id) as like_count FROM `user_follower` where user_id='$id' ");
	 $row=$query->row();
	 return $row->like_count;	
    } 
    function get_follow_count($id)
    {
     $query = 	$this->db->query("SELECT count(id) as like_count FROM `user_follower` where follower_id='$id' ");
	 $row=$query->row();
	 return $row->like_count;	
    }
	function get_country()
	{
		$query=$this->db->get("countries");
		return $query->result();
	}
	    function user_update_address($input = "")
    {
    	$postal_code="";
    	//$this->db->where('is_verify', '1');
    	if(isset($input['postcode'])){
    		$postal_code =$input['postcode'];
    	}
    	$arr_field = array(
            "user_id" => $input['user_id'],
            "house" => $input['house'],   
            "postcode" =>$postal_code , 
              "street" => $input['street'],
            "city" => $input['city'],          
            "country" => $input['country']);
    	
    	
        $this->db->where('user_id', $input['user_id']);
            $query = $this->db->get('user_address');	
           if($query->num_rows()>0)
		   {
		   	$this->db->where('user_id', $input['user_id']);
            $query = $this->db->update('user_address', $arr_field);
		   	if ($query) {
            return true;
        }
		   }else {
            $this->db->insert('user_address', $arr_field);
            //   echo $this->db->last_query();exit;
            $jobid = $this->db->insert_id();
if ($jobid) {
            return true;
        }
        }

        
        return false;
    }
	
	     function user_follow($input = "")
    {
    	error_reporting(1);
    	$this->db->where('user_id',$input['follow_id']);
		$this->db->where('follower_id',$input['user_id']);
		$query = $this->db->get('user_follower');
		if($query->num_rows()>0)
		{
			$this->db->where('user_id',$input['follow_id']);
		    $this->db->where('follower_id',$input['user_id']);
            $result = $this->db->delete('user_follower');
			return 0;
		}else{
				 $arr_field = array(
            "user_id" => $input['follow_id'],
            "follower_id" => $input['user_id']            
           );       
            $this->db->insert('user_follower', $arr_field);
            //   echo $this->db->last_query();exit;
            $id = $this->db->insert_id();
		
			
			$device_token = $this->getUserDevice($input['follow_id']);
			
			$followerdata = $this->get_userdata_for_notice($input['user_id']);
			if($followerdata){
			 $username = $followerdata['firstname']." ".$followerdata['lastname'];
			 $message = $username." started following you";
			 $device[]=$device_token['device_token'];
			
			 try {
			 	$otherdata=array();
			 	 $otherdata['data'] = array('actor_id'=>$input['user_id'], 'action'=>"FOLLOW",
		"post_id"=>""); 
 if($device_token['device_platfrom']=="Ios"){
			send_push($device, $message,$otherdata);
		}else{
			 send_andorid_push($message,$device,$otherdata); 
		}
} catch (Exception $e) {
  //alert the user.
 // var_dump($e->getMessage());
}
}
			return 1;
		}
	}
	function checkLogout($userid){
		$this->db->where("is_logout",'1');
		$this->db->where("id",$userid);
		$querychk =$this->db->get("users");
		 if($querychk->num_rows()>0){
		 	return true;
		 }else{
		 	return false;
		 }
	}
	function checkFollowFlag($userid){
		$this->db->where("follow",'0');
		$this->db->where("user_id",$userid);
		$querychk =$this->db->get("user_setting");
		 if($querychk->num_rows()>0){
		 	return true;
		 }else{
		 	return false;
		 }
	}
	
	
	function getUserDevice($userid)
	{
		$logout = $this->checkLogout($userid);
		
		if($logout){
			return false;
		}
		
		$follownoticeoff = $this->checkFollowFlag($userid);
		if($follownoticeoff){
			return false;
		}
		
	$query	= $this->db->query("select devices.* from user_device  
		join devices on devices.id = user_device.device_id 
		 where user_device.user_id = '$userid'");
		 if($query->num_rows()>0){
		  
			return $query->row_array();
		 }else{
		 	return false;
		 }
	}
		function get_userdata_for_notice($user_id)
	{

		$this->db->where("id",$user_id);
		$query =$this->db->get("users");
		if($query->num_rows()>0){
			
		return $query->row_array();;
		}else{
			return false;
		}
		
	}
		    function user_update_setting($input = "")
    {
    	//$this->db->where('is_verify', '1');
    	$arr_field = array(
            "user_id" => $input['user_id'],
            "likes" => $input['likes'],   
              "price_drop" => $input['price_drop'],
            "offer_sale" => $input['offer_sale'],          
            "follow" => $input['follow']);
    	
    	
        $this->db->where('user_id', $input['user_id']);
            $query = $this->db->get('user_setting');	
           if($query->num_rows()>0)
		   {
		   	$this->db->where('user_id', $input['user_id']);
            $query = $this->db->update('user_setting', $arr_field);
		   	if ($query) {
            return true;
        }
		   }else {
            $this->db->insert('user_setting', $arr_field);
            //   echo $this->db->last_query();exit;
            $jobid = $this->db->insert_id();
if ($jobid) {
            return true;
        }
        }

        
        return false;
    }
	
	function check_email($input)
	{
		   $this->db->where('email', $input['email']);
            $query = $this->db->get('users');
	
	
           if($query->num_rows()>0)
		   {
		   	$row=$query->row();
			$is_verify=$row->is_verify;
			
			if($is_verify=='1')
			{
				if($row->user_type=="S"){
					return SOCIAL_ACCOUNT_ALREADY_EXISTS;
				}
				return ACCOUNT_ALREADY_EXISTS;
			}else{
				$this->verify_account_mail($input['email']);
				return ACCOUNT_VERIFY_ERROR_EMAIL;
			}
		   	
		   }
		   return false;
	}
	
		function check_username($input)
	{
		   $this->db->where('username', $input['username']);
            $query = $this->db->get('users');
	
	
           if($query->num_rows()>0)
		   {
		   	$row=$query->row();
			
				return USERNAME_ERROR;
			
		   	
		   }
		   return false;
	}
	
	
	function check_verification($input)
	{
		   $this->db->where('email', $input['email']);
            $query = $this->db->get('users');
	
	
           if($query->num_rows()>0)
		   {
		   	$row=$query->row();
			$is_verify=$row->is_verify;
			
			if($is_verify=='1')
			{
				return false;
			}else{
				$this->verify_account_mail($input['email']);
				return ACCOUNT_VERIFY_ERROR_EMAIL;
			}
		   	
		   }
		   return false;
	}
    function user_social_register($input = "")
    {
    	$id=$image='';
		if(isset($input['image'])){
			$image=$input['image'];
		}
        $this->db->where('email', $input['email']);
            $query = $this->db->get('users');
	
	
           if($query->num_rows()>0)
		   {
		   	$row=$query->row();
			$id=$row->id;
		   	 $arr_field = array(
		   	 "social_login_id" => $input['social_login_id'],
            "social_login_type" => $input['social_login_type'],
            "firstname" => $input['firstname'],
             "lastname" => $input['lastname'],
              "image" => $image,
            "email" => $input['email'],
             "is_verify"=>'1',
            "user_type" => 'S');
		   }else{
		   	 $arr_field = array(
            "social_login_id" => $input['social_login_id'],
            "social_login_type" => $input['social_login_type'],
              "firstname" => $input['firstname'],
             "lastname" => $input['lastname'],
            "email" => $input['email'], 
             "image" => $image,      
             "auth_token" => $input['auth_token'],
              "is_verify"=>'1',
            "user_type" => 'S');
		   }
	
       
        if ($id>0) {
            $this->db->where('id', $id);
            $query = $this->db->update('users', $arr_field);
            $jobid = $id;
        } else {
            $this->db->insert('users', $arr_field);
            //   echo $this->db->last_query();exit;
            $jobid = $this->db->insert_id();

        }
$this->userLoginActivity($jobid);
        if ($jobid) {
            return $jobid;
        }
        return false;
    }
function user_social_data($input = "")
    {
    	$id='';
        $this->db->where('social_login_id', $input['social_login_id']);
            $query = $this->db->get('users');
	return $query->result_array();
    }
	function user_fetch_data($input = "")
    {
    	$id='';
    	$this->db->select("users.*,user_address.house,
    	user_address.street,user_address.city,user_address.country,user_setting.likes,
    	user_setting.price_drop,user_setting.offer_sale,user_setting.follow");
        $this->db->where('users.id', $input);
		$this->db->join('user_setting','users.id=user_setting.user_id','left');
		$this->db->join('user_address','users.id=user_address.user_id','left');
            $query = $this->db->get('users');
	return $query->result_array();
    }
    
	
		function get_users_of_user($input,$limitdata)
	{
		$page = $input['page'];
	$user_id = $input['user_id'];
	$limit = $limitdata;
	if ($page == 1) {
		$startindex = 0;

	} else {
		$startindex = ($page - 1) * $limit;
	}
		if($input['user_type']=='FOLLOWER')
		{
			$sql = "select user_follower.user_id,users.*  FROM `user_follower` left join
			 users on users.id=user_follower.follower_id where  user_follower.user_id=$user_id limit $startindex,$limit";
		}
else{
			$sql = "select user_follower.user_id,users.*  FROM `user_follower` left join users on 
			users.id=user_follower.user_id where  user_follower.follower_id=$user_id limit $startindex,$limit";
			
		}
		$query=$this->db->query($sql);
        return $query->result_array();
	}
		function check_userfollow($view_by,$userid)
	{
		

		$this->db->where('user_id',$userid);
		$this->db->where('follower_id',$view_by);
		$query = $this->db->get('user_follower');
		if($query->num_rows()>0)
		{
			return "1";
		}else{
			return "0";
		}
       
	}
	
   	function user_device($input = "")
    {
        
	 $arr_field = array(        
            "device_token" => $input['device_token'],
             "device_type" => $input['device_type']
       );
	 
 
            $this->db->where('id', $input['user_id']);
            $query = $this->db->update('users', $arr_field);
            return $query;
       

        
    }
    function forget_password($username)
	{
			$this->db->where("(`email`='$username' )");
		$query = $this->db->get('users');
		//echo $this->db->last_query();exit;
		if($query->num_rows()>0)
		{
			$row=$query->row();
			$email=$row->email;
			$db_reset_token=$row->reset_token;
			//token generate and save
			if($db_reset_token==''){
				
			
			  $reset_token = substr(str_shuffle("0123456789abcdef".$email."ghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                    0, 30);
			$arr_field = array("reset_flag" =>'0',
                 "reset_token" =>$reset_token 
                            );
                          
                $this->db->where('email', $email);
                $query = $this->db->update('users', $arr_field);
			
			}else{
			$reset_token	= $db_reset_token;
			}
			//start
			
			           $config_company_email = "no-reply@Stynite.com";
                    $to = $email;
                    $subject = "Stynite Reset Password";
                    // $txt = 'hello';

                    $txt = "Hello,";
                    $txt.="<p>Thank you for using Stynite.</p>";
                    //$click='Click here for verify now';
                    $click = base_url()."index.php/forgetpassword?reset_token=".$reset_token;
                    // $click=<a href=''></a>;
                    $txt .= "<div >";
                    $txt .= '
   <div style="padding-bottom:8px;">
   <a href=' . $click . ' >Click here to reset your password.</a>
   </div> 
  </div>
  
 </div>
 
 <div >
  Thank You.
 </div>
 <p>Stynite Team</p>
 <p><img src="'.LOGO_IMAGE.'" style="height:50px; width:50px;"/ ></p>
 <p>For more information, contact <a href="mailto:info@Stynite.com">info@Stynite.com</a></p>
 <p>

 </p>
</div>';

                    $headers = "From: " . $config_company_email . "\r\n";
                    //   $headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    mail($to, $subject, $txt, $headers);

                    //end of mail script
			
			//end
			return 1;		
			
		}else{
				
			return 0;
		}	
	}

   
    function verify_account_mail($email)
	{
			$this->db->where("(`email`='$email' )");
			$this->db->where("user_type","N");
		$query = $this->db->get('users');
		//echo $this->db->last_query();exit;
		if($query->num_rows()>0)
		{
			$row=$query->row();
			$email=$row->email;
					$db_reset_token=$row->reset_token;
			//token generate and save
			if($db_reset_token==''){
				
			
			//token generate and save
			  $reset_token = substr(str_shuffle("0123456789abcdef".$email."ghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),
                    0, 30);
			$arr_field = array("reset_flag" =>'0',
                 "reset_token" =>$reset_token 
                            );
                          
                $this->db->where('email', $email);
                $query = $this->db->update('users', $arr_field);
			
			
			//start
			}else{
			$reset_token	= $db_reset_token;	
			}
			           $config_company_email = "no-reply@Stynite.com";
                    $to = $email;
                    $subject = "Stynite account verification";
                    // $txt = 'hello';

                    $txt = "Hello,";
                    $txt.="<p>Thank you for signing up for Stynite</p>";
                    //$click='Click here for verify now';
                    $click = base_url()."index.php/verifyaccount?verify_token=".$reset_token;
                    // $click=<a href=''></a>;
                    $txt .= "<div >";
                    $txt .= '
   <div style="padding-bottom:8px;">
   <a href=' . $click . ' >Click here for verify now</a>
   </div>
   <p>Link expires in 5 minutes.</p>
   <p>After verification, logout and login to Experience Stynite</p>
  </div>
  
 </div>
 
 <div >
Thank You.
 </div>
 <p>Stynite Team</p>
 <p><img src="'.LOGO_IMAGE.'" style="height:50px; width:50px;" ></p>
 <p>For more information, contact <a href="mailto:info@Stynite.com">info@Stynite.com</a></p>
 <p>

 </p>
</div>';

                    $headers = "From: " . $config_company_email . "\r\n";
                    //   $headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    mail($to, $subject, $txt, $headers);

                    //end of mail script
			
			//end
			return 1;		
			
		}else{
				
			return 0;
		}	
	}





	function get_user_byreset_token()
	{
		if(isset($_GET['reset_token'])){
			$reset_token=$_GET['reset_token'];
		$this->db->where("reset_token",$reset_token);
		$this->db->where("reset_flag","0");
		$this->db->where("user_type","N");
		$query = $this->db->get('users');
		//echo $this->db->last_query();exit;
		if($query->num_rows()>0)
		{
			$row=$query->row();
			$email=$row->email;
			
			return $email;
		}else{
			return 0;
		}
		}else{
			return 0;
		}
		
		
	}
function photo_reward()
{
	$this->db->where('id',1);
	
    $query = $this->db->get('photo_setting');
    if($query->num_rows()>0)
    {
    	$row=$query->row();
    	return $row->reward;
    }else{
    	return false;
    }
}


	function reset_password()
	{
	
            $this->db->where("email",$_POST['email']);
             $this->db->where("reset_flag",'0');
            $query = $this->db->get('users');
           
                if ($query->num_rows() > 0)
                {
                    
                 $arr_field = array("reset_flag" =>'1',
                 "reset_token" =>'',
                  "password"=>md5($_POST['password'])
                
                            );
                          
                $this->db->where('email', $_POST['email']);
                $query = $this->db->update('users', $arr_field);
                
                return $query;  
        		}
				return false;
	}
	function verifyaccount()
	{
	
             $this->db->where("reset_token",$_GET['verify_token']);
            $query = $this->db->get('users');
           
                if ($query->num_rows() > 0)
                {
                    
                 $arr_field = array("reset_flag" =>'0',
                 "reset_token" =>'',
                  "is_verify"=>1
                
                            );
                          
                $this->db->where('reset_token', $_GET['verify_token']);
                $query = $this->db->update('users', $arr_field);
                
                return $query;  
        		}
				return false;
	}

}
?>