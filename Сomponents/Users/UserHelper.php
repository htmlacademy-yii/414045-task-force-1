<?php

declare(strict_types=1);

namespace Components\Users;

final class UserHelper
{
    public static function getCountRatingStars($rating)
    {
        return round($rating / 100, 2);
    }
}