<?php

declare(strict_types=1);

namespace Components\Locations;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class LocationService
 *
 * @package Components/Locations
 */
final class LocationService
{
    public array|false $location;
    public string $addressOrPoint;

    public function __construct(string $addressOrPoint)
    {
        $this->getLocation($addressOrPoint);
    }

    /**
     * @param string $addressOrPoint
     * @return array|false
     */
    public function getLocation(string $addressOrPoint): bool|array
    {
        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru/',
        ]);

        try {
            $response = $client->request('GET', '1.x', [
                'query' => [
                    'geocode' => $addressOrPoint,
                    'apikey' => 'e666f398-c983-4bde-8f14-e3fec900592a',
                    'format' => 'json',
                ]
            ]);

            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);

            $result = false;

            if (is_array($response_data)) {
                $result = $response_data;
            }
        } catch (RequestException $e) {
            return $this->location = false;
        }

        return $this->location = $result;
    }

    /**
     * @return false|string
     */
    public function getLocationPoint(): false|string
    {
        if (!is_array($this->location)) {
            return false;
        }

        return $this->location['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
    }

    /**
     * @return false|array
     */
    public function getLocationPointForMap(): false|array
    {
        if (!is_array($this->location)) {
            return false;
        }

        return explode(' ', $this->location['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);
    }

    /**
     * @return false|string
     */
    public function getLocationName(): false|string
    {
        if (!is_array($this->location)) {
            return false;
        }

        return $this->location['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'];
    }

    /**
     * @return false|string
     */
    public function getLocationDescription(): false|string
    {
        if (!is_array($this->location)) {
            return false;
        }

        return $this->location['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['description'];
    }
}