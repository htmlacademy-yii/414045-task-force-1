<?php


namespace Components\Tasks;


class Cancel extends AbstractAction
{

    public function __construct(
        private int $user_id,
        private int $customer_id
    ) {
    }

    public function getAction(): ?string
    {
        if ($this->authUser()) {
            return "Отменить";
        }

        return null;
    }

    public function getActionName(): string
    {
        return "cancel";
    }

    public function authUser(): bool
    {
        return $this->user_id === $this->customer_id;
    }
}