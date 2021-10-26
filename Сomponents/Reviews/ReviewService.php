<?php

namespace Components\Reviews;

use Components\Constants\TaskConstants;
use frontend\models\Review;
use frontend\models\Task;
use frontend\models\TaskCompleteForm;
use Yii;

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
    public static function createReview(Task $task, int $userId)
    {
        $review = new Review();
        $completeForm = new TaskCompleteForm();
        $completeForm->load(Yii::$app->request->post());

        if ($task->customer_id === $userId && $completeForm->validate()) {
            $review->task_id = $task->id;
            $review->sender_id = $userId;
            $review->addressee_id = $task->executor_id;
            $review->rating = $completeForm->rating ?? '';
            $review->comment = $completeForm->comment ?? '';
            $task->state = $completeForm->completeState === TaskConstants::TASK_COMPLETE_FORM_STATE_SUCCESS
                ? TaskConstants::DONE_TASK_STATUS_NAME
                : TaskConstants::FAILED_TASK_STATUS_NAME;

            if (!$review->validate()) {
                return false;
            }

            if (!$review->save() || !$task->save()) {
                return false;
            }

            return true;
        }

        return false;
    }
}