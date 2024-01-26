
/* <div class="map" data-lng="5.3344145" data-lat="50.9294431" data-zoom="15"></div> */

var isRefuga = document.body.getAttribute("data-base") == "refuga";

export default function () {
    var mapStyles = [
        {
            featureType: "administrative.province",
            elementType: "all",
            stylers: [{ visibility: "off" }],
        },
        {
            featureType: "landscape",
            elementType: "all",
            stylers: [{ saturation: -100 }, { lightness: 65 }, { visibility: "on" }],
        },
        {
            featureType: "poi",
            elementType: "all",
            stylers: [{ saturation: -100 }, { lightness: 51 }, { visibility: "simplified" }],
        },
        {
            featureType: "road.highway",
            elementType: "all",
            stylers: [{ saturation: -100 }, { visibility: "simplified" }],
        },
        {
            featureType: "road.arterial",
            elementType: "all",
            stylers: [{ saturation: -100 }, { lightness: 30 }, { visibility: "on" }],
        },
        {
            featureType: "road.local",
            elementType: "all",
            stylers: [{ saturation: -100 }, { lightness: 40 }, { visibility: "on" }],
        },
        {
            featureType: "transit",
            elementType: "all",
            stylers: [{ saturation: -100 }, { visibility: "simplified" }],
        },
        {
            featureType: "transit",
            elementType: "geometry.fill",
            stylers: [{ visibility: "on" }],
        },
        {
            featureType: "water",
            elementType: "geometry",
            stylers: [{ hue: "#ffff00" }, { lightness: -25 }, { saturation: -97 }],
        },
        {
            featureType: "water",
            elementType: "labels",
            stylers: [{ visibility: "on" }, { lightness: -25 }, { saturation: -100 }],
        },
    ];

    function getMapOptions(pos, zoom) {
        var result = {
            // How zoomed in you want the map to start at (always required)
            zoom: zoom,
            draggable: !("ontouchend" in document),
            scrollwheel: false,
            //scrollwheel: !("ontouchend" in document),
            streetViewControl: false,
            mapTypeControl: false,
            styles: mapStyles,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.LEFT_BOTTOM,
            },
        };

        if (pos) {
            result.center = pos;
        }

        return result;
    }

    function getMarker(map, pos, width, height, title, id) {
        const base = document.body.getAttribute("data-base")
            ? "/" + document.body.getAttribute("data-base")
            : "";
        console.log(base);

        return new google.maps.Marker({
            position: pos,
            map: map,
            title: title,
            __id: id,
            icon: {
                url: base + "/img/map-marker.svg",
                scaledSize: new google.maps.Size(width, height),
            },
        });
    }

    function initMap(mapElement) {
        var lat = parseFloat(mapElement.getAttribute("data-lat"));
        var lng = parseFloat(mapElement.getAttribute("data-lng"));
        var zoom = parseFloat(mapElement.getAttribute("data-zoom"));
        var pos = new google.maps.LatLng(lat, lng);
        if (isRefuga) {
            
            var vestioPos = new google.maps.LatLng(50.9292528,5.3352198);
        }

        var mapOptions = getMapOptions(pos, zoom);

        // Create the Google Map using our element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);

        // Let's also add a marker while we're at it
        var marker = getMarker(map, pos, 26, 36);

        if (isRefuga) {
            var vestioMarker = getMarker(map, vestioPos, 26, 36);
        }
    }

    // When the window has finished loading create our google map below
    // $(document).ready(function () {
    //     google.maps.event.addDomListener(window, "load", function () {
    //         var maps = Array.from(document.querySelectorAll(".map"));
    //         maps.forEach(function (mapElement) {
    //             initMap(mapElement);
    //         });
    //     });
    // });
}
