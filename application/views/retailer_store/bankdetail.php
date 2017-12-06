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
<script type="text/javascript" src="<?php echo base_url();?>js/chosen.jquery.min.js"></script>
<div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('admin/index'); ?>"><i class="iconfa-home"></i></a> </span></li>
             <li><span class="separator"></span>Bank Detail </h1></li>
            
        </ul>
  <?php


    $exist_image='new';

$exist_file='new';
?>     



                            
                            
                            
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-align-left"></span></div>
            <div class="pagetitle">
                 <h1>Bank Detail</h1>
                <div class="searchbar" style="top: 15%;">
                	
                	<?php   if(isset($bank_detail[0]->status)){
                			if($bank_detail[0]->status=='verified'){
                				?>
                	 <img src="<?php echo base_url()."/images/verified.png";?>" style="height:80px;width:80px;">
					<?php	
					}else{
						?>
					 <img src="<?php echo base_url()."/images/notverified.png";?>" style="height:80px;width:80px;">	
						<?php
						
					}
					}
                	
                	
                	?>
                </div> 
                 
        
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="widgetbox box-inverse">
               	
                <h4 class="widgettitle">Bank Detail<span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <ul style="border-left: 2px solid #000; border-right: 2px solid #000;" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
                                <li id="accountdetail_lable"  class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="-1" aria-controls="tabs-1" aria-labelledby="ui-id-1" aria-selected="false"><a   class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1" style="cursor: pointer;" onclick="make_active('accountdetail_lable')">Account Details</a></li>
                                <li id="accountverify_lable"  class="ui-state-default ui-corner-top " role="tab" tabindex="-1" aria-controls="tabs-2" aria-labelledby="ui-id-2" aria-selected="false"><a   class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2"  style="cursor: pointer;" onclick="make_active('accountverify_lable')">Verify Account</a></li>
                               </ul>
                <div class="widgetcontent wc1">
                    <form action="" method="post" class="stdform" id="form1" novalidate="novalidate">
                          	<div class="form-group">
                                        <ul class="alert alert-danger" id="error_body" style="padding-left: 30px;display:none;"></ul>
                        </div>
                        <div class="form-group">
                                        <ul class="alert alert-success" id="success_body" style="padding-left: 30px;display:none;"></ul>
                        </div>
                            <input type="hidden" value="<?=(isset($bank_detail[0]->id))?$bank_detail[0]->id:"";?>" name="id"  />
            
                          
                 
                            <div class="par control-group">
                                    <label for="page_title" class="control-label">Account No</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($bank_detail[0]->account_number))?$bank_detail[0]->account_number:"";?>" class="input-xxlarge" id="ac_no" name="ac_no" placeholder="Account No." /></div>
                            </div>    
                           <div class="par control-group">
                                    <label for="page_title" class="control-label">Routing No</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($bank_detail[0]->routing_number))?$bank_detail[0]->routing_number:"";?>" class="input-xxlarge" id="routing_no" name="routing_no" placeholder="" /></div>
                            </div>
           
              <div class="par control-group">
                                    <label for="page_title" class="control-label">Name</label>
                             <span class="field">
                             	<input name="firstname" class="input-medium" placeholder="First Name" value="<?=(isset($bank_detail[0]->first_name))?$bank_detail[0]->first_name:"";?>" type="text">
                             		<input name="lastname" class="input-medium" placeholder="Last Name" value="<?=(isset($bank_detail[0]->last_name))?$bank_detail[0]->last_name:"";?>" type="text">
                             	
                             </span>
                            </div>
      
      <p>
                            <label>Dob </label>
                            <span class="field">
                            	<select id="dobday" name="date"></select>
		<select id="dobmonth" name="month"></select>
		<select id="dobyear" name="year"></select>
                            </span> 
                             
                                   </p>
              <p>
              	
              	 
              	
              	
                            <label>Address</label>
                            <span class="field"><textarea cols="80" name="address" id="address" rows="5" class="span5"><?=(isset($bank_detail[0]->address))?$bank_detail[0]->address:"";?></textarea></span> 
                        </p>  
                   <div class="par control-group">
                                    <label for="page_title" class="control-label">Postal code</label>
                             <span class="field">
                             	<input name="postal_code" class="input-medium" placeholder="Postal code" value="<?=(isset($bank_detail[0]->postal_code))?$bank_detail[0]->postal_code:"";?>" type="text">
                             		 
                             	
                             </span>
                            </div>                    
             <div class="par control-group">
                                    <label for="page_title" class="control-label">City</label>
                             <span class="field">
                             	<input name="city" class="input-medium" placeholder="City" value="<?=(isset($bank_detail[0]->city))?$bank_detail[0]->city:"";?>" type="text">
                             		 
                             	
                             </span>
                            </div>
                                <div class="par control-group">
                                    <label for="page_title" class="control-label">State</label>
                             <span class="field">
                             	<input name="state" class="input-medium" placeholder="State" value="<?=(isset($bank_detail[0]->state))?$bank_detail[0]->state:"";?>" type="text">
                             		 
                             	
                             </span>
                            </div>
                            
                                  <div class="par control-group">
                                    <label for="page_title" class="control-label">Country</label>
                             <span class="field">
                             	<input name="country" class="input-medium" placeholder="Country" value="<?=(isset($bank_detail[0]->country))?$bank_detail[0]->country:"";?>" type="text">
                             		 
                             	
                             </span>
                            </div>
                         <div class="par control-group">
                                    <label for="page_title" class="control-label">SSN</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($bank_detail[0]->ssn))?$bank_detail[0]->ssn:"";?>" class="input-xxlarge" id="ssn_no" name="ssn_no" placeholder="" /></div>
                            </div>
                            <div class="par control-group">
                                    <label for="page_title" class="control-label">Personal ID</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($bank_detail[0]->personal_id))?$bank_detail[0]->personal_id:"";?>" class="input-xxlarge" id="personal_id" name="personal_id" placeholder="Personal Id" /></div>
                            </div>
                        
                    
                            <p class="stdformbutton">
                                    <button class="btn btn-primary">Submit </button>
                                    <button class="btn" type="reset">Reset </button>
                            </p>
                    </form>
                    
                    
                                      <form id="form2"   class="stdform"  style="top:0%; display: none;"  method="post" >
                             	             	<div class="form-group">
                                        <ul class="alert alert-danger" id="error_body_v" style="padding-left: 30px;display:none;"></ul>
                        </div>
                        <div class="form-group">
                                        <ul class="alert alert-success" id="success_body_v" style="padding-left: 30px;display:none;"></ul>
                        </div>  
                             	                 <div class="par control-group">
                			    <label for="icon" class="control-label">Upload Document</label>
                			    <div class="control-label">
                                    <div data-provides="fileupload" class="fileupload fileupload-<?=$exist_image;?>">
                        				<div class="input-append" id="iconUpload">
                            				<!--<div class="uneditable-input span3">
                            				    <i class="iconfa-file fileupload-exists"></i>
                            				    <span class="fileupload-preview"><?=(isset($filedata[0]->filename))?$filedata[0]->filename:"";?></span>
                            				</div>
                            				<span class="btn btn-file">
                                                <span class="fileupload-new">Select file</span>
                            				    <span class="fileupload-exists">Change</span>
                            				   -->
                            				    <input type="file" name="business_pic" onchange="uploadImage('<?php echo site_url('file/upload_doc'); ?>','form2','business_pic','business_photo_name','business_original_name','icon_file_loading','fileupload-preview','catimage')" />
                                                <input type="hidden" id="business_photo_name" name="business_photo_name" value="<?=(isset($filedata[0]->filename))?$filedata[0]->filename:"";?>" />
                                               
                                              <!--  </span>
                                             
                                        <a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>-->
                        				</div>
                                        
                                        <div id="icon_file_loading" style="display: none;">
                                            <img src="<?php echo base_url(); ?>images/loading2.gif" />
                                        </div>
                                        
                                     
                                    </div>
                                    
                                </div>
                			</div>
                			
                			<p class="stdformbutton">
                                    <button class="btn btn-primary">Submit </button>
                                     
                            </p>
                                        
                                           
