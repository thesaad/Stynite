<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>Product Preview</title>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />

  <style type="text/css" media="screen">
    * { margin: auto; }
    body { margin: 20px 0; background: #abc; color: #111; font-family: Helvetica, Arial, Verdana, 'Lucida Grande', sans-serif; }
    h1, h3, p { text-align: center; }
    div.example { padding: 20px; margin: 10px auto; background: #bcd; width: 750px; }
    div.example h3 { margin-bottom: 10px; }
    ul, ol { padding: 0; }
    #list { width: 50px; height: 150px; overflow-y: scroll; }
    #images { width: 600px; height: 550px; overflow-x: hidden; text-align: center; list-style: none; }
    .endless_scroll_loader { position: fixed; top: 10px; right: 20px; }
  </style>

  <script type="text/javascript" src="<?php echo base_url();?>js/scroll/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>js/scroll/jquery.endless-scroll.js"></script>

  <script type="text/javascript" charset="utf-8">
    $(function() {
      $('#list').endlessScroll({
        pagesToKeep: 10,
        fireOnce: false,
        insertBefore: "#list div:first",
        insertAfter: "#list div:last",
        content: function(i, p) {
          console.log(i, p)
          return '<li>' + p + '</li>'
        },
        ceaseFire: function(i) {
          if (i >= 10) {
            return true;
          }
        },
        intervalFrequency: 5
      });

      $('#images').scrollTop(101);
      var images = $("ul#images").clone().find("li");
      $('#images').endlessScroll({
        pagesToKeep: 5,
        inflowPixels: 100,
        fireDelay: 10,
        content: function(i, p, d) {
          console.log(i, p, d)
          return images.eq(Math.floor(Math.random()*8))[0].outerHTML;
        }
      });
    });
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
 <span><br><p><a style="cursor: pointer; background-color: green; color:white; padding: 5px;" onclick="openWindowMap()">  Pick Location from map  </a>
          </p><br>                          
	<div class="control-group">											
											<label class="control-label" for="username">Latitude</label>
											<div class="controls">
												<input type="text" class="span4" name="lat" id="lat" value="55.3781">
												 	</div> <!-- /controls -->				
										</div> 
										
										<div class="control-group">											
											<label class="control-label" for="username">Longitude</label>
											<div class="controls">
												<input type="text" class="span4" name="lng" id="lng" value="-3.4360">
												 	</div> <!-- /controls -->				
										</div> 
										<div class="control-group">											
											 <div class="controls">
												<input type="button" class="span4" name="lng" id="lng" value="GO">
												 	</div> <!-- /controls -->				
										</div> 
									</div>
									</div>
 
  <div class="example">
    <h3>Products List:</h3>
    <ul id="images">
      <li><img src="img/grass-blades.jpg" width="580" height="360" alt="Grass Blades" /></li>
      <li><img src="img/stones.jpg" width="580" height="360" alt="Stones" /></li>
      <li><img src="img/sea-mist.jpg" width="580" height="360" alt="Sea Mist" /></li>
      <li><img src="img/pier.jpg" width="580" height="360" alt="Pier" /></li>
      <li><img src="img/lotus.jpg" width="580" height="360" alt="Lotus" /></li>
      <li><img src="img/mojave.jpg" width="580" height="360" alt="Mojave" /></li>
      <li><img src="img/lightning.jpg" width="580" height="360" alt="Lightning" /></li>
      <li><img src="img/flowing-rock.jpg" width="580" height="360" alt="Grass Blades" /></li>
    </ul>
  </div>

  <div class="example">
    <p>Copyright &copy; <a href="">Stynite</a></p>
  </div>

</body>
<script>
$( document ).ready(function() {
    ///ebayproduct(1);
});
	function ebayproduct(page)
	{
		lat = $("#lat").val();
		lng = $("#lng").val();
		$.ajax({type:'POST',
                    url: '<?php echo API_BASE;?>productByKeywordEbay', 
                  data:{"lat": lat, "lng":lng,"page":page,"keyword":"jeans"}, 
                  success: function(data){
                  	alert(data);
                  }
                });

	}
	
	
</script>
</html>