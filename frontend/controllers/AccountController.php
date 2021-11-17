<?php

namespace frontend\controllers;

use frontend\models\User;
use \Yii;

class AccountController extends SecuredController
{
    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->id);

        return $this->render('index', compact('user'));
    }
}