<?php
return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=db;dbname=yii2advanced',
            'username' => 'yii2advanced',
            'password' => 'yii2advanced',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
