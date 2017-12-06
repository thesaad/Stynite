<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>Product Preview</title>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />

  <style type="text/css" media="screen">
    * { margin: auto; }
    body { margin: 20px 0; background: #abc; color: #111; font-family: Helvetica, Arial, Verdana, 'Lucida Grande', sans-serif; }
    h1, h3, p { text-align: center; }
    div.example { padding: 20px; margin: 10px auto; background: #ebc6db; width: 750px; }
    div.example h3 { margin-bottom: 10px; }
    ul, ol { padding: 0; }
    #list { width: 50px; height: 150px; overflow-y: scroll; }
    #images { min-width: 600px; height: 550px; overflow-x: hidden; text-align: center; list-style: none; }
    .endless_scroll_loader { position: fixed; top: 10px; right: 20px; }
  </style>
<link rel="stylesheet" href="<?=base_url();?>css/bootstrap.min.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.9.1.min.js"></script>
 
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>dist/imagezoom/hover_zoom_v3.min.js"></script>


  <script type="text/javascript" charset="utf-8">
  /**** comment for some time
    $(function() {
    var limit=10;
    $('#images').endlessScroll({
        pagesToKeep: 10,
        fireOnce: false,
        insertAfter: "#list div:last",
        content: function(i, p) {
          console.log(i, p)
          $responsedata	= ebayproduct(i);
          return true;
        },
        ceaseFire: function(i) {
          if (i >= 2) {
            return true;
          }
        },
        intervalFrequency: 20
      });
      */
     /* $('#images').scrollTop(101);
      var images = $("ul#images").clone().find("li");
      $('#images').endlessScroll({
        pagesToKeep: 5,
        inflowPixels: 100,
        fireDelay: 10,
        content: function(i, p, d) {
        $responsedata	= ebayproduct(i);
          console.log(i, p, d)
          
          $("#images ul").append('<li>'+$responsedata+'</li>');

          return true;
        }
      });
    });*/
       function openWindowMap(){
       
        var address = $('#address').val();
        var latitude = $("#lat").val();
        var longitude = $("#lng").val();
       javascript:window.open('<?php echo site_url('admin/map')?>?latitude='+latitude+'&address='+address+'&longitude='+longitude, 'Add Location', 'width=850,height=500,scrollbars=yes,location=no,di rectories=no,status=no,menubar=no,toolbar=no,resiz able=yes');
        //javascript:window.open('map.php?address='+address);
        //return false;
}
  </script>
</head>

<body>
  <h1>Product Preview</h1>
  <div style="display: table;">
     <div style="margin: 0 auto;"> 
  <br> <br>            
<div class="table-responsive">
  <table class="table">
  <tr>
  	<th>Pick Location</th>
    <th>Latitude</th>
    <th>Longitude</th>
    <th>Affiliate Keyword</th>
    <th>Stynite Keyword</th>
    <th> </th>
  </tr>
  <tr>
  	<td><a style="cursor: pointer; background-color: green; color:white; padding: 5px;" onclick="openWindowMap()">  Pick Location from map  </a></td>
    <td><input type="text"  name="lat" id="lat" value="55.3781">
												</td>
    <td><input type="text"  name="lng" id="lng" value="-3.4360">
												</td>
    <td><input type="text"  name="a_keyword" id="a_keyword" value="<?php echo (isset($_REQUEST['a_keyword'])?$_REQUEST['a_keyword']:"")?>">
												</td>
																								</td>
    <td>
    	<input type="text"  name="s_keyword" id="s_keyword" value="<?php echo (isset($_REQUEST['s_keyword'])?$_REQUEST['s_keyword']:"")?>">
												
												</td>
												  <td>
    	<input type="button" onclick="getProducts()"  name="submit" id="submit" value="Go">
												
												</td>
  </tr>
          </table> 
          </div>             
 
									</div>
									</div>
 
  <div class="example">
    <h3>Products List:</h3>
    <ul id="images">
      
      
    </ul>
  </div>

  <div class="example">
    <p>Copyright &copy; <a href="">Stynite</a></p>
  </div>

</body>
<script>
$( document ).ready(function() {
    getProducts();
});
function getProducts()
{
	$("#images").html("");
	productByKeywordLinkshare(1);
	productByKeywordAmazon(1);
	ebayproduct(1);
	productByKeywordCj(1);
}

function productByKeywordLinkshare(page)
	{
		a_keyword = $("#a_keyword").val();
		s_keyword = $("#s_keyword").val();
		lat = $("#lat").val();
		lng = $("#lng").val();
		$.ajax({type:'POST',
                    url: '<?php echo base_url().API_BASE;?>productByKeywordLinkshare', 
                  data:{"lat": lat, "lng":lng,"page":page,"keyword":a_keyword,"web_keyword":s_keyword}, 
                  success: function(data){
                  	 
                  	 $(data.data.linkshare).each(function(index, value) {
                  	 	rightdata(value);
                  });
                  	return data;
                  }
                });

	}
	function productByKeywordAmazon(page)
	{
		a_keyword = $("#a_keyword").val();
		lat = $("#lat").val();
		lng = $("#lng").val();
		$.ajax({type:'POST',
                    url: '<?php echo base_url().API_BASE;?>productByKeywordAmazon', 
                  data:{"lat": lat, "lng":lng,"page":page,"keyword":a_keyword}, 
                  success: function(data){
                  	 
                  	 $(data.data.amazon).each(function(index, value) {
                  	 	rightdata(value);
                  });
                  	return data;
                  }
                });

	}
	function productByKeywordCj(page)
	{
		a_keyword = $("#a_keyword").val();
		lat = $("#lat").val();
		lng = $("#lng").val();
		$.ajax({type:'POST',
                    url: '<?php echo base_url().API_BASE;?>productByKeywordCj', 
                  data:{"lat": lat, "lng":lng,"page":page,"keyword":a_keyword}, 
                  success: function(data){
                  	 
                  	 $(data.data.cj).each(function(index, value) {
                  	 	rightdata(value);
                  });
                  	return data;
                  }
                });

	}
	function ebayproduct(page)
	{
		a_keyword = $("#a_keyword").val();
		lat = $("#lat").val();
		lng = $("#lng").val();
		$.ajax({type:'POST',
                    url: '<?php echo base_url().API_BASE;?>productByKeywordEbay', 
                  data:{"lat": lat, "lng":lng,"page":page,"keyword":a_keyword}, 
                  success: function(data){
                  	 
                  	 $(data.data).each(function(index, value) {
                  	 	rightdata(value);
                  });
                  	return data;
                  }
                });

	}
	function rightdata(value)
	{
				html='<div class="row" style="border: 2px solid #fff;">\
           <div class="col-md-4">\<a href="'+value.strLink+'">\
                        <img class="img-thumbnail" id="btn_favorite" alt="" height="80" width="80" style="border: 2px solid #fff;"  src="'+value.strImageURL+'">\
                            </a>            </div>\
           <div class="col-md-8">\<a href="'+value.strLink+'"><p style="margin: 0;">'+value.strProductName+'</p></a>\
           	<p>'+value.strStorePrice+'</p>\              	<p>'+value.strStoreName+'</p>\
        </div>\ </div>\ ';
    $("#images").append('<li>'+html+'</li>');

	}
	
</script>
</html>