<?php


namespace Components\Tasks;


use Components\Constants\ActionConstants;

class Respond extends AbstractAction
{
    public function getActionNameForUser(): ?string
    {
        return $this->authUser() ? ActionConstants::RESPOND_ACTION_NAME_FOR_USER : null;
    }

    public function getActionName(): string
    {
        return ActionConstants::RESPOND_ACTION_NAME;
    }

    public function authUser(): bool
    {
        return $this->user_id === $this->executor_id;
    }
}