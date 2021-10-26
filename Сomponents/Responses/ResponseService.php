<?php

namespace Components\Responses;

use Components\Constants\ResponseConstants;
use frontend\models\Response;
use frontend\models\Task;
use Yii;

class ResponseService
{
    /**
     * Метод создания отклика
     *
     * Создаёт экземпляр класса Response, наполняет данными из формы и сохраняет
     *
     * @param int $taskId
     * @return bool
     */
    public static function createResponse(int $taskId): bool
    {
        $response = new Response();
        $response->load(Yii::$app->request->post());
        $response->user_id = Yii::$app->user->id;
        $response->task_id = $taskId;
        $response->state = ResponseConstants::NEW_STATUS_NAME;

        if (!$response->validate()) {
            return false;
        }

        if (!$response->save()) {
            return false;
        }

        return true;
    }

    /**
     * Метод проверки отклика
     *
     * Проверяет, отправлял ли пользователь отклик по выбранному заданию
     *
     * @param Task $task
     * @return bool
     */
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