<?php
class Login_model extends CI_Model {
    
    public function __construct()
	{
		$this->load->database();
	}

	function validate_login()
    {
	   
        
    		$this->db->select('*');
    		$this->db->where("( email ='".$_POST['email']."' AND status = 'ACTIVE'   AND password ='".md5($_POST['password'])."')");
    		$query = $this->db->get('retailer');
        
     
    		if ($query->num_rows() > 0){
    		   return $query->row_array();
    		}else
    			return FALSE;
        
	}
    
   
    
    function update_login($id){
        $this->db->update('admin',array('lastlogin'=>date('Y-m-d H:i:s')),array('id'=>$id));
    }
}
?>