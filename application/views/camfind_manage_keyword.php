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
            <li><a href="<?php echo site_url('admin/index'); ?>"><i class="iconfa-home"></i></a> </span><span class="separator"></span> Camfind Keyword </h1></li>
            
        </ul>
  <?php


    $exist_image='new';

$exist_file='new';
?>      
        <div class="pageheader">
           
            <div class="pageicon"><span class="iconfa-camera"></span></div>
            <div class="pagetitle">
                 <h1><?php echo (isset($_GET['id']))?'Edit':'Add';?> Keyword </h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="widgetbox box-inverse">
                <h4 class="widgettitle"><?php echo (isset($_GET['id']))?'Edit':'Add';?> Keyword <span class="back_event"><a href="javascript:void(0);" onclick="history.back()"><i class=" iconfa-arrow-left"></i> Back</a></span></h4>
                <div class="widgetcontent wc1">
                    <form action="" method="post" class="stdform" id="form1" novalidate="novalidate">
                          
                            <input type="hidden" value="<?=(isset($keyword[0]['id']))?$keyword[0]['id']:"";?>" name="id"  />
            
                            
           
              <div class="par control-group">
                                    <label for="page_title" class="control-label">Find Keyword</label>
                                <div class="controls item"><input type="text" required="required" value="<?=(isset($keyword[0]['find_keyword']))?$keyword[0]['find_keyword']:"";?>" class="input-xxlarge" id="f_keyword" name="f_keyword" placeholder="Keyword" /></div>
                            </div>
                <div class="par control-group">
                                    <label for="page_title" class="control-label">Custom Keyword</label>
                               <input type="text" name="c_keyword" value="<?=(isset($keyword[0]['custom_keywords']))?$keyword[0]['custom_keywords']:"";?>" data-role="tagsinput" />   </div>                   
                            
                            
      <div class="par control-group">
                                    <label for="page_title" class="control-label">Affiliate Keyword</label>
                                <div class="controls item"><input type="text"   value="<?=(isset($keyword[0]['affiliate_keyword']))?$keyword[0]['affiliate_keyword']:"";?>" class="input-xxlarge" id="a_keyword" name="a_keyword" placeholder="Affiliate Keyword" />
                                	 
                                </div>
                           
                            </div>
      
      <div class="par control-group">
                                    <label for="page_title" class="control-label">Stynite Keyword</label>
                                <div class="controls item"><input type="text"  value="<?=(isset($keyword[0]['stynite_keyword']))?$keyword[0]['stynite_keyword']:"";?>" class="input-xxlarge" id="s_keyword" name="s_keyword" placeholder="Stynite Keyword" />
                                	
                                </div>
  
                            </div>
                       
      
                            <p class="stdformbutton">
                                   <a target="blank" class="btn btn-danger btn-rounded product-preview" >
<i class="iconfa-eye-open icon-white"></i>
Preview
</a>
                                    <button type="button" onclick="form_submit()" class="btn btn-primary">Submit </button>
                                    <button class="btn" type="reset">Reset </button>
                                   
                            </p>
                    </form>
                    
                </div><!--widgetcontent-->
            </div>     
            </div>
        </div>
</div>
</body>

<!--- input tab--->
   <link rel="stylesheet" href="<?=base_url();?>dist/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/themes/github.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular.min.js"></script>
    <script src="<?=base_url();?>dist/bootstrap-tagsinput.min.js"></script>
    <script src="<?=base_url();?>dist/bootstrap-tagsinput/bootstrap-tagsinput-angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/js/rainbow.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/js/language/generic.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/js/language/html.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rainbow/1.2.0/js/language/javascript.js"></script>
  
<!---- eeee--->
<script type="text/javascript" src="<?=base_url();?>js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>js/ui.spinner.min.js"></script>

<script type="text/javascript">
var $ = jQuery.noConflict();


 $(".product-preview").click(function(){
 	a_keyword = $("#a_keyword").val();
 	 	s_keyword = $("#s_keyword").val();
$(this).target = "_blank";
window.location.href ='<?php echo site_url("admin/product_preview");?>'+'?a_keyword='+a_keyword+'&s_keyword='+s_keyword; 
return false;     
});
    
 function form_submit()
 {
 	 
 	 var querystr = $("#form1").serialize();
 	 $.ajax({type:'POST',
                    url: '<?=site_url('admin/save_camkeyword');?>', 
                  data:querystr, 
                  success: function(data){
                  	window.location.href="<?=site_url('admin/camfindkeywords');?>";
                  }
                });

 }
    
    
</script>
</html>