<?php

namespace frontend\controllers;

use frontend\models\Task;
use frontend\models\User;

class MyTasksController extends SecuredController
{
    public function actionIndex()
    {
        $user = User::findOne(\Yii::$app->user->id);
        $tasks = Task::find()
            ->where(['executor_id' => $user->id])
            ->all();

        return $this->render('index', compact('tasks'));
    }
}