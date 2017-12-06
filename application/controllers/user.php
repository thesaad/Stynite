<?php
class User extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','constant_helper','function_helper'));
		$this->load->model('admin_model');
		$this->load->model('users_photos_model');
		$this->load->model('users_panel_model');
		$this->load->library('session');
		$this->clear_cache();
		$user = $this->session->userdata('logged_in');
		if (!$user) {
			redirect('login/index');
		}
	}

    function index()
    {
     
        $data['active_page'] = 'styuser';
	 
        $this->load->view('templates/header', $data);
        $this->load->view('user/users',$data);
    }
	
 function get_users_dt()
	{
		 
		$user = $this->users_panel_model->get_users_dt();
		echo json_encode($user); 
	}
	  function products()
    {
        $user = $this->users_panel_model->get_a_user($_GET['id']);
        $data['active_page'] = 'styuser';
	  $data['user'] = $user;
        $this->load->view('templates/header', $data);
        $this->load->view('user/user_products',$data);
    }
		function get_userproducts_dt()
	{
		 
		$user = $this->users_panel_model->get_userproducts_dt();
		echo json_encode($user); 
	}
	
	function recommendation_action()
	{
		$res = $this->users_panel_model->recommendation_action();
		echo $res;
		
	}
	function affilate_recommendation_action()
	{
		$res = $this->users_panel_model->affilate_recommendation_action();
		echo $res;
		
	}
	function test_recommendation()
	{
		$res = $this->users_panel_model->get_stynite_recommendation(6);
		echo "<pre>";
		 print_r($res);
	}
	
	function  user_recommendation_keywords()
	{
		$user_id = $_POST['user_id'];
		$res = $this->users_panel_model->user_recommendation_keywords($user_id);
		for ($i=0; $i < 3; $i++) {
			$id=0;
			 
			$admincheck = "";
			$defaulecheck = "";
			if(isset($res[$i]['keyword'])){
				$id = $res[$i]['id'];
				$datarecord = true;
			}else{
				$datarecord = false;
			}
			?>
			<tr>
			<td><input type="text" id="keyword_<?php echo $i;?>" value="<?php echo ($datarecord)?$res[$i]['keyword']:""?>" /></td>
        	 <?php
        	 if(isset($datarecord)){
        	 	if($res[$i]['action_by']=='ADMIN'){
        	 		$admincheck = "checked";
					
        	 	}else{
        	 		$defaulecheck = "checked";
        	 	}
				
        	 }else{
        	 	
        	 }
        	 ?>
        	 
        	<td><input type="radio" value="ADMIN"  name="action_by<?php echo $i;?>" id="admin_<?php echo $i;?>"    <?php echo $admincheck;?> /></td>
        	<td><input type="radio" value="DEFAULT" name="action_by<?php echo $i;?>"  id="default_<?php echo $i;?>"    <?php echo $defaulecheck;?> /></td>
        	<td><input id="r_submit<?php echo $i;?>" type="button" class="btn"  onclick="submit_action('<?php echo $id;?>','<?php echo $user_id;?>','<?php echo $i;?>')" value="Go" /></td>
			</tr>
			<?php
		}
		 
	}
    function submit_recommendation_keys()
	{
		 
		$res = $this->users_panel_model->submit_recommendation_keys();
		echo $res;
	}

	function affilateproducts()
    {
        $user = $this->users_panel_model->get_a_user($_GET['id']);
        $data['active_page'] = 'styuser';
	  $data['user'] = $user;
        $this->load->view('templates/header', $data);
        $this->load->view('user/useraffialte_products',$data);
    }
		function get_useraffilateproducts_dt()
	{
		 
		$user = $this->users_panel_model->get_useraffilateproducts_dt();
		echo json_encode($user); 
	}
	function postsactivity()
	{
		 $user = $this->users_panel_model->get_a_user($_GET['id']);
        $data['active_page'] = 'styuser';
	  $data['user'] = $user;
        $this->load->view('templates/header', $data);
        $this->load->view('user/userposts',$data);
	}
		function get_postsactivity_dt()
	{
		 
		$user = $this->users_panel_model->get_postsactivity_dt();
		echo json_encode($user); 
	}
	
	function editprofile()
	{
		     @$product = "";
		$product_keyword_ids = array();
        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
            $userdata = $this->users_panel_model->get_a_user($id);
              
        }
    
  $data['userdata'] = $userdata;
 
           $data['active_page'] = 'styuser';
        $this->load->view('templates/header', $data);
        $this->load->view('user/edit_user');
	}
	function save_user()
	{
		 $res = $this->users_panel_model->save_user();
        if ($res) {
            redirect('user/index');
        } else {
            // set the message that show the data is not save;
            redirect('user/index');
        }
	}

function delete_user()
	{
		$res = $this->users_panel_model->delete_user();
		echo $res;
	}
	
	
    function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
}
?>