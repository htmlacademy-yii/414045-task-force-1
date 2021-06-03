<?php


class task
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
        private int $implementerID
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

    public function getPossipbleActions(): ?array
    {
        if ($this->taskState === self::TASK_STATUS_NEW) {
            return [
                self::TASK_ACTION_CANCEL => 'Отменить',
                self::TASK_ACTION_RESPOND => 'Откликнуться',
            ];
        }
        if ($this->taskState === self::TASK_STATUS_IN_WORK) {
            return [
                self::TASK_ACTION_DONE => 'Выполнено',
                self::TASK_ACTION_REFUSE => 'Отказаться',
            ];
        }

        return null;
    }

    public function getTaskStateAfterAction($action): ?string
    {
        if ($action === self::TASK_ACTION_CANCEL) {
            return self::TASK_STATUS_CANCELED;
        }
        if ($action === self::TASK_ACTION_RESPOND) {
            return self::TASK_STATUS_IN_WORK;
        }
        if ($action === self::TASK_ACTION_DONE) {
            return self::TASK_STATUS_DONE;
        }
        if ($action === self::TASK_ACTION_REFUSE) {
            return self::TASK_STATUS_FAILED;
        }

        return null;
    }
}