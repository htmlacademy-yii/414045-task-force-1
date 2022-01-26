<?php

declare(strict_types=1);

namespace frontend\modules\api\controllers;

use Components\Notification\NotificationsService;
use frontend\models\Message;
use frontend\models\Task;
use Yii;
use yii\rest\ActiveController;

class MessagesController extends ActiveController
{
    public $modelClass = Message::class;

    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);

        return $actions;
    }

    public function actionIndex(): array
    {
        $taskId = Yii::$app->request->get('task_id') ?? false;

        if ($taskId) {
            return Message::find()->where(['task_id' => $taskId])->all();
        }

        return Message::find()->all();
    }

    public function actionCreate()
    {
        $requestBody = json_decode(Yii::$app->request->getRawBody());
        $content = $requestBody->message;
        $task_id = (int) $requestBody->task_id;

        if (!$content || !$task_id) {
            Yii::$app->response->statusCode = 412;

            return null;
        }

        $message = new Message();
        $task = Task::findOne($task_id);
        $currentUserId = Yii::$app->user->id;

        if ($task->customer_id === $currentUserId) {
            $message->sender_id = $task->customer_id;
            $message->addressee_id = $task->executor_id;
        }

        if ($task->executor_id === $currentUserId) {
            $message->sender_id = $task->executor_id;
            $message->addressee_id = $task->customer_id;
        }

        $message->content = $content;
        $message->task_id = $task_id;

        if (!$message->save()) {
            Yii::$app->response->statusCode = 500;

            return null;
        }

        $message = Message::findOne($message->id);
        $responseBody = [
            'id' => $message->id,
            'message' => $message->content,
            'published_at' => $message->created_at,
            'is_mine' => true,
        ];
        Yii::$app->response->statusCode = 201;
        Yii::$app->response->content = json_encode($responseBody);

        if ($task->customer_id === $currentUserId) {
            (new NotificationsService())->sendNtfNewMessage($task_id, $task->executor_id);
        }

        if ($task->executor_id === $currentUserId) {
            (new NotificationsService())->sendNtfNewMessage($task_id, $task->customer_id);
        }
    }
}