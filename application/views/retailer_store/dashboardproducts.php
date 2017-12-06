<style>
html {
  font-family: "roboto", helvetica;
  position: relative;
  height: 100%;
  font-size: 100%;
  line-height: 1.5;
  color: #444;
}

h2 {
  margin: 1.75em 0 0;
  font-size: 5vw;
}

h3 { font-size: 1.3em; }

.v-center {
  height: 100vh;
  width: 100%;
  display: table;
  position: relative;
  text-align: center;
}

.v-center > div {
  display: table-cell;
  vertical-align: middle;
  position: relative;
  top: -10%;
}



.modal-box {
  display: none;
/*  position: absolute;*/
  position: fixed;
  display: none;
  top:0px;
  left:0px;
  z-index: 1000;
  width: 60%;
  height:80%;
  background: white;
  border-bottom: 1px solid #aaa;
  border-radius: 4px;
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-clip: padding-box;
}
@media (min-width: 32em) {

.modal-box { width: 50%; }
}

.modal-box header,
.modal-box .modal-header {
  padding: 1.25em 1.5em;
  border-bottom: 1px solid #ddd;
}

.modal-box header h3,
.modal-box header h4,
.modal-box .modal-header h3,
.modal-box .modal-header h4 { margin: 0; }

.modal-box .modal-body { padding: 2em 1.5em; }

.modal-box footer,
.modal-box .modal-footer {
  padding: 1em;
  border-top: 1px solid #ddd;
  background: rgba(0, 0, 0, 0.02);
  text-align: right;
}

.modal-overlay {
  opacity: 0;
  filter: alpha(opacity=0);
  /*position: absolute;*/
    position: fixed;
  top: 0;
  left: 0;
  z-index: 900;
  width: 100%;

  background: rgba(0, 0, 0, 0.3) !important;
}

a.close {
  line-height: 1;
  font-size: 1.5em;
  position: absolute;
  top: 5%;
  right: 2%;
  text-decoration: none;
  color: #bbb;
}

a.close:hover {
  color: #222;
  -webkit-transition: color 1s ease;
  -moz-transition: color 1s ease;
  transition: color 1s ease;
}
</style>

<div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="<?=site_url('admin/index');?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Statistic</li>
         
        </ul>
            
               
        <div class="pageheader">
           
       <div class="pageicon"><span class="iconfa-laptop"></span></div>
            <div class="pagetitle">
                   <h1>Retailer: <span style="color: red"><?php echo $retailer[0]['firstname']." ".$retailer[0]['lastname'];?> </span></h1>
           
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
                    <?php $this->view("product_notice");?>
                    <div class="row-fluid">
                     <div class="span6">
                    <h4 class="widgettitle">Most viewed
         <!--           	
<a style="float: right; margin-top: -8px;" onclick="delete_product()" title="Delete Selected Products" class="btn btn-danger btn-rounded"><i class="iconfa-trash"></i>&nbsp;Delete Selected</a>
&nbsp;&nbsp;
                    	<a href="<?=site_url('admin/manage_product');?>" style="float: right; margin-top: -8px;"  title="Add Products" class="btn btn-success btn-rounded"><i class="iconfa-plus"></i>&nbsp;Add&nbsp;&nbsp;</a>&nbsp;&nbsp;
-->
                    </h4>
            <table id="employee-grid"  class="table table-bordered table-infinite" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
					<thead>
						<tr>
							<th  >Sr. no.</th>
							
							
							<th >Title</th>
							 
						 
							
							<th >Count</th>
							 
							<th>Action</th>
							
						</tr>
					</thead>
					<tbody>
						<?php
						$i=1;
						foreach ($products as  $value) {
						$id	=$value['product_id'];
						
						?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $value['title'];?></td>
							<td><?php echo $value['countdata'];?></td>
							 <td>
							  
	<a   href="<?php echo site_url("retailer_store/manage_product")."?id=".$id."&retailer_id=".$_GET['id'];?>" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  onclick="product_id('<?php echo $id;?>')" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" data-id="<?php echo $id;?>"   title="Send Notification"><i class="icon-bell"></i></a>
               &nbsp;&nbsp;|&nbsp;&nbsp;<a   href="<?php echo site_url('admin/product_statistics').'?id='.$id;?>" title="Statistics"><i class="icon-signal"></i></a>    
							 	
							 </td>
						</tr>
						<?php
						$i++;
						}
						?>
					</tbody>
			</table> 
			</div>
			
			
			         <div class="span6">
                    <h4 class="widgettitle">Most Searched
         <!--           	
