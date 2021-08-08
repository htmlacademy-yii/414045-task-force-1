<?php


namespace frontend\controllers;


use Components\Constants\UserConstants;
use frontend\models\User;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex(): string
    {
        $users = User::find()->where(
            ['role' => UserConstants::USER_ROLE_EXECUTOR]
        )->joinWith(['responses', 'tasks', 'categories'])->all();

        return $this->render('index', compact('users'));
    }
}