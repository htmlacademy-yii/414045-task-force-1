<?php

namespace frontend\controllers;

use Components\Constants\TaskConstants;
use frontend\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Task::find()->where(
                ['state' => TaskConstants::NEW_TASK_STATUS_NAME]
            )->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $this->render('index', compact('dataProvider'));
    }
}