<?php

namespace Components\Reviews;

use Components\Constants\TaskConstants;
use Components\Users\UserService;
use frontend\models\Review;
use frontend\models\Task;
use frontend\models\TaskCompleteForm;
use Yii;
use yii\db\Exception;

/**
 * class ReviewService
 *
 * @package Components/Reviews
 */
class ReviewService
{
    /**
     * Метод создания отзыва
     *
     * Создаёт отзыв, наполняет его данными из формы и сохраняет.
     *
     * @param Task $task
     * @param int $userId
     * @return bool
     */
    public function createReview(Task $task, int $userId): bool
    {
        $completeForm = new TaskCompleteForm();
        $completeForm->load(Yii::$app->request->post());

        if ($task->customer_id === $userId && $completeForm->validate()) {
            $review = (new ReviewFactory())->create($task, $completeForm, $userId);
            $task->state = $completeForm->completeState === TaskConstants::TASK_COMPLETE_FORM_STATE_SUCCESS
                ? TaskConstants::DONE_TASK_STATUS_NAME
                : TaskConstants::FAILED_TASK_STATUS_NAME;

            if ($this->saveReview($task, $review)) {
                (new UserService())->updateUserRating($task->executor_id);

                return true;
            }
        }

        return false;
    }

    /**
     * Метод сохраняет отзыв и новый статус задачи
     *
     * @param Task $task
     * @param Review $review
     * @return bool
     */
    public function saveReview(Task $task, Review $review): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$review->validate()) {
                throw new Exception('Ошибка валидации');
            }

            if (!$review->save()) {
                throw new Exception('Ошибка сохранения отзыва');
            }

            if (!$review->save() || !$task->save()) {
                throw new Exception('Ошибка сохранения задачи');
            }

            $transaction->commit();
        } catch (Exception) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}