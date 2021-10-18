<?php

declare(strict_types=1);

namespace Components\Tasks;

use Components\Constants\ActionConstants;
use Components\Constants\TaskConstants;
use Components\Exceptions\TaskActionException;
use Components\Exceptions\TaskStateException;
use frontend\models\TaskAttachment;

/**
 * Class Task
 *
 * @package Components\Tasks
 */
class Task
{
    public string $taskState = TaskConstants::NEW_TASK_STATUS_NAME;

    /**
     * Task constructor.
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
     * Получить доступные действия для задачи
     *
     * @param string $state текущее состояние задачи
     *
     * @return array доступные действия
     * @throws TaskStateException
     */
    public function getPossibleActions(string $state): array
    {
        if (!array_key_exists($state, TaskConstants::STATUS_MAP_FOR_USER)) {
            throw new TaskStateException(
                'Выбранного состояния задания не существует'
            );
        }
        if (!array_key_exists($state, TaskConstants::TRANSFER_MAP)) {
            throw new TaskStateException(
                'Для выбранного статуса задания нет доступных действий'
            );
        }

        return TaskConstants::TRANSFER_MAP[$state];
    }

    /**
     * Получить статус задачи после действия
     *
     * @param string $action действие
     *
     * @return string|null статус задачи после выполненного действия
     * @throws TaskActionException
     */
    public function getTaskStateAfterAction(string $action): string|null
    {
        if (in_array($action, ActionConstants::ACTION_MAP)) {
            throw new TaskActionException('Указанного действия не существует');
        }

        return TaskConstants::STATE_AFTER_ACTION[$action] ?? null;
    }
}