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
            <li><a href="<?=site_url('admin');?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Users</li>
            <li class="right">
                    <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
                    <ul class="dropdown-menu pull-right skin-color">
                        <li><a href="<?php echo base_url()?>css/style.default">Default</a></li>
                        <li><a href="<?php echo base_url()?>css/style.navyblue">Navy Blue</a></li>
                        <li><a href="<?php echo base_url()?>css/style.palegreen">Pale Green</a></li>
                        <li><a href="<?php echo base_url()?>css/style.red">Red</a></li>
                        <li><a href="<?php echo base_url()?>css/style.green">Green</a></li>
                        <li><a href="<?php echo base_url()?>css/style.brown">Brown</a></li>
                    </ul>
            </li>
        </ul>
        
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-list"></span></div>
            <div class="pagetitle">
                <h1>Users</h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
                    
                    <h4 class="widgettitle">Users List
                    </h4>
             <table id="employee-grid"  class="table table-bordered table-infinite" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
					<thead>
						<tr>
							<th>Sr. no.</th>
							<th style="cursor: pointer">Name</th>
							
							<th style="cursor: pointer">Email</th>
							
							<th >Photos</th>
							
							
							<th>Status</th>
						</tr>
					</thead>
			</table>
                    
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
        $('#rcp').dataTable( {
            "sPaginationType": "full_numbers",
            "aaSortingFixed": [[0,'asc']],
            "aLengthMenu": [[10, 40, 60, -1], [10, 40, 60, "All"]],
            "iDisplayLength": 10,
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
        
    });

function delete_group(id)
{
  if(confirm("Are you sure want to delete ? "))
  {
     $.ajax({
        url         :'<?=site_url('admin/remove_group');?>',
        data        :{'id':id},
        type        :'POST',
        beforeSend  :function(){
            $('#image_'+id).css('display','inline');
        },
        success     :function(response){
            if(response=='1')
            {
                //$.bootstrapGrowl("Your record is deleted", { type: 'success' });
                $("#image_"+id).closest('tr').fadeOut('slow');
                window.setTimeout(function(){location.reload()},3000);
            }
            else
            {
               //$.bootstrapGrowl("Sorry, Your record is not deleted", { type: 'error' });
                $('#image_'+id).css('display','none');
            }
            
        }
    })
    
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
			$(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax":{
						url :'<?=site_url('admin/get_all_user_dt');?>', // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".employee-grid-error").html("");
							$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#employee-grid_processing").css("display","none");
							
						}
					}
				} );
			} );
			
			function delete_user_account(id,url='')
{
	if(confirm("Are you sure want to delete this user account and it's data ? "))
  {
     $.ajax({
        url         :'<?=site_url('admin/remove_user_and_data');?>',
        data        :{'id':id},
        type        :'POST',
        beforeSend  :function(){
            $('#image_'+id).css('display','inline');
        },
        success     :function(response){
            if(response=='1')
            {
                //$.bootstrapGrowl("Your record is deleted", { type: 'success' });
                $("#image_"+id).closest('tr').fadeOut('slow');
                window.setTimeout(function(){location.reload()},3000);
            }
            else
            {
               //$.bootstrapGrowl("Sorry, Your record is not deleted", { type: 'error' });
                $('#image_'+id).css('display','none');
            }
            
        }
    })
    
  }else{
    
  }
}
		</script>

<script>
	//this is for Staus (active, inactive) change everywhere
function toggleStatus(url_address,spanid,loadingdiv)
{   
  
    var spanvalue = $('#'+spanid).text();
    $('#'+loadingdiv).css('display','inline');
    var action='status';
    var status = '1';
    if(spanvalue=='Activated' || spanvalue=='Active' || spanvalue=='Close' || spanvalue=='Approve')
    {  
       status = '0';
    }
  

    $.ajax({type:'GET',url: url_address,
   success: function(response) {
        
       statusfeedback(response,spanid,loadingdiv);
     }});
}





function statusfeedback(retstatus,spanid,loadingdiv) 
{
    retstatus=retstatus.trim();
    //alert(retstatus);
    if(retstatus=="-1")
    {
        return;
    }
    else if(retstatus=='0')
    {
        $('#'+spanid).removeClass('label-success').addClass('label-warning');
        $('#'+spanid).text("Suspended");
    }
    else if(retstatus=='Inactive' || retstatus=='Active' || retstatus=='Open' || retstatus=='Close' || retstatus=='Pending' || retstatus=='Approve' || retstatus=='Block')
    {
        if(retstatus=='Active' || retstatus=='Open' || retstatus=='Approve')
        {
            $('#'+spanid).removeClass('label-important').addClass('label-success');
        }
        else
        {
             $('#'+spanid).removeClass('label-success').addClass('label-important');
        }
       
        $('#'+spanid).text(retstatus);
    }
    else
    {
        $('#'+spanid).removeClass('label-warning').addClass('label-success');
        $('#'+spanid).text("Activated");
    }
    $('#'+loadingdiv).css('display','none');
}

	
</script>

</html>