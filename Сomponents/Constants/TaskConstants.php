<?php


namespace Components\Constants;


use Components\Tasks\Cancel;
use Components\Tasks\Done;
use Components\Tasks\Refuse;
use Components\Tasks\Respond;

class TaskConstants
{
    public const NEW_TASK_STATUS_NAME = 'new';
    public const CANCELED_TASK_STATUS_NAME = 'canceled';
    public const IN_WORK_TASK_STATUS_NAME = 'in work';
    public const DONE_TASK_STATUS_NAME = 'done';
    public const FAILED_TASK_STATUS_NAME = 'failed';

    public const NEW_TASK_STATUS_NAME_FOR_USER = 'Новое';
    public const CANCELED_TASK_STATUS_NAME_FOR_USER = 'Отменено';
    public const IN_WORK_TASK_STATUS_NAME_FOR_USER = 'В работе';
    public const DONE_TASK_STATUS_NAME_FOR_USER = 'Выполнено';
    public const FAILED_TASK_STATUS_NAME_FOR_USER = 'Провалено';

    public const TRANSFER_MAP = [
        self::NEW_TASK_STATUS_NAME => [Cancel::class, Respond::class],
        self::IN_WORK_TASK_STATUS_NAME => [Done::class, Refuse::class]
    ];
}