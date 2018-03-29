<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
</head>
<body>
	<div id="map" style="height: 100vh; width: 100vw;"></div>

	<script>
		ymaps.ready(function() {
			mapReady();
		});

		function mapReady() {
			var map = new ymaps.Map(document.querySelector('#map'), {
				center: [55.76, 37.64], 
	            zoom: 7
			}, {
	            projection: ymaps.projection.sphericalMercator
			});

			var objectManager = new ymaps.LoadingObjectManager('/points?tileBounds=%t&z=%z', {
              clusterize: true,
			});

			map.geoObjects.add(objectManager);
		}
	</script>
</body>
</html>