
    ////////////////////////////////////////////// Begin map handling //////////////////////////////////////////////
    var latInput = $('#offer_lat');
    var lngInput = $('#offer_lng');
    var markers = [];
    var hasMarker = true;

    var mapID = $('#map').attr('id');
    var mapDiv = $('#map');

    //var addressDiv = $('#offer_address_en');
    //var nameDiv = $('#offer_name');

    function initmap() {

        var myLatlng = {lat: parseFloat($(latInput).val()), lng: parseFloat($(lngInput).val()) };
        var map = new google.maps.Map(document.getElementById('map'), {zoom: 10, center: myLatlng});

        var wholeNewMarker = new google.maps.Marker({				//add the marker
            position: myLatlng,
            map,
            title: 'new Totle'
        });
        markers[0] = wholeNewMarker;
        var hasMarker = true;

        /////////////////////////////////////////// Configure the click listener. ////////////////////////////////
        map.addListener('click', function (mapsMouseEvent) {
            var newLocation = mapsMouseEvent.latLng;

            if (hasMarker && markers[0]){                                           //return if had one marker
                //return;
                console.log('hasone');
                hasMarker = false;                                                //set marker flag to false
                //addressDiv.val('');             //set address field to blank
                latInput.val('');                 // set lat input to blank
                lngInput.val('');                // set lng input to blank
                markers[0].setMap(null);											//remove the marker from the map
                markers = [];			//remove marker from array
            }
            var yMarker = new google.maps.Marker({				//add marker to map
                position: newLocation,
                map,
                title: "No Title Yet",            // assign offer name to marker title
                label: "label"
            });

            //addressDiv.val(newLocation);          // set address field to geos
            latInput.val(newLocation.lat());                 // set lat input hidden field
            lngInput.val(newLocation.lng());                // set lng input hidden field

            markers[0] = (yMarker);			// add marker location ro array
            hasMarker = true;

            console.log(markers[0]);

        });

    }


