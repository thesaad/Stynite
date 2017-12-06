<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Stynite-Product</title>
 

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 

</head>

<body class="loginpage" style="background: none;">

  
    <div class="container" style="color: #b84082;">
    	 <h3 style="font-size: 16pt;"> <?php echo $product['title'];?> </h3>
 <div class="row" style="min-height: 500px;">
<div class="col-sm-4">
	<br>
<a href="<?php echo get_retailerproduct_image($product['imagename']);?>"><img src="<?php echo get_retailerproduct_image($product['imagename']);?>" width="200" class="img-responsive" /></a>
</div>
<div class="col-sm-6">
	<br>
	                    <div class="par control-group " id="businessnamediv">

                            <label>Price:</label>
                            <span class="field">  <?php echo $product['price'];?> </span>
                        </div>
                         <div class="par control-group " id="businessnamediv">

                            <label>Description:</label>
                            <span class="field"> <?php echo $product['description'];?> </span>
                        </div>
                        <?php
                        if($product['quantity']<=0){
                        	?>
                        	<b style="color: red;">Out of stock</b>
                        	<?php
                        	
							
                        }else{
                        ?>
                        <a class="btn btn-warning btn-rounded" href="<?php echo  site_url("product/buy")."?id=".$_REQUEST['id']."&user_id=".$user_id; ?>">
<i class="iconfa-heart icon-white"></i>
Buy
</a>
<?php
}
?>
</div>
</div>                     
        <!--- end -->
        <center><p>&copy; Stynite. All Rights Reserved.</p></center>
    </div><!--loginpanelinner-->

 

</body>
</html>
