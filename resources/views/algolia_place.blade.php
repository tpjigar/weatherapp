<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Algolia Places search</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/leaflet/1/leaflet.js"></script>

</head>
<body>

<div id="map-example-container"></div>
<input type="search" id="input-map" class="form-control" placeholder="Where are we going?" />
<p>Selected: <strong id="address-value">none</strong></p>

{{--<div id="map-example-container"></div>--}}
{{--<input type="search" id="address" class="form-control" placeholder="Where are we going?" />--}}
{{--<p>Selected: <strong id="address-value">none</strong></p>--}}
<style>
    #map-example-container {height: 300px};
</style>

<script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1"></script>
<script>
    (function() {

        // addresss start
        // var placesAutocomplete = places({
        //     appId: 'plV2S30EXOO0',
        //     apiKey: 'e93642ea4af7145aef67968ae223a29a',
        //     container: document.querySelector('#address')
        // }).configure({
        //     type: 'city',
        //     aroundLatLngViaIP: false,
        // });
        //
        // var $address = document.querySelector('#address-value')
        // placesAutocomplete.on('change', function(e) {
        //     $address.textContent = e.suggestion.value
        // });
        //
        // placesAutocomplete.on('clear', function() {
        //     $address.textContent = 'none';
        // });
        // address over

        //map start
        var placesAutocomplete = places({
            appId: 'plV2S30EXOO0',
            apiKey: 'e93642ea4af7145aef67968ae223a29a',
            container: document.querySelector('#input-map')
        }).configure({
                type: 'city',
                aroundLatLngViaIP: false,
            });

        var $address = document.querySelector('#address-value')
        placesAutocomplete.on('change', function(e) {
            $address.textContent = e.suggestion.value
        });

        var map = L.map('map-example-container', {
            scrollWheelZoom: false,
            zoomControl: false
        });

        var osmLayer = new L.TileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                minZoom: 1,
                maxZoom: 13,
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            }
        );
        var markers = [];

        map.setView(new L.LatLng(0, 0), 1);
        map.addLayer(osmLayer);

        placesAutocomplete.on('suggestions', handleOnSuggestions);
        placesAutocomplete.on('cursorchanged', handleOnCursorchanged);
        placesAutocomplete.on('change', handleOnChange);
        placesAutocomplete.on('clear', handleOnClear);

        function handleOnSuggestions(e) {
            markers.forEach(removeMarker);
            markers = [];

            if (e.suggestions.length === 0) {
                map.setView(new L.LatLng(0, 0), 1);
                return;
            }

            e.suggestions.forEach(addMarker);
            findBestZoom();
        }

        function handleOnChange(e) {
            markers
                .forEach(function(marker, markerIndex) {
                    if (markerIndex === e.suggestionIndex) {
                        markers = [marker];
                        marker.setOpacity(1);
                        findBestZoom();
                    } else {
                        removeMarker(marker);
                    }
                });
        }

        function handleOnClear() {
            map.setView(new L.LatLng(0, 0), 1);
            markers.forEach(removeMarker);
        }

        function handleOnCursorchanged(e) {
            markers
                .forEach(function(marker, markerIndex) {
                    if (markerIndex === e.suggestionIndex) {
                        marker.setOpacity(1);
                        marker.setZIndexOffset(1000);
                    } else {
                        marker.setZIndexOffset(0);
                        marker.setOpacity(0.5);
                    }
                });
        }

        function addMarker(suggestion) {
            var marker = L.marker(suggestion.latlng, {opacity: .4});
            marker.addTo(map);
            markers.push(marker);
        }

        function removeMarker(marker) {
            map.removeLayer(marker);
        }

        function findBestZoom() {
            var featureGroup = L.featureGroup(markers);
            map.fitBounds(featureGroup.getBounds().pad(0.5), {animate: false});
        }
        //map over

    })();
</script>
</body>
</html>
