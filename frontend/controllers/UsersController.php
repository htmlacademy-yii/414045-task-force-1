<?php

namespace frontend\controllers;

use Components\Categories\Category;
use Components\Constants\TaskConstants;
use Components\Constants\UserConstants;
use frontend\models\User;
use frontend\models\UserFilter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use Yii;
use yii\db\ActiveQuery;

class UsersController extends Controller
{
    public function actionIndex(): string
    {
        $userFilter = $this->getUserFilter();
        $dataProvider = $this->getDataProvider($userFilter);

        return $this->render('index', compact('dataProvider', 'userFilter'));
    }

    private function getUserFilter(): UserFilter
    {
        $userFilter = new UserFilter();
        if (Yii::$app->request->getIsPost()) {
            $userFilter->load(Yii::$app->request->post());
        }

        return $userFilter;
    }

    private function getDataProvider(UserFilter $filter): ActiveDataProvider
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
}