var globalMap;

function map() {
    if ($('#map').length && !$('#map ymaps').length) {
        var myMap;
        var myPlacemark;
        var $lng = $('input[name="longitude"]');
        var $ltd = $('input[name="latitude"]');
        if (!$lng.length)	{
            $lng = $('input[name="garage_latitude"]');
            $ltd = $('input[name="garage_longitude"]');
        }
        var lng = $lng.val();
        var ltd = $ltd.val();
        if (!(lng > 0)) lng = 27.553576;
        if (!(ltd > 0)) ltd = 53.901717;
        if(typeof ymaps == 'undefined'){
            return;
        }
        ymaps.ready(function () {
            var center = [ltd, lng];

            myMap = new ymaps.Map("map", {
                center: center,
                controls: ["zoomControl", "searchControl"],
                zoom: 12
            });

            myPlacemark = createPlacemark(center);
            myMap.geoObjects.add(myPlacemark);
            myPlacemark.events.add('dragend', function () {
                setCoords(myPlacemark.geometry.getCoordinates());
            });

            setCoords(center);

            myMap.events.add('click', function (e) {
                var coords = e.get('coords');
                myPlacemark.geometry.setCoordinates(coords);
                setCoords(coords);
            });

            function setCoords(coords) {
                $lng.val(coords[1]);
                $ltd.val(coords[0]);
            }

            function createPlacemark(coords) {
                return new ymaps.Placemark(coords, {}, {
                    preset: 'islands#violetDotIconWithCaption',
                    draggable: true
                });
            }

            globalMap = myMap;
        });
    }
}

// if (typeof ymaps !== 'undefined') {

map();

window.map = map;

$(document).on('change', 'select#street_id', function () {

    ymaps.geocode($('#city_id option:selected').text() + ', ' + $('#street_id option:selected').text(), {
        results: 1
    }).then(function (res) {
        var firstGeoObject = res.geoObjects.get(0),
            bounds = firstGeoObject.properties.get('boundedBy');
        firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
        firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());

        globalMap.geoObjects.add(firstGeoObject);
        globalMap.setBounds(bounds, {
            checkZoomRange: true
        });

    });


});
// }
