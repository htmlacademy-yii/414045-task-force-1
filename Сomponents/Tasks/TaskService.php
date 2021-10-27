<?php

declare(strict_types=1);

namespace Components\Tasks;

use Components\Constants\ActionConstants;
use Components\Constants\TaskConstants;
use Components\Exceptions\TaskActionException;
use Components\Exceptions\TaskStateException;
use Components\Responses\ResponseService;
use frontend\models\TaskAttachment;
use frontend\models\Task;

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
    public static function saveTaskAttachmentFiles($attachmentFileNames, $taskId)
    {
        if ($attachmentFileNames !== null) {
            foreach ($attachmentFileNames as $fileName) {
                $file = new TaskAttachment();
                $file->task_id = $taskId;
                $file->file_base_name = $fileName['baseName'];
                $file->file_name = $fileName['name'];
                $file->file_src = TaskAttachment::UPLOAD_DIR . $fileName['name'];
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
    public static function getStatusMap(): array
    {
        return TaskConstants::STATUS_MAP_FOR_USER;
    }

    /**
     * Получить карту действий
     *
     * @return array
     */
    public static function getActionMap(): array
    {
        return ActionConstants::ACTION_MAP;
    }

    /**
     * Получить доступные классы действий для задачи
     *
     * @param Task $task
     * @return array Классы доступных действий
     * @throws TaskStateException
     */
    public static function getPossibleActions(Task $task): array
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
     * Метод проверяет, может ли задача быть закончена
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     * @throws TaskStateException
     */
    public static function isTaskCanBeComplete(Task $task, int $userId): bool
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
     * Метод проверяет, может ли задача быть отменена исполнителем
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     * @throws TaskStateException
     */
    public static function isTaskCanBeRefuse(Task $task, int $userId): bool
    {
        $possibleActions = self::getPossibleActions($task);

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
    public static function isTaskCanBeCancel(Task $task, int $userId): bool
    {
        $possibleActions = self::getPossibleActions($task);

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
    public static function isTaskCanBeResponse(Task $task): bool
    {
        $possibleActions = self::getPossibleActions($task);
        $isUserSentResponse = ResponseService::isUserSentResponse($task);

        foreach ($possibleActions as $action) {
            if ($action === Refuse::class && !$isUserSentResponse) {
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
    public static function getTaskActionButtonClassName($actionName): string
    {
        return ActionConstants::ACTION_BUTTON_CLASS_NAMES_MAP[$actionName];
    }

    /**
     * Метод возвращает имя класса для DataFor
     *
     * @param $actionName
     * @return string
     */
    public static function getTaskActionDataForClassName($actionName): string
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
    public static function getTaskStateAfterAction(string $action): string|null
    {
        if (in_array($action, ActionConstants::ACTION_MAP)) {
            throw new TaskActionException('Указанного действия не существует');
        }

        return TaskConstants::STATE_AFTER_ACTION[$action] ?? null;
    }
}