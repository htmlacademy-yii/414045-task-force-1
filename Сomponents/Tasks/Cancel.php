<?php

declare(strict_types=1);

namespace Components\Tasks;


use Components\Constants\ActionConstants;
use frontend\models\Task;
use Yii;

/**
 * Class Cancel
 *
 * Действие отмены задачи заказчиком
 *
 * @package Components\Tasks
 */
final class Cancel extends AbstractAction
{
    /**
     * Возвращает название действия для отображения пользователю
     *
     * @param Task $task
     * @return string|null
     */
    public static function getActionNameForUser(Task $task): string|null
    {
        return self::authActionForUser($task) ? ActionConstants::CANCEL_ACTION_NAME_FOR_USER
            : null;
    }

    /**
     * Проверяет доступно ли действие для роли пользователя
     *
     * @param Task $task
     * @return bool
     */
    public static function authActionForUser(Task $task): bool
    {
        return Yii::$app->user->id === $task->customer_id;
    }

    /**
     * Возвращает внутреннее имя действия
     *
     * @return string
     */
    public static function getActionName(): string
    {
        return ActionConstants::CANCEL_ACTION_NAME;
    }
}