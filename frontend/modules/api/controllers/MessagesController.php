<?php

namespace frontend\modules\api\controllers;

use frontend\models\Message;
use yii\rest\ActiveController;

class MessagesController extends ActiveController
{
    public $modelClass = Message::class;

    public function actions(){
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $taskId = \Yii::$app->request->get('task_id') ?? false;

        if ($taskId) {
            return Message::find()->where(['task_id' => 1])->all();
        }

        return Message::find()->all();
    }
}