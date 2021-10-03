<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Constants\CategoryConstants;
use frontend\models\Response;
use frontend\models\Task;
use frontend\models\TaskFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\HttpException;

final class TasksController extends SecuredController
{
    public const TASKS_PAGINATION_SIZE = 5;

    public function actionIndex(): string
    {
        $taskFilter = $this->getTaskFilter();
        $dataProvider = Task::getTaskDataProvider($taskFilter, self::TASKS_PAGINATION_SIZE);

        return $this->render('index', compact('dataProvider', 'taskFilter'));
    }

    private function getTaskFilter(): TaskFilter
    {
        $taskFilter = new TaskFilter();
        if (Yii::$app->request->getIsPost()) {
            $taskFilter->load(Yii::$app->request->post());
        }
        if (Yii::$app->request->get('category_id')) {
            $taskFilter->showCategories[] = Yii::$app->request->get('category_id') - 1;
        }

        return $taskFilter;
    }

    /**
     * Отображает страницу просмотра задачи
     *
     * @param int $id id задачи
     * @return string
     * @throws HttpException
     */
    public function actionGetView(int $id = null): string
    {
        $task = Task::findOne($id);

        if ($id === null || $task === null) {
            throw new HttpException(404, 'Задача не найдена.');
        }

        $customer = $task->customer;
        $countCustomerTasks = count($customer->tasks);
        $countResponses = count($task->responses);
        $dataProvider = Response::getResponsesDataProvider($task->id);
        $city = $task->city->title;
        $categoryId = $task->category->id;
        $categoryName = $task->category->title;
        $categoryMap = array_flip(CategoryConstants::NAME_MAP);
        $categoryClassName = $categoryMap[$categoryName];

        return $this->render('view',
            compact(
                'task',
                'customer',
                'dataProvider',
                'countCustomerTasks',
                'countResponses',
                'city',
                'categoryId',
                'categoryName',
                'categoryClassName'
            ));
    }
}