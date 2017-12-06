 <!DOCTYPE html>
<html lang="en">
<head>
  <title>Stynite</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script>
  	function submit_contact()
  	{
  			 
  		var error=0;
  		
  		if($("#your-name").val()==""){
  			
  			$("#your-name").css("border","1px solid red");
  			error=1;
  		}
  		if($("#your-email").val()==""){
  			
  			$("#your-email").css("border","1px solid red");
  			error=1;
  		}
  		 
  		if(error==0){
  				$("#your-email").css("border","1px solid green");
  				$("#your-name").css("border","1px solid green");
  		  var querystr = $("#contactfrm").serialize(); 
  		$.ajax({type:'POST',
                    url: '<?php echo site_url('page/submit_contact'); ?>', 
                  data:querystr,                
                  success: function (data){
                  	 
                  	$(".success").show().delay(3000).fadeOut();
                  }
                });
               }

  	}
  </script>
  <style>
  	.success{
  		padding:5px;  		
  		text-align:center;
  		border: 1px solid green;
  		display:none;
  	}
  </style>
</head>
<body class="container">
 
 
 <h3>Contact</h3>
  <p >Whether you’re looking to get more info on Stynite, hear more about what and why we do, or simply looking to reach out, fill out the contact form and we’ll get back to you as quickly as we can!</p>
 <div class="row success">Thank you for your message. It has been sent.</div>
  <form  id="contactfrm" action="">
  	<div class="form-group">
  <label for="usr">Your Name*</label>
  <input type="text" class="form-control" name="your-name" id="your-name">
</div>
  <div class="form-group">
    <label for="email">Your Email*</label>
    <input type="email" class="form-control" name="your-email" id="your-email">
  </div>
  	<div class="form-group">
  <label for="usr">Company</label>
  <input type="text" class="form-control" name="your-subject" id="your-subject">
</div>
 <div class="form-group">
  <label for="comment">Your Message</label>
  <textarea class="form-control" rows="5" name="your-message" id="your-message"></textarea>
</div>
  
  <button type="button" onclick="submit_contact()" class="btn btn-default">Send</button>
</form>
<br>
 <div class="row success">Thank you for your message. It has been sent.</div>
<br>
</body>
</html>