<?php


namespace Components\GeoData;


/**
 * Class ConvertStringToGeoPoint
 *
 * @package Components\GeoData
 */
class ConvertStringToGeoPoint
{
    /**
     * ConvertStringToGeoPoint constructor.
     *
     * @param  string  $latitude
     * @param  string  $longitude
     */
    public function __construct(
        private string $latitude,
        private string $longitude
    ) {
    }

    /**
     * Конвертировать широту и долготу в формат запроса SQL
     *
     * @return string
     */
    public function getGeoStringForSql(): string
    {
        return "ST_GeomFromText('POINT(".$this->latitude." ".$this->longitude
            .")')";
    }
}