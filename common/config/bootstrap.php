<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

//extend components
Yii::setAlias('components', '@common/components');

//temporary redefine framework components: change path to file.php; not to Class
Yii::$classMap['yii\caching\Cache'] = '@common/components/redefine/caching/Cache.php';
