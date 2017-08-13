@extends('layouts.layout')

@section('content')
    <script>
        channel.bind('tracking', function (data) {
            console.log(parseFloat(data.latitude));
            console.log(parseFloat(data.longitude));
        });
    </script>
    <div id="map"></div>
    <script>
        // Note: This example requires that you consent to location sharing when
        // prompted by your browser. If you see the error "The Geolocation service
        // failed.", it means you probably did not give permission for the browser to
        // locate you.
        var map, infoWindow,marker;
        var pathCoordinates = [];

        function initMap() {



            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                var pos = {lat: -25.363, lng: 131.044};
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: pos
                });

                var contentString = 'Name : Omar <br> Mobile Number : 01120879248';

                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });
                {{--var icon = {--}}
                    {{--url: '{{asset('assets/images/noun_41223_cc.png')}}', // url--}}
                    {{--scaledSize: new google.maps.Size(70, 60), // scaled size--}}
                    {{--origin: new google.maps.Point(0,0), // origin--}}
                    {{--anchor: new google.maps.Point(0, 0) // anchor--}}
                {{--};--}}
                marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: 'Delivery Man',
                   // icon:icon
                });
                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
                channel.bind('tracking', function (data) {
                    var myCenter = new google.maps.LatLng(parseFloat(data.latitude),parseFloat(data.longitude));
                    pos = {
                        lat: parseFloat(data.latitude),
                        lng: parseFloat(data.longitude)
                    };
                    pathCoordinates.push(pos);
                    var path = new google.maps.Polyline({
                        path: pathCoordinates,
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });
                    path.setMap(map);
                    marker.setPosition(myCenter);
                    map.setCenter(pos);
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        }
        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAl9sU1XjelRgRvFwFgD0Lwk_1bHjOGW7Y&callback=initMap">
    </script>

@endsection