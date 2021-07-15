<?php


namespace Components\Tasks;


use Components\Constants\ActionConstants;

class Done extends AbstractAction
{
    public function getActionNameForUser(): string|null
    {
        return $this->authUser() ? ActionConstants::DONE_ACTION_NAME_FOR_USER : null;
    }

    public function getActionName(): string
    {
        return ActionConstants::DONE_ACTION_NAME;
    }

    public function authUser(): bool
    {
        return $this->user_id === $this->customer_id;
    }
}