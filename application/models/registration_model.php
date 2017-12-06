<?php
class Registration_model extends CI_Model {
    
    public function __construct()
	{
		$this->load->database();
	}

    function save_registration()
    {
    	$gmdate =gmdate("Y-m-d H:i:s");
		$business_name = trim($_POST['business_name']);
        $this->db->where("( email ='" . $_POST['email'] . "'  or  business_name = '".$business_name."'  )");
        $query = $this->db->get("retailer");
        if ($query->num_rows() > 0) {
            return false;
        }
        $arr_field = array(
        "business_name" => $_POST['business_name'],
            "logo" => $_POST['business_photo_name'],
            "firstname" => $_POST['firstname'],
            "lastname" => $_POST['lastname'],
            "contact" => $_POST['contact'],
            "email" => $_POST['email'],
            "address" => $_POST['address'],
            "password" => md5($_POST['password']),
			"createdon" =>$gmdate,
			"updatedon"=>$gmdate			
             );
        $this->db->insert('retailer', $arr_field);
        return $this->db->insert_id();
    }
   
      function check_email()
    {
        $this->db->where("email", $_POST['email']);
        $query = $this->db->get("retailer");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
	      function check_businessname()
    {
      $businessname =  	trim($_POST['business_name']);
        $this->db->where("business_name",$businessname );
        $query = $this->db->get("retailer");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function update_login($id){
        $this->db->update('admin',array('lastlogin'=>date('Y-m-d H:i:s')),array('id'=>$id));
    }
}
?>