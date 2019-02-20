function initMap() {
//full screen option
     var fullscreen = document.querySelector('#optionContener input.fullscreen');
	 fullscreen = (fullscreen.checked == true) ? true : false;	
	  //disDefUI option
	var disDefUI = document.querySelector('#optionContener input.disDefUI');
	disDefUI = (disDefUI.checked == true) ? true : false;
	//draggable option
	var dragg = document.querySelector('#optionContener input.draggg');
    dragg = (dragg.checked == true) ? true : false;
	//map type option
	var mapT = document.querySelector('#optionContener select.mapt');
	mapT = mapT.options[mapT.selectedIndex].value;
	
	
var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: {lat: -34.397, lng: 150.644},
		  scrollwheel:true,
		  disableDefaultUI: disDefUI,
		  draggable: dragg,
          mapTypeId: mapT,
		  fullscreenControl: fullscreen
		 });
		
        var geocoder = new google.maps.Geocoder();
		geocodeAddress(geocoder, map);
       
      }

      function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('address_field').value;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location,
			  title: 'Click this Marker'//add this in wp options
            });
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
		
      }	