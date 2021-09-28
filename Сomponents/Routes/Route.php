<?php

declare(strict_types=1);

namespace Components\Routes;

use yii\helpers\Url;

final class Route
{

    /**
     * Возвращает путь к странице списка задач
     *
     * Если установлен categoriesFilter, возвращает адрес страницы с GET запросом id категории.
     *
     * @param int|null $categoryId
     * @return string
     */
    public static function getTasks(int $categoryId = null): string
    {
        return $categoryId ? Url::to(['/tasks?category_id=' . $categoryId]) : Url::to(['/tasks']);
    }

    /**
     * Возвращает путь к странице списка заказчиков
     *
     * @return string
     */
    public static function getUsers(): string
    {
        return Url::to(['/users']);
    }

    /**
     * Возвращает путь к странице просмотра задачи
     *
     * @param int $taskId id задачи
     * @return string
     */
    public static function getTaskView(int $taskId): string
    {
        return Url::to(['/tasks/view/' . $taskId]);
    }

    /**
     * Возвращает путь к странице просмотра пользователя
     *
     * @param int $userId id пользователя
     * @return string
     */
    public static function getUserView(int $userId): string
    {
        return Url::to(['/users/view/' . $userId]);
    }

    /**
     * Возвращает путь к странице регистрации
     *
     * @return string
     */
    public static function getRegistration(): string
    {
        return Url::to(['/registration']);
    }

    /**
     * Возвращает путь к лендингу
     *
     * @return string
     */
    public static function getLanding(): string
    {
        return Url::to(['/landing']);
    }
    /**
     * Logout
     *
     * @return string
     */
    public static function logout(): string
    {
        return Url::to(['/logout']);
    }

}