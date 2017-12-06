<?php
class Shopbrandcron_model extends CI_Model {

	public function __construct() {
		$this -> load -> database();
	}

	function save_brand($brand) {
		$created_date = get_gmt_time();
		if (count($brand) > 0) {

			//print_r($leagueteams);
			

				foreach ($brand['results'] as $val) {
				$title = 	$val['brand/_title'];
					$this -> db -> where("name", $title);
					$checkteam = $this -> db -> get("shop_brand");
					$arr_field = array('name' => $title, 'updatedon' => $created_date);
					if ($checkteam -> num_rows() > 0) {

						$this -> db -> where("name", $val['team']['Name']);
						$this -> db -> update('shop_brand', $arr_field);

					} else {
						$this -> db -> insert('shop_brand', $arr_field);
						$team_id = $this -> db -> insert_id();
					}

				}

			}

	
		return false;
	}
	
	
 

	function brandimageCron() {
		//$this -> db -> limit(1);
		$this->db->where("imgset","0");
		$query = $this -> db -> get("shop_brand");
		$teamresult = $query -> result_array();
		foreach ($teamresult as $row) {
			$imagearr = get_bing_image($row['name'] . " logo");
			//echo "Image:" . $imagearr['image_thumb'] . "<br>";
           //filename
           $unique = uniqid();
		$filename = "./upload/logo/" . $unique . ".jpg";
           $teamimg = $unique . ".jpg";;
           //end
		   $teamimgurl = $this -> upload_image($imagearr['image_thumb'],$filename);
			//echo "<pre>";
			//print_r($imagearr['image_thumb']);
			 
			$arr_field = array('logo' => $teamimg, 'imgset' => 1);
			$this -> db -> where("id", $row['id']);
			$this -> db -> update('shop_brand', $arr_field);
			//exit;
		}

	}

	function upload_image($file,$filename) {
		
		/////
		  $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $file);
 
  // User agent
  curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
 
  // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_HEADER, 0);
 
  // Should cURL return or print out the data? (true = retu	rn, false = print)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

  // Timeout in seconds
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
 
  // Download the given URL, and return output
  $filedata = curl_exec($ch);
		
		////

		 
		

		file_put_contents($filename, $filedata);

	}

}
?>