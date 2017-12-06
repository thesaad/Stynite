<html>
<style>
.gllpMap	{ width: 750px; height: 350px; }
</style>
  <head>
    <title>Add Location</title>
      <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 
   
       <script src="<?php echo base_url();?>js/jquery-gmaps-latlon-picker.js"></script>
        <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
       <script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyBZYlLS4Z8AS-dYzjQdSztum_YSmH9nXRA"></script>  
 
  </head>
  <body>
  <fieldset class="gllpLatlonPicker">
		<input type="text" class="gllpSearchField" id="gllpSearchField"   size ="50" width="25px" value="<?php echo $_GET['address']; ?>"/>
		<input type="button" class="gllpSearchButton" value="search" onchange="autofill_clicked()" id="search_btn"/>
		<br/><br/>
		<div class="gllpMap" id="map-canvas">Google Maps</div>
		<br/>
		Latitude:&nbsp;
			<input type="text" class="gllpLatitude" id="gllpLatitude" value="<?php echo $_GET['latitude']; ?>" />&nbsp;
			Longitude:&nbsp;
			<input type="text" class="gllpLongitude" id="gllpLongitude" value="<?php echo
$_GET['longitude']; ?>" />&nbsp;
		    <input type="hidden" class="gllpZoom" value="3"/>
		<input type="submit" onClick="getaddress()" value="Click to add" id="addlat"/>
        
		<br/>
	</fieldset>
    <script type="text/javascript">
    
  function getaddress()
    {
        var lat = $('.gllpLatitude').val();
        var lon = $('.gllpLongitude').val();
        var address=$('.gllpSearchField').val();
    	window.opener.document.getElementById('lat').value=lat;
    	window.opener.document.getElementById('lng').value=lon;
        window.close();
	}
   /* function getaddress1()
    {
        var lat=$('#gllpLatitude').val();
        var lng=$('#gllpLongitude').val();
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(lat, lng);
        //alert(latlng);
    	geocoder.geocode({'latLng': latlng}, function(results, status) {  
    	   //alert(status);
    		if (status == google.maps.GeocoderStatus.OK && results[1]) {
          		$(".gllpSearchField").val(results[1].formatted_address);
                $(".gllpZoom").val(10);
                map.setZoom(parseInt($(".gllpZoom").val()) );
          	} else {
          	 alert('Address not found for the given latitude and longitude');
          		$(".gllpSearchField").val('address not found');
          	}
		});
  }*/
    
       jQuery(document).ready(function() 
      { $('input[type=button]').click(); 
    $('#search_btn').click();
      
    });
   
    </script>
  </body>
</html>





