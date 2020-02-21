<template>
  <div>
    <div id="map-example-container"></div>

    <div class="text-white mb-8">
      <div class="places-input text-gray-800">

<!--        <div id="map-example-container"></div>-->
        <input type="search" id="input-map" class="form-control" placeholder="Where are we going?" />
        <p>Selected: <strong id="address-value">none</strong></p>

      </div>
      <div class="weather-container font-sans w-128 max-w-lg rounded-lg overflow-hidden bg-gray-900 shadow-lg mt-4">

        <div class="current-weather flex items-center justify-between px-6 py-8">
          <div class="flex items-center">
            <div>
              <div class="text-6xl font-semibold">{{currentTemperature.actual}}°C</div>
              <div>Feels like {{currentTemperature.feels}}°C</div>
            </div>
            <div class="mx-5">
              <div class="font-semibold">{{currentTemperature.summary}}</div>
              <div>{{location.name}}</div>
            </div>
          </div>
          <div>
            <canvas ref="iconCurrent" id="iconCurrent" width="96" height="96"></canvas>
          </div>
        </div>

        <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">
          <div
            v-for="(day, index) in daily"
            :key="day.time"
            class="flex items-center"
            :class="{'mt-8' : index > 0 }"
            v-if="index < 5"
          >
            <div class="w-1/6 text-lg text-gray-200">{{ toDayOfWeek(day.time) }}</div>
            <div class="w-4/6 px-4 flex items-center">
              <div>
                <canvas :id="`icon${index+1}`" :data-icon="toKebabCase(day.icon)" width="24" height="24"></canvas>
              </div>
              <div class="ml-3">{{day.summary}}</div>
            </div>
            <div class="w-1/6 text-right">
              <div>{{Math.round(day.temperatureHigh) }}°C</div>
              <div>{{Math.round(day.temperatureLow) }}°C</div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
</template>

<script>
    export default {
        mounted() {
          this.fetchData()

          //algolia search
          var placesAutocomplete = places({
            appId: 'plV2S30EXOO0',
            apiKey: 'e93642ea4af7145aef67968ae223a29a',
            container: document.querySelector('#input-map')
          }).configure({
            type: 'city',
            aroundLatLngViaIP: false,
          });

          var $address = document.querySelector('#address-value')
          placesAutocomplete.on('change', (e) => {
            $address.textContent = e.suggestion.value

            this.location.name = `${e.suggestion.name}, ${e.suggestion.country}`
            this.location.lat = e.suggestion.latlng.lat
            this.location.lng = e.suggestion.latlng.lng
          });
          placesAutocomplete.on('clear', function() {
            $address.textContent = 'none';
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

          //
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

        },
      watch: {
          location: {
            handler(newValue, oldValue) {
              this.fetchData()
            },
            deep: true
          }

      },
      data() {
        return {
          currentTemperature: {
            actual: '',
            feels: '',
            summary: '',
            icon: ''
          },
          daily: [],
          location: {
            name: 'Surat, India',
            lat: '21.1865',
            lng: '72.8081'
          }
        }
      },
        methods: {
          fetchData() {
            var skycons = new Skycons({'color': 'white'});
            fetch(`api/weather?lat=${this.location.lat}&lng=${this.location.lng}`)
            .then(response => response.json())
            .then(data => {
              this.currentTemperature.actual= Math.round(data.currently.temperature)
              this.currentTemperature.feels= Math.round(data.currently.apparentTemperature)
              this.currentTemperature.summary=data.currently.summary
              this.currentTemperature.icon= this.toKebabCase(data.currently.icon)

              this.daily = data.daily.data

              skycons.add("iconCurrent", 'partly-cloudy-day');
              skycons.play()

              this.$nextTick( () => {
                skycons.add("icon1", document.getElementById('icon1').getAttribute('data-icon'));
                skycons.add("icon2", document.getElementById('icon2').getAttribute('data-icon'));
                skycons.add("icon3", document.getElementById('icon3').getAttribute('data-icon'));
                skycons.add("icon4", document.getElementById('icon4').getAttribute('data-icon'));
                skycons.add("icon5", document.getElementById('icon5').getAttribute('data-icon'));
                skycons.play()
              })

            })
          },
          toKebabCase(stringToConvert) {
            return stringToConvert.split(' ').join('-')
          },
          toDayOfWeek(timestamp) {
            const newDate = new Date(timestamp*1000)
            const days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT']

            return days[newDate.getDay()]
          }
        }
    }
</script>
<style>
  #map-example-container {height: 300px};
</style>
