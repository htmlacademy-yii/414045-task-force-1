<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Constants\TaskConstants;
use Components\Constants\UserConstants;
use Components\Exceptions\TimeException;
use Components\Time\TimeDifference;
use frontend\models\Review;
use frontend\models\Task;
use frontend\models\User;
use yii\web\HttpException;

final class UsersController extends SecuredController
{
    public const MESSAGE_USER_NOT_FOUND = 'Пользователь не найден.';
    public const RESPONSE_STATUS_CODE = 404;

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $userFilter = User::getUserFilter();
        $dataProvider = User::getDataProviderFilter($userFilter);

        return $this->render('index', compact('dataProvider', 'userFilter'));
    }

    /**
     * @throws TimeException
     * @throws HttpException
     */
    public function actionGetView(int $id = null): string
    {
        if (!$id || User::findOne($id)->role !== UserConstants::USER_ROLE_EXECUTOR) {
            throw new HttpException(self::RESPONSE_STATUS_CODE, self::MESSAGE_USER_NOT_FOUND);
        }

        $user = User::findOne($id);
        $timeDiff = new TimeDifference(date('Y-m-d'), $user->birthday);
        $userAge = $timeDiff->getCountTimeUnits(['year' => 'Y']);
        $countUserTasksDone = Task::find()->where([
            'executor_id' => $user->id,
            'state' => TaskConstants::DONE_TASK_STATUS_NAME
        ])->count();
        $dataProvider = Review::getDataProviderReviews($user->id);

        return $this->render('view', compact('user', 'userAge', 'countUserTasksDone', 'dataProvider'));
    }
}