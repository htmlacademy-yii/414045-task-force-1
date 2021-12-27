<?php

declare(strict_types=1);

namespace Components\Tasks;

use Components\Constants\ActionConstants;
use Components\Constants\MyTaskListFilterConstants;
use Components\Constants\TaskConstants;
use Components\Exceptions\TaskActionException;
use Components\Exceptions\TaskStateException;
use Components\Responses\ResponseService;
use frontend\models\Task;
use yii\db\Query;

/**
 * Class TaskService
 *
 * @package Components\Tasks
 */
class TaskService
{
    public string $taskState = TaskConstants::NEW_TASK_STATUS_NAME;

    /**
     * Метод сохранения вложенных файлов для задачи
     *
     * @param $attachmentFileNames
     * @param $taskId
     */
    public function saveTaskAttachmentFiles($attachmentFileNames, $taskId)
    {
        if ($attachmentFileNames !== null) {
            foreach ($attachmentFileNames as $fileName) {
                $file = (new TaskAttachmentFactory())->create($taskId, $fileName);
                if ($file->validate()) {
                    $file->save();
                }
            }
        }
    }

    /**
     * Получить карту статусов задачи
     *
     * @return array
     */
    public function getStatusMap(): array
    {
        return TaskConstants::STATUS_MAP_FOR_USER;
    }

    /**
     * Получить карту действий
     *
     * @return array
     */
    public function getActionMap(): array
    {
        return ActionConstants::ACTION_MAP;
    }

    /**
     * Метод проверяет, может ли задача быть закончена
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     * @throws TaskStateException
     */
    public function isTaskCanBeComplete(Task $task, int $userId): bool
    {
        $possibleActions = self::getPossibleActions($task);

        foreach ($possibleActions as $action) {
            if ($action === Done::class && $task->customer_id === $userId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Получить доступные классы действий для задачи
     *
     * @param Task $task
     * @return array Классы доступных действий
     * @throws TaskStateException
     */
    public function getPossibleActions(Task $task): array
    {
        if (!array_key_exists($task->state, TaskConstants::STATUS_MAP_FOR_USER)) {
            throw new TaskStateException(
                'Выбранного состояния задания не существует'
            );
        }
        if (!array_key_exists($task->state, TaskConstants::TRANSFER_MAP)) {
            throw new TaskStateException(
                'Для выбранного статуса задания нет доступных действий'
            );
        }

        $actions = TaskConstants::TRANSFER_MAP[$task->state];
        $possibleActions = [];

        /**
         * @var AbstractAction $action
         */
        foreach ($actions as $action) {
            if ($action::authActionForUser($task)) {
                $possibleActions[] = $action;
            }
        }

        return $possibleActions;
    }

    /**
     * Метод проверяет, может ли задача быть отменена исполнителем
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     * @throws TaskStateException
     */
    public function isTaskCanBeRefuse(Task $task, int $userId): bool
    {
        $possibleActions = $this->getPossibleActions($task);

        foreach ($possibleActions as $action) {
            if ($action === Refuse::class && $task->executor_id === $userId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Метод проверяет, может ли задача быть отменена заказчиком
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     * @throws TaskStateException
     */
    public function isTaskCanBeCancel(Task $task, int $userId): bool
    {
        $possibleActions = $this->getPossibleActions($task);

        foreach ($possibleActions as $action) {
            if ($action === Cancel::class && $task->customer_id === $userId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Метод проверяет, можно ли откликнуться на задачу
     *
     * @param Task $task
     * @return bool
     * @throws TaskStateException
     */
    public function isTaskCanBeResponse(Task $task): bool
    {
        $possibleActions = $this->getPossibleActions($task);
        $isUserSentResponse = (new ResponseService())->isUserSentResponse($task);

        foreach ($possibleActions as $action) {
            if ($action === Response::class && !$isUserSentResponse) {
                return true;
            }
        }

        return false;
    }

    /**
     * Метод возвращает имя класса для кнопки действия
     *
     * @param $actionName
     * @return string
     */
    public function getTaskActionButtonClassName($actionName): string
    {
        return ActionConstants::ACTION_BUTTON_CLASS_NAMES_MAP[$actionName];
    }

    /**
     * Метод возвращает имя класса для DataFor
     *
     * @param $actionName
     * @return string
     */
    public function getTaskActionDataForClassName($actionName): string
    {
        return ActionConstants::ACTION_DATA_FOR_CLASS_NAMES_MAP[$actionName];
    }

    /**
     * Получить статус задачи после действия
     *
     * @param string $action действие
     *
     * @return string|null Статус задачи после выполненного действия
     * @throws TaskActionException
     */
    public function getTaskStateAfterAction(string $action): string|null
    {
        if (in_array($action, ActionConstants::ACTION_MAP)) {
            throw new TaskActionException('Указанного действия не существует');
        }

        return TaskConstants::STATE_AFTER_ACTION[$action] ?? null;
    }

    public function getFilteredTasks($userId, $filter)
    {
        $tasks = Task::find();

        if ($filter === null) {
            $tasks->orWhere(['executor_id' => $userId])->orWhere(['customer_id' => $userId]);
        }

        if ($filter === MyTaskListFilterConstants::COMPLETED) {
            $tasks->orWhere(['executor_id' => $userId])
                ->orWhere(['customer_id' => $userId])
                ->where(['state' => TaskConstants::DONE_TASK_STATUS_NAME]);
        }

        if ($filter === MyTaskListFilterConstants::NEW) {
            $tasks->where(['customer_id' => $userId, 'state' => TaskConstants::NEW_TASK_STATUS_NAME]);
        }

        if ($filter === MyTaskListFilterConstants::ACTIVE) {
            $tasks->orWhere(['executor_id' => $userId])
                ->orWhere(['customer_id' => $userId])
                ->where(['state' => TaskConstants::IN_WORK_TASK_STATUS_NAME]);
        }

        if ($filter === MyTaskListFilterConstants::CANCELED) {
            $tasks->orWhere(['executor_id' => $userId, 'state' => TaskConstants::CANCELED_TASK_STATUS_NAME])
                ->orWhere(['executor_id' => $userId, 'state' => TaskConstants::FAILED_TASK_STATUS_NAME])
                ->orWhere(['customer_id' => $userId, 'state' => TaskConstants::CANCELED_TASK_STATUS_NAME])
                ->orWhere(['customer_id' => $userId, 'state' => TaskConstants::FAILED_TASK_STATUS_NAME]);
        }

        if ($filter === MyTaskListFilterConstants::EXPIRED) {
            $tasks->orWhere(['executor_id' => $userId])
                ->orWhere(['customer_id' => $userId])
                ->where(['state' => TaskConstants::IN_WORK_TASK_STATUS_NAME])
                ->andWhere(['<', 'deadline', date('Y-m-d')]);
        }

        return $tasks;
    }
}