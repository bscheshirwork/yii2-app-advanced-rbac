<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'user' => [
            // 'as backend' => 'dektrium\user\filters\BackendFilter',
            'controllerMap' => [
                'admin' => [
                    'class' => 'dektrium\user\controllers\AdminController',
                    'as access' => [
                        'class' => 'yii\filters\AccessControl',
                        'rules' => [
                            [
                                'allow' => true,
                                'roles' => ['administrateUser'],
                            ],
                        ],
                    ],
                ],
                'security' => [
                    'class' => \dektrium\user\controllers\SecurityController::class,
                    'layout' => '@backend/views/layouts-admin-lte/layouts/min-login',
                ],
                'recovery' => [
                    'class' => \dektrium\user\controllers\RecoveryController::class,
                    'layout' => '@backend/views/layouts-admin-lte/layouts/min-login',
                ],
                'registration' => [
                    'class' => \dektrium\user\controllers\RegistrationController::class,
                    'layout' => '@backend/views/layouts-admin-lte/layouts/min-login',
                ],
            ],
        ],
        'rbac' => [
            'class' => 'githubjeka\rbac\Module',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrateRbac'],
                    ],
                ],
            ],
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrateRbac'],
                    ],
                ],
            ],
            'defaultUrlLabel' => new class {
                public function __toString()
                {
                    return \Yii::t('main', 'Administrate RBAC');
                }
            },
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
//        'user' => [
//            'identityClass' => 'common\models\User',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
//        ],
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
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => ($skins = [
                        "skin-blue",
                        "skin-black",
                        "skin-red",
                        "skin-yellow",
                        "skin-purple",
                        "skin-green",
                        "skin-blue-light",
                        "skin-black-light",
                        "skin-red-light",
                        "skin-yellow-light",
                        "skin-purple-light",
                        "skin-green-light",
                    ])[array_rand($skins)],
                ],
                'insolita\wgadminlte\JsCookieAsset' => [
                    'depends' => [
                        'yii\web\YiiAsset',
                        'dmstr\web\AdminLteAsset',
                    ],
                ],
                'insolita\wgadminlte\CollapseBoxAsset' => [
                    'depends' => [
                        'insolita\wgadminlte\JsCookieAsset',
                    ],
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@backend/views/layouts-admin-lte',
                    '@dektrium/user/views/security' => '@backend/views/layouts-admin-lte/security',
                    '@dektrium/user/views/settings' => '@backend/views/layouts-admin-lte/settings',
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
