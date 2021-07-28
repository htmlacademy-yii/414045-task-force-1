<?php


namespace Components\Time;


class TimeDifferent extends Time
{
    public array $errors;

    public function __construct(
        protected string $firstDateTimeStringPoint,
        protected string|null $secondDateTimeStringPoint = null
    ) {
    }

    public function getDif($param): int
    {
        $paramMap = [
            'year' => 31536000,
            'month' => 18144000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1,
        ];

        if (!array_key_exists($param, $paramMap)) {
            return $this->errors['getDif'] = 'invalid param';
        }

        $firstTimePoint = new \DateTime($this->firstDateTimeStringPoint);
        $firstTimePoint = $firstTimePoint->getTimestamp();
        $secondTimePoint = new \DateTime($this->secondDateTimeStringPoint);
        $secondTimePoint = $secondTimePoint->getTimestamp();

        $difTime = ($secondTimePoint - $firstTimePoint) / $param;

        return round($difTime);
    }
}