<?php

namespace frontend\controllers;

use frontend\models\User;
use Yii;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex(): string
    {
        $user = new User();
        if (Yii::$app->request->getIsPost()){
            $user->load(Yii::$app->request->post());
            if ($user->validate()) {
                $user->save();
                $this->redirect('/tasks');
            }
        }

        return $this->render('index', compact('user'));
    }
}