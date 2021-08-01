<?php


namespace frontend\controllers;


use Components\Constants\TaskConstants;
use frontend\models\Task;
use yii\web\Controller;


class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $tasks = Task::find()->where(
            ['state' => TaskConstants::NEW_TASK_STATUS_NAME]
        )->orderBy(['created_at' => SORT_DESC])->all();

        return $this->render('index', ['tasks' => $tasks]);
    }
}