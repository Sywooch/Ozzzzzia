<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../frontend/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$smtp = require(__DIR__ . '/smtp.php');
return [
    'id' => 'app-backend',
    'name' => 'Obiavo',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $smtp['host'],
                'username' => $smtp['username'],
                'password' => $smtp['password'],
                'port' => $smtp['port'],
                'encryption' => $smtp['encryption'],
            ]
        ],
        'user' => [
            'class' => 'common\models\WebUser',
            'identityClass' => 'common\models\User',
            'loginUrl' => '/login',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'view' => [
                'theme' => [
                    'pathMap' => [
                       '@app/views' => '@app/views/themes/admin-lte'
                    ],
                ],
            ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'site/login',
                'categories/children-element-<id:\d+>' => 'categories/index',
                'categories/edit-<id:\d+>' => 'categories/edit-category',
            ],
        ],
        /**
         * Компонент для работы с текущей локацией
         */
        'location' => [
            'class' => 'frontend\components\Location'
        ],

//        'urlManagerFrontend' => [
//            // here is your frontend URL manager config
//            'class' => 'yii\web\UrlManager',
//            'baseUrl' => Yii::$app->params['staticDomain'],
//            'enablePrettyUrl' => true,
//            'enableStrictParsing' => true,
//            'showScriptName' => false,
//        ],
    ],
    'params' => $params,
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => [
            'site/login',
            'site/error',
            'autoposting-api/index',
            ],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
];
