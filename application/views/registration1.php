<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Stynite-Registration</title>
<link rel="stylesheet" href="<?= base_url(); ?>css/style.default.css" type="text/css" />
<link href="<?= base_url(); ?>css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="" type="image/x-icon">
<link rel="icon" href="" type="image/x-icon">

<style>
.formError
{
    top:0 !important;
}
</style>  
<script type="text/javascript" src="<?= base_url(); ?>js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/modernizr.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.cookie.js"></script>
<script src="<?= base_url(); ?>js/jquery.validationEngine-en.js" type="text/javascript"></script> 
<script src="<?= base_url(); ?>js/jquery.validationEngine.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/custom.js"></script>

<!-- DatePicker Script -->
<!--<script type="text/javascript" src="<?=base_url();?>js/jquery.weekpicker.js"></script>-->



<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<style>
.rightpanel{
    min-height: 625px;
}
#headertop{
	 background: #b84082 none repeat scroll 0 0;
    clear: both;
    height: 110px;
}
</style>
</head>

<body>

<div class="mainwrapper" style="background-color: #eee;">
    


 
    
    
    <div class="rightpanel" style="margin-left: 200px; margin-right: 200px;">
        

        
        <div class="pageheader">
            
            <div class="pageicon"><span class="iconfa-edit"></span></div>
            <div class="pagetitle">
                
                <h1>Signing up to join the Stynite</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
            <!---------------------pricing ------------------------>
         
            <!-------------------------end pricing--------------------->
            <div class="widget">
            <h4 class="widgettitle">Sign Up</h4>
            <div class="widgetcontent">
                      <form action="<?php echo site_url('registration/registration_submit'); ?>" method="post" class="stdform" id="form1" style="width:90% ;">
                      
                          <p>
                        	<label>Business Name</label>
                            <span class="field"><input type="text" name="business_name"  class="input-xlarge validate[required]" placeholder="Business Name" /></span>
                        </p> 	
                       
                        
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
                            <span class="field"><input type="text" name="email" onblur="check_email(this.value)" class="span4 validate[required,custom[email]]" placeholder="Email" />
                         <span class="help-inline" id="emailsuccess_span" style="display: none;">Email is available</span>
                        <span class="help-inline" id="emailerror_span" style="display: none;">Email already used</span>
                        </span>
                      </div> 
                           
                        <div class="par control-group" id="usernamediv">
                          <label for="inputError" class="control-label">Username</label>
                          <div class="controls">
                             <span class="field"> <input type="text" name="username" class="span4 validate[required]" onblur="check_username(this.value)" id="inputError"  placeholder="Username"/>
                        <span class="help-inline" id="success_span" style="display: none;">Username is available</span>
                        <span class="help-inline" id="error_span" style="display: none;">Username is not available</span>
                        </span>
                          </div>
                        </div><!--par-->
                        
                        
                       <p>
                            <label>Password</label>
                            <span class="field"><input type="password" name="password" id="password" class="input-large validate[required]" placeholder="Password" /></span>
                        </p>
                        <p>
                            <label>Conform Password</label>
                            <span class="field"><input type="password" name="conform_password" class="input-large validate[required,equals[password]]" placeholder="Conform Password" /></span>
                        </p>
                         <p>
                            <label>Street</label>
                            <span class="field"><input type="text" name="street" class="input-large validate[required]" placeholder="Street" /></span>
                        </p>
                        <p>
                            <label>City</label>
                            <span class="field"><input type="text" name="city" class="input-large validate[required]" placeholder="City" /></span>
                        </p>
                         <p>
                            <label>State</label>
                            <span class="field"><input type="text" name="state" class="input-large validate[required]" placeholder="State" /></span>
                        </p>
                         <p>
                            <label>Zipcode</label>
                            <span class="field"><input type="text" name="zipcode" class="input-large validate[required]" placeholder="Zipcode" /></span>
                        </p>
                        
                            <p class="stdformbutton">
                                    <button class="btn btn-primary">Submit </button>
                                    <button class="btn" id="resetbtn"  type="button" value="Reset" >Reset </button>
                            </p>
                    </form>
            </div><!--widgetcontent-->
            </div><!--widget-->
            
                
           
            
            <div class="footer">
                 
    <p>&copy; Stynite. All Rights Reserved.</p>
 
                </div><!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
<script type="text/javascript">
   var $ = jQuery.noConflict();
    $(document).ready(function(){
    
       
                
          
        
        	$("#form1").validationEngine('attach',{
         autoHidePrompt:true,
             autoHideDelay: 1500,
		unbindEngine	:	false,
		validationEventTriggers	:	"keyup blur",
        promptPosition : "topRight"
		//onValidationComplete	:	function(formData,status) { submit_form(formData,status) }
	});
    });
    function check_username(username)
    {
      if(username){
         $.ajax({type:'POST',
                    url: "<?php echo site_url('registration/check_username'); ?>", 
                  data:{"username":username }, 
                  success: function(data){
                    //alert(data);
                    if(data==1){
                         $('#usernamediv').removeClass( "par control-group" ).addClass( "par control-group success" );
                          $('#usernamediv').removeClass( "par control-group error" ).addClass( "par control-group success" );
                    $('#success_span').css("display",'inline-block');
                     $('#error_span').css("display",'none');
                    }else{
                         $('#usernamediv').removeClass( "par control-group" ).addClass( "par control-group error" );
                         $('#usernamediv').removeClass( "par control-group success" ).addClass( "par control-group error" );
                          $('#success_span').css("display",'none');
                         $('#error_span').css("display",'inline-block');
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
</script>
</body>
</html>
