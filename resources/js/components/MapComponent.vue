<template>
    <div class="row">
        <div class="col-2">
            <div class="container map-sidebar">
                <h5>Maršruts</h5>

                <div class="input-group mb-3">
                    <select class="custom-select" id="navigationFrom" v-model="navigationFrom">
                        <option disabled value="">Izvēlaties...</option>
                        <optgroup v-for="(points, type) in optionGroups" :label="type">
                            <option v-for="point in points" :value="[point.position_X, point.position_Y]">{{ point.title }}</option>
                        </optgroup>
                    </select>

                    <div class="input-group-append">
                        <label class="input-group-text" for="navigationFrom">No</label>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <select class="custom-select" id="navigationTo" v-model="navigationTo">
                        <option disabled value="">Izvēlaties...</option>
                        <optgroup v-for="(points, type) in optionGroups" :label="type">
                            <option v-for="point in points" :value="[point.position_X, point.position_Y]">{{ point.title }}</option>
                        </optgroup>
                    </select>

                    <div class="input-group-append">
                        <label class="input-group-text" for="navigationTo">Uz</label>
                    </div>
                </div>

                <button @click="createRoute()" class="btn btn-outline-primary mb-3">Aprēķināt maršrutu</button>

                <h5>Vilcieni</h5>
                <ul class="pl-0" style="list-style-type: none">
                    <li class="py-1" v-for="line in lines">
                        <button class="btn" :class="line.isActive ? 'btn-primary' : 'btn-outline-primary'" @click="toggleLayer(line)">{{ line.title }}</button>
                    </li>
                </ul>

                <!-- <h5>Buses</h5>
                <ul class="pl-0" style="list-style-type: none">
                    <li class="py-1" v-for="bus in buses">
                        <button class="btn" :class="bus.isActive ? 'btn-primary' : 'btn-outline-primary'" @click="toggleLayer(bus)">{{ bus.title }}</button>
                    </li>
                </ul> -->

                <h5>Skatu torņi</h5>
                <ul class="pl-0" style="list-style-type: none">
                    <li class="py-1" v-if="Object.entries(towers).length !== 0 && towers.constructor !== Object">
                        <button class="btn" :class="towers.isActive ? 'btn-primary' : 'btn-outline-primary'" @click="toggleLayer(towers)">{{ towers.title }}</button>
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
    require('leaflet-routing-machine');

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

                        this.optionGroups['Stacijas'].push({
                            title: stop.title,
                            position_X: stop.position_X,
                            position_Y: stop.position_Y,
                        });
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

                this.readyTrain = true;
                this.addLayersToMap(baseMaps, overlayMaps);
            }).catch(error => console.error(error));

            // Lookout towers
            axios.get('/api/tower').then(response => {
                const data = response.data;

                let markers = [];

                data.forEach(tower => {
                    let marker = L.marker([tower.position_X, tower.position_Y], {
                        title: tower.title,
                        icon: new L.Icon({
                            iconUrl: 'images/icons/8.png',
                            iconSize: [25, 41],
                            iconAnchor: [12.5, 41]
                        })
                    }).bindPopup(`<b>${tower.title}</b><br/>${tower.description}`);

                    markers.push(marker);

                    this.optionGroups['Skatu torņi'].push({
                        title: tower.title,
                        position_X: tower.position_X,
                        position_Y: tower.position_Y,
                    });
                });

                let layerGroup = L.layerGroup(markers);

                overlayMaps['Skatu torņi'] = layerGroup;

                map.addLayer(layerGroup);

                let modifiedLayerGroup = layerGroup;
                modifiedLayerGroup['title'] = 'Skatu torņi';
                modifiedLayerGroup['isActive'] = true;

                this.towers = modifiedLayerGroup;

                layerGroup.addTo(map);

                this.readyTower = true;
                this.addLayersToMap(baseMaps, overlayMaps);
            }).catch(error => console.error(error));

            // Bus stops
            // axios.get('/api/bus').then(response => {
            //     const data = response.data;

            //     let markerCount = 8;

            //     let markers = [];

            //     data.forEach(bus => {
            //         let marker = L.marker([bus.position_X, bus.position_Y], {
            //             title: bus.title,
            //             icon: new L.icon({
            //                 iconUrl: `images/icons/${markerCount}.png`,
            //                 iconSize: [25, 41],
            //                 iconAnchor: [12.5, 41]
            //             })
            //         }).bindPopup(bus.title);

            //         markers.push(marker);
            //     });

            //     markerCount++;

            //     let layerGroup = L.layerGroup(markers);

            //     overlayMaps['Buses'] = layerGroup;

            //     map.addLayer(layerGroup);

            //     let modifiedLayerGroup = layerGroup;
            //     modifiedLayerGroup['title'] = 'Buses';
            //     modifiedLayerGroup['isActive'] = true;

            //     this.buses.push(modifiedLayerGroup);

            //     L.control.layers(baseMaps, overlayMaps).addTo(map);
            // }).catch(error => console.error(error));
        },

        data() {
            return {
                lines: [],
                buses: [],
                towers: {},
                map: {},
                readyTrain: false,
                readyTower: false,
                optionGroups: {
                    'Stacijas': [],
                    'Skatu torņi': []
                },
                navigationFrom: {},
                navigationTo: {},
                routeControl: {},
                isNavigating: false
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
            },

            addLayersToMap(baseMaps, overlayMaps) {
                if (this.readyTrain && this.readyTower)
                {
                    L.control.layers(baseMaps, overlayMaps).addTo(this.map);
                }
            },

            createRoute() {
                if (Object.entries(this.navigationFrom).length !== 0 && this.navigationFrom.constructor !== Object
                    && Object.entries(this.navigationTo).length !== 0 && this.navigationTo.constructor !== Object)
                {
                    if (this.isNavigating)
                    {
                        this.routeControl.getPlan().setWaypoints([]);
                        this.isNavigating = false;
                    }

                    this.routeControl = L.Routing.control({
                        waypoints: [
                            this.navigationFrom,
                            this.navigationTo
                        ],
                        routeWhileDragging: false,
                        router: new L.Routing.OSRMv1({
                            profile: 'driving'
                        })
                    });

                    this.routeControl.addTo(this.map);

                    this.isNavigating = true;
                }
            }
        }
    }
</script>
