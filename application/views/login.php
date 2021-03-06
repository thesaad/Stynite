<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Stynite</title>
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
<script type="text/javascript">
function forgetpassword()
{
	$("#login").css("display","none");
	$("#forgotform").css("display","block");
}

   var $ = jQuery.noConflict();
    $(document).ready(function(){
    $("#forgotform").css("display","none");
        function submit_form(formData,status)
        {
            if(status==true)
            {
                var querystr = $("#login").serialize();//+"&is_ajax=1";
        		$.ajax({
        			url		:	"<?php echo site_url('login/submit'); ?>",
        			type	:	"POST",
        			data	:	querystr,
                beforeSend  :   function(){
                                    $(".loader").show();
                                },   
        			success	:	function(data){
        			         data=$.parseJSON(data)
        			         $(".loader").hide();
                			if(data.IsValid==0){
                                    $('#login').validationEngine('showPrompt', 'Invalid Password or Username', 'error');
                                    
                				}else {
                                   window.location='<?php echo site_url('login/success'); ?>';
                				}
                			}
        		});
            }
            
        }
                
          
        
        	$("#login").validationEngine('attach',{
         autoHidePrompt:true,
             autoHideDelay: 1500,
		unbindEngine	:	false,
		validationEventTriggers	:	"keyup blur",
        promptPosition : "topRight",
		onValidationComplete	:	function(formData,status) { submit_form(formData,status) }
	});
    });
</script>
</head>

<body class="loginpage">

<div class="loginpanel">
    <div class="loginpanelinner">
        <div class="logo animate0 bounceIn"><img src="<?= base_url(); ?>logo.png" style="height: 150px; width: 150px;" alt="" /></div>
      <form id="login" action="" method="post">
            <div class="inputwrapper login-alert">
                <div class="alert alert-error">Please Provide the Email and Password</div>
            </div>
            
            <div class="inputwrapper animate1 bounceIn">
                
                <input type="text" name="email" id="email" data-prompt-position="topRight:20,5"   class="validate[required]" placeholder="Email" />
            </div>
            <div class="inputwrapper animate2 bounceIn">
                <input type="password" name="password" id="password" data-prompt-position="topRight:20,5" class="validate[required]" placeholder="Enter Your password" />
            </div>
            <div class="inputwrapper animate3 bounceIn">
                <button name="submit">Sign In <span class="loader" style="float: right; display: none;"><img src="<?php echo base_url(); ?>images/loaders/loader19.gif" alt=""/> </span></button>
            </div>
             <div class="inputwrapper animate4 bounceIn">
              <!-- <label onclick="forgetpassword()" style="color:#b84082;">Forgotten password?</label>-->
                 <label   style="color:#b84082;" class="pull-right"><a href="<?php echo site_url("registration");?>" style="color:#b84082; text-decoration: none;">Register new account</a></label>
              
            </div>
            
            
            
        </form>
        <!--- forgot password--->
         <form id="forgotform" action="" method="post">
            
            <div class="inputwrapper animate1 bounceIn">
              
                <input type="text" name="email" id="email" data-prompt-position="topRight:20,5"   class="validate[required]" placeholder="Email associated with your account" />
            </div>
            
            <div class="inputwrapper animate3 bounceIn">
                <button name="submit">Submit <span class="loader" style="float: right; display: none;"><img src="<?php echo base_url(); ?>images/loaders/loader19.gif" alt=""/> </span></button>
            </div>
            
            
            
        </form>
        <!--- end -->
    </div><!--loginpanelinner-->
</div><!--loginpanel-->

<div class="loginfooter">
    <p>&copy; Stynite. All Rights Reserved.</p>
</div>

</body>
</html>
