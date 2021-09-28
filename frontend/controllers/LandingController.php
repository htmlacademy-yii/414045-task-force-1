<?php

namespace frontend\controllers;

use Components\Routes\Route;
use frontend\models\LoginForm;
use yii\web\Controller;
use Yii;

class LandingController extends Controller
{
    public $layout = 'landing';

    public function actionIndex()
    {
        $loginForm = new LoginForm();
        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());
            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                return $this->goHome();
            }
        }

        return $this->render('index', compact('loginForm'));
    }
}