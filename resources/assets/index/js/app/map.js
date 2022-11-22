let addressShowFrom = false;
let addressShowTo = false;
var myPlacemark, myMap, lng, lat;

$(document).on('click', '.js_map-from', function() {
    addressShowFrom = !addressShowFrom;

    if (addressShowFrom) {
        $('#map-to').hide();
        if (myMap) {
            myMap.destroy();
            myMap = null;
            myPlacemark = null;
        }
        map('map-from', '#suggest_from');
        $('#map-from').show();
    } else {
        $('#map-from').hide();
    }
});

$(document).on('click', '.js_map-to', function() {
    addressShowTo = !addressShowTo;

    if (addressShowTo) {
        $('#map-from').hide();
        if (myMap) {
            myMap.destroy();
            myMap = null;
            myPlacemark = null;
        }
        map('map-to', '#suggest_to');
        $('#map-to').show();
    } else {
        $('#map-to').hide();
    }
});

function map (map, suggest) {

    if (!myMap) {
        myPlacemark, myMap = new ymaps.Map(map, {
            center: [53.902051, 27.553615],
            zoom: 9
        }, {
            searchControlProvider: 'yandex#search'
        });
    }

    var myGeocoder = ymaps.geocode($("#" + map).attr('city'));
    myGeocoder.then(
        function (res) {
            var firstGeoObject = res.geoObjects.get(0), coords = firstGeoObject.geometry.getCoordinates(),
                bounds = firstGeoObject.properties.get('boundedBy');
            myMap.setCenter([res.geoObjects.get(0).geometry.getCoordinates()[0], res.geoObjects.get(0).geometry.getCoordinates()[1]], 12, {
                checkZoomRange: true
            });
        },
    );

    myMap.events.add('click', function (e) {
        var coords = e.get('coords');

        if (myPlacemark) {
            myPlacemark.geometry.setCoordinates(coords);
        } else {
            myPlacemark = createPlacemark(coords);
            myMap.geoObjects.add(myPlacemark);
            myPlacemark.events.add('dragend', function () {
                getAddress(myPlacemark.geometry.getCoordinates());
            });
        }
        getAddress(coords);
    });

    function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {
            iconCaption: 'поиск...'
        }, {
            preset: 'islands#violetDotIconWithCaption',
            draggable: true
        });
    }

    function getAddress(coords) {
        myPlacemark.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);

            myPlacemark.properties.set({
                iconCaption: [
                    firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                    firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                ]
            });
            str = firstGeoObject.getLocalities().toString();
            if(str.split(',')[0].trim() == $("#" + map).attr('city').trim()) {
                $(suggest).val(firstGeoObject.getAddressLine());
            }
        });
    }  
}