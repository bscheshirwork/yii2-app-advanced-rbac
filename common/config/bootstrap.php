<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');

//extend components
Yii::setAlias('components', '@common/components');
//temporary redefine framework components: change path to file.php; not to Class
// wait for merge https://github.com/yiisoft/yii2/pull/15318
Yii::$classMap['yii\web\Session'] = '@common/components/redefine/web/Session.php';
