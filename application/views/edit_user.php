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
            <li><a href="<?php echo site_url('admin/all_users'); ?>">Users</a> <span class="separator"></span> edit points</li>
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
  <?php

if(@$group[0]->image!="")
{
    $exist_image='exists';
}
else
{
    $exist_image='new';
}

?>      
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-list"></span></div>
            <div class="pagetitle">
                <h1>Users</h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="widgetbox box-inverse">
                <h4 class="widgettitle">Users<span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <div class="widgetcontent wc1">
                    <form action="<?=site_url('admin/save_user');?>" method="post" class="stdform" id="form1" novalidate="novalidate">
                            <input type="hidden" value="<?=(isset($point[0]->id))?$point[0]->id:"";?>" name="id"  />
                            <div class="par control-group">
                                    <label for="page_title" class="control-label">Current Bonus Point</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($point[0]->bonus_points))?$point[0]->bonus_points:"";?>" class="input-xxlarge" id="bonus_points" name="bonus_points" placeholder="Group Name" /></div>
                            </div>
                               
                            
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
<script type="text/javascript" src="<?=base_url();?>js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/ui.spinner.min.js"></script>

<script type="text/javascript">
var $ = jQuery.noConflict();


    $(document).ready(function(){
        
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