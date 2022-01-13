<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'landing' => 'landing/index',
                'logout' => 'site/logout',
                'tasks' => 'tasks/index',
                'tasks/view/<id:\d+>' => 'tasks/get-view',
                'tasks/view/<id:\d+>/response-accept/<responseId:\d+>' => 'tasks/response-accept',
                'tasks/view/<id:\d+>/response-refuse/<responseId:\d+>' => 'tasks/response-refuse',
                'tasks/view/<id:\d+>/response' => 'tasks/response',
                'tasks/view/<id:\d+>/complete' => 'tasks/complete',
                'tasks/view/<id:\d+>/refuse' => 'tasks/refuse',
                'tasks/view/<id:\d+>/cancel' => 'tasks/cancel',
                'my-tasks' => 'my-tasks/index',
                'account' => 'account/index',
                'users' => 'users/index',
                'users/view/<id:\d+>' => 'users/get-view',
                'registration' => 'registration/index',
                'create' => 'create/index',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/messages'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/tasks'],
            ],
        ],
        'user' => [
            'class' => '\yii\web\User',
            'loginUrl' => ['landing'],
        ],
    ],
    'modules' => [
        'api' => [
            'class' => 'frontend\modules\api\Module'
        ]
    ],
];
