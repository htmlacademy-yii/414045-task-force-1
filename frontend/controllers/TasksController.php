<?php


namespace frontend\controllers;


use frontend\models\Task;
use yii\web\Controller;
use Components\Constants\TaskConstants;


class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $tasks = Task::findAll(['state' => TaskConstants::NEW_TASK_STATUS_NAME]);
        return $this->render('index', ['tasks' => $tasks]);
    }
}