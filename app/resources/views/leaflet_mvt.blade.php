<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
   integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
   crossorigin=""/> 

   <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
   integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
   crossorigin=""></script>

   <script src="https://unpkg.com/leaflet.vectorgrid@latest/dist/Leaflet.VectorGrid.bundled.js"></script>
</head>
<body>
	<div id="map" style="height: 100vh; width: 100vw;"></div>

	<script>

		(function () {
			var map = L.map('map').setView([55.76, 37.64], 7);

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);

			L.vectorGrid.protobuf("/points/{z}/{x}/{y}.pbf", {
				vectorTileLayerStyles: {
					'poi': function (properties, zoom) {
						return {
							weight: 2,
							color: 'red',
							opacity: 1,
							fillColor: 'yellow',
							fill: true,
							radius: 6,
							fillOpacity: 0.7
						}
					},
				}
			}).addTo(map);
		})();


	</script>
</body>
</html>