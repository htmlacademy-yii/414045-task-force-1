<?php

namespace Components\Responses;

use frontend\models\Task;
use Yii;

class ResponseHelper
{
    public static function isUserSentResponse(Task $task): bool
    {
        $userId = Yii::$app->user->id;
        $isUserSentResponse = false;
        $responses = $task->responses;

        if ($userId !== $task->customer_id) {
            foreach ($responses as $response) {
                $isUserSentResponse = $response->user_id === $userId ? true : $isUserSentResponse;
            }
        }

        return $isUserSentResponse;
    }
}