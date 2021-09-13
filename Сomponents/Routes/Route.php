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

    public static function getTaskView($taskId): string
    {
        return Url::to(['/tasks/view/' . $taskId]);
    }

    public static function getUserView($userId): string
    {
        return Url::to(['/users/view/' . $userId]);
    }
}