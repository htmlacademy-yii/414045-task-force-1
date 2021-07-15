<?php

namespace Components\Tasks;

use Components\Constants\ActionConstants;
use Components\Constants\TaskConstants;
use Components\Exceptions\TaskActionException;
use Components\Exceptions\TaskStateException;

class Task
{
    public string $taskState = TaskConstants::NEW_TASK_STATUS_NAME;

    public function __construct(
        private int $user_id,
        private int $customer_id,
        private int $executor_id
    ) {
    }

    public function getStatusMap(): array
    {
        return TaskConstants::STATUS_MAP;
    }

    public function getActionMap(): array
    {
        return ActionConstants::ACTION_MAP;
    }

    /**
     * @throws TaskStateException
     */
    public function getPossibleActions(string $state): array|null
    {
        if (!array_key_exists($state, TaskConstants::STATUS_MAP)){
            throw new TaskStateException('Выбранного состояния задания не существует');
        }
        if (!array_key_exists($state, TaskConstants::TRANSFER_MAP)){
            throw new TaskStateException('Для выбранного статуса задания нет доступных действий');
        }
        $possibleActions = TaskConstants::TRANSFER_MAP[$state];

        return $possibleActions ?? null;
    }

    /**
     * @throws TaskActionException
     */
    public function getTaskStateAfterAction(string $action): string|null
    {
        if (in_array($action, ActionConstants::ACTION_MAP)){
            throw new TaskActionException('Указанного действия не существует');
        }
        return TaskConstants::STATE_AFTER_ACTION[$action] ?? null;
    }
}