<a style="float: right; margin-top: -8px;" onclick="delete_product()" title="Delete Selected Products" class="btn btn-danger btn-rounded"><i class="iconfa-trash"></i>&nbsp;Delete Selected</a>
&nbsp;&nbsp;
                    	<a href="<?=site_url('admin/manage_product');?>" style="float: right; margin-top: -8px;"  title="Add Products" class="btn btn-success btn-rounded"><i class="iconfa-plus"></i>&nbsp;Add&nbsp;&nbsp;</a>&nbsp;&nbsp;
-->
                    </h4>
            <table id="search-grid"  class="table table-bordered table-infinite" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
					<thead>
						<tr>
							<th  >Sr. no.</th>
							
							
							<th >Title</th>
							 
						 
							
							<th>Count</th>
							 
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i=1;
						foreach ($searched as  $value) {
							$id	=$value['product_id'];
						
						?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $value['title'];?></td>
							<td><?php echo $value['countdata'];?></td>
							 	 <td>
							  
	<a   href="<?php echo site_url("retailer_store/manage_product")."?id=".$id."&retailer_id=".$_GET['id'];?>" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  onclick="product_id('<?php echo $id;?>')" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" data-id="<?php echo $id;?>"   title="Send Notification"><i class="icon-bell"></i></a>
               &nbsp;&nbsp;|&nbsp;&nbsp;<a   href="<?php echo site_url('admin/product_statistics').'?id='.$id;?>" title="Statistics"><i class="icon-signal"></i></a>     
	
	
							 	
							 </td>
						</tr>
						<?php
						$i++;
						}
						?>
					</tbody>
			</table> 
			</div>
			</div>
			  <div class="row-fluid">
			
				         <div class="span6">
                    <h4 class="widgettitle">Most Buylink Clicked
         <!--           	
<a style="float: right; margin-top: -8px;" onclick="delete_product()" title="Delete Selected Products" class="btn btn-danger btn-rounded"><i class="iconfa-trash"></i>&nbsp;Delete Selected</a>
&nbsp;&nbsp;
                    	<a href="<?=site_url('admin/manage_product');?>" style="float: right; margin-top: -8px;"  title="Add Products" class="btn btn-success btn-rounded"><i class="iconfa-plus"></i>&nbsp;Add&nbsp;&nbsp;</a>&nbsp;&nbsp;
-->
                    </h4>
            <table id="search-grid"  class="table table-bordered table-infinite" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
					<thead>
						<tr>
							<th  >Sr. no.</th>
							
							
							<th >Title</th>
							 
						 
							
							<th >Count</th>
							 
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i=1;
						foreach ($clickbuy as  $value) {
							$id	=$value['product_id'];
						
						?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $value['title'];?></td>
							<td><?php echo $value['countdata'];?></td>
							 	 <td>
							  
	<a   href="<?php echo site_url("retailer_store/manage_product")."?id=".$id."&retailer_id=".$_GET['id'];?>" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  onclick="product_id('<?php echo $id;?>')" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" data-id="<?php echo $id;?>"   title="Send Notification"><i class="icon-bell"></i></a>
               &nbsp;&nbsp;|&nbsp;&nbsp;<a   href="<?php echo site_url('admin/product_statistics').'?id='.$id;?>" title="Statistics"><i class="icon-signal"></i></a>    
							 </td>
						</tr>
						<?php
						$i++;
						}
						?>
					</tbody>
			</table> 
			</div>
			
				         <div class="span6">
                    <h4 class="widgettitle">Most Buy
         <!--           	
<a style="float: right; margin-top: -8px;" onclick="delete_product()" title="Delete Selected Products" class="btn btn-danger btn-rounded"><i class="iconfa-trash"></i>&nbsp;Delete Selected</a>
&nbsp;&nbsp;
                    	<a href="<?=site_url('admin/manage_product');?>" style="float: right; margin-top: -8px;"  title="Add Products" class="btn btn-success btn-rounded"><i class="iconfa-plus"></i>&nbsp;Add&nbsp;&nbsp;</a>&nbsp;&nbsp;
-->
                    </h4>
            <table id="search-grid"  class="table table-bordered table-infinite" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
					<thead>
						<tr>
							<th  >Sr. no.</th>
							
							
							<th >Title</th>
							 
						 
							
							<th >Count</th>
							 
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i=1;
						foreach ($buy as  $value) {
							
						$id	=$value['product_id'];
						?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $value['title'];?></td>
							<td><?php echo $value['countdata'];?></td>
							 	 <td>
							  
	<a   href="<?php echo site_url("retailer_store/manage_product")."?id=".$id."&retailer_id=".$_GET['id'];?>" title="Edit"><i class="icon-edit"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a  onclick="product_id('<?php echo $id;?>')" data-toggle="modal" data-target="#myModal" style="cursor:pointer;" data-id="<?php echo $id;?>"   title="Send Notification"><i class="icon-bell"></i></a>
               &nbsp;&nbsp;|&nbsp;&nbsp;<a   href="<?php echo site_url('admin/product_statistics').'?id='.$id;?>" title="Statistics"><i class="icon-signal"></i></a>    
	
	
							 	
							 </td>
						</tr>
						<?php
						$i++;
						}
						?>
					</tbody>
			</table> 
			</div>
			 </div>
            <form  id="deleteproduct"  method="POST"  >
	</form>       
            </div>
        </div>
