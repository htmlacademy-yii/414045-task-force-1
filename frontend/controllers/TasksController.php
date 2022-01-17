<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Constants\CategoryConstants;
use Components\Constants\ResponseConstants;
use Components\Constants\TaskConstants;
use Components\Locations\LocationService;
use Components\Responses\ResponseService;
use Components\Reviews\ReviewService;
use Components\Routes\Route;
use Components\Tasks\TaskService;
use Components\Users\UserService;
use frontend\models\Response;
use frontend\models\Task;
use frontend\models\TaskCompleteForm;
use frontend\models\TaskFilter;
use Yii;
use yii\db\Exception;
use yii\web\HttpException;
use Components\Exceptions\TaskStateException;

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

    public function actionResponseAccept($id, $responseId): \yii\web\Response
    {
        $task = Task::findOne($id);
        $response = Response::findOne($responseId);

        if (Yii::$app->user->id === $task->customer_id || $response !== null) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $response->state = ResponseConstants::ACCEPT_STATUS_NAME;
                $task->executor_id = $response->user_id;
                $task->state = TaskConstants::IN_WORK_TASK_STATUS_NAME;

                if (!$task->save()) {
                    throw new Exception('Ошибка сохранения задачи!');
                }

                if (!$response->save()) {
                    throw new Exception('Ошибка сохранения отклика!');
                }

                $transaction->commit();
            } catch (Exception) {
                $transaction->rollBack();
            }
        }

        return $this->redirect(Route::getTaskView($id));
    }

    public function actionResponseRefuse($id, $responseId): \yii\web\Response
    {
        $task = Task::findOne($id);
        $response = Response::findOne($responseId);

        if (Yii::$app->user->id === $task->customer_id || $response !== null) {
            $response->state = ResponseConstants::REFUSE_STATUS_NAME;
            $response->save();
        }

        return $this->redirect(Route::getTaskView($id));
    }

    /**
     * Отображает страницу просмотра задачи
     *
     * @param int|null $id id задачи
     * @return string
     * @throws HttpException
     * @throws TaskStateException
     */
    public function actionGetView(int $id = null): string
    {
        $task = Task::findOne($id);

        if ($id === null || $task === null) {
            throw new HttpException(404, 'Задача не найдена.');
        }

        $userId = Yii::$app->user->id;
        $taskCompleteForm = new TaskCompleteForm();
        $response = new Response();
        $isUserSentResponse = (new ResponseService())->isUserSentResponse($task);
        $possibleTaskActions = (new TaskService())->getPossibleActions($task);
        $customer = $task->customer;
        $countCustomerTasks = count($customer->tasks);
        $countResponses = count($task->responses);
        $categoryId = $task->category->id;
        $categoryName = $task->category->title;
        $categoryMap = array_flip(CategoryConstants::NAME_MAP);
        $categoryClassName = $categoryMap[$categoryName];
        $dataProvider = $isUserSentResponse ? Response::getResponsesDataProvider($task->id,
            $userId) : Response::getResponsesDataProvider($task->id);
        $location = $task->location_point ?? (new UserService())->getUserLocation($task->customer_id);
        $locationService = new LocationService(address: false, point: $location);
        $locationName = $locationService->getLocationName() ?? '';
        $locationDescription = $locationService->getLocationDescription() ?? '';
        $locationPoint = $locationService->getLocationPointForMap();

        return $this->render('view',
            compact(
                'task',
                'taskCompleteForm',
                'possibleTaskActions',
                'customer',
                'response',
                'isUserSentResponse',
                'dataProvider',
                'countCustomerTasks',
                'countResponses',
                'locationName',
                'locationDescription',
                'locationPoint',
                'categoryId',
                'categoryName',
                'categoryClassName'
            ));
    }

    /**
     * @throws TaskStateException
     */
    public function actionResponse($id): \yii\web\Response
    {
        $task = Task::findOne($id);

        if ((new TaskService())->isTaskCanBeResponse($task) && Yii::$app->request->isPost) {
            (new ResponseService())->createResponse($id);
        }

        return $this->redirect(Route::getTaskView($id));
    }

    /**
     * @throws TaskStateException
     */
    public function actionComplete($id): \yii\web\Response
    {
        $task = Task::findOne($id);
        $userId = Yii::$app->user->id;

        if ((new TaskService())->isTaskCanBeComplete($task, $userId) && Yii::$app->request->isPost) {
            (new ReviewService())->createReview($task, $userId);

            return $this->redirect(Route::getTasks());
        }

        return $this->redirect(Route::getTaskView($id));
    }

    /**
     * @throws TaskStateException
     */
    public function actionRefuse($id): \yii\web\Response
    {
        $task = Task::findOne($id);
        $userId = Yii::$app->user->id;

        if ((new TaskService())->isTaskCanBeRefuse($task, $userId) && Yii::$app->request->isPost) {
            $task->state = TaskConstants::FAILED_TASK_STATUS_NAME;
            $task->save();

            return $this->redirect(Route::getTasks());
        }

        return $this->redirect(Route::getTaskView($id));
    }

    /**
     * @throws TaskStateException
     */
    public function actionCancel($id): \yii\web\Response
    {
        $task = Task::findOne($id);
        $userId = Yii::$app->user->id;

        if ((new TaskService())->isTaskCanBeCancel($task, $userId) && Yii::$app->request->isPost) {
            $task->state = TaskConstants::CANCELED_TASK_STATUS_NAME;
            $task->save();

            return $this->redirect(Route::getTasks());
        }

        return $this->redirect(Route::getTaskView($id));
    }
}