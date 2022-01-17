<?php

declare(strict_types=1);

namespace Components\Locations;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

/**
 * Class LocationService
 *
 * @package Components/Locations
 */
final class LocationService
{
    public const DAY_CACHE_DURATION = 86400;

    public array|false $location;
    public string|false $address;
    public string|false $point;

    /**
     * @param string|false $address
     * @param string|false $point
     */
    public function __construct(string|false $address, string|false $point)
    {
        $this->address = $address;
        $this->point = $point;

        $this->getLocation();
    }

    /**
     * @return void
     */
    private function getLocation(): void
    {
        if ($this->point !== false) {
            $location = $this->getLocationFromApi($this->point);

            Yii::$app->cache->set(md5($location['name']), $location, self::DAY_CACHE_DURATION);

            $this->location = $location;
            return;
        } elseif ($this->address !== false) {
            $locationCache = Yii::$app->cache->get(md5($this->address));

            if ($locationCache !== false) {
                $this->location = $locationCache;
                return;
            }

            $location = $this->getLocationFromApi($this->address);

            Yii::$app->cache->set(md5($location['name']), $location, self::DAY_CACHE_DURATION);

            $this->location = $location;
            return;
        }

        $this->location = false;
    }

    /**
     * @param string $addressOrPoint
     * @return false|mixed
     */
    private function getLocationFromApi(string $addressOrPoint): mixed
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
                $result = $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject'];
            }
        } catch (GuzzleException) {
            return false;
        }

        return $result;
    }

    /**
     * @return false|string
     */
    public function getLocationPoint(): false|string
    {
        if (!is_array($this->location)) {
            return false;
        }

        return $this->location['Point']['pos'];
    }

    /**
     * @return false|array
     */
    public function getLocationPointForMap(): false|array
    {
        if (!is_array($this->location)) {
            return false;
        }

        return explode(' ', $this->location['Point']['pos']);
    }

    /**
     * @return false|string
     */
    public function getLocationName(): false|string
    {
        if (!is_array($this->location)) {
            return false;
        }

        return $this->location['name'];
    }

    /**
     * @return false|string
     */
    public function getLocationDescription(): false|string
    {
        if (!is_array($this->location)) {
            return false;
        }

        return $this->location['description'];
    }
}