</div>
</body>
<link rel="stylesheet" href="<?=base_url();?>css/popover.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?=base_url();?>js/jquery.popover-1.1.2.js"></script>

<script type="text/javascript" src="<?=base_url();?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
var $ = jQuery.noConflict();
function initPOPOVER()
{
    var rowcount = $("#txt_row_count").val();
  
     for (var i=0;i<=rowcount;i++)
        { 
            var linkID = 'POP_'+i;
            var popoverID = 'pop-up_'+i;
            $('#'+linkID).popover({header: "Category Icon", html:true,content: '#'+popoverID+' > .image_pop'});
            //alert($('#'+linkID).popover({header: 'Trail Photo', html:true,content: '#'+popoverID+' > .image_pop'}););
        }  
}
    $(document).ready(function(){
        initPOPOVER();
     /*   $('#employee-grid').dataTable( {
            "sPaginationType": "full_numbers",
            "aaSortingFixed": [[0,'asc']],
            "aLengthMenu": [[10, 40, 60, -1], [10, 40, 60, "All"]],
            "iDisplayLength": 10,
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
         $('#search-grid').dataTable( {
            "sPaginationType": "full_numbers",
            "aaSortingFixed": [[0,'asc']],
            "aLengthMenu": [[10, 40, 60, -1], [10, 40, 60, "All"]],
            "iDisplayLength": 10,
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });*/
        
    });

    function delete_product()
{
  if(confirm("Are you sure want to delete ? "))
  {
       var formData = new FormData($('#deleteproduct')[0]);
                            $.ajax({
                                
              		    type: "post",
             			url : "<?php echo site_url("admin/delete_products");?>",
               			data: formData,
                        contentType: false,
                        processData: false,
               			success:	function(data){
                     			if(data==1){
                                   alert("Products deleted successfully.");
                                   window.location.reload();
                				}else{
                                   alert("Try again!! Error occur while delete."); 
                                    }
                			}
                            
                       });
 
    
  }else{
    
  }
   
}
   
</script>

<!------------------------------pop up script-------------------------------------------------------------------->

<script>
$(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('a[data-modal-id]').click(function(e) {
		e.preventDefault();
    $("body").append(appendthis);
    $(".modal-overlay").fadeTo(500, 0.9);
    //$(".js-modalbox").fadeIn(500);
		var modalBox = $(this).attr('data-modal-id');
		$('#'+modalBox).fadeIn($(this).data());
        $("html").fadeIn();
	});  
  
  
$(".js-modal-close, .modal-overlay").click(function() {
    $(".modal-box, .modal-overlay").fadeOut(500, function() {
        $(".modal-overlay").remove();
    });
 
});
 
$(window).resize(function() {
    $(".modal-box").css({
        top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
        left: ($(window).width() - $(".modal-box").outerWidth()) / 2
    });
});
 
$(window).resize();
 
});
</script>

		<!--<link rel="stylesheet" type="text/css" href="<?=base_url();?>server_datatable/css/jquery.dataTables.css">-->
		<script type="text/javascript" language="javascript" src="<?=base_url();?>server_datatable/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="<?=base_url();?>server_datatable/js/jquery.dataTables.js"></script>
		
		<script type="text/javascript" language="javascript" >
			 
			
			function make_pic_of_day(pic_id)
			{
				
				  if(confirm("Are you sure want to make it pic of the day ? "))
				  {
				     $.ajax({
				        url         :'<?=site_url('admin/pic_of_day');?>',
				        data        :{'pic_id':pic_id},
				        type        :'POST',
				        success     :function(response){
				            if(response=='1')
				            {
				            	alert("Pic of the day updated");
				            	window.setTimeout(function(){location.reload()},100);
				                //$.bootstrapGrowl("Your record is deleted", { type: 'success' });
				                $("#image_"+id).closest('tr').fadeOut('slow');
				              //  window.setTimeout(function(){location.reload()},3000);
				            }
				            else
				            {
				            	alert("Error occur try again");
				            	window.setTimeout(function(){location.reload()},100);
				               //$.bootstrapGrowl("Sorry, Your record is not deleted", { type: 'error' });
				                $('#image_'+id).css('display','none');
				            }
				            
				        }
				    })
				    
				  }else{
				    
				  }
			}
		</script>



</html>