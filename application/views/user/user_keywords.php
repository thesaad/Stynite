
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
          <h4 class="modal-title">Keywords for recommendation</h4>
        </div>
        <div class="modal-body">
        	 <form action="" method="post"   id="form1"    >
        	 	<table class="table table-bordered">
        	 		<thead>
        	 		<tr>
        	 			<th>Keyword</th>
        	 			<th style="width:20%">By Admin</th>
        	 			<th style="width:20%">By Default</th>
        	 			<th style="width:20%">Action</th>
        	 		</tr>
        	 		</thead>
        	 		<tbody id="tb_recommendation">
        	 		<tr>
        	 			<td>Hey</td>
        	 			<td>df</td>
        	 			<td>df</td>
        	 			<td>df</td>
        	 		</tr>
        	 		</tbody>

</table>
      
                          
                          </form>
        </div>
        <div class="modal-footer">
        	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    
    </div>
  </div>
  <script>
 
  function user_recommendation(id)
  {
  	 $("#user_id").val( id );
   $.ajax({type:'POST',
                    url: '<?php echo site_url("user/user_recommendation_keywords");?>', 
                  data:{"user_id":id }, 
                  success:  function(data){
                  	$("#tb_recommendation").empty();
                  	$("#tb_recommendation").html(data);
                  	 
                  }
                });

  }
  	    function submit_action(id,user_id,data_id)
        {
          admin = defaultval = 0;
       
 
           keyword = $("#keyword_"+data_id).val();
           if(document.getElementById('admin_'+data_id).checked) {
           	admin =   1;
           }
          if(document.getElementById('default_'+data_id).checked) {
          	  defaultval = 1;
          }
           
         
            
                  
                var querystr = $("#form1").serialize();//+"&is_ajax=1";
        		$.ajax({
        			url		:	"<?php echo site_url('user/submit_recommendation_keys'); ?>",
        			type	:	"POST",
        			data	:	{"keyword":keyword,"admin":admin,"defaultval":defaultval,"id":id,"user_id":user_id},
                beforeSend  :   function(){
                                    $(".loader").show();
                                },   
        			success	:	function(data){
        				if(data==1){
        					$("#r_submit"+data_id).addClass("btn-success");
        				}else{
        					$("#r_submit"+data_id).addClass("btn-danger");
        				}
        				//user_recommendation(user_id)
        			}
        			          
        		});
            
            
        }
  </script>