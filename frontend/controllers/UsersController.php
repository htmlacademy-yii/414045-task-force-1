<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Constants\TaskConstants;
use Components\Constants\UserConstants;
use Components\Exceptions\TimeException;
use Components\Routes\Route;
use Components\Time\TimeDifference;
use Components\Users\UserService;
use frontend\models\FavoriteExecutors;
use frontend\models\Review;
use frontend\models\Task;
use frontend\models\User;
use Throwable;
use yii\db\StaleObjectException;
use yii\web\HttpException;
use yii\web\Response;

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

        $userService = new UserService();
        $user = User::findOne($id);
        $isFavorite = $userService->isFavoriteExecutor($id);
        $lastActivity = $userService->getLastActivity($user);
        $userAge = $user->birthday !== null ? (new TimeDifference(date('Y-m-d'),
            $user->birthday))->getCountTimeUnits(['year' => 'Y']) : '';
        $countUserTasksDone = Task::find()->where([
            'executor_id' => $user->id,
            'state' => TaskConstants::DONE_TASK_STATUS_NAME
        ])->count();
        $dataProvider = Review::getDataProviderReviews($user->id);
        $rating = (new UserService())->getCountRatingStars($user->rating);

        return $this->render('view',
            compact('user', 'userAge', 'countUserTasksDone', 'dataProvider', 'lastActivity', 'isFavorite', 'rating'));
    }

    /**
     * @param int $executorId
     * @return Response
     * @throws StaleObjectException|Throwable
     */
    public function actionAddInFavorite(int $executorId): Response
    {
        $user = (new UserService())->getUser();
        $favoriteExecutors = $user->favoriteExecutors;

        foreach ($favoriteExecutors as $executor) {
            if ($executor->id === $executorId) {
                FavoriteExecutors::find()->where([
                    'user_id' => $user->id,
                    'executor_id' => $executor->id
                ])->one()->delete();

                return $this->redirect(Route::getUserView($executorId));
            }
        }

        $favorite = new FavoriteExecutors();
        $favorite->user_id = $user->id;
        $favorite->executor_id = $executorId;
        $favorite->save();

        return $this->redirect(Route::getUserView($executorId));
    }
}