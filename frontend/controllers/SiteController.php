<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Routes\Route;
use Yii;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use Components\AuthHandler\AuthHandler;
use yii\web\ErrorAction;

/**
 * Site controller
 */
final class SiteController extends Controller
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
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['auth'],
                        'roles' => ['?'],
                    ]
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex(): mixed
    {
        $this->redirect(Route::getTasks());
    }

    /**
     * @return mixed
     */
    public function actionLogout(): mixed
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
}
