<?php


namespace Components\Time;

/**
 * Class TimeNumEnding
 *
 * Класс для получения правильных окончаний числительных
 *
 * @package Components\Time
 */
class TimeNumEnding extends Time
{
    /**
     * @param  int  $number числительное для которого нужно существительное в правильном склонении
     * @param  array  $endingArray массив со склоненными существительными
     *
     * @return string
     */
    public function getEnding(int $number, array $endingArray): string
    {
        $number %= 100;
        if ($number>=11 && $number<=19) {
            $ending=$endingArray[2];
        }
        else {
            $i = $number % 10;
            $ending = match ($i) {
                1 => $endingArray[0],
                2, 3, 4 => $endingArray[1],
                default => $endingArray[2],
            };
        }
        return $ending;
    }
}