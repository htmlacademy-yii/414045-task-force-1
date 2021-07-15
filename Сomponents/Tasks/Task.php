<?php

namespace Components\Tasks;

use Components\Constants\ActionConstants;
use Components\Constants\TaskConstants;

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
        return [
            TaskConstants::NEW_TASK_STATUS_NAME => TaskConstants::NEW_TASK_STATUS_NAME_FOR_USER,
            TaskConstants::CANCELED_TASK_STATUS_NAME => TaskConstants::CANCELED_TASK_STATUS_NAME_FOR_USER,
            TaskConstants::IN_WORK_TASK_STATUS_NAME => TaskConstants::IN_WORK_TASK_STATUS_NAME_FOR_USER,
            TaskConstants::DONE_TASK_STATUS_NAME => TaskConstants::DONE_TASK_STATUS_NAME_FOR_USER,
            TaskConstants::FAILED_TASK_STATUS_NAME => TaskConstants::FAILED_TASK_STATUS_NAME_FOR_USER,
        ];
    }

    public function getActionMap(): array
    {
        return [
            ActionConstants::CANCEL_ACTION_NAME => ActionConstants::CANCEL_ACTION_NAME_FOR_USER,
            ActionConstants::RESPOND_ACTION_NAME => ActionConstants::RESPOND_ACTION_NAME_FOR_USER,
            ActionConstants::DONE_ACTION_NAME => ActionConstants::DONE_ACTION_NAME_FOR_USER,
            ActionConstants::REFUSE_ACTION_NAME => ActionConstants::REFUSE_ACTION_NAME_FOR_USER,
        ];
    }

    public function getPossibleActions()
    {
        $possibleActions = [];

        foreach (TaskConstants::TRANSFER_MAP as $possibleActionsForStatus) {
            foreach ($possibleActionsForStatus as $action) {
                $action = new $action($this->user_id, $this->customer_id, $this->executor_id);
                if ($action->authUser()) {
                    $possibleActions[] = $action;
                }
            }
        }

        return $possibleActions ?? null;
    }

    public function getTaskStateAfterAction($action): string|null
    {
        $taskStateAfterAction = [
            ActionConstants::CANCEL_ACTION_NAME => TaskConstants::CANCELED_TASK_STATUS_NAME,
            ActionConstants::RESPOND_ACTION_NAME => TaskConstants::IN_WORK_TASK_STATUS_NAME,
            ActionConstants::DONE_ACTION_NAME => TaskConstants::DONE_TASK_STATUS_NAME,
            ActionConstants::REFUSE_ACTION_NAME => TaskConstants::FAILED_TASK_STATUS_NAME,
        ];

        return $taskStateAfterAction[$action] ?? null;
    }
}