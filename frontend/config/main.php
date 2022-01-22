<?php

use Components\Users\UserService;
use frontend\models\User;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => frontend\models\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-frontend',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'maxSourceLines' => 20,
        ],
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\vkontakte',
                    'clientId' => '8041531',
                    'clientSecret' => 'kaByfLuf8UQSbNmRIOJP',
                    'scope' => 'email',
                    //  backurl: http://taskforce/
                ],
            ],
        ]
    ],
    'params' => $params,
    'on beforeAction' => function(){
        if (!Yii::$app->user->isGuest) {
            User::updateAll(['last_activity'=>date('Y-m-d h:i:s')],['id'=>Yii::$app->user->id]);
        }

        if (Yii::$app->request->get('r') === 'events/index') {
            $user = (new UserService())->getUser();
            $notifications = $user->notifications;

            foreach ($notifications as $notification) {
                $notification->delete();
            }
        }
    },
];
