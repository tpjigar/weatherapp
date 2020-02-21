<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Algolia Places search</title>
</head>
<body>
<input type="search" id="address" class="form-control" placeholder="Where are we going?" />

<p>Selected: <strong id="address-value">none</strong></p>
<script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1" defer></script>
<script>
    (function() {
        var placesAutocomplete = places({
            appId: 'plV2S30EXOO0',
            apiKey: 'e93642ea4af7145aef67968ae223a29a',
            container: document.querySelector('#address')
        }).configure({
            type: 'city',
            aroundLatLngViaIP: false,
        });

        var $address = document.querySelector('#address-value')
        placesAutocomplete.on('change', function(e) {
            console.log(e.suggestion);
            $address.textContent = e.suggestion.value
        });

        placesAutocomplete.on('clear', function() {
            $address.textContent = 'none';
        });

    })();
</script>
</body>
</html>
