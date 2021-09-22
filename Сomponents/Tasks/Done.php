<?php

declare(strict_types=1);

namespace Components\Tasks;


use Components\Constants\ActionConstants;

/**
 * Class Done
 *
 * Действие исполнения задачи исполнителем
 *
 * @package Components\Tasks
 */
final class Done extends AbstractAction
{
    /**
     * Возвращает название действия для отображения пользователю
     *
     * @return string|null
     */
    public function getActionNameForUser(): string|null
    {
        return $this->authUser() ? ActionConstants::DONE_ACTION_NAME_FOR_USER
            : null;
    }

    /**
     * Проверяет доступно ли действие для роли пользователя
     *
     * @return bool
     */
    public function authUser(): bool
    {
        return $this->user_id === $this->customer_id;
    }

    /**
     * Возвращает внутреннее имя действия
     *
     * @return string
     */
    public function getActionName(): string
    {
        return ActionConstants::DONE_ACTION_NAME;
    }
}