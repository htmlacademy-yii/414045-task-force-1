<?php


namespace Components\Time;


use Components\Constants\TimeConstants;
use Components\Exceptions\TimeException;
use Components\NumberHelpers\NumberHelpersNumEnding;
use DateInterval;
use DateTime;
use Exception;

class TimeDifference extends Time
{
    public function __construct(
        protected string      $firstDateTimeStringPoint,
        protected string|null $secondDateTimeStringPoint = null
    ) {
    }

    /**
     * Метод возвращает количество лет/месяцев/дней/часов/минут в виде строки,
     * в зависимости от параметра timeUnits, где указывается единицы измерений и шаблон для вывода
     *
     * @param array $timeUnits массив с единицами измерений и их шаблонами, например ['hour' => '%h'].
     * Доступные единицы измерений и шаблоны:
     *
     * year / 'Y', 'y'
     * month / 'M', 'm'
     * day / 'D', 'd', 'a'
     * hour/ 'H', 'h'
     * minute/ 'I', 'i'
     *
     * @return string
     * @throws TimeException
     * @throws Exception
     */
    public function getCountTimeUnits(array $timeUnits): string
    {
        $result = '';
        $format = '';
        $timeInterval = $this->getDiff();
        $timeUnitsMap = [
            'year' => [
                'formatChars' => ['Y', 'y'],
                'endings' => TimeConstants::ENDINGS_FOR_YEAR,
                'count' => $timeInterval->y,
            ],
            'month' => [
                'formatChars' => ['M', 'm'],
                'endings' => TimeConstants::ENDINGS_FOR_MONTH,
                'count' => $timeInterval->m,
            ],
            'day' => [
                'formatChars' => ['D', 'd', 'a'],
                'endings' => TimeConstants::ENDINGS_FOR_DAY,
                'count' => $timeInterval->d,
            ],
            'hour' => [
                'formatChars' => ['H', 'h'],
                'endings' => TimeConstants::ENDINGS_FOR_HOUR,
                'count' => $timeInterval->h,
            ],
            'minute' => [
                'formatChars' => ['I', 'i'],
                'endings' => TimeConstants::ENDINGS_FOR_MINUTE,
                'count' => $timeInterval->i,
            ],
        ];


        foreach ($timeUnits as $timeUnit => $formatChar) {
            if (!array_key_exists($timeUnit, $timeUnitsMap)) {
                throw new TimeException(
                    'Неверный параметр timeUnits, метода getCountTimeUnits'
                );
            }
            $timeUnitName = new NumberHelpersNumEnding();
            $countTimeUnit = $timeUnitsMap[$timeUnit]['count'];
            $timeUnitName = $timeUnitName->getEnding(
                $countTimeUnit,
                $timeUnitsMap[$timeUnit]['endings']
            );
            $format .= '%' . $formatChar . ' ';
            $result .= $countTimeUnit . ' ' . $timeUnitName . ' ';
        }

        return $result;
    }

    /**
     * Метод возвращает разницу во времени между двумя DateTime.
     *
     * @return DateInterval|false
     * @throws Exception
     */
    public function getDiff(): DateInterval|bool
    {
        $firstTimePoint = new DateTime($this->firstDateTimeStringPoint);
        $secondTimePoint = new DateTime($this->secondDateTimeStringPoint);

        return $firstTimePoint->diff($secondTimePoint);
    }
}