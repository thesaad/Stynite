<style>
.txtEffext{ 
    border:1px solid #CE5454 !important; 
    box-shadow: 0 1px 3px 0 #CE5454!important; 
    /*position:relative; 
    left:0;*/ 
    -moz-animation:.7s 1 shake linear!important;
    -webkit-animation:0.7s 1 shake linear!important; 
    transition: all 250ms ease-in-out 0s!important; 
    color: #CE5454!important;
}

.back_event a {
    color: white!important;
}
.back_event{
    cursor: pointer!important;
    float: right!important;
   
}
</style>
	<?php
	$view=$search = $buylink_click= $buy = array();
	foreach ($product_view as $row) {
		$date_arr = explode('-', $row['p_date']);
		$view[]= array("Date.UTC(".$date_arr[0].",".($date_arr[1]-1).",".$date_arr[2].")",$row['countdata']);
	}
	
	foreach ($product_search as $row) {
		$date_arr = explode('-', $row['p_date']);
		$search[]= array("Date.UTC(".$date_arr[0].",".($date_arr[1]-1).",".$date_arr[2].")",$row['countdata']);
		 
	}
	foreach ($product_buylink_click as $row) {
		$date_arr = explode('-', $row['p_date']);
		$buylink_click[]= array("Date.UTC(".$date_arr[0].",".($date_arr[1]-1).",".$date_arr[2].")",$row['countdata']);
		 
		 
	}
	foreach ($product_buy as $row) {
		$date_arr = explode('-', $row['p_date']);
		$buy[]= array("Date.UTC(".$date_arr[0].",".($date_arr[1]-1).",".$date_arr[2].")",$row['countdata']);
		 
		 
	}
	
 
  $search_json = json_encode($search);
  $search_data = str_replace('"', "", $search_json);
  
  $view_json = json_encode($view);
  $view_data = str_replace('"', "", $view_json);
  
 $buylink_click_json = json_encode($buylink_click);
  $buylink_click_data = str_replace('"', "", $buylink_click_json);
  
  $buy_json = json_encode($buy);
  $buy_data = str_replace('"', "", $buy_json);
 
?>	
<script src="<?php echo base_url()."js/chart/";?>highcharts.js"></script>
 <script src="<?php echo base_url()."js/chart/";?>exporting.js"></script>


<div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('admin/affiliate_retailers'); ?>"><i class="iconfa-home"></i></a> </span><span class="separator"></span>  Product Statistics </h1></li>
            
        </ul>
  <?php



    $exist_image='new';

$exist_file='new';
?>      
        <div class="pageheader">
  
            <div class="pageicon"><span class="iconfa-align-left"></span></div>
            <div class="pagetitle">
                 <h1><?php echo (isset($product[0]->product_name))?$product[0]->product_name:"";?></h1>
                 <div style="line-height: 18px;"><b style="line-height: 18px;">Total view:&nbsp;<span style="color: #b84082;"><?php echo $product_view_count;?></span> </b>&nbsp;|&nbsp;
 <b style="line-height: 18px;">Total search:&nbsp;<span style="color: #b84082;"><?php echo $product_search_count;?></span></b></div>
 
                      
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
   <div class="row-fluid">
                    
                    <div class="span12">
                        
                        <h4 class="widgettitle">Product Statistics</h4>
                        <div class="widgetcontent">
           
            				<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            			 
                        </div><!--widgetcontent-->
                    </div>  
                    </div>  
                    
                     
            </div>
        </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


<script type="text/javascript">
 $(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Product Statistics'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e-%b-%Y',
                year: '%b'
            },
            title: {
                text: 'Date'
            }
        },
        yAxis: {
            title: {
                text: 'Times'
            },
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e-%b-%Y}: {point.y:2f} times'
        },

        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
            }
        },

        series: [{
            name: 'View',
            // Define the data points. All series have a dummy year
            // of 1970/71 in order to be compared on the same x axis. Note
            // that in JavaScript, months start at 0 for January, 1 for February etc.
            data:  <?php echo $view_data;?>
        },
        {
            name: 'Search',
            // Define the data points. All series have a dummy year
            // of 1970/71 in order to be compared on the same x axis. Note
            // that in JavaScript, months start at 0 for January, 1 for February etc.
            data:  <?php echo $search_data;?>
        } ]
    });
});
    
</script>
</html>