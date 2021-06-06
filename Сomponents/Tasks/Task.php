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

    private string $taskState;

    public function __construct(
        private int $customerId,
        private int $executorID
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

    public function getPossibleActionsForCustomer(): string|null
    {
        $possibleActions = [
            self::TASK_ACTION_CANCEL => self::TASK_STATUS_CANCELED,
            self::TASK_ACTION_DONE => self::TASK_STATUS_DONE,
        ];

        return $possibleActions[$this->taskState] ?? null;
    }

    public function getPossibleActionsForExecutor(): string|null
    {
        $possibleActions = [
            self::TASK_ACTION_RESPOND => self::TASK_STATUS_IN_WORK,
            self::TASK_ACTION_REFUSE => self::TASK_STATUS_FAILED,
        ];

        return $possibleActions[$this->taskState] ?? null;
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