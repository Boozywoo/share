let addressShow = false;
let map, infoWindow, markerBus, currRend, service, directionsRenderer;
let pos, start, finish;

$(document).on('click', '.js_address_show', function(){
    addressShow = !addressShow;
    addressShow = !addressShow;
    if (addressShow) {
        pos = {
            lat: 53.9122225,
            lng: 27.4226339
        };
        map = new google.maps.Map(document.getElementById('address_map'), {
            center: pos,
            zoom: 15
        });
        infoWindow = new google.maps.InfoWindow;
        service = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                markerBus = new google.maps.Marker({
                    position: pos,
                    map: map,
                    icon: '/assets/admin/images/bus-icon.png',
                });
            });
        }

        const tourId = $(this).data('tour');
        const orderId = $(this).data('order');

        $.ajax({
            url: "/driver/routewaypoints",
            type: 'GET',
            dataType: 'json',
            data: {
                tour_id: tourId,
                order_id: orderId,
            },
            success: (data) => createRoute(data),
            error: (data) => createRoute(data),
        });

        function createRoute(waypoints) {
            if (!waypoints.length) {
                markerBus.setMap(map);
                infoWindow.open(map);
                map.setCenter(pos);
                console.log("no waypoints");
                return;
            }
            markerBus.setMap(null);
            markerBus = null;

            directionsRenderer.setMap(map);

            start = {lat: parseFloat(pos.lat), lng: parseFloat(pos.lng)};

            finish = {lat: parseFloat(waypoints[0].latitude),lng: parseFloat(waypoints[0].longitude)};

            service.route(
                {
                    origin: start,
                    destination: finish,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                  if (status === "OK") {
                    directionsRenderer.setDirections(response);
                  } else {
                    window.alert("Directions request failed due to " + status);
                  }
                }
            );
        }
        
        $("#address_map").show();
        $("#navigator").show();
    } else {
        $("#address_map").hide();
        $("#navigator").hide();
    }
});

$(document).on('click', '.js_address_show_street', function(){
    addressShow = !addressShow;
    if (addressShow) {
        const myLatLng = { lat: parseFloat($(this).attr('latitude')), lng: parseFloat($(this).attr('longitude')) };

        const map = new google.maps.Map(document.getElementById('station_map'), {
            center: myLatLng,
            zoom: 15
        });
        new google.maps.Marker({
            position: myLatLng,
            map,
        });
        
        $("#station_map").show();
    } else {
        $("#station_map").hide();
    }
});