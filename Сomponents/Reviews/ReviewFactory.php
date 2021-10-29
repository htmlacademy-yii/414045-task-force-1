<?php

namespace Components\Reviews;

use frontend\models\Review;
use frontend\models\Task;
use frontend\models\TaskCompleteForm;

class ReviewFactory
{
    public function create(Task $task, TaskCompleteForm $completeForm, int $userId): Review
    {
        $review = new Review();
        $review->task_id = $task->id;
        $review->sender_id = $userId;
        $review->addressee_id = $task->executor_id;
        $review->rating = $completeForm->rating ?? '';
        $review->comment = $completeForm->comment ?? '';

        return $review;
    }
}