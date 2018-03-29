<?php

namespace App;

use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Coordinate\CoordinateInterface;

class Tile
{
    protected $x;
    protected $y;
    protected $z;

    public function __construct($x, $y, $z)
    {
        $this->x = (int) $x;
        $this->y = (int) $y;
        $this->z = (int) $z;
    }

    public function getUpperLeftCoordinate()
    {
        $n = 2.0 ** $this->z;

        $longitudeDegrees = $this->x / $n * 360.0 - 180.0;
        $latitudeRadians = atan(sinh(pi() * (1.0 - 2.0 * $this->y / $n)));
        return new Coordinate([rad2deg($latitudeRadians), $longitudeDegrees]);
    }

    public function getCoordinates()
    {
        return $this->getUpperLeftCoordinate();
    }

    public function getBoundingBox()
    {
        $nextTile = new static($this->x, $this->y, $this->z);
        $nextTileCoordinate = $nextTile->getUpperLeftCoordinate();
        return;
    }

    public static function fromCoordinate(CoordinateInterface $coordinate, $zoom)
    {
        $longitudeDegrees = $coordinate->getLongitude();
        $latitudeRadians = $coordinate->getLatitude();

        $n = 2.0 ** $zoom;

        $x = (int) floor($n * ($longitudeDegrees + 180.0) / 360.0);
        $y = (int) floor($n * (1.0 - (log(tan($latitudeRadians) + sec($latitudeRadians)) / pi())) / 2.0);

        return new static($x, $y, $zoom);
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getZ()
    {
        return $this->z;
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function setY($y)
    {
        $this->y = $y;
    }

    public function setZ($z)
    {
        $this->z = $z;
    }
}
