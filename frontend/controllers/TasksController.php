<?php

namespace frontend\controllers;

use Components\Constants\TaskConstants;
use frontend\models\Task;
use frontend\models\TaskFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
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
        $conditions['state'] = TaskConstants::NEW_TASK_STATUS_NAME;

        if (!empty($filter->showCategories)) {
            $conditions['category_id'] = $this->getCategoriesFilter($filter->showCategories);
        }

        if ($filter->isNotExecutor) {
            $conditions['executor_id'] = null;
        }

        if ($filter->isRemoteWork) {
            $conditions['address'] = null;
        }

        if ($filter->period) {

        }

        return new ActiveDataProvider([
            'query' => Task::find()->where($conditions)->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }

    private function getCategoriesFilter($categoriesId): array {
        $showCategoriesId = [];
        foreach ($categoriesId as $categoryId) {
            $showCategoriesId[] = $categoryId + 1;
        }
        return $showCategoriesId;
    }
}