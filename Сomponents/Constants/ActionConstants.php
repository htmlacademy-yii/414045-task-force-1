<?php

namespace Components\Constants;

final class ActionConstants
{
    public const CANCEL_ACTION_NAME = 'cancel';
    public const RESPONSE_ACTION_NAME = 'response';
    public const DONE_ACTION_NAME = 'done';
    public const REFUSE_ACTION_NAME = 'refuse';

    public const CANCEL_ACTION_NAME_FOR_USER = 'Отменить';
    public const RESPONSE_ACTION_NAME_FOR_USER = 'Откликнуться';
    public const DONE_ACTION_NAME_FOR_USER = 'Выполнено';
    public const REFUSE_ACTION_NAME_FOR_USER = 'Отказаться';

    public const CANCEL_ACTION_BUTTON_CLASS_NAME = 'refuse-button';
    public const RESPONSE_ACTION_BUTTON_CLASS_NAME = 'response-button';
    public const DONE_ACTION_BUTTON_CLASS_NAME = 'request-button';
    public const REFUSE_ACTION_BUTTON_CLASS_NAME = 'refuse-button';

    public const CANCEL_ACTION_DATA_FOR_CLASS_NAME = 'refuse-form';
    public const RESPONSE_ACTION_DATA_FOR_CLASS_NAME = 'response-form';
    public const DONE_ACTION_DATA_FOR_CLASS_NAME = 'complete-form';
    public const REFUSE_ACTION_DATA_FOR_CLASS_NAME = 'refuse-form';

    public const ACTION_MAP
        = [
            self::CANCEL_ACTION_NAME => self::CANCEL_ACTION_NAME_FOR_USER,
            self::RESPONSE_ACTION_NAME => self::RESPONSE_ACTION_NAME_FOR_USER,
            self::DONE_ACTION_NAME => self::DONE_ACTION_NAME_FOR_USER,
            self::REFUSE_ACTION_NAME => self::REFUSE_ACTION_NAME_FOR_USER,
        ];

    public const ACTION_BUTTON_CLASS_NAMES_MAP
        = [
            self::CANCEL_ACTION_NAME => self::CANCEL_ACTION_BUTTON_CLASS_NAME,
            self::RESPONSE_ACTION_NAME => self::RESPONSE_ACTION_BUTTON_CLASS_NAME,
            self::DONE_ACTION_NAME => self::DONE_ACTION_BUTTON_CLASS_NAME,
            self::REFUSE_ACTION_NAME => self::REFUSE_ACTION_BUTTON_CLASS_NAME,
        ];

    public const ACTION_DATA_FOR_CLASS_NAMES_MAP
        = [
            self::CANCEL_ACTION_NAME => self::CANCEL_ACTION_DATA_FOR_CLASS_NAME,
            self::RESPONSE_ACTION_NAME => self::RESPONSE_ACTION_DATA_FOR_CLASS_NAME,
            self::DONE_ACTION_NAME => self::DONE_ACTION_DATA_FOR_CLASS_NAME,
            self::REFUSE_ACTION_NAME => self::REFUSE_ACTION_DATA_FOR_CLASS_NAME,
        ];
}