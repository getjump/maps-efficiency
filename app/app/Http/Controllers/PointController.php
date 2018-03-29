<?php

namespace App\Http\Controllers;

use App\Tile;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function lazyRequest(Request $request)
    {
        $points = app('db')->select('SELECT id, ST_X(geom) as longitude, ST_Y(geom) as latitude FROM points');

        $response = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        ini_set('memory_limit', '1024M');

        while (($point = array_pop($points))) {
            $response['features'][] = [
                'type' => 'Feature',
                'id' => $point->id,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float) $point->latitude, (float) $point->longitude],
                ]
            ];
        }

        return response()->json($response);
    }

    public function bboxRequest(Request $request)
    {
    	$boundaries = explode(',', $request->get('tileBounds'));

        if (count($boundaries) != 4) {
            return response()->json([], 402);
        }

        $boundaries = array_map('intval', $boundaries);

        $zoom = $request->get('z');

        $response = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        for ($x = $boundaries[0]; $x <= $boundaries[2]; $x++) {
            for ($y = $boundaries[1]; $y <= $boundaries[3]; $y++) {
                $data = $this->tileQueryDb($x, $y, $zoom);
                $response['features'] = array_merge($response['features'], $data['features']);
            }
        }

        return response()->json($response)->withCallback($request->input('callback'));
    }

    public function pbfRequest(Request $request, $z, $x, $y)
    {
    	$z = intval($z);
        $x = intval($x);
        $y = intval($y);

        $tile = $this->tileQueryDbMvt($z, $x, $y);

        if (strlen($tile) == 0) {
            return response($tile, 204)->header('Content-Type', 'application/octet-stream');
        }

        return response($tile)->header('Content-Type', 'application/octet-stream');
    }

    public function tileQueryDbMvt($x, $y, $z)
    {
    	$tileMin = new Tile($x, $y, $z);
        $tileMax = new Tile($x + 1, $y + 1, $z);

        $coordinatesMin = $tileMin->getCoordinates();
        $coordinatesMax = $tileMax->getCoordinates();

        $boundaries = [
            'xmin' => $coordinatesMin->getLongitude(),
            'ymin' => $coordinatesMin->getLatitude(),
            'xmax' => $coordinatesMax->getLongitude(),
            'ymax' => $coordinatesMax->getLatitude(),
            'clusterDistane' => 5000 / (2 ** min(max(0, $z), 20))
        ];

        $tile = stream_get_contents(app('db')->select('SELECT ST_AsMVT(tile, \'poi\') FROM (SELECT id, ST_AsMVTGeom(geom, ST_Makebox2d(ST_MakePoint(:xmin, :ymin), ST_MakePoint(:xmax, :ymax)), 4096, 256, false) AS geom FROM points WHERE geom @ ST_Makebox2d(ST_MakePoint(:xmin, :ymin), ST_MakePoint(:xmax, :ymax)) GROUP BY points.id) AS tile', $boundaries)[0]->st_asmvt);

        return $tile;
    }

    public function tileQueryDb($x, $y, $z)
    {
    	$tileMin = new Tile($x, $y, $z);
	    $tileMax = new Tile($x + 1, $y + 1, $z);

	    $coordinatesMin = $tileMin->getCoordinates();
	    $coordinatesMax = $tileMax->getCoordinates();

	    $boundaries = [
	        $coordinatesMin->getLongitude(),
	        $coordinatesMin->getLatitude(),
	        $coordinatesMax->getLongitude(),
	        $coordinatesMax->getLatitude(),
	    ];

	    $response = [
	        'type' => 'FeatureCollection',
	        'features' => [],
	    ];

	    $points = app('db')->select('SELECT id, ST_X(geom) as longitude, ST_Y(geom) as latitude FROM points WHERE geom @ ST_Makebox2d(ST_MakePoint(?, ?), ST_MakePoint(?, ?))', $boundaries);

	    foreach ($points as $point) {
	        $response['features'][] = $this->pointToGeoJson($point);
	    }

	    return $response;
    }

    public function pointToGeoJson($point) {
        return [
                'type' => 'Feature',
                'id' => $point->id,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float) $point->latitude, (float) $point->longitude],
                ]
            ];
    }
}
