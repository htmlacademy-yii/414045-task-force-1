<?php

namespace Components\Constants;

use Components\Tasks\Cancel;
use Components\Tasks\Done;
use Components\Tasks\Refuse;
use Components\Tasks\Response;

final class TaskConstants
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

    public const TRANSFER_MAP
        = [
            self::NEW_TASK_STATUS_NAME => [Cancel::class, Response::class],
            self::IN_WORK_TASK_STATUS_NAME => [Done::class, Refuse::class],
        ];
    public const STATUS_MAP
        = [
            TaskConstants::NEW_TASK_STATUS_NAME,
            TaskConstants::CANCELED_TASK_STATUS_NAME,
            TaskConstants::IN_WORK_TASK_STATUS_NAME,
            TaskConstants::DONE_TASK_STATUS_NAME,
            TaskConstants::FAILED_TASK_STATUS_NAME,
        ];
    public const STATUS_MAP_FOR_USER
        = [
            TaskConstants::NEW_TASK_STATUS_NAME => TaskConstants::NEW_TASK_STATUS_NAME_FOR_USER,
            TaskConstants::CANCELED_TASK_STATUS_NAME => TaskConstants::CANCELED_TASK_STATUS_NAME_FOR_USER,
            TaskConstants::IN_WORK_TASK_STATUS_NAME => TaskConstants::IN_WORK_TASK_STATUS_NAME_FOR_USER,
            TaskConstants::DONE_TASK_STATUS_NAME => TaskConstants::DONE_TASK_STATUS_NAME_FOR_USER,
            TaskConstants::FAILED_TASK_STATUS_NAME => TaskConstants::FAILED_TASK_STATUS_NAME_FOR_USER,
        ];
    public const STATE_AFTER_ACTION
        = [
            ActionConstants::CANCEL_ACTION_NAME => TaskConstants::CANCELED_TASK_STATUS_NAME,
            ActionConstants::RESPONSE_ACTION_NAME => TaskConstants::IN_WORK_TASK_STATUS_NAME,
            ActionConstants::DONE_ACTION_NAME => TaskConstants::DONE_TASK_STATUS_NAME,
            ActionConstants::REFUSE_ACTION_NAME => TaskConstants::FAILED_TASK_STATUS_NAME,
        ];
}