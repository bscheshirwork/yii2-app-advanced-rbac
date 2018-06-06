<?php

// NOTE: Make sure this file is not accessible when deployed to production
//if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
//    die('You are not allowed to access this file.');
//}

//NOTE: DOUBLE CHECK! Make sure this file is not accessible when deployed to production
//check if not in same subnet /16 (255.255.0.0). Use for docker Codeception tests
if ((ip2long(@$_SERVER['REMOTE_ADDR']) ^ ip2long(@$_SERVER['SERVER_ADDR'])) >= 2 ** 16) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';


$config = require __DIR__ . '/../config/test-merge.php';

// Codeception testing routes. required Codeception\Codeception
if (file_exists(__DIR__ . '/../../c3.php')) {
    //Optional (if not set the default c3 output dir will be used)
    define('C3_CODECOVERAGE_ERROR_LOG_FILE', __DIR__ . '/runtime/logs/c3_error.log');
    //Optional (if not set the default c3 output dir will be used)
    define('C3_CODECEPTION_CONFIG_PATH', __DIR__ . '/tests');
    require_once __DIR__ . '/../../c3.php';
}

(new yii\web\Application($config))->run();
