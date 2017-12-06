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
            <li><a href="<?php echo site_url('admin/index'); ?>"><i class="iconfa-home"></i></a> </span><span class="separator"></span> <a href="<?php echo site_url('admin/retailer_products')."?id=".$_GET['retailer_id']; ?>">All Product </a></h1></li>
             <li><span class="separator"></span> <?php echo (isset($_GET['id']))?'Edit':'Add';?> Product </h1></li>
            
        </ul>
  <?php


    $exist_image='new';

$exist_file='new';
?>      
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-align-left"></span></div>
            <div class="pagetitle">
                 <h1>Retailer: <span style="color:red;"> <?php echo $retailer[0]['firstname']." ".$retailer[0]['lastname'];?> </span></h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="widgetbox box-inverse">
                <h4 class="widgettitle"><?php echo (isset($_GET['id']))?'Edit':'Add';?> Product <span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <div class="widgetcontent wc1">
                    <form action="<?=site_url('retailer_store/save_product');?>" method="post" class="stdform" id="form1" novalidate="novalidate">
                          
                            <input type="hidden" value="<?=(isset($product[0]->id))?$product[0]->id:"";?>" name="id"  />
            
                         <input type="hidden" value="<?php echo $_GET['retailer_id'];?>" name="retailer_id"  />
               
           
              <div class="par control-group">
                                    <label for="page_title" class="control-label">Title</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($product[0]->title))?$product[0]->title:"";?>" class="input-xxlarge" id="title" name="title" placeholder="Title" /></div>
                            </div>
      
      <p>
                            <label>Description</label>
                            <span class="field"><textarea cols="80" name="description" id="description" rows="5" class="span5"><?=(isset($product[0]->description))?$product[0]->description:"";?></textarea></span> 
                        </p>
                         <div class="par control-group">
                                    <label for="page_title" class="control-label">Quantity</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($product[0]->quantity))?$product[0]->quantity:"";?>" class="input-xxlarge" id="quantity" name="quantity" placeholder="" /></div>
                            </div>
                            <div class="par control-group">
                                    <label for="page_title" class="control-label">Price</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($product[0]->price))?$product[0]->price:"";?>" class="input-xxlarge" id="price" name="price" placeholder="Price" /></div>
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
                            				    <input type="file" name="business_pic" onchange="uploadImage('<?php echo site_url('file/upload_image'); ?>','form1','business_pic','business_photo_name','business_original_name','icon_file_loading','fileupload-preview','catimage')" />
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
                			<?php if(isset($product[0]->imagename)){ ?>
         <div class="par control-group">
                                    <label for="page_title" class="control-label">Current Image</label>
                                <div class="controls item">
                                	<img src="<?php echo PRODUCT_IMAGE.$product[0]->imagename;?>" style="height: 50px;" class="img-responsive" />
                                </div>
                            </div>
                            <?php
							}
                            ?>
                              <p>
                            <label>Select Keyword  </label>
                            <span class="formwrapper">
                                <select data-placeholder="Choose Keywords..." class="chzn-select" multiple="multiple" name="keywords[]" style="width:350px;" tabindex="4">
                                  <?php
                                  $keyword_arr = array();
								  if(count($product_keyword_ids)>0){
                                $keyword_arr  =  explode(',', $product_keyword_ids[0]['keyword_ids']);
								  }
                                  foreach ($keywords as $row) {
                                      
                                  
									  if (in_array($row['id'], $keyword_arr))
  {
 $select = "selected";
  }
else
  {
 $select = "";
  }
                                  ?>
                                  <option value="<?php echo $row['id']?>" <?php echo $select;?>><?php echo $row['keyword']?></option> 
                                  <?php 
								  }
                                  ?>
                                  </select>
                                 </span>
                                 </p>
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