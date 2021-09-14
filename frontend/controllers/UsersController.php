<?php

namespace frontend\controllers;

use Components\Categories\Category;
use Components\Constants\TaskConstants;
use Components\Constants\UserConstants;
use Components\Time\TimeDifference;
use frontend\models\Review;
use frontend\models\Task;
use frontend\models\User;
use frontend\models\UserFilter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;

class UsersController extends Controller
{
    public function actionIndex(): string
    {
        $userFilter = $this->getUserFilter();
        $dataProvider = $this->getDataProviderFilter($userFilter);

        return $this->render('index', compact('dataProvider', 'userFilter'));
    }

    /**
     * @throws HttpException
     */
    public function actionView(int $id = null): string
    {
        if (!$id || User::findOne($id)->role !== UserConstants::USER_ROLE_EXECUTOR) {
            throw new HttpException(404, 'Пользователь не найден.');
        }

        $user = User::findOne($id);
        $timeDiff = new TimeDifference(date('Y-m-d'), $user->birthday);
        $userAge = $timeDiff->getCountTimeUnits(['year' => 'Y']);
        $countUserTasksDone = Task::find()->where(['executor_id' => $user->id, 'state' => TaskConstants::DONE_TASK_STATUS_NAME])->count();
        $dataProvider = $this->getDataProviderReviews($user->id);

        return $this->render('view', compact('user', 'userAge', 'countUserTasksDone', 'dataProvider'));
    }

    private function getUserFilter(): UserFilter
    {
        $userFilter = new UserFilter();
        if (Yii::$app->request->getIsPost()) {
            $userFilter->load(Yii::$app->request->post());
        }

        return $userFilter;
    }

    private function getDataProviderFilter(UserFilter $filter): ActiveDataProvider
    {
        $conditions = [
            'role' => UserConstants::USER_ROLE_EXECUTOR,
            's.category_id' => array_flip($filter->categories)
        ];
        $query = User::find()->leftJoin(['s' => 'users_specialty'],
            's.user_id = users.id')->where($conditions);

        if (!empty($filter->showCategories)) {
            $category = new Category();
            $conditionCategoryId = ['category_id' => $category->categoriesFilter($filter->showCategories)];
            $query->filterWhere($conditionCategoryId);
        }

        if ($filter->isFree) {
            $conditionUserIsFree = ['!=', 'state', TaskConstants::NEW_TASK_STATUS_NAME];
            $query->leftJoin(['t' => 'tasks'], 't.executor_id = users.id')->andWhere($conditionUserIsFree);
        }

        if ($filter->isOnline) {
            //
        }

        if ($filter->hasReview) {
            $conditionsHasReview = 'addressee_id = users.id';
            $query->leftJoin('reviews', 'addressee_id = users.id')->andWhere($conditionsHasReview);
        }

        if ($filter->isFavorites) {
            //
        }

        if ($filter->userName) {
            $conditionName = ['like', 'name', $filter->userName];
            $query->andWhere($conditionName);
        }

        return new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }

    private function getDataProviderReviews($userId)
    {
        $query = Review::find()->where(['addressee_id' => $userId]);

        return new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }
}