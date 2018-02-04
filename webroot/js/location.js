
$(document).ready(function () {});

var lat;
var lon;

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
            $('#mylocation').html("User denied the request for Geolocation.")
            break;
        case error.POSITION_UNAVAILABLE:
            $('#mylocation').html("Location information is unavailable.")
            break;
        case error.TIMEOUT:
            $('#mylocation').html("The request to get user location timed out.")
            break;
        case error.UNKNOWN_ERROR:
            $('#mylocation').html("An unknown error occurred.")
            break;
    }
}

//getLocation();



var map;
var infowindow;
var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
var radius = 500;

function initMap() {
    var pyrmont = {lat: lat, lng: lon};
    var latlon = new google.maps.LatLng(lat, lon);

    map = new google.maps.Map(document.getElementById('map'), {
        center: pyrmont,
        zoom: 14
    });


    new google.maps.Marker({
        position: latlon, map: map, title: "You are here!"});

    infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    service.nearbySearch({
        location: pyrmont,
        radius: radius,
        type: ['finance']
    }, callback);
}

function callback(results, status) {
    if (status === google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
            createMarker(results[i]);
        }
    }
}

var search;

function createMarker(place) {
    place.geometry.location;
    var marker = new google.maps.Marker({
        map: map,
        icon: iconBase + 'info-i_maps.png',
        position: place.geometry.location
    });

    google.maps.event.addListener(marker, 'click', function () {
        infowindow.setContent(place.name);
        infowindow.open(map, this);
        search = {place_id: place.id, place_name: place.nome, radius: radius, lat: lat, lon: lon};
        sendSearch();
    });
}

/**
 * Comment
 */
function sendSearch() {
//    console.log('search: ' + search);
    $.ajax({
        url: "http://localhost/challenge/Pages/save_request",
        type: "POST",
        data: search,
        dataType: "json"
    });
}
