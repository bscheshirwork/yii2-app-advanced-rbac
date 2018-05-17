<?php
return
    [
        'components' => [
            'db' => [
                // Uncomment this line if your run Codeception test without Docker
                //'dsn' => 'mysql:host=localhost;dbname=yii2advanced_test',],
            ],
            'mailer' => [
                'useFileTransport' => true,
            ],
        ],
    ]
;
