<template>
    <div class="row">
        <div class="col-2">
            <div class="container map-sidebar">
                <h3>Controls</h3>

                <ul id="controls"></ul>
            </div>
        </div>

        <div class="col-10 p-0">
            <div id="map"></div>
        </div>
    </div>
</template>

<script>
    require('leaflet');

    export default {
        mounted() {
            const layerSettings = {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                accessToken: 'pk.eyJ1IjoiY3JlZXBwb3JrIiwiYSI6ImNqbmx6Y203cjAzcG8za3F4aWkwNWYxN3EifQ.wbPp9_Og_iaHOjUBz91HsQ'
            };

            const streets = L.tileLayer('https://api.tiles.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token={accessToken}', layerSettings);
            const satelliteStreets = L.tileLayer('https://api.tiles.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}.png?access_token={accessToken}', layerSettings);
            const light = L.tileLayer('https://api.tiles.mapbox.com/v4/mapbox.light/{z}/{x}/{y}.png?access_token={accessToken}', layerSettings);
            const dark = L.tileLayer('https://api.tiles.mapbox.com/v4/mapbox.dark/{z}/{x}/{y}.png?access_token={accessToken}', layerSettings);
            const outdoors = L.tileLayer('https://api.tiles.mapbox.com/v4/mapbox.outdoors/{z}/{x}/{y}.png?access_token={accessToken}', layerSettings);

            const map = L.map('map', {
                center: [56.814425, 24.603694],
                zoom: 14,
                layers: [outdoors]
            });

            const baseMaps = {
                "Streets": streets,
                "Satellite Streets": satelliteStreets,
                "Light": light,
                "Dark": dark,
                "Outdoors": outdoors
            };

            let overlayMaps = {};

            axios.get('/api/train').then(response => {
                let markers = [];

                const data = response.data;

                data.forEach(marker => {
                    let newMarker = L.marker([marker.position_X, marker.position_Y], {}).bindPopup(marker.title);

                    markers.push(newMarker);
                });

                markers = L.layerGroup(markers);

                overlayMaps['Trains'] = markers;

                map.addLayer(markers);

                L.control.layers(baseMaps, overlayMaps).addTo(map);
            }).catch(error => console.error(error));
        }
    }
</script>
