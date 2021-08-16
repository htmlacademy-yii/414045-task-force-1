<?php

namespace frontend\controllers;

use Components\Constants\UserConstants;
use frontend\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(
                ['role' => UserConstants::USER_ROLE_EXECUTOR]
            )->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $this->render('index', compact('dataProvider'));
    }
}