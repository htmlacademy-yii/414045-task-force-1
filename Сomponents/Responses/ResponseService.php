<?php

namespace Components\Responses;

use frontend\models\Response;
use frontend\models\Task;
use Yii;

/**
 * class ResponseService
 *
 * @package Components/Responses
 */
class ResponseService
{
    /**
     * Метод создания отклика
     *
     * Создаёт экземпляр класса Response, наполняет данными из формы и сохраняет
     *
     * @param $taskId
     * @return bool
     */
    public function createResponse($taskId): bool
    {
        $response = (new ResponseFactory())->create($taskId);

        if (!$this->saveResponse($response)) {
            return false;
        }

        return true;
    }

    /**
     * Метод сохраняет отклик
     *
     * @param Response $response
     * @return bool
     */
    public function saveResponse(Response $response): bool
    {
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
    public function isUserSentResponse(Task $task): bool
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