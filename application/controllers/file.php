<?php
class File extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        /*$this->load->helper(array(
            'url','constants_helper','number'));*/
        $this->load->helper(array('form', 'url','constant_helper'));    
        $this->load->library(array('session'));       
        $this -> load -> library(array('upload','S3_lib'));
        $this->load->library('image_lib');
		        $this->load->model('admin_model');
        $user = $this->session->userdata('logged_in');
        
    }
	
	  //amazon bucket fun start
  	function upload_image()
		{
			
			
			if (isset($_FILES['business_pic']))
			{

				if ($_FILES['business_pic']['error'] == 0)
				{
                       $fileupload=$_POST['file_upload_name'];
        $source=$_POST['source'];
        $orig_name=$_FILES[$fileupload]['name'];
					$file_name = uniqid();				
					$config['allowed_types'] = 'jpg|jpeg|gif|png';
					$config['file_name'] = $file_name;
					$s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
				    $source=$_FILES['business_pic']['tmp_name'];
					$ext = pathinfo($_FILES['business_pic']['name'], PATHINFO_EXTENSION);
					$file_name=$file_name.'.'.$ext;
					$aws_upload = $s3->putObjectFile($source, AWS_BUCKET_PRODUCT,
					$file_name,   S3::ACL_PUBLIC_READ);
					 
				 
					  if($aws_upload==1){
                       $response=array('upload_flag'=>1,"image_name"=>$file_name,"orig_name"=>$orig_name);
            echo json_encode($response);
            exit;
        }
        else{

			 $response['upload_flag']=0;
            echo json_encode($response);
            exit;
        }
						
					
				}
			}
		}	
	
		  //amazon bucket fun start
  	function upload_profile_image()
		{
			
			
			if (isset($_FILES['business_pic']))
			{

				if ($_FILES['business_pic']['error'] == 0)
				{
                       $fileupload=$_POST['file_upload_name'];
        $source=$_POST['source'];
        $orig_name=$_FILES[$fileupload]['name'];
					$file_name = uniqid();				
					$config['allowed_types'] = 'jpg|jpeg|gif|png';
					$config['file_name'] = $file_name;
					$s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
				    $source=$_FILES['business_pic']['tmp_name'];
					$ext = pathinfo($_FILES['business_pic']['name'], PATHINFO_EXTENSION);
					$file_name=$file_name.'.'.$ext;
					$aws_upload = $s3->putObjectFile($source, AWS_BUCKET_PROFILE,
					$file_name,   S3::ACL_PUBLIC_READ);
					 
				 
					  if($aws_upload==1){
                       $response=array('upload_flag'=>1,"image_name"=>$file_name,"orig_name"=>$orig_name);
            echo json_encode($response);
            exit;
        }
        else{

			 $response['upload_flag']=0;
            echo json_encode($response);
            exit;
        }
						
					
				}
			}
		}	
	
	
	  	function upload_csv()
		{
			//print_r($_FILES);exit;
		$fileupload=$_POST['file_upload_name'];
        $source=$_POST['source'];
        $orig_name=$_FILES[$fileupload]['name'];
        $FileName			= strtolower($_FILES[$fileupload]['name']); //uploaded file name
    	$FileTitle			= uniqid(); // file title
    	$ext			    = pathinfo($FileName, PATHINFO_EXTENSION);
    	$RandNumber   		= rand(0, 9999999999); //Random number to make each filename unique.
    	$NewFileName        = $FileTitle.'_'.$RandNumber.'.'.$ext;
        
        if($source=='importproducts'){
            $config['upload_path'] = './upload/excel/';
            $config['allowed_types'] = '*';
        }
 
 if($ext=='xls')
 {
 	$allow=true;
 }else if($ext=='xlsx')
 {
 	$allow=true;
 }else{
 	$allow=false;
 }
 
        if(!($allow)){
        	 $error_flag=1;
            $response=array('upload_flag'=>0,"message"=>"Please upload .xls or .xlsx format file!");
            echo json_encode($response);
            exit;
        }
        
        $this->load->library('upload', $config);
        
        $error_flag=0;
         
        $config['file_name']=$NewFileName;
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($fileupload))
		{
            $error_flag=1;
            $response=array('upload_flag'=>0,"message"=>$this->upload->display_errors());
            echo json_encode($response);
            exit;
            //echo "<pre>";
            //print_R($error);
		}
		else
		{
			$data=$this->upload->data();
            
		}
         
        if($error_flag){
            $response['upload_flag']=0;
            echo json_encode($response);
            exit;
        }
        else{
        	$this->loadproduct($NewFileName);
            $response=array('upload_flag'=>1,"image_name"=>$NewFileName,"orig_name"=>$orig_name);
            echo json_encode($response);
            exit;
        }
		}	
	// amazon bucket fun end
	
	
	
    public function upload_doc()
    {
        $fileupload=$_POST['file_upload_name'];
        $source=$_POST['source'];
        $orig_name=$_FILES[$fileupload]['name'];
        $FileName			= strtolower($_FILES[$fileupload]['name']); //uploaded file name
    	$FileTitle			= uniqid(); // file title
    	$ext			    = pathinfo($FileName, PATHINFO_EXTENSION);
    	$RandNumber   		= rand(0, 9999999999); //Random number to make each filename unique.
    	$NewFileName        = $FileTitle.'_'.$RandNumber.'.'.$ext;
        
        if($source=='catimage'){
            $config['upload_path'] = './upload/doc';
            $config['allowed_types'] = 'jpg|png|jpeg|gif';
        }
        
        
        $this->load->library('upload', $config);
        
        $error_flag=0;
         
        $config['file_name']=$NewFileName;
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($fileupload))
		{
            $error_flag=1;
            $response=array('upload_flag'=>0,"message"=>$this->upload->display_errors());
            echo json_encode($response);
            exit;
            //echo "<pre>";
            //print_R($error);
		}
		else
		{
			$data=$this->upload->data();
            /*$data = array('upload_data' => $this->upload->data());
            $image_file = $data['upload_data']['file_name'];
            
            $thumb['image_library'] = 'gd2';
            $thumb['source_image'] = './app_images/screen/'.$NewFileName;
            $thumb['new_image'] = './app_images/screen/thumbs/' . $NewFileName;
            $thumb['maintain_ratio'] = true;
            $thumb['create_thumb'] = false;         // for remove _thumb from file name
            $thumb['width'] = 150;
            $thumb['height'] = 150;

            $this->image_lib->initialize($thumb);

           
            
            $this->image_lib->resize();*/
            
            //$this->image_lib->initialize($thumb);
            
		}
         
        if($error_flag){
            $response['upload_flag']=0;
            echo json_encode($response);
            exit;
        }
        else{
            $response=array('upload_flag'=>1,"image_name"=>$NewFileName,"orig_name"=>$orig_name);
            echo json_encode($response);
            exit;
        }
    }
    

   function for_remove_screen()
    {
        $img_id= $_POST['img_id'];
        $this->app_manage_model->remove_screenShot($img_id);
    }
		function loadproduct($filename)
	{
		//echo "<pre>";
		$file = './upload/excel/'.$filename;
 
//load the excel library
$this->load->library('excel');
 
//read file from path
$objPHPExcel = PHPExcel_IOFactory::load($file);
 
  $shitcount = $objPHPExcel->getSheetCount(0);
  $itemadd=0;
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

///check file data format

		$header_product_flag = 0;
		//print_r($header);exit;
		foreach ($header as $header_row) {
			//echo $header_row['A'];
				if($header_row['A']=='Title'){$header_product_flag = 1;}
				else{$header_product_flag = 0;break;}
				if($header_row['B']=='Description'){$header_product_flag = 1; }
				else{$header_product_flag = 0;break;}
				if($header_row['C']=='Quantity'){$header_product_flag = 1; }
				else{$header_product_flag = 0;break;}
				if($header_row['D']=='Price'){$header_product_flag = 1; }
				else{$header_product_flag = 0;break;}
				if($header_row['E']=='Image-Link'){$header_product_flag = 1; }
				else{$header_product_flag = 0;break;}				
				
			}
			if($header_product_flag == 1){
		//echo $header_product_flag;
		//echo $header_acc_flag;
		//print_r("success");
		//exit;			
		
 
foreach ($arr_data as $prodata) {
	$productDetail = array("title"=>$prodata['A'],
	                       "description"=>$prodata['B'],
	                       "quantity"=>$prodata['C'],
	                       "price"=>$prodata['D'],
	                       "imagelink"=> $prodata['E']
	);
	
$productadd	= $this->admin_model->save_feed_product($productDetail);
if($productadd){
	$itemadd++;
}
}


}else{
	 $error_flag=1;
            $response=array('upload_flag'=>0,"message"=>"Trying to enter wrong format data, Please visit sample upload file.");
            echo json_encode($response);
            exit;
}

//end of check format



 }
      //unlink($file);
    
			$response=array('upload_flag'=>1,"message"=>$itemadd." Product added.");
            echo json_encode($response);
            exit;
      
	}
	

}
?>