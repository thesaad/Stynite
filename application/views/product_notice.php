
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

   <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Send Notification</h4>
        </div>
        <div class="modal-body">
        	 <form action="" method="post"   id="form1"    >
            <p>
                            <label>Message</label>
                            <span class="field">
                            
                            	
                            	<textarea cols="80" rows="5" id="message" name="message"  placeholder="Message"  class="span5 validate[required]" ></textarea></span> 
                        </p>
                        <input type="hidden" name="type" value="PRODUCT_NOTICE" />
                         <input type="hidden" name="product_id" id="product_id" value=""/>
                          </form>
        </div>
        <div class="modal-footer">
        	 <button type="button" onclick="submit_form()" class="btn btn-primary" data-dismiss="modal">Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    
    </div>
  </div>
  <script>
 
  function product_id(id)
  {
  	 $("#product_id").val( id );
   
  }
  	    function submit_form( )
        {
          
            
                  
                var querystr = $("#form1").serialize();//+"&is_ajax=1";
        		$.ajax({
        			<?php if($this->session->userdata('is_admin')!='1'){?>
        			url		:	"<?php  echo site_url('notification/request_notice'); ?>",
        			<?php }else{
        				?>
        			url		:	"<?php  echo site_url('notification/push_notify'); ?>",	
        				<?php
        			}?>
        			type	:	"POST",
        			data	:	querystr,
                beforeSend  :   function(){
                                    $(".loader").show();
                                },   
        			success	:	function(data){
        			         data=$.parseJSON(data)
        			        
                             
                			if(data.IsValid==0){
                			 
                                    $('#form1').validationEngine('showPrompt', 'Operation Fail', 'error');
                                    
                				}else {
                                   $('#form1').validationEngine('showPrompt', 'Notification Sent ', 'error');
                				}
                			}
        		});
            
            
        }
  </script>