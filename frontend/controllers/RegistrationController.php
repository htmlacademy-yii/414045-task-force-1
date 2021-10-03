<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Routes\Route;
use frontend\models\City;
use frontend\models\User;
use Yii;
use yii\web\Controller;

final class RegistrationController extends Controller
{
    public function actionIndex(): string
    {
        $user = new User();
        $cities = City::getCitiesForOptionsList();
        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());
            if ($user->validate()) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                $user->save();
                $this->redirect(Route::getTasks());
            }
        }

        return $this->render('index', compact('user', 'cities'));
    }
}