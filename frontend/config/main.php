<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'user' => [
            'as frontend' => 'dektrium\user\filters\FrontendFilter',
            'controllerMap' => [
                'profile' => [
                    'class' => 'dektrium\user\controllers\ProfileController',
                    'as access' => [
                        'class' => 'yii\filters\AccessControl',
                        'rules' => [
                            ['allow' => true, 'actions' => ['index'], 'roles' => ['@']], //redirect на show. id в данном действии нет
                            ['allow' => true, 'actions' => ['show'], 'roles' => ['showUserProfile']], // просмотр других запрещён
                        ],
                    ],
                ],
                'settings' => [
                    'class' => 'dektrium\user\controllers\SettingsController',
                    'as access' => [
                        'class' => 'yii\filters\AccessControl',
                        'rules' => [
                            ['allow' => true, 'actions' => ['confirm'], 'roles' => ['@']], //подтвердить почту по ссылке можно только залогинившись
                            ['allow' => true, 'actions' => ['account'], 'roles' => ['updateSelfAccount']],
                            ['allow' => true, 'actions' => ['profile'], 'roles' => ['updateSelfProfile']],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
//        'user' => [
//            'identityClass' => 'common\models\User',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
//        ],
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
