<?php


namespace Components\GeoData;


class ConvertStringToGeoPoint
{
    public function __construct(
        private string $latitude,
        private string $longitude
    ) {
    }

    public function getGeoStringForSql():string
    {
        return "ST_GeomFromText('POINT(".$this->latitude." ".$this->longitude
            .")')";
    }
}