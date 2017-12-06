<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }
	

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function help()
	{
		$this->load->view('help');
	}
	public function contactus()
	{
		$this->load->view('contactus');
	}
	public function about()
	{
		$this->load->view('about');
	}
	public function termsCondition()
	{
		$this->load->view('terms');
	}
	public function privacyPolicy()
	{
		$this->load->view('privacypolicy');
	}
	function submit_contact()
	{
		$url = 'http://www.stynite.com/contact-us/';
		
			$data = array('your-email' => $_REQUEST['your-email'], 'your-message' => $_REQUEST['your-message'],
			'your-name' => $_REQUEST['your-name'], 'your-subject' => $_REQUEST['your-subject']);

// You can POST a file by prefixing with an @ (for <input type="file"> fields)

 
$handle = curl_init($url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
$res= curl_exec($handle);
echo $res;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */