<?php

namespace Components\Tasks;

class Task
{
    private const TASK_STATUS_NEW = 'new';
    private const TASK_STATUS_CANCELED = 'canceled';
    private const TASK_STATUS_IN_WORK = 'in work';
    private const TASK_STATUS_DONE = 'done';
    private const TASK_STATUS_FAILED = 'failed';
    private const TASK_ACTION_CANCEL = 'cancel';
    private const TASK_ACTION_RESPOND = 'respond';
    private const TASK_ACTION_DONE = 'done';
    private const TASK_ACTION_REFUSE = 'refuse';

    public string $taskState = self::TASK_STATUS_NEW;

    public function __construct(
        private int $user_id,
        private int $customer_id,
        private int $executor_id
    ) {
    }

    public function getStatusMap(): array
    {
        return [
            self::TASK_STATUS_NEW => 'Новое',
            self::TASK_STATUS_CANCELED => 'Отменено',
            self::TASK_STATUS_IN_WORK => 'В работе',
            self::TASK_STATUS_DONE => 'Выполнено',
            self::TASK_STATUS_FAILED => 'Провалено',
        ];
    }

    public function getActionMap(): array
    {
        return [
            self::TASK_ACTION_CANCEL => 'Отменить',
            self::TASK_ACTION_RESPOND => 'Откликнуться',
            self::TASK_ACTION_DONE => 'Выполнено',
            self::TASK_ACTION_REFUSE => 'Отказаться',
        ];
    }

    public function getPossibleActions()
    {
        $possibleActions = [];
        $cancel = new Cancel(
            $this->user_id,
            $this->customer_id,
            $this->executor_id
        );
        $respond = new Respond(
            $this->user_id,
            $this->customer_id,
            $this->executor_id
        );
        $done = new Done(
            $this->user_id, $this->customer_id, $this->executor_id
        );
        $refuse = new Refuse(
            $this->user_id,
            $this->customer_id,
            $this->executor_id
        );

        if ($this->taskState === self::TASK_STATUS_NEW && $cancel->authUser()) {
            $possibleActions[] = $cancel;
        }

        if ($this->taskState === self::TASK_STATUS_NEW
            && $respond->authUser()
        ) {
            $possibleActions[] = $respond;
        }

        if ($this->taskState === self::TASK_STATUS_IN_WORK
            && $done->authUser()
        ) {
            $possibleActions[] = $done;
        }

        if ($this->taskState === self::TASK_STATUS_IN_WORK
            && $refuse->authUser()
        ) {
            $possibleActions[] = $refuse;
        }

        return $possibleActions ?? null;
    }

    public function getTaskStateAfterAction($action): string|null
    {
        $taskStateAfterAction = [
            self::TASK_ACTION_CANCEL => self::TASK_STATUS_CANCELED,
            self::TASK_ACTION_RESPOND => self::TASK_STATUS_IN_WORK,
            self::TASK_ACTION_DONE => self::TASK_STATUS_DONE,
            self::TASK_ACTION_REFUSE => self::TASK_STATUS_FAILED,
        ];

        return $taskStateAfterAction[$action] ?? null;
    }
}