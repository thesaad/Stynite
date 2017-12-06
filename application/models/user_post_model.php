<?php
class User_post_model extends CI_Model {

	public function __construct() {
		$this -> load -> database();
	}

	function create_post($input = '') {
		$image = $tags= "";
		$image_flag = 0;
		if (isset($input['image'])) {
			$image = $input['image'];
			$image_flag = 1;
		}
		
		if (isset($input['tags'])) {
			$tags = $input['tags'];
			
		}
		$datatime = get_gmt_time();

		$arr_field = array("user_id" => $input['user_id'], "image" => $image, "is_image" => $image_flag, "post_text" => $input['post_text'], "post_date" => $datatime
		,"tags"=>$tags);

		$this -> db -> insert('user_post', $arr_field);
		// echo $this->db->last_query();exit;
		$postid = $this -> db -> insert_id();

        if($postid>0){
        	if(isset($input['datetime'])){
        		$datetime=$input['datetime'];
        	}else{
        		$datetime=get_gmt_time();
        	}
        	$this->userAction($input['user_id'],$postid,$datetime,ACTION_POST);
        }
		return $postid;

	}
	
		function share_post($input = '') {

		$datatime = get_gmt_time();

		$arr_field = array("user_id" => $input['user_id'],  "post_id" => $input['post_id']);

		$this -> db -> insert('share_post', $arr_field);
		// echo $this->db->last_query();exit;
		$postid = $input['post_id'];

        if($postid>0){
        	if(isset($input['datetime'])){
        		$datetime=$input['datetime'];
        	}else{
        		$datetime=get_gmt_time();
        	}
        	$this->userAction($input['user_id'],$postid,$datetime,ACTION_SHARE);
        }
		return $postid;

	}
	

