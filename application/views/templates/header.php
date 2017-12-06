<?php
$retailer=$user=$winner=$sales=$camfindkeywords=$adultkeywords=$notification_display=$notification_list=$editprofile=$product=$keywords=$dashboard=$affilateretailer=$sales=$totalsales=$styuser="";

if($active_page=='retailer' )
{
    $retailer='active';
}
elseif($active_page=='user' || $active_page=='add_employee')
{
    $user='active';
}
elseif($active_page=='finishing' || $active_page=='add_coupon')
{
    $finishing='active';
}elseif($active_page=='product'||$active_page=='manage_product')
{
    $product='active';
}
elseif($active_page=='dashboard')
{
    $dashboard='active';
}
elseif($active_page=='styuser')
{
    $styuser='active';
}
elseif($active_page=='adultkeywords')
{
    $adultkeywords='active';
}
elseif($active_page=='totalsales')
{
    $totalsales='active';
}
elseif($active_page=='affiliate_retailers')
{
    $affilateretailer='active';
}
elseif($active_page=='editprofile')
{
    $editprofile='active';
}
elseif($active_page=='keywords')
{ $keywords='active';
    
}
elseif($active_page=='camfindkeywords')
{ $camfindkeywords='active';
    
}elseif($active_page=='notification')
{ $notification_list='active';
$notification_display='display: block;';
    
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Stynite</title>
<link rel="shortcut icon" href="<?= base_url(); ?>favicon.ico" type="image/x-icon" />


<!-- Add Form Validation CSS -->
<link rel="stylesheet" href="<?=base_url();?>formvalidation/fv.css" />


<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.default.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-fileupload.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>css/responsive-tables.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-timepicker.min.css" type="text/css" />
<!--DATE PICKER SHEET-->


<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.12/integration/font-awesome/dataTables.fontAwesome.css" type="text/css" />

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/modernizr.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/responsive-tables.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/custom.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/uploader.js"></script>


<script type="text/javascript" src="<?php echo base_url(); ?>js/tinymce/jquery.tinymce.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/js/jquery.validationEngine.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/js/jquery.validationEngine-en.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo base_url();?>dist/dob/dobPicker.min.js"></script>
<!-- Add Form Validation SCRIPT  -->
<script src="<?=base_url();?>formvalidation/validator.js"></script>

<!-- DatePicker Script -->
<!--<script type="text/javascript" src="<?=base_url();?>js/jquery.weekpicker.js"></script>-->


<script>
	var $=jQuery.noConflict();
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>dist/imagezoom/hover_zoom_v3.min.js"></script>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<style>
.rightpanel{
    min-height: 625px;
}
</style>
</head>

<body>
    <div class="mainwrapper">
        <div class="header" >
            <div class="logo" style="height: 40px; padding-top: 1px !important; color: white !important; font-size: 20px!important;">
              <a href="<?php echo site_url('admin/index'); ?>"><img src="<?php echo base_url(); ?>logo.png" alt="" style="height: 100px; width: 100px;" /></a>  
               
            </div>
            <div class="headerinner">
               <ul class="headmenu">
                <li class="right" style="height: 110px;">
                    <a href="<?php echo site_url("admin/balance_history");?>">
                    <span class="count"></span>
                    <span  ><label style="color:yellow;font-size: 18pt; font-weight: bolder;"><?php echo getbalance();?></label> </span>
                    <span class="headmenu-label">Balance</span>
                    </a>
                     
                </li>
                   <!-- <li class="right" style="border-left: none;" >
                        <div class="userloggedinfo">
                       <img style="height: 70px;" src="<?php echo base_url(); ?>images/user.png" alt=""/>
                        <div class="userinfo" >
                        	<h5 ><?php echo $this->session->userdata('username');?> <small>- Logged in &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small></h5>
                            
                            <ul>
                                <li><a href="<?php echo site_url('admin/editprofile'); ?>">Edit Profile</a></li>
                               
                                <li><a href="<?php echo site_url('login/logout'); ?>">Sign Out</a></li>
                            </ul>
                        </div>
                    </div>
                    </li>-->
                </ul> 
            </div>
        </div>
        <div class="leftpanel">
        
            <div class="leftmenu">        
                <ul class="nav nav-tabs nav-stacked">
                	<li class="nav-header">Logged in: <span style="color: #b84082;"><?php echo $this->session->userdata('username');?></span></li>
                    <!--<li class="<?=$dashboard;?>"><a href="<?php echo site_url('rcp/index'); ?>"><span class="iconfa-laptop"></span> Dashboard</a></li>-->
                
                <?php if($this->session->userdata('is_admin')=='1')
                {
                ?>
                  <li class="<?=$retailer;?>"><a href="<?php echo site_url('admin/retailers'); ?>"><span class="iconfa-list"></span> Retailers</a></li>  
        
        <li class="<?=$affilateretailer;?>"><a href="<?php echo site_url('admin/affiliate_retailers'); ?>"><span class="iconfa-list"></span> Affiliate retailers</a></li>  
       <li class="<?=$styuser;?>"><a href="<?php echo site_url('user/index'); ?>"><span class="iconfa-group"></span> Users</a></li>  
        <li class="<?=$camfindkeywords;?>"><a href="<?php echo site_url('admin/camfindkeywords'); ?>"><span class="iconfa-camera"></span> Camfind Keywords</a></li>  
       <li class="<?=$adultkeywords;?>"><a href="<?php echo site_url('admin/adultkeywords'); ?>"><span class="iconfa-filter"></span> Adult Keywords filter</a></li>  
      
        <?php
				}
        ?>
         <?php if($this->session->userdata('is_admin')!='1')
                {
                ?>
                <li class="<?=$dashboard;?>"><a href="<?php echo site_url('admin/dashboard'); ?>"><span class="iconfa-laptop"></span> Dashboard</a></li>
                <!-- <li class="<?=$dashboard;?>"><a href="<?php echo site_url('admin/dashboard'); ?>"><span class="iconfa-edit"></span> Update Bank Info</a></li>
            -->   
                  <li class="<?=$product;?>"><a href="<?php echo site_url('admin/index'); ?>"><span class="iconfa-list"></span> Products</a></li>  
        <li class="<?=$sales;?>"><a href="<?php echo site_url('admin/sales'); ?>"><span class="iconfa-list"></span> Monthly Sales</a></li>
        <li class="<?=$totalsales;?>"><a href="<?php echo site_url('admin/totalsales'); ?>"><span class="iconfa-list"></span> Total Sales</a></li>
        <li class="<?=$totalsales;?>"><a href="<?php echo site_url('bankdetail/index'); ?>"><span class="iconfa-list"></span> Bank Details</a></li>
               
        <?php
				}
        ?>
        
           <li class="<?=$keywords;?>"><a href="<?php echo site_url('admin/keywords'); ?>"><span class="iconfa-list"></span> Keywords</a></li>  
          
	 
	 <li class="dropdown <?=$notification_list;?>"><a href=""><span class="iconfa-bell"></span></span> Notification</a>
                	<ul style="<?=$notification_display;?>">
                    	 <?php if($this->session->userdata('is_admin')!='1')
                {
                ?>
                        <li  ><a href="<?php echo site_url('admin/send_notification'); ?>">Send Notification </a></li>
                     <li  ><a href="<?php echo site_url('admin/notification_list'); ?>">Notification List </a></li>
                    <?php
                    }else{
                    ?>
                      <li  ><a href="<?php echo site_url('admin/send_notification'); ?>">Send Notification </a></li>
                     <li  ><a href="<?php echo site_url('admin/retailer_notification_list'); ?>">Notification List </a></li>
                  
                    <?php
					}
                    ?>
                    </ul>
                </li>
	  <li class="<?=$editprofile;?>"><a href="<?php echo site_url('admin/editprofile'); ?>"><span class="iconfa-edit"></span>Edit Profile </a></li> 
                   <li class=""><a href="<?php echo site_url('login/logout'); ?>"><span class="iconfa-eject"></span>Sign Out</a></li>
             
              
                  
      
                        
                        
                    </ul>
                </li>  
                    
                    
                </ul>
            </div><!--leftmenu-->
            
        </div><!-- leftpanel -->