<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Stynite-Registration</title>
<link rel="shortcut icon" href="<?= base_url(); ?>favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="<?= base_url(); ?>css/style.default.css" type="text/css" />
<link href="<?= base_url(); ?>css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="" type="image/x-icon">
<link rel="icon" href="" type="image/x-icon">

<style>
.formError
{
    top:0 !important;
}
#registertop{
	height:100px;
	background:#b84082;
}
</style>  
<script type="text/javascript" src="<?= base_url(); ?>js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/modernizr.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/uploader.js"></script>

<script src="<?= base_url(); ?>js/jquery.validationEngine-en.js" type="text/javascript"></script> 
<script src="<?= base_url(); ?>js/jquery.validationEngine.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/custom.js"></script>
<script type="text/javascript">
function forgetpassword()
{
	$("#login").css("display","none");
	$("#forgotform").css("display","block");
}
 
       var $ = jQuery.noConflict();
    $(document).ready(function(){
    
       
           function submit_form(formData,status)
        {
            if(status==true)
            {
                var querystr = $("#form1").serialize();//+"&is_ajax=1";
        		$.ajax({
        			url		:	"<?php echo site_url('registration/registration_submit'); ?>",
        			type	:	"POST",
        			data	:	querystr,
        			success	:	function(data){
        			         data=$.parseJSON(data)
        			         
                			if(data.status==1){
                				alert("Registration successfull!!");
                                   window.location='<?php echo site_url('login'); ?>';
                                    
                				}else {
                                   alert("Error in Registration!!");
                				}
                			}
        		});
            }
            
        }          
          
        

    
   $("#form1").validationEngine('attach',{
         autoHidePrompt:true,
             autoHideDelay: 1500,
		unbindEngine	:	false,
		validationEventTriggers	:	"keyup blur",
        promptPosition : "topRight",
		onValidationComplete	:	function(formData,status) { submit_form(formData,status) }
	});
    });
    
    
        function check_email(email)
    {


      if(email){
               var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
if (testEmail.test(email))
{
    
              $.ajax({type:'POST',
                    url: "<?php echo site_url('registration/check_email'); ?>", 
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
                    url: "<?php echo site_url('registration/check_businessname'); ?>", 
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
//}    // Do whatever if it passes.
//else{
//    // Do whatever if it fails.
//      }
//        
               
     
    
</script>
</head>

<body class="loginpage">

 <div id="registertop">
    	 <div class="container">
    	 	 <img class="img-responsive" src="<?= base_url(); ?>logo.png" alt="Stynite" style="height:90px;padding: 5px 0px;"> 
    	 </div>
    	 </div>
    <div class="container" style="color: #b84082;">
    	 <h1> Stynite Register </h1>
                 <form action="" method="post" class="stdform" id="form1" style="width:90% ;">
                                         <div class="par control-group " id="businessnamediv">

                            <label>Business Name</label>
                            <span class="field"><input type="text" name="business_name" class="input-large validate[required]" onblur="check_businessname(this.value)"  placeholder="Business name" />
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
                            				    <input type="file" name="business_pic" onchange="uploadImage('<?php echo site_url('file/upload_image'); ?>','form1','business_pic','business_photo_name','business_original_name','icon_file_loading','fileupload-preview','catimage')" />
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
                        <p>
                            <label>Name</label>
                            <span class="field"><input type="text" name="firstname"  class="input-large validate[required]" placeholder="Firstname" />
                            <input type="text" name="lastname" class="input-large validate[required]"  placeholder="Lastname" /></span>
                        </p>
                        
                        <p>
                            <label>Contact No.</label>
                            <span class="field"><input type="text" name="contact" class="input-large validate[required]" placeholder="Contact No." /></span>
                        </p>
                        
                    <div class="par control-group " id="email_chk">
                    <label for="inputError" class="control-label">Email</label>
                            <span class="field"><input type="text" id="email" name="email" onblur="check_email(this.value)" class="span4 validate[required,custom[email]]" placeholder="Email" />
                         <span class="help-inline" id="emailsuccess_span" style="display: none;">Email is available</span>
                        <span class="help-inline" id="emailerror_span" style="display: none;">Email already used</span>
                        </span>
                      </div> 
                  
                        
                       <p>
                            <label>Password</label>
                            <span class="field"><input type="password" name="password" id="password" class="input-large validate[required]" placeholder="Password" /></span>
                        </p>
                        <p>
                            <label>Conform Password</label>
                            <span class="field"><input type="password" name="conform_password" class="input-large validate[required,equals[password]]" placeholder="Conform Password" /></span>
                        </p>
                         <p>
                            <label>Address</label>
                            <span class="field">
                            	<textarea cols="80" rows="5" name="address" id="address" class="span5"></textarea>
                            	</span>
                        </p>
                  
                        
                            <p class="stdformbutton">
                                    <button class="btn btn-primary">Register </button>
                                     </p>
                    </form>
                     
        <!--- end -->
        <center><p>&copy; Stynite. All Rights Reserved.</p></center>
    </div><!--loginpanelinner-->

 

</body>
</html>
