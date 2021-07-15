<?php


namespace Components\Constants;


class ActionConstants
{
    public const CANCEL_ACTION_NAME = 'cancel';
    public const RESPOND_ACTION_NAME = 'respond';
    public const DONE_ACTION_NAME = 'done';
    public const REFUSE_ACTION_NAME = 'refuse';

    public const CANCEL_ACTION_NAME_FOR_USER = 'Отменить';
    public const RESPOND_ACTION_NAME_FOR_USER = 'Откликнуться';
    public const DONE_ACTION_NAME_FOR_USER = 'Выполнено';
    public const REFUSE_ACTION_NAME_FOR_USER = 'Отказаться';

    public const ACTION_MAP = [
        ActionConstants::CANCEL_ACTION_NAME => ActionConstants::CANCEL_ACTION_NAME_FOR_USER,
        ActionConstants::RESPOND_ACTION_NAME => ActionConstants::RESPOND_ACTION_NAME_FOR_USER,
        ActionConstants::DONE_ACTION_NAME => ActionConstants::DONE_ACTION_NAME_FOR_USER,
        ActionConstants::REFUSE_ACTION_NAME => ActionConstants::REFUSE_ACTION_NAME_FOR_USER,
    ];
}