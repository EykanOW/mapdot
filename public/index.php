<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    <title>Mapdot</title>
</head>
<body style="background-color: darkslategray;">
    <div id="map" style="height: 800; border: solid black 5px; border-radius: 5px; margin-bottom: 10px;"></div>

    <div class="inputs">
        <input type="number" name="radius" min="0" id="radius" onchange="setCircle()" placeholder="Diameter in kilometers"> <br>
        <input type="button" value="Randomize point" onclick="getRandomPoint()"> <br>
        <input type="button" value="Remove dots" onclick="removeDots()"> <br>
        <input type="button" value="Reset Circle" onclick="resetCircle()"> <br>
    </div>

    <style>
        /* .inputs{
            display: flex;
            justify-content: center;
        } */
        .inputs input{
            margin: 5px;
            width: auto;
            font-size:larger;
            color:aliceblue;
            background-color:dimgray;
        }
        .inputs ::placeholder {
            color:aliceblue;
        }
    </style>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // app.js
        // Initialize the map and set its view to your desired coordinates and zoom level
        var map = L.map('map').setView([61.30434,17.05966], 13);

        // Add the OSM tile layer to your map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // my code

        var dots = [];
        var lines = [];
        const area = {
            lat:61.30434,
            lng:17.05966,
            rad:0
        }
        const initialPosition = [61.30434, 17.05966];

        var circle = L.circle([area.lat,area.lng], {
            color: 'green',
            fillColor: 'green',
            fillOpacity: 0.2,
            radius: 0
        }).addTo(map);

        function resetCircle(){
            circle.setLatLng(initialPosition);
            area.lat = initialPosition[0];
            area.lng = initialPosition[1];
        }

        function onMapClick(e) {

            area.lat = e.latlng.lat;
            area.lng = e.latlng.lng;
            circle.setLatLng(e.latlng);
            // popup
            // .setLatLng(e.latlng)
            // .setContent("You clicked the map at " + e.latlng.toString())
            // .openOn(map);
        }



        var diameter = document.getElementById("radius");

        function setCircle() {

            area.rad = diameter.value * 1000/2;
            circle.setRadius(area.rad);

            // let currentUrl = new URL(window.location.href);
            // currentUrl.searchParams.set('radius', area.rad);
            // window.history.pushState({}, '', currentUrl);
        }

        function getRandomPoint() {
            var angle = Math.random() * Math.PI * 2;
            var distance = Math.sqrt(Math.random()) * area.rad;
            var latOffset = distance * Math.cos(angle) / 111320;  // 111320 meters in a degree of latitude
            var lngOffset = distance * Math.sin(angle) / (40075000 * Math.cos(area.lat * Math.PI / 180) / 360);  // Adjust for longitude

            var foundLatitude = area.lat + latOffset;
            var foundLongitude = area.lng + lngOffset;
            var dot = L.circle([foundLatitude,foundLongitude], {
                color: 'red',
                fillColor: 'red',
                fillOpacity: 0.2,
                radius: 30
            }).addTo(map);
            dots.push(dot);
            dot.bindPopup(`<b>Travel here!</b><br>${Math.round(distance)/1000}km away from center.`).openPopup();
            var line = L.polygon([
                [area.lat, area.lng],
                [foundLatitude, foundLongitude]
            ]).addTo(map);
            lines.push(line);
        }


        function removeDots() {
            for (var i = 0; i < dots.length; i++) {
                map.removeLayer(dots[i]);
                map.removeLayer(lines[i]);
            }
            dots = [];  // Clear the array
            lines = []
        }

        var popup = L.popup();

        map.on('click', onMapClick);

    </script>
</body>
</html>
