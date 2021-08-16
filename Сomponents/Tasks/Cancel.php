<?php


namespace Components\Tasks;


use Components\Constants\ActionConstants;

/**
 * Class Cancel
 *
 * Действие отмены задачи заказчиком
 *
 * @package Components\Tasks
 */
class Cancel extends AbstractAction
{
    /**
     * Возвращает название действия для отображения пользователю
     *
     * @return string|null
     */
    public function getActionNameForUser(): string|null
    {
        return $this->authUser() ? ActionConstants::CANCEL_ACTION_NAME_FOR_USER
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
        return ActionConstants::CANCEL_ACTION_NAME;
    }
}