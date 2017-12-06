<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Stynite-Product</title>
 
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.default.css" type="text/css" />

   <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

  <script type="text/javascript">
    // This identifies your website in the createToken call below
    Stripe.setPublishableKey('<?php echo STRIPE_PUBLISH_KEY;?>');
    var stripeResponseHandler = function(status, response) {
      var $form = $('#payment-form');
      if (response.error) {
      	alert("errp");  $("#error_body").show();
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('button').prop('disabled', false);
      } else {
        // token contains id, last4, and card type
        var token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        // and re-submit
        $form.get(0).submit();
      }
    };
    jQuery(function($) {
      $('#payment-form').submit(function(e) {
        var $form = $(this);
        // Disable the submit button to prevent repeated clicks
        $form.find('button').prop('disabled', true);
        Stripe.card.createToken($form, stripeResponseHandler);
        // Prevent the form from submitting with the default action
        return false;
      });
    });
    
    
   // $('#cc_number').change(function() {
   	function ccvaliadtion(){
    	//alert();
		  var foo = $('#cc_number').val().split("-").join(""); // remove hyphens
		  if (foo.length > 0) {
		    foo = foo.match(new RegExp('.{1,4}', 'g')).join("-");
		  }
		  $('#cc_number').val(foo);
		}
		//);

  </script>
</head>

<body class="loginpage" style="background: none;">

  
    <div class="container" style="color: #b84082;">
    	  <br>
 <div class="row" style="min-height: 500px;">
               
           <!-----price form--------------------------------------------->
            <div class="widget">
            <h4 class="widgettitle"><?php echo $product['title'];?> Payment</h4>
            <div class="widgetcontent">
                      <form action="<?php echo site_url('product/payment_submit'); ?>" method="post" class="stdform" id="payment-form" style="width:90% ;">
                          <ul class="alert alert-danger payment-errors"  id="error_body" style="background:#ffe6e6; border-color:red; color: red; ;padding-left: 30px;display:none;"></ul>
                                 
                          <p>
                        	<label>Price</label>
                            <span class="field"><input type="text" name="price" readonly  class="input-xlarge " placeholder="Price"  value="<?php echo $product['price'];?>"/></span>
                        </p> 	
                        
                         <input type="hidden" name="product_id" readonly  class="input-xlarge " placeholder="Price"  value="<?php echo $product['id'];?>"/>
              <input type="hidden" name="user_id" readonly  class="input-xlarge " placeholder="user"  value="<?php echo $_GET['user_id'];?>"/>
              
                        <p>
                        	<label>Card Number</label>
                            <span class="field">
                            	<input id="cc_number" onkeyup="ccvaliadtion()" type="text" data-stripe="number" class="input-xlarge " placeholder="Card no." /></span>
                        </p> 
                        <p>
                            <label>CVC</label>
                            <span class="field"><input type="text" data-stripe="cvc" class="input-small" placeholder="CVC" /></span>
                         
                        </p>
                        <p>
                            <label>Expiration (MM/YYYY)</label>
                            <span class="field"><input type="text" data-stripe="exp-month" class="input-small" placeholder="MM" /><span style="font-size: 25px; margin-left: 2px; margin-right: 2px;">/</span><input type="text" data-stripe="exp-year" class="input-small" placeholder="YYYY" /></span>
                           
                        </p>
                      
                           
                       
                        
                            <p class="stdformbutton">
                                    <button class="btn btn-primary">Pay </button>
                                     
                            </p>
                    </form>
            </div><!--widgetcontent-->
            </div><!--widget-->
            
                
           
           <!----------end of price form--------------------------------->
            
</div>                     
        <!--- end -->
        <center><p>&copy; Stynite. All Rights Reserved.</p></center>
    </div><!--loginpanelinner-->

 

</body>
</html>
