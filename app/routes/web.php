<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () {
    return view('index');
});

$router->get('/leaflet_mvt', function () {
	return view('leaflet_mvt');
});

$router->get('/leaflet', function () {
	return view('leaflet');
});

$router->get('/leaflet-sliced', function () {
	return view('leaflet-sliced');
});

$router->get('/yandex', function () {
	return view('yandex');
});

$router->get('/mapbox_mvt', function () {
	return view('mapbox_mvt');
});

$router->get('/mapbox', function () {
	return view('mapbox');
});

$router->get('/points', 'PointController@bboxRequest');
$router->get('/points/{z}/{x}/{y}.pbf', 'PointController@pbfRequest');
$router->get('/lazy', 'PointController@lazyRequest');