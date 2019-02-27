<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'Opora',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => [
        'log',
        'frontend\components\UsersActions'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => ''
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,

            'rules' => [
                '' => '/',
                'catalog' => 'products/category',
                'product/<id:\d+>' => 'products/view',
                'article/<id:\d+>' => 'site/article-view',
                'tecdoc/models/<mfa_id:\d+>/year/<year:\d+>' => 'tecdoc/models',
                'tecdoc/models/<mfa_id:\d+>' => 'tecdoc/models',
                'tecdoc/types/<mod_id:\d+>/year/<year:\d+>' => 'tecdoc/types',
                'tecdoc/types/<mod_id:\d+>' => 'tecdoc/types',
                'tecdoc/test-tree/<type_id:\d+>' => 'tecdoc/test-tree',
                'tecdoc/category/<category:\d+>/type/<type:\d+>' => 'tecdoc/category',
                'tecdoc/info/<article:\w+>' => 'tecdoc/info',
                'tecdoc/lookup/<number:\w+>' => 'tecdoc/lookup',
                'category/<category:\d+>' => 'products/category',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<action:(about|contact|login|signup|view|cabinet|actions|articles|all-images)>' => 'site/<action>'
            ],
        ],
    ],
    'params' => $params,
];
