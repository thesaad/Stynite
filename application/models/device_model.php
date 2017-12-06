<?php
class Device_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }
    function register($input = "")
    {
    	
        $datatime = get_gmt_time();
		$arr_field = array(
            "device_model" => $input['device_model'],
            "device_platfrom" => $input['device_platfrom'],
            "uuid" => $input['uuid'],
            "device_token" => $input['device_token'],
            "device_type" => $input['device_type'],
            "is_debug" => $input['is_debug'],
            "createdon" => $datatime
      );
        $this->db->where('uuid', $input['uuid']);		
        $query = $this->db->get('devices');
		if($query->num_rows()>0){
		
		
		  $this->db->where('uuid', $input['uuid']);	
            $query = $this->db->update('devices', $arr_field);
			return $query;
		}else{
			 
      
            $this->db->insert('devices', $arr_field);
            // echo $this->db->last_query();exit;
            $query = $this->db->insert_id();
return $query;
		}
        

    }
	function userDevice($input = "")
	{
		$this->db->where('uuid', $input['uuid']);		
        $query = $this->db->get('devices');
		if($query->num_rows()>0){
			  $datatime = get_gmt_time();
		
			
			$row = $query->row();
			$device_id = $row->id;
			$arr_field = array(
            "user_id" => $input['user_id'],
            "device_id" => $device_id,
            "updatedon" => $datatime
      );
			 $this->db->where('user_id', $input['user_id']);		
        $queryUsr = $this->db->get('user_device');
		if($queryUsr->num_rows()>0){
			 $this->db->where('user_id', $input['user_id']);	
            $query = $this->db->update('user_device', $arr_field);
			return $query;
		}else{
			 $this->db->insert('user_device', $arr_field);
            // echo $this->db->last_query();exit;
            $query = $this->db->insert_id();
return $query;
		}
			
	    }else{
			
		}
	}
	
}
?>