	function edit_post($input = '') {
		$image =$tags= "";
		$image_flag = 0;
		
		
		if (isset($input['tags'])) {
			$tags = $input['tags'];
			
		}
				
		$datatime = get_gmt_time();
if (isset($input['image'])) {
			$image = $input['image'];
			$image_flag = 1;
			$arr_field = array("image" => $image, "is_image" => $image_flag,"tags"=>$tags, "post_text" => $input['post_text'], "post_date" => $datatime);
		
		}else{
			$arr_field = array("tags"=>$tags, "post_text" => $input['post_text'], "post_date" => $datatime);
		
		}
		
		$this -> db -> where('user_id', $input['user_id']);
		$this -> db -> where('id', $input['post_id']);
		$this -> db -> update('user_post', $arr_field);

		// echo $this->db->last_query();exit;
		$postid = $input['post_id'];

		return $postid;

	}
    function get_user_action($user_id,$post_id,$action) {

		$this -> db -> where("user_id", $user_id);
		$this -> db -> where("post_id", $post_id);
		$this -> db -> where("action", $action);
		$query = $this -> db -> get("user_action");
		if( $query ->num_rows()>0){
			$row=$query->row();
			return $row -> action_time;
		}else{
			return false;
		}
		
	}
	
	
	function get_userposts($input = '',$pagelimit) {
        $user_id = $input['user_id'];
        $page = $input['page'];
		$limit = $pagelimit;
		if ($page == 1) {
			$startindex = 0;

		} else {
			$startindex = ($page - 1) * $limit;
		}
	
		$query = $this -> db -> query("select * from  user_post where user_id = '$user_id' 
		order by id desc
		limit $startindex,$limit  ");
		return $query -> result_array();
	}
	function getUserLikedPosts($input = '',$pagelimit) {
         $user_id = $input['user_id'];
			$page = $input['page'];
		$limit = $pagelimit;
		if ($page == 1) {
			$startindex = 0;

		} else {
			$startindex = ($page - 1) * $limit;
		}
		$query = $this -> db -> query("select user_post.* from user_post   join 
		post_like on post_like.post_id =  user_post.id where post_like.user_id = '$user_id' order by post_like.id desc
		limit $startindex,$limit ");
		return $query -> result_array();
	}

	function get_post_count($user_id) {

		$query = $this -> db -> query("SELECT count(id) as post_count FROM `user_post` where user_id='$user_id' ");
		$row = $query -> row();
		return $row -> post_count;
	}

	function get_post_data($postid) {
		$this -> db -> where("id", $postid);
		$query = $this -> db -> get("user_post");
		return $query -> result_array();
	}
	
		function get_all_post_data($input,$pagelimit) {
			$page = $input['page'];
		$user_id = $input['user_id'];
		$limit = $pagelimit;
		if ($page == 1) {
			$startindex = 0;

		} else {
			$startindex = ($page - 1) * $limit;
		}
		$query = $this -> db -> query("SELECT p.id,count(pl.id) + count(pc.id) trendcount FROM `user_post` p 
		left join post_like pl on pl.post_id=p.id left join share_post pc on pc.post_id=p.id
		 group by p.id order by trendcount desc limit $startindex,$limit");
		//  echo $this->db->last_query();exit;
		return $query -> result_array();
	}
	function get_all_post_data_trendKeyword($input,$pagelimit) {
			$page = $input['page'];
		$keyword	= $input['keyword'];
		$user_id = $input['user_id'];
		$limit = $pagelimit;
		if ($page == 1) {
			$startindex = 0;

		} else {
			$startindex = ($page - 1) * $limit;
		}
		$query = $this -> db -> query("SELECT p.id,count(pl.id) + count(pc.id) trendcount FROM `user_post` p 
		left join post_like pl on pl.post_id=p.id left join share_post pc on pc.post_id=p.id
		left join users u on u.id=p.user_id
		where p.post_text like '%$keyword%' or p.tags like '%$keyword%' or u.firstname like  '%$keyword%'
		or u.username like  '%$keyword%' or u.lastname like  '%$keyword%' or u.email like  '%$keyword%'
		 group by p.id order by trendcount desc limit $startindex,$limit");
		//  echo $this->db->last_query();exit;
		return $query -> result_array();
	}

	function userPostLike($input = "") {
		$this -> db -> where('user_id', $input['user_id']);
		$this -> db -> where('post_id', $input['post_id']);
		$query = $this -> db -> get('post_like');
		if ($query -> num_rows() > 0) {
			$this -> db -> where('user_id', $input['user_id']);
			$this -> db -> where('post_id', $input['post_id']);
			$result = $this -> db -> delete('post_like');
			
			$this->db->where("action",ACTION_LIKE);
   		$this->db->where("post_id",$input['post_id']);
   		$this->db->where("user_id",$input['user_id']);
   		$query =$this->db->delete("user_action");

			return 0;
		} else {
			$arr_field = array("user_id" => $input['user_id'], "post_id" => $input['post_id']);
			$this -> db -> insert('post_like', $arr_field);
			//   echo $this->db->last_query();exit;
			$id = $this -> db -> insert_id();
			if($id){
				if(isset($input['datetime'])){
        		$datetime=$input['datetime'];
        	}else{
        		$datetime=get_gmt_time();
        	}
        	$this->userAction($input['user_id'],$input['post_id'],$datetime,ACTION_LIKE);
			}

            //send notification
            	$postuserid	= $this->getPostUserId($input['post_id']);	
				if($postuserid!=$input['user_id']){
			$device_token = $this->getUserDevice($postuserid);
			
			$followerdata = $this->get_userdata_for_notice($input['user_id']);
			
			 $username = $followerdata['firstname']." ".$followerdata['lastname'];
			 $message = $username." liked your post";
			 $device[]=$device_token['device_token'];
			
			 try {
			 	
			 	$otherdata=array();
			 	 $otherdata['data'] = array('actor_id'=>$input['user_id'], 'action'=>"LIKE",
		"post_id"=>$input['post_id']);
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
            
            //end
			return 1;
		}

	}
function getPostUserId($postid)
{
	$query	= $this->db->query("select user_post.* from user_post 
		 where user_post.id = '$postid'");
		 if($query->num_rows()>0){
		 	$row = $query->row();
			return $row->user_id;
		 }else{
		 	return false;
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

function checkLikeFlag($userid){
		$this->db->where("likes",'0');
		$this->db->where("user_id",$userid);
		$querychk =$this->db->get("user_setting");
		//echo $this->db->last_query();exit;
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
		
		$likenoticeoff = $this->checkLikeFlag($userid);
		if($likenoticeoff){
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

	function userPostComment($input = "") {
		$arr_field = array("user_id" => $input['user_id'], "post_id" => $input['post_id'], "comment" => $input['comment']);
		$this -> db -> insert('post_comment', $arr_field);
		// $this->db->last_query();exit;
		$id = $this -> db -> insert_id();
		if ($id) {
				if(isset($input['datetime'])){
        		$datetime=$input['datetime'];
        	}else{
        		$datetime=get_gmt_time();
        	}
        	$this->userAction($input['user_id'],$input['post_id'],$datetime,ACTION_COMMENT);
			return 1;
		}
		return 0;
	}
	
	  function userAction($user_id,$post_id,$datetime,$action)
   {
   	if($action==ACTION_LIKE){
   		$this->db->where("action",ACTION_LIKE);
   		$this->db->where("post_id",$post_id);
   		$this->db->where("user_id",$user_id);
   		$query =$this->db->get("user_action");
	
		if($query->num_rows()>0){
			$this->db->where("action",ACTION_LIKE);
   		$this->db->where("post_id",$post_id);
   		$this->db->where("user_id",$user_id);
   		$query =$this->db->delete("user_action");
		//echo	$this->db->last_query();exit;
		}
   	}
   	  $arr_field = array("user_id" => $user_id, "post_id" => $post_id, "action_time" => $datetime,"action"=>$action);
		$this -> db -> insert('user_action', $arr_field);
		// $this->db->last_query();exit;
		$id = $this -> db -> insert_id();
		if ($id) {
			return 1;
		}
		return 0;
   }

	function get_post_comment($post_id) {
		$query = $this -> db -> query("select post_comment.*,users.username,users.image as userimage from post_comment left join
		users on users.id = post_comment.user_id where post_comment.post_id = '$post_id'");
		return $query -> result_array();
	}
	
	function get_post_like($post_id) {
		$query = $this -> db -> query("select post_like.*,users.username,users.image as userimage from post_like left join
		users on users.id = post_like.user_id where post_like.post_id = '$post_id'");
		return $query -> result_array();
	}

	function get_post_likecount($post_id) {
		$query = $this -> db -> query("select count(id) as likecount  from post_like  where post_id = '$post_id'");
		$row = $query -> row();
		return $row -> likecount;
	}
	
	function get_post_sharecount($post_id) {
		$query = $this -> db -> query("select count(id) as sharecount  from share_post  where post_id = '$post_id'");
		$row = $query -> row();
		return $row -> sharecount;
	}

	function get_post_commentcount($post_id) {
		$query = $this -> db -> query("select count(id) as commentcount  from post_comment  where post_id = '$post_id'");
		$row = $query -> row();
		return $row -> commentcount;
	}

	function getUsersFeeds($input, $pagelimit) {
		$page = $input['page'];
		$user_id = $input['user_id'];
		$limit = $pagelimit;
		if ($page == 1) {
			$startindex = 0;

		} else {
			$startindex = ($page - 1) * $limit;
		}
		$sql = "select user_follower.user_id,user_action.*  FROM `user_follower` 
			 join user_action on user_action.user_id = user_follower.user_id
			where  user_follower.follower_id=$user_id order by user_action.id desc limit $startindex,$limit";
			
		$query =$this->db->query($sql);
		return $query->result_array();
	}
	
		function getUsersFeeds_v2($input, $pagelimit) {
			$last_record = $input['last_record'];
		if($last_record>0){
			$morequery=" and user_action.id < '$last_record'";
		}else{ 
		$morequery="";	
		}
		
		$user_id = $input['user_id'];
		
		
		$sql = "select user_follower.user_id,user_action.*  FROM `user_follower` 
			 join user_action on user_action.user_id = user_follower.user_id
			where  user_follower.follower_id=$user_id ".$morequery." order by user_action.id desc ";
			
		$query =$this->db->query($sql);
		return $query->result_array();
	}
		function getGenFeeds($input, $pagelimit) {
		$page = $input['page'];
		$user_id = $input['user_id'];
		$limit = $pagelimit;
		if ($page == 1) {
			$startindex = 0;

		} else {
			$startindex = ($page - 1) * $limit;
		}
		$sql = "select user_action.*  FROM `user_action` where action =  '".ACTION_POST."'  order by id desc			 
			 limit $startindex,$limit";
			
		$query =$this->db->query($sql);
		return $query->result_array();
	}
	function getGenFeeds_v2($input) {
		$last_record = $input['last_record'];
		if($last_record>0){
			$morequery="where id < '$last_record'";
		}else{
		$morequery="";	
		}
		$sql = "select user_action.*  FROM `user_action`  ".$morequery."   order by id desc			 
			 ";
			
		$query =$this->db->query($sql);
		return $query->result_array();
	}
	function getUserOwnFeeds_v2($input, $pagelimit) {

		$user_id = $input['user_id'];
			$last_record = $input['last_record'];
		if($last_record>0){
			$morequery=" and user_action.id < '$last_record'";
		}else{
		$morequery="";	
		}
			 $query = $this->db->query("SELECT group_concat(id) as postids  FROM `user_post` WHERE `user_id` = '$user_id' ");
		
		$row = $query->row();
		$post_ids = $row->postids;
		$sql = "select user_action.*  FROM `user_action` 			 
			where  ( user_action.user_id=$user_id  or  post_id in ($post_ids ) ) ".$morequery."  order by id desc ";
			
			$query =$this->db->query($sql);
		//echo $this->db->last_query();exit;
		$query =$this->db->query($sql);
		return $query->result_array();
	}
	function getUserOwnFeeds($input, $pagelimit) {
		$page = $input['page'];
		$user_id = $input['user_id'];
		$limit = $pagelimit;
		if ($page == 1) {
			$startindex = 0;

		} else {
			$startindex = ($page - 1) * $limit;
		}
	
		
	   $sql = "select user_action.*  FROM `user_action` 			 
			where  user_action.user_id=$user_id  limit $startindex,$limit";
			
		
		return $query->result_array();
	}
	function get_userdata($user_id)
	{
	
		$this->db->where("id",$user_id);
		$query =$this->db->get("users");
		if($query->num_rows()>0){
			$row =$query->row();
		return $query->result_array();;
		}else{
			return "";
		}
		
	}
	
	function getPostByKeyword($keyword)
	{
		$query =$this->db->query("select id from user_post where post_text like '%".$keyword."%' or 
		tags like '%".$keyword."%' order by post_date desc  ");
		if($query->num_rows()>0){
			
		return $query->result_array();;
		}else{
			return "";
		}
		
	}
	function get_username($user_id)
	{
		$this->db->select("users.username");
		$this->db->where("id",$user_id);
		$query =$this->db->get("users");
		if($query->num_rows()>0){
			$row =$query->row();
		return $row->username;
		}else{
			return "";
		}
		
	}
	
		function is_postlike($user_id,$post_id)
	{
		$this->db->where("user_id",$user_id);
		$this->db->where("post_id",$post_id);
		$query =$this->db->get("post_like");
		if($query->num_rows()>0){
			
		return "1";
		}else{
			return "0";
		}
		
	}

}
?>