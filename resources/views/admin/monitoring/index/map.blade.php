<div id="google_map" style="width: 100%; height: 35em;"></div>

<script defer>
var gmap, service;
var currRend;
var array = [];
var interval;
let waypointsCount = 0;
var marker;
var mapLoaded = false;
let lastPoint = null;
let points = [];
let busNumber = null;
let busPath = null;
let markerTimeout = null;

function initMap() {
    gmap = new google.maps.Map(document.getElementById('google_map'), {
        center: {
            lat: 53.9122225,
            lng: 27.4226339
        },
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    });

    $('#bus').val('');
    $('#route').val('');
    $('#time').val('');

    service = new google.maps.DirectionsService();
    mapLoaded = true;
}

async function setMarker(data) {
    function timeout() {
        setTimeout(() => {
            if (points.length) {
                markerTimeout = setTimeout(setMarker, 2);
            } else {
                timeout();
            }
        }, 100)
    }

    if (points.length) {
        const firstPoint = points.shift();
        const point = { lat: parseFloat(firstPoint.latitude), lng: parseFloat(firstPoint.longitude) };

        if (marker) {
            const animation = 150;
            const deltaLat = (point.lat - lastPoint.lat) / animation;
            const deltaLng = (point.lng - lastPoint.lng) / animation;
            let i = 0;

            const moveMarker = () => {
                lastPoint.lat += deltaLat;
                lastPoint.lng += deltaLng;

                marker.setPosition(new google.maps.LatLng(lastPoint.lat, lastPoint.lng));
                if (i !== animation) {
                    i++;
                    setTimeout(moveMarker, 3);
                } else {
                    lastPoint = point;
                    gmap.panTo(point);
                    timeout();
                }
            };

            moveMarker();
        }
    }
}

function createMarker() {
    if (points.length) {
        const firstPoint = points.shift();
        const point = {lat: parseFloat(firstPoint.latitude), lng: parseFloat(firstPoint.longitude)};

        marker = new google.maps.Marker({
            map: gmap,
            draggable: false,
            animation: google.maps.Animation.NONE,
            label: {
                text: busNumber,
                color: '#FFFFFF',
                fontSize: '10px'
            },
            title: busNumber,
            position: point,
            icon: '/assets/admin/images/bus-icon.png',
        });

        lastPoint = point;
        gmap.panTo(point);
    }
}

function showPath(request, service, renderer) {
    service.route(request, function(response, status) {
        if (status === google.maps.DirectionsStatus.OK) {
            renderer.setDirections(response);
        } else {
            console.log(status);
        }
    });
}

// Показ автобусов на маршруте
function showBusesOnPath(buses) {
        if (marker) {
            marker.setMap(null);
        }

        buses.map((bus, i) => {
            const point = {lat: parseFloat(bus.latitude), lng: parseFloat(bus.longitude)};

            marker = new google.maps.Marker({
                map: gmap,
                draggable: false,
                animation: google.maps.Animation.NONE,
                label: {
                    text: bus.name + ' ' + bus.number,
                    color: '#000',
                    fontSize: '10px'
                },
                title: bus.name + ' ' + bus.number,
                position: point,
                icon: '/assets/admin/images/bus-icon.png',
            });
        });
}

// Смена маршрута
function onRouteChange() {
    const routeId = document.getElementById('route').value;

    if (!mapLoaded) {
        return;
    }

    if (interval !== null) {
        clearInterval(interval);
    }

    if (routeId === '') {
        marker.setMap(null);
        return;
    }

    $.ajax({
        url: "/admin/monitoring/routewaypoints",
        type: 'GET',
        dataType: 'json',
        data: {
            route_id: routeId,
        },
        success: function (data) {
            if ($('#bus').val().length == 0) {
                showBusesOnPath(data.buses);
            }
            createRoute(data.waypoints)
        }
    });

    interval = window.setInterval(function() {
        $.ajax({
            url: "/admin/monitoring/driverswaypoints",
            type: 'GET',
            dataType: 'json',
            data: {
                route_id: routeId,
            },
            success: function (data) {
                if ($('#bus').val().length == 0) {
                    showBusesOnPath(data.buses);
                }
            }
        });
    }, 1000 * 4);


}

