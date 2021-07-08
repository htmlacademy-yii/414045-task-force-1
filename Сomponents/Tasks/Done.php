<?php


namespace Components\Tasks;


class Done extends AbstractAction
{

    public function __construct(
        private int $user_id,
        private int $customer_id
    ) {
    }

    public function getAction(): ?string
    {
        if ($this->authUser()) {
            return "Выполнено";
        }

        return null;
    }

    public function getActionName(): string
    {
        return "done";
    }

    public function authUser(): bool
    {
        return $this->user_id === $this->customer_id;
    }
}