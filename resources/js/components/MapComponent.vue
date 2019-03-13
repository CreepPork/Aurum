<template>
    <div class="row">
        <div class="col-2">
            <div class="container map-sidebar">
                <h3>Controls</h3>

                <h5>Trains</h5>
                <ul class="pl-0" style="list-style-type: none">
                    <li class="py-1" v-for="line in lines">
                        <button class="btn" :class="line.isActive ? 'btn-primary' : 'btn-outline-primary'" @click="toggleLayer(line)">{{ line.title }}</button>
                    </li>
                </ul>

                <h5>Buses</h5>
                <ul class="pl-0" style="list-style-type: none">
                    <li class="py-1" v-for="bus in buses">
                        <button class="btn" :class="bus.isActive ? 'btn-primary' : 'btn-outline-primary'" @click="toggleLayer(bus)">{{ bus.title }}</button>
                    </li>
                </ul>
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

            this.map = map;

            const baseMaps = {
                "Streets": streets,
                "Satellite Streets": satelliteStreets,
                "Light": light,
                "Dark": dark,
                "Outdoors": outdoors
            };

            let overlayMaps = {};

            // Train lines
            axios.get('/api/line').then(response => {
                const data = response.data;

                let markerCount = 1;

                data.forEach(line => {
                    let markers = [];

                    line.stops.forEach(stop => {
                        let marker = L.marker([stop.position_X, stop.position_Y], {
                            title: stop.title,
                            icon: new L.Icon({
                                iconUrl: `images/icons/${markerCount}.png`,
                                iconSize: [25, 41],
                                iconAnchor: [12.5, 41]
                            })
                        }).bindPopup(stop.title);

                        markers.push(marker);
                    });

                    markerCount++;

                    let layerGroup = L.layerGroup(markers);

                    overlayMaps[line.title] = layerGroup;

                    map.addLayer(layerGroup);

                    let modifiedLayerGroup = layerGroup;
                    modifiedLayerGroup['title'] = line.title;
                    modifiedLayerGroup['isActive'] = true;

                    this.lines.push(modifiedLayerGroup);
                });

                L.control.layers(baseMaps, overlayMaps).addTo(map);
            }).catch(error => console.error(error));

            // Bus lines
            axios.get('/api/bus').then(response => {
                const data = response.data;

                let markerCount = 8;

                let markers = [];

                data.forEach(bus => {
                    let marker = L.marker([bus.position_X, bus.position_Y], {
                        title: bus.title,
                        icon: new L.icon({
                            iconUrl: `images/icons/${markerCount}.png`,
                            iconSize: [25, 41],
                            iconAnchor: [12.5, 41]
                        })
                    }).bindPopup(bus.title);

                    markers.push(marker);
                });

                markerCount++;

                let layerGroup = L.layerGroup(markers);

                overlayMaps[bus.title] = layerGroup;

                map.addLayer(layerGroup);

                let modifiedLayerGroup = layerGroup;
                modifiedLayerGroup['title'] = bus.title;
                modifiedLayerGroup['isActive'] = true;

                this.buses.push(modifiedLayerGroup);

                L.control.layers(baseMaps, overlayMaps).addTo(map);
            });
        },

        data() {
            return {
                lines: [],
                buses: [],
                map: {}
            }
        },

        methods: {
            toggleLayer(layerGroup) {
                layerGroup.isActive = !layerGroup.isActive;

                if (this.map.hasLayer(layerGroup))
                {
                    this.map.removeLayer(layerGroup);
                }
                else
                {
                    this.map.addLayer(layerGroup);
                }
            }
        }
    }
</script>
