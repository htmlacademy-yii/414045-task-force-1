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

    public const ACTION_MAP
        = [
            ActionConstants::CANCEL_ACTION_NAME => ActionConstants::CANCEL_ACTION_NAME_FOR_USER,
            ActionConstants::RESPONSE_ACTION_NAME => ActionConstants::RESPONSE_ACTION_NAME_FOR_USER,
            ActionConstants::DONE_ACTION_NAME => ActionConstants::DONE_ACTION_NAME_FOR_USER,
            ActionConstants::REFUSE_ACTION_NAME => ActionConstants::REFUSE_ACTION_NAME_FOR_USER,
        ];
}