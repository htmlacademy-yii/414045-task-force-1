<?php

declare(strict_types=1);

namespace Components\Tasks;

use Components\Constants\ActionConstants;
use Components\Constants\TaskConstants;
use Components\Exceptions\TaskActionException;
use Components\Exceptions\TaskStateException;
use frontend\models\TaskAttachment;
use frontend\models\Task;

/**
 * Class TaskHelper
 *
 * @package Components\Tasks
 */
class TaskHelper
{
    public string $taskState = TaskConstants::NEW_TASK_STATUS_NAME;

    /**
     * TaskHelper constructor.
     *
     * @param int $user_id
     * @param int $customer_id
     * @param int $executor_id
     */
    public function __construct(
        private int $user_id,
        private int $customer_id,
        private int $executor_id
    ) {
    }

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
     * @return array классы доступных действий
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

        foreach ($actions as $action) {
            if ($action::authActionForUser($task)) {
                $possibleActions[] = $action;
            }
        }

        return $possibleActions;
    }

    public static function getTaskActionButtonClassName($actionName): string
    {
        return ActionConstants::ACTION_BUTTON_CLASS_NAMES_MAP[$actionName];
    }

    public static function getTaskActionDataForClassName($actionName): string
    {
        return ActionConstants::ACTION_DATA_FOR_CLASS_NAMES_MAP[$actionName];
    }

    /**
     * Получить статус задачи после действия
     *
     * @param string $action действие
     *
     * @return string|null статус задачи после выполненного действия
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