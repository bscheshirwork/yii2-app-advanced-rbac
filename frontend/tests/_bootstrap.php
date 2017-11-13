<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', __DIR__.'/../../');

require_once YII_APP_BASE_PATH . '/vendor/autoload.php';
require_once YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
require_once YII_APP_BASE_PATH . '/common/config/bootstrap.php';
require_once __DIR__ . '/../config/bootstrap.php';

//codeception namespace fix
\Yii::setAlias('@common/tests/Page', '@common/tests/_support/Page');
\Yii::setAlias('@frontend/tests/Page', '@frontend/tests/_support/Page');
\Yii::setAlias('@common/tests/Helper', '@common/tests/_support/Helper');
\Yii::setAlias('@frontend/tests/Helper', '@frontend/tests/_support/Helper');
\Yii::setAlias('@frontend/stub', '@frontend/tests/_stub');
