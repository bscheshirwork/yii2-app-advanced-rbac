<?php

namespace frontend\fixtures;

use yii\test\ActiveFixture;

class ProfileFixture extends ActiveFixture
{
    public $modelClass = 'dektrium\user\models\Profile';

    public $depends = [
        'common\fixtures\UserFixture'
    ];
}
