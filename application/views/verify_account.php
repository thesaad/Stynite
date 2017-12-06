<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Stynite</title>
<link rel="shortcut icon" href="<?= base_url(); ?>favicon.png" type="image/x-icon" />
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

<script type="text/javascript" src="<?= base_url(); ?>js/custom.js"></script>

</head>

<body class="loginpage">

<div class="loginpanel">
    <div class="loginpanelinner">
        <div class="logo animate0 bounceIn"><img src="<?= base_url(); ?>logo.png" style="height: 150px; width: 150px;" alt="" /></div>
       
        	<?php
        	if($user==1){
        		
        	
        	?>
             <div class="logo animate0 bounceIn">
               <h3 style="color:#FFF;">Account verification complete, Enjoy Stynite!!!!</h3>
            </div>
            <?php
			}else{
				?>
				  <div class="logo animate0 bounceIn">
                <h3 style="color:#FFF;">Verification link expire!!!!</h3>
            </div>
				<?php
			}
            ?>
            
          
            
      
    </div><!--loginpanelinner-->
</div><!--loginpanel-->

<div class="loginfooter">
    <p>&copy; Stynite. All Rights Reserved.</p>
</div>

</body>
</html>
