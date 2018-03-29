<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

	<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.1/mapbox-gl.js'></script>
	<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.1/mapbox-gl.css' rel='stylesheet' />
</head>
<body>
	<div id="map" style="height: 100vh; width: 100vw;"></div>

	<script>

		(function () {
			mapboxgl.accessToken = 'pk.eyJ1IjoiZ2V0anVtcCIsImEiOiJjaXoxemlnbHEwMDI0MndsdzA0MWY2aGNmIn0.2xQ7AymQ-HQ37KHCRyiElw';
			var map = new mapboxgl.Map({
			    container: 'map',
			    style: 'mapbox://styles/mapbox/streets-v9',
			    center: [37.64, 55.76],
	            zoom: 3
			});

			map.once('style.load', function () {
				map.addSource('points', {
		            type: 'geojson',
		            data: 'http://localhost:8000/lazy',
		            buffer: 0,
		            cluster: true
		        });

				map.addLayer({
			        id: "clusters",
			        type: "circle",
			        source: "points",
			        filter: ["has", "point_count"],
			        paint: {
			            // Use step expressions (https://www.mapbox.com/mapbox-gl-js/style-spec/#expressions-step)
			            // with three steps to implement three types of circles:
			            //   * Blue, 20px circles when point count is less than 100
			            //   * Yellow, 30px circles when point count is between 100 and 750
			            //   * Pink, 40px circles when point count is greater than or equal to 750
			            "circle-color": [
			                "step",
			                ["get", "point_count"],
			                "#51bbd6",
			                100,
			                "#f1f075",
			                750,
			                "#f28cb1"
			            ],
			            "circle-radius": [
			                "step",
			                ["get", "point_count"],
			                20,
			                100,
			                30,
			                750,
			                40
			            ]
			        }
			    });

			    map.addLayer({
			        id: "cluster-count",
			        type: "symbol",
			        source: "points",
			        filter: ["has", "point_count"],
			        layout: {
			            "text-field": "{point_count_abbreviated}",
			            "text-font": ["DIN Offc Pro Medium", "Arial Unicode MS Bold"],
			            "text-size": 12
			        }
			    });

			    map.addLayer({
			        id: "unclustered-point",
			        type: "circle",
			        source: "points",
			        filter: ["!has", "point_count"],
			        paint: {
			            "circle-color": "#11b4da",
			            "circle-radius": 4,
			            "circle-stroke-width": 1,
			            "circle-stroke-color": "#fff"
			        }
			    });
			})
		})();


	</script>
</body>
</html>