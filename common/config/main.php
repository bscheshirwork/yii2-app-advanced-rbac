<?php
use yii\db\ActiveRecord;
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
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
