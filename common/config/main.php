<?php
use yii\db\ActiveRecord;
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'modelMap' => [
                'User' => [
                    'class' => 'dektrium\user\models\User',
                    'on ' . ActiveRecord::EVENT_AFTER_INSERT => function ($event) {
                        $auth = Yii::$app->authManager;
                        $userRole = $auth->getRole('user');
                        $auth->assign($userRole, $event->sender->getId());
                    },
                ],
            ],
        ],
    ],
];
