<?php

namespace Components\Constants;

class NotificationConstants
{
    public const NTF_TYPE_MESSAGE = 1;
    public const NTF_TYPE_EXECUTOR = 2;
    public const NTF_TYPE_TASK = 3;

    public const NTF_MESSAGE_CLASS_NAME = 'lightbulb__new-task--message';
    public const NTF_EXECUTOR_CLASS_NAME = 'lightbulb__new-task--executor';
    public const NTF_TASK_CLASS_NAME = 'lightbulb__new-task--close';

    public const NTF_MAP = [
        self::NTF_TYPE_MESSAGE => self::NTF_MESSAGE_CLASS_NAME,
        self::NTF_TYPE_EXECUTOR => self::NTF_EXECUTOR_CLASS_NAME,
        self::NTF_TYPE_TASK => self::NTF_TASK_CLASS_NAME,
    ];
}