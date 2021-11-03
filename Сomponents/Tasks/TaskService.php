<?php

declare(strict_types=1);

namespace Components\Tasks;

use Components\Constants\ActionConstants;
use Components\Constants\TaskConstants;
use Components\Exceptions\TaskActionException;
use Components\Exceptions\TaskStateException;
use Components\Responses\ResponseService;
use frontend\models\TaskAttachment;
use frontend\models\Task;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class TaskService
 *
 * @package Components\Tasks
 */
class TaskService
{
    public string $taskState = TaskConstants::NEW_TASK_STATUS_NAME;

    /**
     * Метод сохранения вложенных файлов для задачи
     *
     * @param $attachmentFileNames
     * @param $taskId
     */
    public static function saveTaskAttachmentFiles($attachmentFileNames, $taskId)
    {
        if ($attachmentFileNames !== null) {
            foreach ($attachmentFileNames as $fileName) {
                /**
                 * @var TaskAttachment $file
                 */
                $file = (new TaskAttachmentFactory())->create($taskId, $fileName);
                if ($file->validate()) {
                    $file->save();
                }
            }
        }
    }

    /**
     * Получить карту статусов задачи
     *
     * @return array
     */
    public static function getStatusMap(): array
    {
        return TaskConstants::STATUS_MAP_FOR_USER;
    }

    /**
     * Получить карту действий
     *
     * @return array
     */
    public static function getActionMap(): array
    {
        return ActionConstants::ACTION_MAP;
    }

    /**
     * Метод проверяет, может ли задача быть закончена
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     * @throws TaskStateException
     */
    public static function isTaskCanBeComplete(Task $task, int $userId): bool
    {
        $possibleActions = self::getPossibleActions($task);

        foreach ($possibleActions as $action) {
            if ($action === Done::class && $task->customer_id === $userId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Получить доступные классы действий для задачи
     *
     * @param Task $task
     * @return array Классы доступных действий
     * @throws TaskStateException
     */
    public static function getPossibleActions(Task $task): array
    {
        if (!array_key_exists($task->state, TaskConstants::STATUS_MAP_FOR_USER)) {
            throw new TaskStateException(
                'Выбранного состояния задания не существует'
            );
        }
        if (!array_key_exists($task->state, TaskConstants::TRANSFER_MAP)) {
            throw new TaskStateException(
                'Для выбранного статуса задания нет доступных действий'
            );
        }

        $actions = TaskConstants::TRANSFER_MAP[$task->state];
        $possibleActions = [];

        /**
         * @var AbstractAction $action
         */
        foreach ($actions as $action) {
            if ($action::authActionForUser($task)) {
                $possibleActions[] = $action;
            }
        }

        return $possibleActions;
    }

    /**
     * Метод проверяет, может ли задача быть отменена исполнителем
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     * @throws TaskStateException
     */
    public static function isTaskCanBeRefuse(Task $task, int $userId): bool
    {
        $possibleActions = self::getPossibleActions($task);

        foreach ($possibleActions as $action) {
            if ($action === Refuse::class && $task->executor_id === $userId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Метод проверяет, может ли задача быть отменена заказчиком
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     * @throws TaskStateException
     */
    public static function isTaskCanBeCancel(Task $task, int $userId): bool
    {
        $possibleActions = self::getPossibleActions($task);

        foreach ($possibleActions as $action) {
            if ($action === Cancel::class && $task->customer_id === $userId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Метод проверяет, можно ли откликнуться на задачу
     *
     * @param Task $task
     * @return bool
     * @throws TaskStateException
     */
    public static function isTaskCanBeResponse(Task $task): bool
    {
        $possibleActions = self::getPossibleActions($task);
        $isUserSentResponse = ResponseService::isUserSentResponse($task);

        foreach ($possibleActions as $action) {
            if ($action === Response::class && !$isUserSentResponse) {
                return true;
            }
        }

        return false;
    }

    /**
     * Метод возвращает имя класса для кнопки действия
     *
     * @param $actionName
     * @return string
     */
    public static function getTaskActionButtonClassName($actionName): string
    {
        return ActionConstants::ACTION_BUTTON_CLASS_NAMES_MAP[$actionName];
    }

    /**
     * Метод возвращает имя класса для DataFor
     *
     * @param $actionName
     * @return string
     */
    public static function getTaskActionDataForClassName($actionName): string
    {
        return ActionConstants::ACTION_DATA_FOR_CLASS_NAMES_MAP[$actionName];
    }

    /**
     * Получить статус задачи после действия
     *
     * @param string $action действие
     *
     * @return string|null Статус задачи после выполненного действия
     * @throws TaskActionException
     */
    public static function getTaskStateAfterAction(string $action): string|null
    {
        if (in_array($action, ActionConstants::ACTION_MAP)) {
            throw new TaskActionException('Указанного действия не существует');
        }

        return TaskConstants::STATE_AFTER_ACTION[$action] ?? null;
    }

    /**
     * @param string $address
     * @return array|false|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getLocation(string $address)
    {
        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru/',
        ]);

        try {
            $response = $client->request('GET', '1.x', [
                'query' => [
                    'geocode' => $address,
                    'apikey' => 'e666f398-c983-4bde-8f14-e3fec900592a',
                    'format' => 'json',
                ]
            ]);

            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);

            $result = false;

            if (is_array($response_data)) {
                $result = $response_data;
            }
        } catch (RequestException $e) {
            return false;
        }

        return $result;
    }

    /**
     * @param $address
     * @return false|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getLocationPoint($address)
    {
        $location = self::getLocation($address);

        if (!is_array($location)) {
            return false;
        }

        return $location['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
    }

    /**
     * @param $point
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getLocationName($point)
    {
        $location = self::getLocation($point);

        if (!is_array($location)) {
            return false;
        }

        return [
            'name' => $location['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'],
            'description' => $location['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['description']
            ];
    }
}