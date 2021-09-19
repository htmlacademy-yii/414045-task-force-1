<?php

namespace Components\Users;

class UserHelper
{
    static function getCountRatingStars($rating)
    {
        return round($rating/100, 2);
    }
}