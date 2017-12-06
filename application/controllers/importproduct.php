<?php
 class Importproduct extends CI_Controller
{

     public function __construct()
     {
         parent::__construct();
         $this->load->helper(array('form', 'url','constant_helper','function_helper'));
         $this->load->library('excel');
         $this->load->model('admin_model');
         $this->load->model('users_photos_model');
         $this->load->library('session');
         $user = $this->session->userdata('logged_in');
         if (!$user) {
             redirect('login/index');
         }
     }
	function loadproduct()
	{
		//echo "<pre>";
		$file = './upload/excel/584e73f41e1b4_198932825.xls';
 
//load the excel library
$this->load->library('excel');
 
//read file from path
$objPHPExcel = PHPExcel_IOFactory::load($file);
 
  $shitcount = $objPHPExcel->getSheetCount(0);
 for ($i=0; $i < $shitcount; $i++) { 
     

 $objPHPExcel->setActiveSheetIndex($i);
//get only the Cell Collection
$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
 
//extract to a PHP readable array format
foreach ($cell_collection as $cell) {
    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
 
    //header will/should be in row 1 only. of course this can be modified to suit your need.
    if ($row == 1) {
        $header[$row][$column] = $data_value;
    } else {
        $arr_data[$row][$column] = $data_value;
    }
}
 
//send the data in an array format
$data['header'] = $header;
$data['values'] = $arr_data;
//print_r($data); 
 }
      //unlink($file);
	}
	
}

?>
