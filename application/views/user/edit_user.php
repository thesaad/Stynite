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
            <li><a href="<?php echo site_url('admin/index'); ?>"><i class="iconfa-home"></i></a> </span><span class="separator"></span> <?php echo (isset($_GET['id']))?'Edit':'Add';?> User </h1></li>
            
        </ul>
  <?php
date_default_timezone_set("UTC");

    $exist_image='new';

$exist_file='new';
?>      
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-user"></span></div>
            <div class="pagetitle">
                 <h1><?php echo (isset($_GET['id']))?'Edit':'Add';?> User </h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="widgetbox box-inverse">
                <h4 class="widgettitle"><?php echo (isset($_GET['id']))?'Edit':'Add';?> User <span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <div class="widgetcontent wc1">
                    <form action="<?=site_url('user/save_user');?>" method="post" class="stdform" id="form1" novalidate="novalidate">
                          
                            <input type="hidden" value="<?=(isset($userdata[0]['id']))?$userdata[0]['id']:"";?>" name="id"  />
            
           <p>
<label>Username</label>
<span class="field">
<input class="input-medium" name="username" placeholder="Username" value="<?=(isset($userdata[0]['username']))?$userdata[0]['username']:"";?>" type="text">
</span>
</p>                            
           <p>
<label>Name</label>
<span class="field">
<input class="input-medium" name="firstname" placeholder="Firstname" value="<?=(isset($userdata[0]['firstname']))?$userdata[0]['firstname']:"";?>" type="text">
<input class="input-medium" name="lastname" placeholder="Lastname" value="<?=(isset($userdata[0]['lastname']))?$userdata[0]['lastname']:"";?>" type="text">
</span>
</p>
              <div class="par control-group">
                                    <label for="page_title" class="control-label">Email</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($userdata[0]['email']))?$userdata[0]['email']:"";?>" class="input-large" id="email" name="email" placeholder="Email" /></div>
                            </div>
      
   
                         <div class="par control-group">
                                    <label for="page_title" class="control-label">Contact</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($userdata[0]['contact']))?$userdata[0]['contact']:"";?>" class="input-xxlarge" id="contact" name="contact" placeholder="" /></div>
                            </div>
                             <?php
                             if(isset($userdata[0]['dob'])){
                             $dob=	 date("m/d/Y",strtotime($userdata[0]['dob']));
                             	}else{
                             		$dob = '';
                             	}?>
                            <div class="par">
<label>Date of birth</label>
<span class="field">

<input class="input-medium" id="datepicker" name="dob" placeholder="Date of birth" value="<?php echo $dob;?>" type="text">
</span>
</div>
                                  <div class="par control-group">
                			    <label for="icon" class="control-label">Image</label>
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
                            				    <input type="file" name="business_pic" onchange="uploadImage('<?php echo site_url('file/upload_profile_image'); ?>','form1','business_pic','business_photo_name','business_original_name','icon_file_loading','fileupload-preview','userimage')" />
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
                			<?php if(isset($userdata[0]['image'])){ ?>
         <div class="par control-group">
                                    <label for="page_title" class="control-label">Current Image</label>
                                <div class="controls item">
                                	<img src="<?php echo get_user_image($userdata[0]['image']);?>" style="height: 50px;" class="img-responsive" />
                                </div>
                            </div>
                            <?php
							}
							 if(isset($userdata[0]['gender'])){
                            if($userdata[0]['gender']=='FEMALE'){
                            	$female="selected";
                            }else{
                            	$male="selected";
                            }
                             	}else{
                             		$male = "";
                             		$female="";
                             	}
                             	?>
                            
                            
                              <p>
                            <label>Gender </label>
                            <span class="formwrapper">
                                <select data-placeholder="Choose Keywords..."  name="gender"  style="width:350px;" tabindex="4">
                    
                                  <option value="MALE" <?php echo $male;?> >Male</option> 
                                  <option value="FEMALE" <?php echo $female;?> >Female</option> 
                                  </select>
                                 </span>
                                 </p>
                                 <span class="field">

</span>
                            <p class="stdformbutton">
                                    <button class="btn btn-primary">Submit </button>
                                    <button class="btn" type="reset">Reset </button>
                            </p>
                    </form>
                    
                </div><!--widgetcontent-->
            </div>     
            </div>
        </div>
</div>
</body>
<link rel="stylesheet" href="<?=base_url();?>css/popover.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?=base_url();?>js/jquery.popover-1.1.2.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/ui.spinner.min.js"></script>

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
 ;
 

    $(document).ready(function(){
     
    	//jQuery("#datepickerend").datepicker();
jQuery("#datepicker1").datepicker();

    	//$("#datepicker").datepicker();
    	jQuery("#datepicker").datepicker();
    	initPOPOVER();
 	// Select with Search
	
       $('.uniform-file').uniform();
       
       // Spinner
	   $("#difficulty").spinner({min: 0, max: 5, increment: 1});
        	
        // slider with fixed minimum
	
     
      //Validation Start Here
      
      validator.defaults.alerts = false;    // for remove alert
      // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
		$('#form1')
			.on('blur', 'input[required], textarea[required], input.optional, select.required', validator.checkField)
			.on('change', 'select.required', validator.checkField)
            .on('submit', 'input[required],textarea[required], input.optional, select.required', validator.checkField)
			.on('keypress', 'input[required][pattern]', validator.keypress);

	

		// bind the validation to the form submit event
		//$('#send').click('submit');//.prop('disabled', true);

		$('#form1').submit(function(e){
			e.preventDefault();
			var submit = true;
			// evaluate the form using generic validaing
			if( !validator.checkAll( $(this) ) ){
				submit = false;
			}
            if(!validateCkeditor())
            {
                submit = false;
            }
			if( submit )
				this.submit();
			return false;
            
		}); 
        // End Of Validations
        
        function validateCkeditor()
        {
           var content= $('#caption').val();
           var iconFileUpload = $('#iconUpload').val();
           if(content== "")
           {
            //$('#editorMsg').show();
            $('#caption').addClass('txtEffext');
            return false;
           } 
           else
           {    $('#caption').removeClass('txtEffext');
                return true
            }    
        }
        
      
     
    });
    
 
    
    
</script>
</html>