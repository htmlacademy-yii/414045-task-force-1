<?php


namespace Components\Tasks;


class Refuse extends AbstractAction
{

    public function __construct(
        private int $user_id,
        private int $executor_id
    ) {
    }

    public function getAction(): ?string
    {
        if ($this->authUser()) {
            return "Отказаться";
        }

        return null;
    }

    public function getActionName(): string
    {
        return "refuse";
    }

    public function authUser(): bool
    {
        return $this->user_id === $this->executor_id;
    }
}