</form>
                    
                </div><!--widgetcontent-->
            </div>     
            </div>
        </div>
</div>
</body>
<script type="text/javascript" src="<?=base_url();?>js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/ui.spinner.min.js"></script>

<script type="text/javascript">
var $ = jQuery.noConflict();
 
jQuery("#datepickerend").datepicker();
jQuery("#datepicker").datepicker();

function autoselect()
{
	 
 
 $('#dobday').val('<?php echo (isset($bank_detail[0]->b_day))?$bank_detail[0]->b_day:"";?>').trigger('change');

$('#dobmonth').val('<?php echo (isset($bank_detail[0]->b_month))?$bank_detail[0]->b_month:"";?>').trigger('change');
$('#dobyear').val('<?php echo (isset($bank_detail[0]->b_year))?$bank_detail[0]->b_year:"";?>').trigger('change');
 	
}
function make_active(active_id)
{
	
	//ui-tabs-active ui-state-active  accountverify_lable
	if(active_id=='accountdetail_lable'){
		$( "#accountdetail_lable" ).addClass( "ui-tabs-active ui-state-active" );
		$( "#accountverify_lable" ).removeClass( "ui-tabs-active ui-state-active" );
		
		$("#form2").css("display", "none");
$("#form1").css("display", "block");
	}else{
		$( "#accountverify_lable" ).addClass( "ui-tabs-active ui-state-active" );
		$( "#accountdetail_lable" ).removeClass( "ui-tabs-active ui-state-active" );
		$("#form1").css("display", "none");
$("#form2").css("display", "block");
		 
	}
	
	
}
    $(document).ready(function(){
 	// Select with Search
 	
	 setTimeout(autoselect,2000)
     
       
       
       // Spinner
 
        // End Of Validations
        
     
        
      
     
    });
    
 	$("#form1").unbind('submit').submit(function(e) {
			$('#error_body > li').remove();
            	   e.preventDefault();
            e.stopImmediatePropagation();
             $("#error_body").html("");
                $("#error_body").hide();
                
                  var global_error = false;
                var error_reason;
                //prevent default action of loading
                e.preventDefault();
                //Initiate all variables
                if ($("[name='firstname']").val() == ""){
                    $("#error_body").append("<li>Firstname is empty</li>");
                    global_error = true;
                } 
                  if ($("[name='lastname']").val() == ""){
                    $("#error_body").append("<li>Lastname is empty</li>");
                    global_error = true;
                }   if ($("[name='ac_no']").val() == ""){
                    $("#error_body").append("<li>Account no. is empty</li>");
                    global_error = true;
                }  
              
               if (!global_error){
               	/*
               	new_start=stringToTimestamp(start);
               	$("[name='start']").val(new_start);
               new_end =	stringToTimestamp(end);
               		$("[name='end']").val(new_end);*/
              
                   var form=$("#form1");
                      var formData = form.serialize();
                 $.ajax({
                url		:	"<?php echo site_url('bankdetail/addBankDetail'); ?>",
                type	:	"POST", 
                data	:	formData, 
                success	:	function(data){
                	 
                	data=$.parseJSON(data)
                    //   alert(data.message);
                 	 if (data.status==true)
                    {
                    	 $("#success_body").append("<li>Bank information updated successfully. </li>");
                    	   $("#success_body").show();
                    	 
                    }else{
                    	 $("#error_body").append("<li>Error occur </li>");
                    	   $("#error_body").show();
                    	  
                    	  
                    }
			 $("html, body").animate({ scrollTop: 0 }, "slow");
                 }
            
            });
                      //global error is false
                }else {
                    // $("#error_body").append("<li>Form was not submitted. You have errors. Correct the errors before submitting again</li>");
                    $("#error_body").show();
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    if (error_reason)
                        console.log(error_reason);
                   $("#new_event_submit").prop('disabled', false);
                }
                return false;
            });
    /// form 2
    
    $("#form2").unbind('submit').submit(function(e) {
			$('#error_body > li').remove();
            	   e.preventDefault();
            e.stopImmediatePropagation();
             $("#error_body").html("");
                $("#error_body").hide();
                
                  var global_error = false;
                var error_reason;
                //prevent default action of loading
                e.preventDefault();
                //Initiate all variables
    
              
               if (!global_error){
               	/*
               	new_start=stringToTimestamp(start);
               	$("[name='start']").val(new_start);
               new_end =	stringToTimestamp(end);
               		$("[name='end']").val(new_end);*/
                 //var formData = new FormData(this);
            var form=$("#form2"); 
                 $.ajax({
                url		:	"<?php echo site_url('bankdetail/uploadVerifyDoc'); ?>",
                type	:	"POST",
              // processData: false,
              // contentType: false,
                data	:	form.serialize(),
               //dataType: "JSON",
                success	:	function(data){
                	// var obj = JSON.parse(data);
                	var obj = jQuery.parseJSON(data);
 
                	//data=$.parseJSON(data)
                      // alert(data.message);
                 	 if (obj.status==1)
                    {
                    	 $("#success_body_v").append("<li>"+obj.message+"</li>");
                    	   $("#success_body_v").show();
                    	 
                    }else{
                    	 $("#error_body_v").append("<li>Error occur </li>");
                    	   $("#error_body_v").show();
                    	  
                    	  
                    }
			 $("html, body").animate({ scrollTop: 0 }, "slow");
                 }
            
            });
                      //global error is false
                }else {
                    // $("#error_body").append("<li>Form was not submitted. You have errors. Correct the errors before submitting again</li>");
                    $("#error_body").show();
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    if (error_reason)
                        console.log(error_reason);
                 //  $("#new_event_submit").prop('disabled', false);
                }
                return false;
            });
    
</script>
	<script>
			$(document).ready(function() {
				$.dobPicker({
					daySelector: '#dobday', /* Required */
					monthSelector: '#dobmonth', /* Required */
					yearSelector: '#dobyear', /* Required */
					dayDefault: 'Day', /* Optional */
					monthDefault: 'Month', /* Optional */
					yearDefault: 'Year', /* Optional */
					minimumAge: 12, /* Optional */
					maximumAge: 80 /* Optional */
				});
			});
		</script>
</html>