<?php

namespace Components\Responses;

use Components\Constants\ResponseConstants;
use frontend\models\Response;
use Yii;

class ResponseFactory
{
    public function create(int $taskId): Response
    {
        $response = new Response();
        $response->load(Yii::$app->request->post());
        $response->user_id = Yii::$app->user->id;
        $response->task_id = $taskId;
        $response->state = ResponseConstants::NEW_STATUS_NAME;

        return $response;
    }
}