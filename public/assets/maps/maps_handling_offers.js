
////////////////////////////////////////////// Begin map handling //////////////////////////////////////////////
    var markers = [];
    var hasMarker = false;

    //var addressDiv = $('#offer_address_1');
    //var nameDiv = $('#offer_name');
    var latInput = $('#offer_lat');
    var lngInput = $('#offer_lng');

    function initmap() {

        var myLatlng = {lat: 26.692268230028354, lng: 33.97870395829699};
        var map = new google.maps.Map(document.getElementById('map'), {zoom: 10, center: myLatlng});

        map.addListener('click', function (mapsMouseEvent) {

            if (hasMarker && markers[0]){                                           //return if had one marker
                //return;
                console.log('hasone');
                //addressDiv.val('');             //set address field to blank
                latInput.val('');                 // set lat input to blank
                lngInput.val('');                // set lng input to blank
                markers[0].setMap(null);											//remove the marker from the map
                markers = [];			//remove marker from array
                hasMarker = false;                                                //set marker flag to false
            }

            var yMarker = new google.maps.Marker({				//add marker to map
                position: mapsMouseEvent.latLng,
                map,
                title: "No Title Yet",            // assign offer name to marker title
                label: "label"
            });

            var newLocation = mapsMouseEvent.latLng;

            //addressDiv.val(newLocation);          // set address field to geos
            latInput.val(newLocation.lat());                 // set lat input hidden field
            lngInput.val(newLocation.lng());                // set lng input hidden field

            markers[0] = (yMarker);			// add marker location ro array
            hasMarker = true;

            console.log(markers[0]);

        });

    }



