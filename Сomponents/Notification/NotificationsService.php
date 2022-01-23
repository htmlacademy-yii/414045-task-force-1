<?php

namespace Components\Notification;

use Components\Constants\NotificationConstants;
use Components\Routes\Route;
use Components\Users\UserService;
use frontend\models\Notification;
use frontend\models\Task;
use frontend\models\User;
use Yii;
use yii\db\StaleObjectException;

class NotificationsService
{
    /**
     * @param string $email
     * @param string $content
     * @return void
     */
    private function sendNotification(string $email, string $content)
    {
        Yii::$app->mailer->compose()
            ->setFrom('from@domain.com')
            ->setTo($email)
            ->setSubject('Уведомление от сервиса TaskForce')
            ->setHtmlBody($content)
            ->send();
    }

    /**
     * @param int $taskId
     * @return void
     */
    public function sendNtfNewTaskResponse(int $taskId)
    {
        $task = Task::findOne($taskId);
        $user = User::findOne($task->customer_id);

        if ($user->userSettings->is_action_ntf_enabled) {
            $message = 'Новый отклик на ваше задание';
            $content = $this->getContentForEmailNotification($message, $task);
            $this->sendNotification($user->email, $content);

            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->task_id = $task->id;
            $notification->type = NotificationConstants::NTF_TYPE_EXECUTOR;
            $notification->content = $message;
            $notification->save();
        }
    }

    /**
     * @param int $taskId
     * @return void
     */
    public function sendNtfNewMessage(int $taskId)
    {
        $task = Task::findOne($taskId);
        $user = User::findOne($task->customer_id);

        if ($user->userSettings->is_message_ntf_enabled) {
            $message = 'Новое сообщение в чате';
            $content = $this->getContentForEmailNotification($message, $task);
            $this->sendNotification($user->email, $content);

            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->task_id = $task->id;
            $notification->type = NotificationConstants::NTF_TYPE_MESSAGE;
            $notification->content = $message;
            $notification->save();
        }
    }

    /**
     * @param int $taskId
     * @return void
     */
    public function sendNtfTaskRefuse(int $taskId)
    {
        $task = Task::findOne($taskId);
        $user = User::findOne($task->customer_id);

        if ($user->userSettings->is_action_ntf_enabled) {
            $message = 'Исполнитель отказался от задания';
            $content = $this->getContentForEmailNotification($message, $task);
            $this->sendNotification($user->email, $content);

            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->task_id = $task->id;
            $notification->type = NotificationConstants::NTF_TYPE_EXECUTOR;
            $notification->content = $message;
            $notification->save();
        }
    }

    /**
     * @param int $taskId
     * @return void
     */
    public function sendNtfStartTask(int $taskId)
    {
        $task = Task::findOne($taskId);
        $user = User::findOne($task->executor_id);

        if ($user->userSettings->is_action_ntf_enabled) {
            $message = 'Начато задание';
            $content = $this->getContentForEmailNotification($message, $task);
            $this->sendNotification($user->email, $content);

            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->task_id = $task->id;
            $notification->type = NotificationConstants::NTF_TYPE_TASK;
            $notification->content = $message;
            $notification->save();
        }
    }

    /**
     * @param int $taskId
     * @return void
     */
    public function sendNtfEndTask(int $taskId)
    {
        $task = Task::findOne($taskId);
        $user = User::findOne($task->executor_id);

        if ($user->userSettings->is_action_ntf_enabled) {
            $message = 'Закончено задание';
            $content = $this->getContentForEmailNotification($message, $task);
            $this->sendNotification($user->email, $content);

            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->task_id = $task->id;
            $notification->type = NotificationConstants::NTF_TYPE_EXECUTOR;
            $notification->content = $message;
            $notification->save();
        }
    }

    /**
     * @param string $message
     * @param Task $task
     * @return string
     */
    public function getContentForEmailNotification(string $message, Task $task): string
    {
        return $message . ' - ' . '<a href="' . Yii::$app->request->hostInfo . Route::getTaskView($task->id) . '">' . $task->title . '</a>';
    }

    /**
     * @param int $type
     * @return string
     */
    public function getNotificationClassName(int $type): string
    {
        return NotificationConstants::NTF_MAP[$type];
    }

    /**
     * @return void
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function updateNewNotification()
    {
        if (Yii::$app->request->get('r') === 'events/index') {
            $user = (new UserService())->getUser();
            $notifications = $user->notifications;

            foreach ($notifications as $notification) {
                $notification->delete();
            }
        }
    }
}