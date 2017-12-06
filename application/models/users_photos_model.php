<?php
class Users_photos_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }
    
    
        function user_photo_upload($input = "")
    {
        
		 $photo_date = gmdate("Y-m-d H:i:s");
		if(isset($input['caption']))
		{
			$caption=$input['caption'];
		}else{
			$caption="";
		}
	 	 $arr_field = array(
            "user_id" => $input['user_id'],            
            "image" => $input['image_name'],
             "caption" => $caption,            
            "photo_date" => $photo_date
           );
	
       
        
            $this->db->insert('user_photos', $arr_field);
            //   echo $this->db->last_query();exit;
            $id = $this->db->insert_id();

      

        if ($id) {
            return $id;
        }
        return false;
    }
	function get_photos($input,$user_id='')
	{
	
		$page = $input['page'];

	$limit = 10;
	if ($page == 1) {
		$startindex = 0;

	} else {
		$startindex = ($page - 1) * $limit;
	}
	
		if($input['type']=='USER')
		$this->db->where('user_id',$user_id);
		$this->db->order_by("photo_date", "desc"); 
		$query = $this->db->get('user_photos',$limit,$startindex);
        return $query->result_array();
	}
function get_win_photos()
	{
	
        $photo_date = gmdate("Y-m-d");
		//$photo_date_past=date('Y-m-d', strtotime('-1 day', strtotime($photo_date)));
		
		//$this->db->where("photo_date", $photo_date_past);
		//$this->db->order_by("like_count","desc"); 
		//$query = $this->db->get('user_photos',1);
		//echo $this->db->last_query();exit;
		//$query =$this->db->query("select user_photos.*,users.name from user_photos left join users on users.id=user_photos.user_id where date(photo_date)='$photo_date_past'  order by like_count desc");
     $query =$this->db->query("SELECT user_photos.*,users.name,users.email,photos_like.photo_id,count( photo_id )
      as like_count FROM `photos_like` left join 
     user_photos on user_photos.id = photos_like.photo_id left join users on
      users.id=user_photos.user_id WHERE date(photos_like.createdon)='$photo_date' 
      and date(user_photos.photo_date)='$photo_date'
	   group by photo_id order by like_count desc limit 1 ");
     //echo $this->db->last_query();exit;
        return $query->result_array();
	}
	    function user_photolike($input = "")
    {
    	 $photo_date = gmdate("Y-m-d H:i:s");
    	$this->db->where('user_id',$input['user_id']);
		$this->db->where('photo_id',$input['photo_id']);
		$query = $this->db->get('photos_like');
		if($query->num_rows()>0)
		{
			$this->db->where('user_id',$input['user_id']);
		    $this->db->where('photo_id',$input['photo_id']);
            $result = $this->db->delete('photos_like');
			$count_qry=$this->db->query("update user_photos set like_count=(like_count-1) where id='".$input['photo_id']."' ");
			return 0;
		}else{
				 $arr_field = array(
            "user_id" => $input['user_id'],
            "photo_id" => $input['photo_id'],
            "createdon" =>$photo_date            
           );       
            $this->db->insert('photos_like', $arr_field);
            //   echo $this->db->last_query();exit;
            $id = $this->db->insert_id();
			$count_qry=$this->db->query("update user_photos set like_count=(like_count+1) where id='".$input['photo_id']."' ");
			
			return 1;
		}		
    	
    }
	function check_userlike($userid,$photoid)
	{
		

		$this->db->where('user_id',$userid);
		$this->db->where('photo_id',$photoid);
		$query = $this->db->get('photos_like');
		if($query->num_rows()>0)
		{
			return 1;
		}else{
			return 0;
		}
       
	}
function last_photoupload($userid)
{
	
	$this->db->where('user_id',$userid);
	$this->db->order_by('photo_date','desc');
    $query = $this->db->get('user_photos');
    if($query->num_rows()>0)
    {
    	$row=$query->row();
    	return $row->photo_date;
    }else{
    	return '0000-00-00 00:00:00';
    }
}
function photo_setting()
{
	$this->db->where('id',1);
	
    $query = $this->db->get('photo_setting');
    if($query->num_rows()>0)
    {
    	$row=$query->row();
    	return $row->photo_time;
    }else{
    	return false;
    }
}

}
?>