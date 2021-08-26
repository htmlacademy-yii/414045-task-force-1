<?php

namespace frontend\controllers;

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

    private function getDataProvider(UserFilter $filter): ActiveDataProvider
    {
        $conditions['role'] = UserConstants::USER_ROLE_EXECUTOR;
        $conditionsName = '';

        if (!empty($filter->showCategories)) {
            $conditions['category_id'] = $this->categoriesFilter($filter->showCategories);
        }

        if ($filter->isFree) {
            $conditions['executor_id'] = null;
        }

        if ($filter->isOnline) {
            $conditions['address'] = null;
        }

        if ($filter->hasReview) {
            $conditions['executor_id'] = null;
        }

        if ($filter->isFavorites) {
            $conditions['address'] = null;
        }

        if ($filter->userName) {
            $conditionsName = ['like', 'title', $filter->userName];
        }

        return new ActiveDataProvider([
            'query' => User::find()->where($conditions)->andWhere($conditionsName)->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
    }

    private function getUserFilter(): UserFilter
    {
        $userFilter = new UserFilter();
        if (Yii::$app->request->getIsPost()) {
            $userFilter->load(Yii::$app->request->post());
        }
        return $userFilter;
    }

    private function categoriesFilter($categoriesId): array
    {
        $showCategoriesId = [];
        foreach ($categoriesId as $categoryId) {
            $showCategoriesId[] = $categoryId + 1;
        }
        return $showCategoriesId;
    }
}