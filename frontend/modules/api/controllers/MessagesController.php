<?php

namespace frontend\modules\api\controllers;

use frontend\models\Message;
use frontend\models\Task;
use yii\rest\ActiveController;

class MessagesController extends ActiveController
{
    public $modelClass = Message::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        return $actions;
    }

    public function actionIndex()
    {
        $taskId = \Yii::$app->request->get('task_id') ?? false;

        if ($taskId) {
            return Message::find()->where(['task_id' => $taskId])->all();
        }

        return Message::find()->all();
    }

    public function actionCreate()
    {
        $content = \Yii::$app->request->post('message');
        $task_id = \Yii::$app->request->post('task_id');
        $message = new Message();
        $task = Task::findOne($task_id);
        $message->sender_id = $task->executor_id;
        $message->addressee_id = $task->category_id;
        $message->content = $content;
        $message->task_id = $task_id;

        if ($message->save()) {
            \Yii::$app->response->statusCode = 201;
        } else {
            \Yii::$app->response->statusCode = 404;
        }
    }
}