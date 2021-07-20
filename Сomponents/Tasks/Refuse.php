<?php


namespace Components\Tasks;


use Components\Constants\ActionConstants;

/**
 * Class Refuse
 *
 * Действие отказа исполнителя от задания
 *
 * @package Components\Tasks
 */
class Refuse extends AbstractAction
{
    /**
     * Возвращает название действия для отображения пользователю
     *
     * @return string|null
     */
    public function getActionNameForUser(): string|null
    {
        return $this->authUser() ? ActionConstants::REFUSE_ACTION_NAME_FOR_USER : null;
    }

    /**
     * Возвращает внутреннее имя действия
     *
     * @return string
     */
    public function getActionName(): string
    {
        return ActionConstants::REFUSE_ACTION_NAME;
    }

    /**
     * Проверяет доступно ли действие для роли пользователя
     *
     * @return bool
     */
    public function authUser(): bool
    {
        return $this->user_id === $this->executor_id;
    }
}