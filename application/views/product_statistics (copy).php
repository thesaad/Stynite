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
		$view[]= array($row['countdata']);
	}
	
	foreach ($product_search as $row) {
		$search[]= array($row['countdata']);
	}
	foreach ($product_buylink_click as $row) {
		$buylink_click[]= array($row['countdata']);
	}
	foreach ($product_buy as $row) {
		$buy[]= array($row['countdata']);
	}
	
	foreach ($product_date as $row) {
		$prodate[]= $row['p_date'];
	}

 
?>	
<!--<script type="text/javascript" src="<?php echo base_url();?>js/charts.js"></script>-->
 <script type="text/javascript" src="<?php echo base_url(); ?>js/useChart.js"></script>
	<script>
		var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var lineChartData = {
			labels : <?php echo json_encode($prodate);?>,	
			datasets : [
				
				{
					label: "My Second dataset",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : <?php echo json_encode($view);?>
				},
				{
					label: "My Second dataset",
					fillColor : "rgba(222,187,205,0.2)",
					strokeColor : "rgba(333,187,205,1)",
					pointColor : "rgba(333,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : <?php echo json_encode($search);?>
				},
				{
					label: "My Second dataset",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : <?php echo json_encode($buylink_click);?>
				},
				{
					label: "My Second dataset",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : <?php echo json_encode($buy);?>
				}
			]

		}

	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext('2d');
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true
		});
	}


	</script>
<script type="text/javascript" src="<?php echo base_url();?>js/chosen.jquery.min.js"></script>
<div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('admin/index'); ?>"><i class="iconfa-home"></i></a> </span><span class="separator"></span>  Product Statistics </h1></li>
            
        </ul>
  <?php



    $exist_image='new';

$exist_file='new';
?>      
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-align-left"></span></div>
            <div class="pagetitle">
                 <h1><?php echo (isset($product[0]->title))?$product[0]->title:"";?></h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
   <div class="row-fluid">
                    
                    <div class="span12">
                        
                        <h4 class="widgettitle">Simple Chart</h4>
                        <div class="widgetcontent">
           
            				<canvas id="canvas" ></canvas>
            			 
                        </div><!--widgetcontent-->
                    </div>  
                    </div>  
                    
                    <?php
                    echo "<pre>";
                    print_r($search);
					print_r($prodate);
					echo json_encode($prodate);
                     
                    ?>  
            </div>
        </div>
</div>
</body>
<script type="text/javascript" src="<?=base_url();?>js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/ui.spinner.min.js"></script>

</html>