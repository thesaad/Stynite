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
            <li>Send Notification
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
           
            <div class="pageicon"><span class="iconfa-align-left"></span></div>
            <div class="pagetitle">
                <h1>Notification</h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="widgetbox box-inverse">
                <h4 class="widgettitle">Send-Notification <span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <div class="widgetcontent wc1">
                    <form action="" method="post" class="stdform" id="form1" style="width: 70%;" novalidate="novalidate">
                    <!--
                       <div class="par control-group">
                                    <label for="page_title" class="control-label">Subject</label>
                                <div class="controls item"><input type="text"  value="" class="input-xxlarge validate[required]" id="title" name="title" placeholder="Subject" /></div>
                            </div>
                           -->
                            
                              <p>
                            <label>Message</label>
                            <span class="field">
                            
                            	
                            	<textarea cols="80" rows="5" id="message" name="message"  placeholder="Message"  class="span5 validate[required]" ></textarea></span> 
                        </p>
                             
                            <p>
                             <label>Notificationt Type</label>
                                	<select tabindex="2" style="width:350px; height: 33px;" name="type" id="type"  class="span5 validate[required]" id="business" >
                                      <option value="GENERAL">General</option>
                                      <option value="PRICE_DROP">Price Drops</option>                                      
                                      <option value="SALE">Offers and Sales </option>
                                    </select>
                                
                                
                            </p>
                                    
                            <p class="stdformbutton">
                                    <button class="btn btn-primary">Send </button>
                                    <button class="btn" type="reset">Reset </button>
                            </p>
                            
                            
                            <p>
                           
                            <span class="field">
                            <label></label>
                             <div id="sendload" style="display: none; color:red">
                                       Sending <img src="<?php echo base_url(); ?>images/loading2.gif" />
                                        </div>
                            </span> 
                        </p>  
                    </form>
                    
                </div><!--widgetcontent-->
            </div>     
            </div>
        </div>
</div>
</body>
<script type="text/javascript" src="<?=base_url();?>js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/ui.spinner.min.js"></script>

<script type="text/javascript">
   var $ = jQuery.noConflict();
    $(document).ready(function(){
    $("#selectuser").hide();
        function submit_form(formData,status)
        {
          
            if(status==true)
            {
                  $('#sendload').css("display",'');
                var querystr = $("#form1").serialize();//+"&is_ajax=1";
        		$.ajax({
        			url		:	"<?php echo site_url('notification/request_notice'); ?>",
        			type	:	"POST",
        			data	:	querystr,
                beforeSend  :   function(){
                                    $(".loader").show();
                                },   
        			success	:	function(data){
        			         data=$.parseJSON(data)
        			         $(".loader").hide();
                             $('#sendload').hide();
                			if(data.IsValid==0){
                			 
                                    $('#form1').validationEngine('showPrompt', 'Operation Fail', 'error');
                                    
                				}else {
                                   $('#form1').validationEngine('showPrompt', 'Notification Sent ', 'error');
                				}
                			}
        		});
            }
            
        }
        jQuery(".chzn-select").chosen();
                
          
        
        	$("#form1").validationEngine('attach',{
         autoHidePrompt:true,
             autoHideDelay: 1500,
		unbindEngine	:	false,
		validationEventTriggers	:	"keyup blur",
        promptPosition : "topRight",
		onValidationComplete	:	function(formData,status) { submit_form(formData,status) }
	});
    });
    function check_select()
    {
    	if($("#users").val()=='SPECIFIC'){
    		$("#selectuser").show();
    	}else{
    		$("#selectuser").hide();
    	}
    }
</script>
</html>