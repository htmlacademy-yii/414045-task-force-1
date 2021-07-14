<?php
/**
 * @property int $user_id
 */


namespace Components\Tasks;


use Components\Constants\ActionConstants;

class Refuse extends AbstractAction
{
    public function getActionNameForUser(): ?string
    {
        if ($this->authUser()) {
            return ActionConstants::REFUSE_ACTION_NAME_FOR_USER;
        }

        return null;
    }

    public function getActionName(): string
    {
        return ActionConstants::REFUSE_ACTION_NAME;
    }

    public function authUser(): bool
    {
        return $this->user_id === $this->executor_id;
    }
}