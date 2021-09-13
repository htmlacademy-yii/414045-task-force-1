<?php

namespace frontend\controllers;

use Components\Categories\Category;
use Components\Constants\CategoryConstants;
use Components\Constants\TaskConstants;
use frontend\models\Task;
use frontend\models\TaskFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $taskFilter = $this->getTaskFilter();
        $dataProvider = $this->getTaskDataProvider($taskFilter);

        return $this->render('index', compact('dataProvider', 'taskFilter'));
    }

    public function actionView($id): string
    {
        $task = Task::findOne($id);
        $customer = $task->customer;
        $countCustomerTasks = count($customer->tasks);
        $countResponses = count($task->responses);
        $dataProvider = $this->getResponsesDataProvider($task->id);
        $city = $task->city->title;
        $categoryName = $task->category->title;
        $categoryMap = array_flip(CategoryConstants::NAME_MAP);
        $categoryClassName = $categoryMap[$categoryName];

        return $this->render('view',
            compact('task', 'customer', 'dataProvider', 'countCustomerTasks', 'countResponses', 'city', 'categoryName',
                'categoryClassName'));
    }

    private function getTaskFilter(): TaskFilter
    {
        $taskFilter = new TaskFilter();
        if (Yii::$app->request->getIsPost()) {
            $taskFilter->load(Yii::$app->request->post());
        }

        return $taskFilter;
    }

    private function getTaskDataProvider(TaskFilter $filter): ActiveDataProvider
    {
        $conditions['state'] = TaskConstants::NEW_TASK_STATUS_NAME;
        $query = Task::find()->where($conditions);

        if (!empty($filter->showCategories)) {
            $category = new Category();
            $conditionCategoryId = ['category_id' => $category->categoriesFilter($filter->showCategories)];
            $query->filterWhere($conditionCategoryId);
        }

        if ($filter->isNotExecutor) {
            $isNotExecutor = ['executor_id' => null];
            $query->andWhere($isNotExecutor);
        }

        if ($filter->isRemoteWork) {
            $conditionsIsRemoteWork = ['address' => null];
            $query->andWhere($conditionsIsRemoteWork);
        }

        if ($filter->period) {
            $conditionsPeriod = ['>', 'created_at', $this->dateFilter($filter->period)];
            $query->andWhere($conditionsPeriod);
        }

        if ($filter->taskName) {
            $conditionsName = ['like', 'title', $filter->taskName];
            $query->andWhere($conditionsName);
        }

        return new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }

    private function dateFilter($period): string|bool
    {
        return match ($period) {
            TaskFilter::PERIOD_DAY => date('Y-m-d H:i:s', strtotime('-1 day')),
            TaskFilter::PERIOD_WEEK => date('Y-m-d H:i:s', strtotime('-7 day')),
            TaskFilter::PERIOD_MONTH => date('Y-m-d H:i:s', strtotime('-1 month')),
            TaskFilter::PERIOD_ALL => false,
        };
    }

    private function getResponsesDataProvider($taskId): ActiveDataProvider
    {
        $query = (new Query())->select(['user_id', 'content', 'price', 'name', 'avatar_src', 'rating'])
            ->from('responses')
            ->where(['task_id' => $taskId])
            ->leftJoin(['u' => 'users'], 'u.id = responses.user_id');

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }
}