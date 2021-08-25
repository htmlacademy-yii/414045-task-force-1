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
        $conditionsDate = '';
        $conditionsName = '';

        if (!empty($filter->showCategories)) {
            $conditions['category_id'] = $this->categoriesFilter($filter->showCategories);
        }

        if ($filter->isNotExecutor) {
            $conditions['executor_id'] = null;
        }

        if ($filter->isRemoteWork) {
            $conditions['address'] = null;
        }

        if ($filter->period) {
            $conditionsDate = ['>', 'created_at', $this->dateFilter($filter->period)];
        }

        if ($filter->taskName) {
            $conditionsName = ['like', 'title', $filter->taskName];
        }

        return new ActiveDataProvider([
            'query' => Task::find()->where($conditions)->andWhere($conditionsDate)->andWhere($conditionsName)->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }

    private function categoriesFilter($categoriesId): array
    {
        $showCategoriesId = [];
        foreach ($categoriesId as $categoryId) {
            $showCategoriesId[] = $categoryId + 1;
        }
        return $showCategoriesId;
    }

    private function dateFilter($period): string|bool
    {
        if ($period === TaskFilter::PERIOD_DAY) {
            return date('Y-m-d H:i:s', strtotime('-1 day'));
        }
        if ($period === TaskFilter::PERIOD_WEEK) {
            return date('Y-m-d H:i:s', strtotime('-7 day'));
        }
        if ($period === TaskFilter::PERIOD_MONTH) {
            return date('Y-m-d H:i:s', strtotime('-1 month'));
        }
        return false;
    }
}