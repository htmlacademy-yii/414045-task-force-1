<?php

declare(strict_types=1);

namespace Components\Tasks;


/**
 * @property int user_id
 * @property int customer_id
 * @property int executor_id
 */
abstract class AbstractAction
{
    public function __construct(
        protected int $user_id,
        protected int $customer_id,
        protected int $executor_id
    ) {
    }

    abstract public function getActionNameForUser();

    abstract public function getActionName();

    abstract public function authUser();
}