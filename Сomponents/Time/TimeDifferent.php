<?php


namespace Components\Time;


use Components\Exceptions\TimeException;
use DateTime;
use Exception;

class TimeDifferent extends Time
{
    public array $errors;

    public function __construct(
        protected string $firstDateTimeStringPoint,
        protected string|null $secondDateTimeStringPoint = null
    ) {
    }

    /**
     * Метод возвращает разницу во времени между двумя DateTime.
     *
     * @param string $unitOfMeasurement параметр отвечающий за единицу измерения возвращаемого значения.
     * Доступны: 'year', 'month', 'week', 'day', 'hour', 'minute', 'second'
     * @param  string  $roundType тип округления
     *
     * @return int
     * @throws TimeException
     * @throws Exception
     */
    public function getDif(string $unitOfMeasurement, string $roundType = 'down'): int
    {
        $unitOfMeasurementMap = [
            'year' => 31536000,
            'month' => 18144000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1,
        ];

        $roundTypeMap = [
            'up' => PHP_ROUND_HALF_UP,
            'down' => PHP_ROUND_HALF_DOWN
        ];

        if (!array_key_exists($unitOfMeasurement, $unitOfMeasurementMap)) {
            $this->errors['getDif'] = 'invalid param unitOfMeasurement';
            throw new TimeException('Неверный параметр единицы измерения, метода получения разницы во времени');
        }

        if (!array_key_exists($roundType, $roundTypeMap)) {
            $this->errors['getDif'] = 'invalid param roundType';
            throw new TimeException('Неверный параметр типа округления, метода получения разницы во времени');
        }



        $firstTimePoint = new DateTime($this->firstDateTimeStringPoint);
        $firstTimePoint = $firstTimePoint->getTimestamp();
        $secondTimePoint = new DateTime($this->secondDateTimeStringPoint);
        $secondTimePoint = $secondTimePoint->getTimestamp();

        $difTime = ($secondTimePoint - $firstTimePoint) / $unitOfMeasurementMap[$unitOfMeasurement];

        return round($difTime, 0, $roundTypeMap[$roundType]);
    }
}