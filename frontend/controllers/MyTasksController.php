<?php

namespace frontend\controllers;

use Components\Constants\MyTaskListFilterConstants;
use Components\Tasks\TaskService;
use frontend\models\User;
use Yii;
use yii\data\ActiveDataProvider;

class MyTasksController extends SecuredController
{
    public function actionIndex(string $filter = MyTaskListFilterConstants::NEW)
    {
        $pageSize = 10;
        $user = User::findOne(Yii::$app->user->id);
        $tasks = (new TaskService())->getFilteredTasks($user->id, $filter);
        $dataProvider = new ActiveDataProvider([
            'query' => $tasks,
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);

        return $this->render('index', compact('dataProvider', 'filter'));
    }
}