function onBusChange(waypoints) {

    if (!mapLoaded) {
        return;
    }

    const busId = document.getElementById('bus').value;

    if (marker) {
        marker.setMap(null);
    }
    markerTimeout = setTimeout(setMarker, 2);

    if (interval !== null) {
        clearInterval(interval);
    }

    points = [];

    if (busId === '') {
        marker.setMap(null);

        return;
    }

    requestBusPath(busId);

    requestBusWaypoints(busId).then(() => {
        let started = false;

        if (points.length) {
            createMarker();
        }

        $.get("/admin/monitoring/getbusspeed", {busId: busId}).done(function( data ) {
            $('input[name="speed"]').attr('placeholder', data.speed + " км/ч");
        });

        interval = window.setInterval(function() {
            requestBusWaypoints(busId).then(() => {
                if (points.length && !started) {

                    $.get("/admin/monitoring/getbusspeed", {busId: busId}).done(function( data ) {
                            $('input[name="speed"]').attr('placeholder', data.speed + " км/ч");
                        });

                    if (!marker) {
                        createMarker();
                    }

                    if (points.length === 2 && !started && marker) {
                        started = true;
                        setTimeout(setMarker, 2);
                    }
                }
            });
        }, 1000 * 3);

        $('select#route option:eq(1)').prop('selected', true);
    })
}

async function requestBusWaypoints(busId) {
    return $.ajax({
        url: "/admin/monitoring/driverwaypoints",
        type: 'GET',
        dataType: 'json',
        data: {
            bus_id: busId,
        },
        success: function(data) {
            if (data.bus_path.length) {
                busPath = data.bus_path;
            }
            if (data.waypoints.length) {
                points.push(data.waypoints.shift());
                busNumber = data.busNumber;
            }
        }
    });
}

function requestBusPath(busId) {
    return $.ajax({
        url: "/admin/monitoring/driverwaypoints",
        type: 'GET',
        dataType: 'json',
        data: {
            bus_id: busId,
        },
        success: function(data) {
            if (data.bus_path.length) {
                createRoute(data.bus_path)
            }
        }
    });
}

// Создание маршрута
function createRoute(waypoints) {
    if (!currRend) {
        currRend = buildRenderer();
    }

    if (!waypoints.length) {
        console.log("no waypoints");
        return;
    }

    const request = waypointsToRequest(waypoints);
    showPath(request, service, currRend);
}

function buildRenderer() {
    if (currRend) {
        currRend.setMap(null);
    }
    const renderer = new google.maps.DirectionsRenderer();

    currRend = renderer;
    renderer.setMap(gmap);

    return renderer;
}

function updateFilter(data) {
    const $filter = $('.js_filter-wrapper');

    $filter.html(data.view);
}

// Создание точек маршрута
function waypointsToRequest(waypoints) {
    const start = waypoints[0];
    const end = waypoints[waypoints.length - 1];
    const points = [];

    waypoints.forEach((waypoint) => {
        points.push({
            location: waypoint.latitude + ', ' + waypoint.longitude,
            stopover: false
        });
    });

    return {
        origin: start.latitude + ', ' + start.longitude,
        waypoints: points,
        optimizeWaypoints: true,
        destination: end.latitude + ', ' + end.longitude,
        travelMode: "DRIVING"
    };
}
</script>

<script defer>
    let waitForJQuery = setInterval(function () {
        if (typeof $ !== 'undefined') {
            $(document).ready(function() {
                initMap();
                $(document).on('change', '.js_map-filter :input', searchFilter);
                $(document).on('submit', '.js_map-filter', searchFilter);

                $(document).on('change', '#route', onRouteChange);

                function searchFilter(e) {
                    e.preventDefault();
                    $('.wrapper-spinner').show();

                    const $form = $('.js_map-filter');

                    history.pushState({}, '', '?' + $form.serialize());
                    $form.ajaxSubmit({
                        success: (data) => {
                            $('.wrapper-spinner').hide();
                            updateFilter(data);
                        }
                    });
                    return false;
                }
            });

            clearInterval(waitForJQuery);
        }
    }, 100);

</script>
