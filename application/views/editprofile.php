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

<div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="<?php echo site_url('admin/index'); ?>"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
              <li>Profile</li>
              
        </ul>
        
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-laptop"></span></div>
            <div class="pagetitle">
                <h1>Edit Profile</h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="widgetbox box-inverse">
               	 <?php if($this->session->userdata('is_admin')!='1')
                {
                ?>
                <h4 class="widgettitle">Edit
                	 
                	<span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <div class="widgetcontent wc1">
                    <form action="" method="post" class="stdform" id="form2" style="width:60% ;">
                            <input type="hidden" value="<?=(isset($admin[0]->id))?$admin[0]->id:"";?>" name="id"  />
                            <div class="par control-group " id="businessnamediv">

                            <label>Business Name</label>
                            <span class="field"><input type="text" name="business_name" class="input-large validate[required]" onblur="check_businessname(this.value)"  placeholder="Business name" value="<?=(isset($admin[0]->business_name))?$admin[0]->business_name:"";?>" />
                       <span class="help-inline" id="bnamesuccess_span" style="display: none;">Name is available</span>
                        <span class="help-inline" id="bnameerror_span" style="display: none;">Name already used</span>
                       </span>
                        </div>
                                              <div class="par control-group">
                			    <label for="icon" class="control-label">Logo</label>
                			    <div class="control-label">
                                    <div data-provides="fileupload" class="fileupload fileupload-new">
                        				<div class="input-append" id="iconUpload">
                            				<!--<div class="uneditable-input span3">
                            				    <i class="iconfa-file fileupload-exists"></i>
                            				    <span class="fileupload-preview"><?=(isset($filedata[0]->filename))?$filedata[0]->filename:"";?></span>
                            				</div>
                            				<span class="btn btn-file">
                                                <span class="fileupload-new">Select file</span>
                            				    <span class="fileupload-exists">Change</span>
                            				   -->
                            				    <input type="file" name="business_pic" onchange="uploadImage('<?php echo site_url('file/upload_image'); ?>','form2','business_pic','business_photo_name','business_original_name','icon_file_loading','fileupload-preview','catimage')" />
                                                <input type="hidden" id="business_photo_name" name="business_photo_name" value="" />
                                               
                                              <!--  </span>
                                             
                                        <a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>-->
                        				</div>
                                        
                                        <div id="icon_file_loading" style="display: none;">
                                            <img src="<?php echo base_url(); ?>images/loading2.gif" />
                                        </div>
                                        
                                     
                                    </div>
                                    
                                </div>
                			</div>
                         <?php if(isset($admin[0]->logo)){ ?>
         <div class="par control-group">
                                    <label for="page_title" class="control-label">Current Logo</label>
                                <div class="controls item">
                                	<img src="<?php echo PRODUCT_IMAGE.$admin[0]->logo;?>" style="height: 50px;" class="img-responsive" />
                                </div>
                            </div>
                            <?php
							}
                            ?>
                         
                              <p>
                            <label>Name</label>
                            <span class="field"><input type="text" name="firstname"  class="input-large validate[required]" placeholder="Firstname" value="<?=(isset($admin[0]->firstname))?$admin[0]->firstname:"";?>" />
                            <input type="text" name="lastname" class="input-large validate[required]"  placeholder="Lastname" value="<?=(isset($admin[0]->lastname))?$admin[0]->lastname:"";?>" /></span>
                        </p>
                        
                        <p>
                            <label>Contact No.</label>
                            <span class="field"><input type="text" name="contact" class="input-large validate[required]" placeholder="Contact No." value="<?=(isset($admin[0]->contact))?$admin[0]->contact:"";?>" /></span>
                        </p>
                        
                    <div class="par control-group " id="email_chk">
                    <label for="inputError" class="control-label">Email</label>
                            <span class="field"><input type="text" id="email" name="email" onblur="check_email(this.value)" class="span4 validate[required,custom[email]]" placeholder="Email" value="<?=(isset($admin[0]->email))?$admin[0]->email:"";?>" />
                         <span class="help-inline" id="emailsuccess_span" style="display: none;">Email is available</span>
                        <span class="help-inline" id="emailerror_span" style="display: none;">Email already used</span>
                        </span>
                      </div> 
                  
                        
                       <p>
                            <label>Password</label>
                            <span class="field"><input type="password" name="password" id="password" class="input-large validate[required]" placeholder="Password" /></span>
                        </p>
                       
                         <p>
                            <label>Address</label>
                            <span class="field">
                            	<textarea cols="80" rows="5" name="address" id="address" class="span5"><?=(isset($admin[0]->address))?$admin[0]->address:"";?></textarea>
                            	</span>
                        </p>
                  
                        
                            <p class="stdformbutton">
                                    <button class="btn btn-primary">Submit </button>
                                    <button class="btn" id="resetbtn"  type="button" value="Reset" >Reset </button>
                            </p>
                    </form>
                    
                    
                </div><!--widgetcontent-->
                <?php
                }
                ?>
                        <h4 class="widgettitle">Change Password
                	
                	<span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <div class="widgetcontent wc1">
                    <form action="" method="post" class="stdform" id="form1" style="width:60% ;">
                            <input type="hidden" value="<?=(isset($admin[0]->id))?$admin[0]->id:"";?>" name="id"  />
                           
                  
                        
                       <p>
                            <label>Password</label>
                            <span class="field"><input type="password" name="password" id="password" class="input-large validate[required]" placeholder="Password" /></span>
                        </p>
                        <p>
                            <label>New Password</label>
                            <span class="field"><input type="password" name="stynitenewpassword" id="stynitenewpassword" class="input-large validate[required]" placeholder="Password" /></span>
                        </p>
                       
                        
                            <p class="stdformbutton">
                                    <button class="btn btn-primary" onclick="change_password()">Submit </button>
                                    <button class="btn" id="resetbtn"  type="button" value="Reset" >Reset </button>
                            </p>
                    </form>
                    
                    
                </div>
                <!--- change password -->
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


    $(document).ready(function(){
            function submit_form(formData,status)
            {
       
               if(status==true)
                {
                   
                        var formData = new FormData($('#form2')[0]);
                            $.ajax({
                                
              		    type: "post",
             			url : "<?=site_url('admin/update_profile');?>",
               			data: formData,
                        contentType: false,
                        processData: false,
               			success:	function(data){
        			         data=$.parseJSON(data)
        			         $(".loader").hide();
                			if(data.IsValid==0){
                                     
                                     $('#form2').validationEngine('showPrompt', 'Current password wrong', 'error');
                                    
                				}
                				else{
                                    $('#form2').validationEngine('showPrompt', 'Profile Update successfully', 'error');
                                    
                                    }
                			}
                            
                       });
                         
                  }
           }
       
            jQuery("#form2").validationEngine({
            autoHidePrompt:true,
             autoHideDelay: 1500,
            promptPosition:'Center',
            onValidationComplete	:	function(formData,status) { submit_form(formData,status);}

           	
              });  
               
           jQuery("#form1").validationEngine({
            autoHidePrompt:true,
             autoHideDelay: 1500,
            promptPosition:'Center',
            onValidationComplete	:	function(formData,status) { change_password(formData,status);}

           	
              });  
                    
               
                   
     
    });
    
                function change_password(formData,status)
            {
       
               if(status==true)
                {
                   
                        var formData = new FormData($('#form1')[0]);
                            $.ajax({
                                
              		    type: "post",
             			url : "<?=site_url('admin/change_password');?>",
               			data: formData,
                        contentType: false,
                        processData: false,
               			success:	function(data){
        			         data=$.parseJSON(data)
        			         $(".loader").hide();
                			if(data.IsValid==0){
                                    
                                     $('#form1').validationEngine('showPrompt', 'Current password wrong', 'error');
                                    
                				}
                				else{
                                    $('#form1').validationEngine('showPrompt', 'Password Update successfully', 'error');
                                    
                                    }
                			}
                            
                       });
                         
                  }
           }
    
        
        function check_email(email)
    {


      if(email){
               var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
if (testEmail.test(email))
{
    
              $.ajax({type:'POST',
                    url: "<?php echo site_url('admin/check_email'); ?>", 
                  data:{"email":email }, 
                  success: function(data){
                    //alert(data);
                    if(data==1){
                         $('#email_chk').removeClass( "par control-group" ).addClass( "par control-group success" );
                          $('#email_chk').removeClass( "par control-group error" ).addClass( "par control-group success" );
                    $('#emailsuccess_span').css("display",'inline');
                     $('#emailerror_span').css("display",'none');
                    }else{
                         $('#email_chk').removeClass( "par control-group" ).addClass( "par control-group error" );
                         $('#email_chk').removeClass( "par control-group success" ).addClass( "par control-group error" );
                          $('#emailsuccess_span').css("display",'none');
                         $('#emailerror_span').css("display",'inline');
                          
                    }
                   
                    
                  }
                });
    }
//}    // Do whatever if it passes.
//else{
//    // Do whatever if it fails.
//      }
//        
               
        }
    }
    
       
            function check_businessname(name)
    {


      
              $.ajax({type:'POST',
                    url: "<?php echo site_url('admin/check_businessname'); ?>", 
                  data:{"business_name":name }, 
                  success: function(data){
                    //alert(data);
                    if(data==1){
                         $('#businessnamediv').removeClass( "par control-group" ).addClass( "par control-group success" );
                          $('#businessnamediv').removeClass( "par control-group error" ).addClass( "par control-group success" );
                    $('#bnamesuccess_span').css("display",'inline');
                     $('#bnameerror_span').css("display",'none');
                    }else{
                         $('#businessnamediv').removeClass( "par control-group" ).addClass( "par control-group error" );
                         $('#businessnamediv').removeClass( "par control-group success" ).addClass( "par control-group error" );
                          $('#bnamesuccess_span').css("display",'none');
                         $('#bnameerror_span').css("display",'inline');
                        
                    }
                   
                    
                  }
                });
    } 
</script>
<script type="text/javascript">
$(document).ready(function(){
$("#resetbtn").click(function(){
/* Single line Reset function executes on click of Reset Button */
$("#form2")[0].reset();
});});

</script>
</html>