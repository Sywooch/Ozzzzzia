<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$smtp = require(__DIR__ . '/../../backend/config/smtp.php');
return [
    'id' => 'app-frontend',
    'name' => 'Obiavo',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\ApcCache',
            'keyPrefix' => 'obiavo',
            'useApcu' => true,
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
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
            'enableStrictParsing' => false,
            'suffix' => '/',
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        /**
         * Компонент для работы с текущей локацией
         */
        'location' => [
            'class' => 'frontend\components\Location'
        ],
    ],
    'params' => $params,
];
