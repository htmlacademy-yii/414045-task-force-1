<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Constants\UserConstants;
use Components\Routes\Route;
use Components\Users\UserService;
use frontend\models\City;
use frontend\models\User;
use frontend\models\UserSettings;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

final class RegistrationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     *
     * @throws Exception
     */
    public function actionIndex(): Response|string
    {
        $user = new User();
        $cities = City::getCitiesForOptionsList();
        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());
            if ($user->validate()) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                $user->role = UserConstants::USER_ROLE_CUSTOMER;
                $user->save();
                (new UserService())->saveUserSettings($user->id);
                Yii::$app->user->login($user);

                return $this->redirect(Route::getTasks());
            }
        }

        return $this->render('index', compact('user', 'cities'));
    }
}