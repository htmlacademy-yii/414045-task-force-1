<?php

namespace Components\Routes;

use yii\helpers\Url;

class Route
{
    public static function getTasks(): string
    {
        return Url::to(['/tasks']);
    }

    public static function getUsers(): string
    {
        return Url::to(['/users']);
    }
}