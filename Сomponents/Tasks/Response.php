<?php

declare(strict_types=1);

namespace Components\Tasks;


use Components\Constants\ActionConstants;
use Components\Constants\UserConstants;
use Components\Responses\ResponseHelper;
use frontend\models\Task;
use frontend\models\User;
use Yii;

/**
 * Class Response
 *
 * Действие отклика на задание исполнителя
 *
 * @package Components\Tasks
 */
final class Response extends AbstractAction
{
    /**
     * Возвращает название действия для отображения пользователю
     *
     * @param Task $task
     * @return string|null
     */
    public static function getActionNameForUser(Task $task): string|null
    {
        return self::authActionForUser($task) ? ActionConstants::RESPONSE_ACTION_NAME_FOR_USER
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
        $user = User::findOne(Yii::$app->user->id);
        $isUserSentResponse = ResponseHelper::isUserSentResponse($task);

        return $user->role === UserConstants::USER_ROLE_EXECUTOR && $user->id !== $task->customer_id && !$isUserSentResponse;
    }

    /**
     * Возвращает внутреннее имя действия
     *
     * @return string
     */
    public static function getActionName(): string
    {
        return ActionConstants::RESPONSE_ACTION_NAME;
    }
}