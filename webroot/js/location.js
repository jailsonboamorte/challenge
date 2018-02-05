
var browser = 'others';
var lat;
var lon;
var map;
var infowindow;
var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
var radius = 500;
var search;

$(document).ready(function () {});
function setBrowser() {
    if (navigator.userAgent.search("MSIE") >= 0) {
        browser = 'MSIE'
    } else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
        browser = 'Safari'
    } else if (navigator.userAgent.search("Chrome") >= 0) {
        browser = 'Chrome'
    } else if (navigator.userAgent.search("Firefox") >= 0) {
        browser = 'Firefox'
    } else if (navigator.userAgent.search("Opera") >= 0) {
        browser = 'Opera'
    }

}
setBrowser();

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(setPosition, showError);
    } else {
        $('#mylocation').html("Geolocation is not supported by this browser.")
    }
}

function setPosition(position) {
    lat = position.coords.latitude;
    lon = position.coords.longitude;
    initMap();
}

function showError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            $('#error').html("User denied the request for Geolocation.")
            break;
        case error.POSITION_UNAVAILABLE:
            $('#error').html("Location information is unavailable.")
            break;
        case error.TIMEOUT:
            $('#error').html("The request to get user location timed out.")
            break;
        case error.UNKNOWN_ERROR:
            $('#error').html("An unknown error occurred.")
            break;
    }
}

function initMap() {
    var pyrmont = {lat: lat, lng: lon};
    var latlon = new google.maps.LatLng(lat, lon);

    map = new google.maps.Map(document.getElementById('map'), {
        center: pyrmont,
        zoom: 14
    });

    new google.maps.Marker({position: latlon, map: map, title: "You are here!"});

    infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    service.nearbySearch({
        location: pyrmont,
        radius: radius,
//        query: 'Universidade',
        type: 'school'
    }, callback);
}

function callback(results, status) {
    if (status === google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
            createMarker(results[i]);
        }
    }
}

function createMarker(place) {
    place.geometry.location;
    var marker = new google.maps.Marker({
        map: map,
        icon: iconBase + 'info-i_maps.png',
        position: place.geometry.location
    });

    google.maps.event.addListener(marker, 'click', function () {
        var lat_destino = this.getPosition().lat();
        var lon_destino = this.getPosition().lng();
        infowindow.setContent(place.name);
        infowindow.open(map, this);
        $('#descplace').html(place.name);
        search = {place_id: place.id, place_name: place.name, radius: radius, lat: lat, lon: lon, browser: browser, lat_destino: lat_destino, lon_destino: lon_destino};
        sendSearch();
    });
}

function sendSearch() {
    $.ajax({
        url: "http://localhost/challenge/Pages/save_request",
        type: "POST",
        data: search,
        dataType: "json",
        beforeSend: function (xhr) {
            $('#listprice').html('');
        },
        success: function (data, textStatus, jqXHR) {
            $.each(data, function (index, value) {
                var descUber = value.distance + ' miles' + ', Price: ' + value.estimate + ' by ' + value.type;
                $('#listprice').append("<li><a class='rideuber' data-uber='" + value.data + "' href='javascript:void(0)'>" + descUber + "</a></li>");
                // Do something with value here, e.g.                
            })
        }
    });
}

$('body').on('click', '.rideuber', function () {
    var dados = $(this).data('uber');
    $.ajax({
        type: "POST",
        url: "http://localhost/challenge/Pages/ride_uber",
        data: dados,        
        dataType: "json",
        beforeSend: function (xhr) {
            $('#ok').addClass('hidden');
        },
        success: function (data, textStatus, jqXHR) {
            $.each(data, function (index, value) {
                $('#ok').removeClass('hidden');
                $('#ok').html(value);
            });
        }
    });
});
