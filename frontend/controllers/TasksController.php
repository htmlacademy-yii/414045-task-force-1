<?php

namespace frontend\controllers;

use Components\Constants\TaskConstants;
use frontend\models\Task;
use frontend\models\TaskFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $taskFilter = $this->getTaskFilter();

        $dataProvider = $this->getDataProvider($taskFilter);

        return $this->render('index', compact('dataProvider', 'taskFilter'));
    }

    private function getTaskFilter(): TaskFilter
    {
        $taskFilter = new TaskFilter();
        if (Yii::$app->request->getIsPost()) {
            $taskFilter->load(Yii::$app->request->post());
        }
        return $taskFilter;
    }

    private function getDataProvider(TaskFilter $filter): ActiveDataProvider
    {
        $query = Task::find()->where(
            ['state' => TaskConstants::NEW_TASK_STATUS_NAME]
        )->orderBy(['created_at' => SORT_DESC]);

        if (!empty($filter->showCategories)) {
            $showCategoriesId = [];
            foreach ($filter->showCategories as $categoryId) {
                $showCategoriesId[] = $categoryId + 1;
            }
            $query = Task::find()->where(
                [
                    'state' => TaskConstants::NEW_TASK_STATUS_NAME,
                    'category_id' => $showCategoriesId
                ]
            )->orderBy(['created_at' => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        return $dataProvider;
    }
}