<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>Test Task</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- bootstrap -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/custom.css')}}" rel="stylesheet">
    <script src="{{asset('js/app.js')}}"></script>

    <!-- Styles -->
    <style type="text/css">
        div#googleMap {
            width: 100%;
            height: 100%;
        }

        .mapouter {
            text-align: right;
            height: 500px;
            width: 600px;
        }

        .gmap_canvas {
            overflow: hidden;
            background: none !important;
            height: 500px;
            width: 600px;
        }
    </style>
</head>
<body>
<div id="app">
    <header>
        <h1>Clients on GoogleMap</h1>
    </header>
    <main>
        <aside class="sidebar">
            @if(isset($clients))
                @foreach($clients as $client)
                    <a href="#" data-cid="{{$client->id}}"
                       class="link @if(!in_array($client->id, $clients_with_coordinates))disabled @endif">
                        {{$client->fullname}}
                    </a>
                @endforeach
            @endif
        </aside>
        <div class="content">
            <div id="googleMap"></div>
        </div>
    </main>
</div>

<!-- Google Map -->
<script>

    var map;
    var clients = {!! $filled_clients !!};
    var cities = {!! $cities !!};
    var markerArray = [];
    var directionsDisplay;
    var directionsService;

    function centerCityMap(client_id) {
        if (client_id) {
            var city_id = clients[client_id].city_id;
            var lat = cities[city_id].latitude;
            var lon = cities[city_id].longitude;

            myMap(lat, lon);
        }
    }

    $('.sidebar a.link:not(.disabled)').on('click', function () {
        var client_id = $(this).data('cid');
        initMap();
        addMarkers(client_id);
    });

    function addMarkers(client_id) {
        var latitude, longitude,
            client = clients[client_id];

        if (client_id && client.today_coordinates.length) {
            client.today_coordinates.forEach(function(coordinates, index, array){
                latitude = parseFloat(coordinates.latitude);
                longitude = parseFloat(coordinates.longitude);
                var timeStamp = new Date(coordinates.updated_at);

                var marker = new google.maps.Marker({
                    position: {lat: latitude, lng: longitude},
                    map: map,
                    label:  timeStamp.getHours()+":"+timeStamp.getMinutes()
                });

                markerArray.push(latitude + ',' + longitude);

                addInfoWindowToMarker(marker, client);
            });
        }

        if (markerArray.length > 1) {
            calculateAndDisplayRoute(directionsDisplay, directionsService,
                markerArray, client);
        }

        map.setCenter({lat: latitude, lng: longitude});
    }

    function addInfoWindowToMarker(marker, client) {
        //info Window with client info
        var contentString = '<div id="content">' +
            '<h3 id="firstHeading" class="firstHeading">' + client.fullname + '</h3>' +
            '<div id="bodyContent">' +
            '<p><b>Email: </b> ' + client.email + '</p>' +
            '<p><b>Phone: </b> ' + client.phone + '</p>' +
            '</div>' +
            '</div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });
        //bind infoWindow to click event
        marker.addListener('click', function () {
            infowindow.open(map, marker);
        });
    }

    function myMap(latitude, longitude) {
        var mapOptions = {
            center: new google.maps.LatLng(latitude, longitude),
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
    }

    function initMap() {
        if (google != undefined) {
            myMap(cities[1].latitude, cities[1].longitude);
            markerArray = [];
            // Instantiate a directions service.
            directionsService = new google.maps.DirectionsService;

            // Create a renderer for directions and bind it to the map.
            directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true, map: map});
        }
    }

    function calculateAndDisplayRoute(directionsDisplay, directionsService,
                                      markerArray, client) {
        var waypts = [];
        for (var i = 1; i < (markerArray.length - 1); i++) {
            if (markerArray[i]) {
                waypts.push({
                    location: markerArray[i],
                    stopover: true
                });
            }
        }

        // Retrieve the start and end locations and create a DirectionsRequest using
        // WALKING directions.
        directionsService.route({
            origin: markerArray[0],
            destination: markerArray[markerArray.length - 1],
            waypoints: waypts,
            optimizeWaypoints: false,
            travelMode: 'WALKING'
        }, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                var _route = response.routes[0].legs[0];
                pinA = new google.maps.Marker({
                    position: _route.start_location,
                    map: map
                });
                addInfoWindowToMarker(pinA, client);
            } else {
               console.log('Directions request failed due to ' + status);
            }
        });
    }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAP_API_KEY')}}&callback=initMap"></script>
<!-- Google Map (END) -->

</body>
</html>
