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
<body>
    <div id="map" style="height: 600;"></div>

        <input type="number" name="radius" min="0" id="radius" onchange="setCircle()" placeholder="Diameter in kilometers">
        <input type="button" value="Randomize point" onclick="getRandomPoint()">
        <input type="button" value="Remove dots" onclick="removeDots()">


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

        var circle = L.circle([61.30434,17.05966], {
            color: 'green',
            fillColor: 'green',
            fillOpacity: 0.2,
            radius: 0
        }).addTo(map);

        var dots = [];
        const area = {
            lat:61.30434,
            lng:17.05966,
            rad:0
        }

        var diameter = document.getElementById("radius");

        function setCircle() {

            area.rad = diameter.value * 1000/2;
            circle.setRadius(area.rad);

            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('radius', area.rad);
            window.history.pushState({}, '', currentUrl);
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
                radius: 50
            }).addTo(map);
            dots.push(dot);
        }


        function removeDots() {
            for (var i = 0; i < dots.length; i++) {
                map.removeLayer(dots[i]);
            }
            dots = [];  // Clear the array
        }

    </script>
</body>
</html>
