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
            <li><a href="<?php echo site_url('admin/index'); ?>"><i class="iconfa-home"></i></a> </span><span class="separator"></span> <?php echo (isset($_GET['id']))?'Edit':'Add';?> Keyword </h1></li>
            
        </ul>
  <?php


    $exist_image='new';

$exist_file='new';
?>      
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-align-left"></span></div>
            <div class="pagetitle">
                 <h1><?php echo (isset($_GET['id']))?'Edit':'Add';?> Keyword </h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="widgetbox box-inverse">
                <h4 class="widgettitle"><?php echo (isset($_GET['id']))?'Edit':'Add';?> Keyword <span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <div class="widgetcontent wc1">
                    <form action="<?=site_url('admin/save_keyword');?>" method="post" class="stdform" id="form1" novalidate="novalidate">
                          
                            <input type="hidden" value="<?=(isset($keyword[0]['id']))?$keyword[0]['id']:"";?>" name="id"  />
            
                            
           
              <div class="par control-group">
                                    <label for="page_title" class="control-label">Keyword</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($keyword[0]['keyword']))?$keyword[0]['keyword']:"";?>" class="input-xxlarge" id="title" name="title" placeholder="Keyword" /></div>
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
 	// Select with Search
	jQuery(".chzn-select").chosen();
       $('.uniform-file').uniform();
       
       // Spinner
	   $("#difficulty").spinner({min: 0, max: 5, increment: 1});
        	jQuery("#datepicker").datepicker();
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