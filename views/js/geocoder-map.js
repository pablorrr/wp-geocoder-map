function initMap() {
    //full screen option
	var fullscreen = (object_name.fullscreen_field_id == null) ? false : true;
	//disDefUI option
	var disDefUI = (object_name.disable_defaultUI_field_id == null) ? true : false;
	//draggable option
	var dragg = (object_name.draggable_field_id == null) ? false : true;
	//map type option
	var mapT = object_name.map_type_field_id;



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
	const address = object_name.address_field_id;
	geocoder.geocode({'address': address}, function(results, status) {
		if (status === 'OK') {
			resultsMap.setCenter(results[0].geometry.location);
			new google.maps.Marker({
				map: resultsMap,
				position: results[0].geometry.location,
				title: 'Click this Marker'//add this in wp options
			});
		} else {
			alert('Geocode was not successful for the following reason: ' + status);
		}

	});

}
console.log( object_name.address_field_id);
console.log( object_name.fullscreen_field_id);
console.log( object_name.disable_defaultUI_field_id);
console.log( object_name.draggable_field_id);
console.log( object_name.map_type_field_id);